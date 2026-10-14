<?php
/**
 * Capturas automáticas de los proyectos.
 *
 * Cada 24 horas se revisa la URL pública de cada proyecto con «Actualizar capturas
 * automáticamente» activado:
 * 1. Se calcula una huella de su interfaz: contenido de las hojas de estilo y URL
 *    (con versión) de los scripts del propio dominio. El texto de las noticias, fechas
 *    o carruseles no cambia la huella; un rediseño o una actualización del tema sí.
 * 2. Si la huella no cambió, la captura actual se conserva.
 * 3. Si cambió, se toman capturas nuevas (escritorio y, si el proyecto tiene, móvil)
 *    con el servicio Microlink y sustituyen a las anteriores, que quedan en la
 *    biblioteca de medios. Si algo falla, no se toca la captura actual.
 * Las páginas en mantenimiento o con verificación antibots no se capturan.
 *
 * Configuración opcional en wp-config.php:
 *   define( 'AM_MICROLINK_API_KEY', '…' ); // plan de pago de Microlink (sin clave: cuota gratuita diaria).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const AM_CAPTURE_DAILY_HOOK = 'am_daily_project_captures';
const AM_CAPTURE_HOOK = 'am_refresh_project_capture';

add_action( 'init', function () {
    if ( ! wp_next_scheduled( AM_CAPTURE_DAILY_HOOK ) ) {
        wp_schedule_event( time() + HOUR_IN_SECONDS, 'daily', AM_CAPTURE_DAILY_HOOK );
    }
} );
add_action( 'switch_theme', function () {
    wp_clear_scheduled_hook( AM_CAPTURE_DAILY_HOOK );
    wp_unschedule_hook( AM_CAPTURE_HOOK );
} );

/** Proyectos publicados con actualización automática y URL pública. */
function am_capture_project_ids() {
    return get_posts( array(
        'post_type' => 'proyecto', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_am_capture_auto', 'value' => '1' ), array( 'key' => '_am_demo', 'compare' => '!=', 'value' => '' ) ),
    ) );
}

// Una tarea por proyecto, espaciadas: cada una cabe en el tiempo máximo de PHP de un hosting compartido.
add_action( AM_CAPTURE_DAILY_HOOK, function () {
    foreach ( array_values( am_capture_project_ids() ) as $i => $id ) {
        if ( ! wp_next_scheduled( AM_CAPTURE_HOOK, array( (int) $id, 'desktop' ) ) ) {
            wp_schedule_single_event( time() + $i * 120, AM_CAPTURE_HOOK, array( (int) $id, 'desktop' ) );
        }
    }
} );
add_action( AM_CAPTURE_HOOK, function ( $id, $kind = 'desktop' ) {
    if ( function_exists( 'set_time_limit' ) ) { @set_time_limit( 150 ); } // phpcs:ignore WordPress.PHP.NoSilencedErrors
    $kind === 'mobile' ? am_capture_mobile( (int) $id ) : am_refresh_project_capture( (int) $id );
}, 10, 2 );

function am_capture_log( $post_id, $state, $detail ) {
    update_post_meta( $post_id, '_am_capture_status', wp_json_encode( array( 'estado' => $state, 'detalle' => $detail, 'fecha' => current_time( 'mysql' ) ), JSON_UNESCAPED_UNICODE ) );
    return array( 'estado' => $state, 'detalle' => $detail );
}

function am_capture_status( $post_id ) {
    $status = json_decode( (string) get_post_meta( $post_id, '_am_capture_status', true ), true );
    return is_array( $status ) ? $status : array();
}

/**
 * Comprueba un proyecto y, si su interfaz cambió (o no tiene captura), la actualiza.
 *
 * @param bool $force Capturar aunque la huella no haya cambiado.
 */
function am_refresh_project_capture( $post_id, $force = false ) {
    $url = (string) get_post_meta( $post_id, '_am_demo', true );
    if ( ! $url || ( ! $force && get_post_meta( $post_id, '_am_capture_auto', true ) !== '1' ) ) {
        return array( 'estado' => 'desactivada', 'detalle' => 'Sin URL pública o actualización automática desactivada.' );
    }
    $page = am_capture_inspect( $url );
    if ( is_wp_error( $page ) ) { return am_capture_log( $post_id, 'error', 'No se pudo revisar la web: ' . $page->get_error_message() ); }
    if ( $page['blocked'] ) { return am_capture_log( $post_id, 'omitida', 'La web muestra una página de mantenimiento o verificación; se conserva la captura actual.' ); }

    $previous = (string) get_post_meta( $post_id, '_am_capture_fingerprint', true );
    $has_image = (bool) get_post_thumbnail_id( $post_id );
    if ( ! $force && $previous === $page['fingerprint'] ) { return am_capture_log( $post_id, 'sin_cambios', 'La interfaz no cambió; se conserva la captura.' ); }
    if ( ! $force && $previous === '' && $has_image ) {
        update_post_meta( $post_id, '_am_capture_fingerprint', $page['fingerprint'] );
        return am_capture_log( $post_id, 'referencia', 'Primera revisión: se guarda la huella de la interfaz y se conserva la captura actual.' );
    }

    $image = am_capture_screenshot( $url, 'desktop' );
    if ( is_wp_error( $image ) && $image->get_error_code() === 'bloqueada' ) { return am_capture_log( $post_id, 'omitida', 'La web muestra una página de mantenimiento o verificación; se conserva la captura actual.' ); }
    if ( is_wp_error( $image ) ) { return am_capture_log( $post_id, 'error', 'No se pudo tomar la captura: ' . $image->get_error_message() ); }
    $attachment = am_capture_store( $post_id, $image, 'desktop' );
    if ( is_wp_error( $attachment ) ) { return am_capture_log( $post_id, 'error', $attachment->get_error_message() ); }
    update_post_meta( $post_id, '_am_capture_fingerprint', $page['fingerprint'] );
    update_post_meta( $post_id, '_am_capture_updated', current_time( 'mysql' ) );
    // La captura móvil va en otra tarea para no superar el tiempo máximo de ejecución.
    if ( am_capture_find_kind( $post_id, 'mobile' ) && ! wp_next_scheduled( AM_CAPTURE_HOOK, array( (int) $post_id, 'mobile' ) ) ) {
        wp_schedule_single_event( time() + 60, AM_CAPTURE_HOOK, array( (int) $post_id, 'mobile' ) );
    }
    return am_capture_log( $post_id, 'actualizada', $previous === '' ? 'Captura inicial tomada de la web pública.' : 'La interfaz cambió: captura de escritorio actualizada.' );
}

function am_capture_mobile( $post_id ) {
    $url = (string) get_post_meta( $post_id, '_am_demo', true );
    if ( ! $url ) { return; }
    $image = am_capture_screenshot( $url, 'mobile' );
    if ( is_wp_error( $image ) ) { am_capture_log( $post_id, 'parcial', 'Escritorio actualizado; la captura móvil falló: ' . $image->get_error_message() ); return; }
    $stored = am_capture_store( $post_id, $image, 'mobile' );
    if ( ! is_wp_error( $stored ) ) { am_capture_log( $post_id, 'actualizada', 'La interfaz cambió: capturas de escritorio y móvil actualizadas.' ); }
}

/**
 * Descarga la página y calcula la huella de su interfaz. Si la web responde a los
 * servidores con una verificación antibots (p. ej. HTTP 202), los recursos se leen
 * con el navegador de Microlink.
 */
function am_capture_inspect( $url ) {
    $args = array( 'timeout' => 20, 'redirection' => 5, 'limit_response_size' => 3 * MB_IN_BYTES, 'user-agent' => 'Mozilla/5.0 (compatible; MerchanDev-Portafolio; +' . home_url( '/' ) . ')' );
    $response = wp_safe_remote_get( $url, $args );
    if ( is_wp_error( $response ) ) { return $response; }
    $html = (string) wp_remote_retrieve_body( $response );
    $challenge = (bool) preg_match( '~cf-chl-|id="challenge-form"|robot challenge~i', $html );
    $styles = array(); $scripts = array(); $title = '';
    if ( wp_remote_retrieve_response_code( $response ) === 200 && ! $challenge ) {
        $title = preg_match( '~<title[^>]*>(.*?)</title>~is', $html, $m ) ? wp_strip_all_tags( $m[1] ) : '';
        if ( preg_match_all( '~<link\b[^>]*>~i', $html, $links ) ) {
            foreach ( $links[0] as $tag ) {
                if ( preg_match( '~rel=["\']?[^"\'>]*stylesheet~i', $tag ) && preg_match( '~href=["\']([^"\']+)~i', $tag, $href ) ) { $styles[] = am_capture_absolute( html_entity_decode( $href[1] ), $url ); }
            }
        }
        if ( preg_match_all( '~<script\b[^>]*\bsrc=["\']([^"\']+)~i', $html, $srcs ) ) {
            foreach ( $srcs[1] as $src ) { $scripts[] = am_capture_absolute( html_entity_decode( $src ), $url ); }
        }
    } else {
        $remote = am_capture_remote_assets( $url );
        if ( is_wp_error( $remote ) ) { return new WP_Error( 'http', 'respuesta HTTP ' . wp_remote_retrieve_response_code( $response ) . ' y lectura con navegador fallida (' . $remote->get_error_message() . ')' ); }
        list( $title, $styles, $scripts ) = $remote;
        $html = '';
    }
    $blocked = (bool) preg_match( '~mantenimiento|maintenance|robot challenge|just a moment|attention required|access denied~i', $title );
    $host = strtolower( (string) wp_parse_url( $url, PHP_URL_HOST ) );
    $same_site = function ( $asset ) use ( $host ) {
        $asset_host = strtolower( (string) wp_parse_url( $asset, PHP_URL_HOST ) );
        return $asset_host && ( $asset_host === $host || substr( $asset_host, -strlen( '.' . $host ) ) === '.' . $host || substr( $host, -strlen( '.' . $asset_host ) ) === '.' . $asset_host );
    };
    $styles = array_slice( array_values( array_unique( array_filter( $styles, $same_site ) ) ), 0, 15 );
    $scripts = array_values( array_unique( array_filter( $scripts, $same_site ) ) );
    sort( $styles ); sort( $scripts );
    $parts = array();
    foreach ( $styles as $style ) {
        $css = wp_safe_remote_get( $style, array_merge( $args, array( 'timeout' => 15, 'limit_response_size' => 2 * MB_IN_BYTES ) ) );
        // El contenido decide; si no se puede descargar, cuenta la URL para no provocar falsos cambios.
        $parts[] = 'css ' . $style . ' ' . ( ! is_wp_error( $css ) && wp_remote_retrieve_response_code( $css ) === 200 ? hash( 'sha256', wp_remote_retrieve_body( $css ) ) : '' );
    }
    foreach ( $scripts as $script ) { $parts[] = 'js ' . $script; }
    if ( ! $parts && $html ) { $parts[] = 'html ' . hash( 'sha256', preg_replace( '~>[^<]*<~', '><', $html ) ); } // Sin recursos propios: estructura del HTML.
    if ( ! $parts ) { return new WP_Error( 'huella', 'no se encontraron recursos de la interfaz' ); }
    return array( 'fingerprint' => hash( 'sha256', implode( "\n", $parts ) ), 'blocked' => $blocked, 'assets' => count( $parts ) );
}

/** Título, hojas de estilo y scripts de una página renderizada por el navegador de Microlink. */
function am_capture_remote_assets( $url ) {
    $query = array(
        'url' => $url, 'meta' => 'false',
        'data.styles.selectorAll' => 'link[rel="stylesheet"]', 'data.styles.attr' => 'href',
        'data.scripts.selectorAll' => 'script[src]', 'data.scripts.attr' => 'src',
        'data.title.selector' => 'title', 'data.title.attr' => 'text',
    );
    $data = am_microlink_request( $query );
    if ( is_wp_error( $data ) ) { return $data; }
    $absolute = function ( $list ) use ( $url ) { return array_map( function ( $src ) use ( $url ) { return am_capture_absolute( (string) $src, $url ); }, array_filter( (array) $list, 'is_string' ) ); };
    return array( (string) ( $data['title'] ?? '' ), $absolute( $data['styles'] ?? array() ), $absolute( $data['scripts'] ?? array() ) );
}

/** Llamada a la API de Microlink; devuelve el bloque `data` de la respuesta o WP_Error. */
function am_microlink_request( array $query, $timeout = 60 ) {
    $key = defined( 'AM_MICROLINK_API_KEY' ) ? (string) AM_MICROLINK_API_KEY : '';
    $endpoint = ( $key ? 'https://pro.microlink.io/?' : 'https://api.microlink.io/?' ) . http_build_query( $query );
    $response = wp_safe_remote_get( $endpoint, array( 'timeout' => $timeout, 'headers' => $key ? array( 'x-api-key' => $key ) : array() ) );
    if ( is_wp_error( $response ) ) { return $response; }
    $body = json_decode( (string) wp_remote_retrieve_body( $response ), true );
    if ( wp_remote_retrieve_response_code( $response ) !== 200 || ( $body['status'] ?? '' ) !== 'success' ) {
        $message = $body['message'] ?? ( 'respuesta HTTP ' . wp_remote_retrieve_response_code( $response ) );
        return new WP_Error( 'microlink', wp_strip_all_tags( (string) $message ) );
    }
    return (array) ( $body['data'] ?? array() );
}

function am_capture_absolute( $src, $base ) {
    if ( preg_match( '~^https?://~i', $src ) ) { return $src; }
    $parts = wp_parse_url( $base );
    $scheme = $parts['scheme'] ?? 'https';
    if ( strpos( $src, '//' ) === 0 ) { return $scheme . ':' . $src; }
    $root = $scheme . '://' . ( $parts['host'] ?? '' );
    if ( strpos( $src, '/' ) === 0 ) { return $root . $src; }
    $dir = isset( $parts['path'] ) ? preg_replace( '~/[^/]*$~', '/', $parts['path'] ) : '/';
    return $root . $dir . $src;
}

/** Captura de pantalla con Microlink. Devuelve los bytes JPEG o WP_Error. */
function am_capture_screenshot( $url, $kind ) {
    $pre = apply_filters( 'am_capture_screenshot', null, $url, $kind ); // Permite usar otro proveedor.
    if ( $pre !== null ) { return $pre; }
    $viewport = $kind === 'mobile'
        ? array( 'viewport.width' => 390, 'viewport.height' => 844, 'viewport.deviceScaleFactor' => 2, 'viewport.isMobile' => 'true', 'viewport.hasTouch' => 'true' )
        : array( 'viewport.width' => 1280, 'viewport.height' => 800, 'viewport.deviceScaleFactor' => 1 );
    $query = array_merge( array(
        'url' => $url, 'screenshot' => 'true', 'meta' => 'false', 'screenshot.type' => 'jpeg', 'waitForTimeout' => 3000, 'adblock' => 'true',
        // Título y encabezado ya renderizados: detectan mantenimientos que la web muestra con JavaScript.
        'data.title.selector' => 'title', 'data.title.attr' => 'text', 'data.heading.selector' => 'h1', 'data.heading.attr' => 'text',
    ), $viewport );
    $data = am_microlink_request( $query );
    if ( is_wp_error( $data ) ) { return $data; }
    if ( preg_match( '~mantenimiento|maintenance|robot challenge|just a moment|attention required|access denied~i', ( $data['title'] ?? '' ) . ' ' . ( $data['heading'] ?? '' ) ) ) {
        return new WP_Error( 'bloqueada', 'la web muestra una página de mantenimiento o verificación' );
    }
    $image_url = $data['screenshot']['url'] ?? '';
    if ( ! $image_url ) { return new WP_Error( 'captura', 'el servicio no devolvió una imagen' ); }
    $image = wp_safe_remote_get( $image_url, array( 'timeout' => 30, 'limit_response_size' => 8 * MB_IN_BYTES ) );
    if ( is_wp_error( $image ) ) { return $image; }
    $bytes = (string) wp_remote_retrieve_body( $image );
    $info = $bytes ? @getimagesizefromstring( $bytes ) : false; // phpcs:ignore WordPress.PHP.NoSilencedErrors
    if ( ! $info || ! in_array( $info['mime'], array( 'image/jpeg', 'image/png', 'image/webp' ), true ) || strlen( $bytes ) < 5000 ) {
        return new WP_Error( 'captura', 'la imagen recibida no es válida' );
    }
    return $bytes;
}

/** Imagen actual de un tipo (desktop = portada; mobile = captura marcada como móvil). */
function am_capture_find_kind( $post_id, $kind ) {
    $gallery = array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, '_am_gallery', true ) ) ) );
    foreach ( $gallery as $attachment_id ) {
        if ( get_post_meta( $attachment_id, '_am_capture_kind', true ) === $kind ) { return $attachment_id; }
    }
    return $kind === 'desktop' ? (int) get_post_thumbnail_id( $post_id ) : 0;
}

/** Guarda la captura y la pone en lugar de la anterior (que se conserva en la biblioteca). */
function am_capture_store( $post_id, $bytes, $kind ) {
    $info = getimagesizefromstring( $bytes );
    $extension = array( 'image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp' )[ $info['mime'] ] ?? 'jpg';
    $slug = get_post_field( 'post_name', $post_id );
    $upload = wp_upload_bits( sprintf( '%s-%s-%s.%s', $slug, $kind === 'mobile' ? 'movil' : 'escritorio', gmdate( 'Ymd-His' ), $extension ), null, $bytes );
    if ( ! empty( $upload['error'] ) ) { return new WP_Error( 'subida', 'No se pudo guardar la captura: ' . $upload['error'] ); }
    $host = wp_parse_url( (string) get_post_meta( $post_id, '_am_demo', true ), PHP_URL_HOST );
    $alt = sprintf( 'Captura %s de %s tomada automáticamente el %s', $kind === 'mobile' ? 'móvil' : 'de escritorio', $host, date_i18n( 'j \d\e F \d\e Y' ) );
    $attachment = wp_insert_attachment( array( 'post_mime_type' => $info['mime'], 'post_title' => $alt, 'post_excerpt' => $alt, 'post_status' => 'inherit' ), $upload['file'], $post_id, true );
    if ( is_wp_error( $attachment ) ) { return $attachment; }
    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata( $attachment, wp_generate_attachment_metadata( $attachment, $upload['file'] ) );
    update_post_meta( $attachment, '_wp_attachment_image_alt', $alt );
    update_post_meta( $attachment, '_am_capture_kind', $kind );
    update_post_meta( $attachment, '_am_capture_auto', current_time( 'mysql' ) );

    $previous = am_capture_find_kind( $post_id, $kind );
    $gallery = array_values( array_filter( array_map( 'absint', explode( ',', (string) get_post_meta( $post_id, '_am_gallery', true ) ) ) ) );
    $position = $previous ? array_search( $previous, $gallery, true ) : false;
    if ( $position !== false ) { $gallery[ $position ] = $attachment; } elseif ( $kind === 'desktop' ) { array_unshift( $gallery, $attachment ); } else { $gallery[] = $attachment; }
    update_post_meta( $post_id, '_am_gallery', implode( ',', array_unique( $gallery ) ) );
    if ( $kind === 'desktop' ) { set_post_thumbnail( $post_id, $attachment ); }
    return $attachment;
}

/* -------------------------------------------------------------------------- */
/* Administración: estado, acción manual y WP-CLI                              */
/* -------------------------------------------------------------------------- */
function am_capture_status_text( $post_id ) {
    $status = am_capture_status( $post_id );
    if ( ! $status ) { return get_post_meta( $post_id, '_am_capture_auto', true ) === '1' ? 'Pendiente de la primera revisión.' : ''; }
    $labels = array( 'actualizada' => 'Actualizada', 'sin_cambios' => 'Sin cambios', 'referencia' => 'Huella registrada', 'omitida' => 'Omitida', 'parcial' => 'Parcial', 'error' => 'Error', 'desactivada' => 'Desactivada' );
    return sprintf( '%s · %s · %s', $labels[ $status['estado'] ] ?? $status['estado'], mysql2date( 'j M Y H:i', $status['fecha'] ), $status['detalle'] );
}

add_filter( 'post_row_actions', function ( $actions, $post ) {
    if ( $post->post_type === 'proyecto' && current_user_can( 'edit_post', $post->ID ) && get_post_meta( $post->ID, '_am_demo', true ) ) {
        $url = wp_nonce_url( admin_url( 'admin-post.php?action=am_capture_now&id=' . $post->ID ), 'am_capture_now_' . $post->ID );
        $actions['am_capture'] = '<a href="' . esc_url( $url ) . '">Actualizar captura ahora</a>';
    }
    return $actions;
}, 10, 2 );

add_action( 'admin_post_am_capture_now', function () {
    $id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
    if ( ! $id || ! current_user_can( 'edit_post', $id ) ) { wp_die( 'Acceso denegado.', 403 ); }
    check_admin_referer( 'am_capture_now_' . $id );
    if ( function_exists( 'set_time_limit' ) ) { @set_time_limit( 150 ); } // phpcs:ignore WordPress.PHP.NoSilencedErrors
    $result = am_refresh_project_capture( $id, true );
    set_transient( 'am_capture_notice_' . get_current_user_id(), get_the_title( $id ) . ': ' . $result['detalle'], 60 );
    wp_safe_redirect( admin_url( 'edit.php?post_type=proyecto' ) );
    exit;
} );

add_action( 'admin_notices', function () {
    $notice = get_transient( 'am_capture_notice_' . get_current_user_id() );
    if ( ! $notice ) { return; }
    delete_transient( 'am_capture_notice_' . get_current_user_id() );
    echo '<div class="notice notice-info is-dismissible"><p><strong>Capturas:</strong> ' . esc_html( $notice ) . '</p></div>';
} );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    /**
     * Revisa las capturas de los proyectos y actualiza las que cambiaron.
     *
     * [--forzar]
     * : Captura aunque la interfaz no haya cambiado.
     *
     * [--id=<id>]
     * : Solo un proyecto.
     */
    WP_CLI::add_command( 'merchandev capturas', function ( $args, $assoc ) {
        $ids = ! empty( $assoc['id'] ) ? array( absint( $assoc['id'] ) ) : am_capture_project_ids();
        foreach ( $ids as $id ) {
            $result = am_refresh_project_capture( $id, ! empty( $assoc['forzar'] ) );
            if ( $result['estado'] === 'actualizada' && am_capture_find_kind( $id, 'mobile' ) ) {
                wp_unschedule_event( (int) wp_next_scheduled( AM_CAPTURE_HOOK, array( (int) $id, 'mobile' ) ), AM_CAPTURE_HOOK, array( (int) $id, 'mobile' ) );
                am_capture_mobile( $id );
                $result = am_capture_status( $id );
            }
            WP_CLI::log( sprintf( '%s: %s — %s', get_the_title( $id ), $result['estado'], $result['detalle'] ) );
        }
    } );
}
