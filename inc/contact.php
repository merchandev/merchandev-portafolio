<?php
/** Contact delivery: persistence first, then a separately tracked notification. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Opciones del campo «¿Qué necesitas?» (valor guardado en el lead => texto visible). */
function am_contact_service_options() {
    return array(
        'Producto digital o sistema a medida' => 'Producto digital, plataforma o sistema a medida',
        'Aplicación móvil' => 'Aplicación móvil conectada',
        'Integraciones y automatización' => 'Integraciones, pagos o automatización',
        'WordPress o comercio electrónico' => 'WordPress o comercio electrónico',
        'Infraestructura y despliegue' => 'Infraestructura, despliegue o streaming',
        'SEO técnico' => 'SEO técnico',
        'Consultoría' => 'Consultoría, auditoría u otro',
    );
}

/**
 * Opción preseleccionada a partir de ?interes=<slug del servicio de origen>.
 * No se usa ?servicio=: es la variable de consulta del tipo de contenido «servicio» y
 * WordPress mostraba el servicio (o un 404) en lugar de la página de contacto.
 */
function am_contact_preselected_service() {
    $raw = isset( $_GET['interes'] ) && is_string( $_GET['interes'] ) ? sanitize_title( wp_unslash( $_GET['interes'] ) ) : '';
    if ( $raw === '' ) { return ''; }
    $map = array(
        'consultoria' => 'Consultoría', 'movil' => 'Aplicación móvil', 'desarrollo-software' => 'Producto digital o sistema a medida', 'saas' => 'Producto digital o sistema a medida',
        'wordpress' => 'WordPress o comercio electrónico', 'ecommerce' => 'WordPress o comercio electrónico', 'integraci' => 'Integraciones y automatización',
        'infraestructura' => 'Infraestructura y despliegue', 'vps' => 'Infraestructura y despliegue', 'devops' => 'Infraestructura y despliegue', 'streaming' => 'Infraestructura y despliegue',
        'seo' => 'SEO técnico',
    );
    foreach ( $map as $needle => $option ) {
        if ( strpos( $raw, $needle ) !== false ) { return $option; }
    }
    return 'Consultoría';
}

function am_contact_value( $key, $legacy = '' ) {
    $value = $_POST[$key] ?? ( $legacy ? ( $_POST[$legacy] ?? '' ) : '' );
    return is_string( $value ) ? trim( wp_unslash( $value ) ) : '';
}

/** Mensajes visibles del formulario, por código (el código viaja en la URL cuando no hay JavaScript). */
function am_contact_messages() {
    return array(
        'enviado' => 'Tu solicitud quedó registrada. Gracias por contarme sobre tu proyecto.',
        'caducado' => 'La sesión ha caducado. Recarga la página e inténtalo de nuevo.',
        'rechazado' => 'No se pudo procesar la solicitud.',
        'incompleto' => 'Indica tu nombre, un correo válido y un mensaje.',
        'extenso' => 'El mensaje o alguno de los campos supera el tamaño permitido.',
        'privacidad' => 'Acepta el tratamiento de tus datos para responder a la consulta.',
        'limite' => 'Has enviado varias solicitudes. Espera unos minutos o escríbeme por correo.',
        'error' => 'No se pudo guardar tu solicitud. Inténtalo de nuevo o escribe a soporte@merchan.dev.',
    );
}

function am_contact_result( $success, $code ) {
    $messages = am_contact_messages();
    $message = $messages[ $code ] ?? $messages['error'];
    if ( wp_doing_ajax() ) {
        if ( $success ) { wp_send_json_success( $message ); }
        wp_send_json_error( $message );
    }
    // Envío sin JavaScript: vuelve al formulario de origen con el resultado.
    $back = wp_validate_redirect( wp_get_referer(), home_url( '/contacto/' ) );
    wp_safe_redirect( add_query_arg( 'contacto', $code, remove_query_arg( 'contacto', $back ) ) . '#contacto', 303 );
    exit;
}

/** Nonce fresco para el formulario: las páginas cacheadas (LiteSpeed/CDN) pueden servir uno caducado. */
function am_contact_token() {
    wp_send_json_success( wp_create_nonce( 'am_nonce' ) );
}
add_action( 'wp_ajax_am_contact_token', 'am_contact_token' );
add_action( 'wp_ajax_nopriv_am_contact_token', 'am_contact_token' );

function am_handle_contact_form() {
    $nonce = am_contact_value( 'security' );
    if ( ! wp_verify_nonce( $nonce, 'am_nonce' ) ) {
        am_contact_result( false, 'caducado' );
        return;
    }
    if ( am_contact_value( 'website' ) !== '' ) {
        am_contact_result( false, 'rechazado' ); return;
    }
    $name = sanitize_text_field( am_contact_value( 'name', 'nombre' ) );
    $raw_email = am_contact_value( 'email' );
    $email = sanitize_email( $raw_email );
    $service = sanitize_text_field( am_contact_value( 'service', 'servicio' ) );
    $message = sanitize_textarea_field( am_contact_value( 'message', 'mensaje' ) );
    if ( ! $name || ! $message || ! is_email( $raw_email ) || $raw_email !== $email ) {
        am_contact_result( false, 'incompleto' ); return;
    }
    if ( strlen( $name ) > 150 || strlen( $email ) > 254 || strlen( $service ) > 150 || strlen( $message ) > 10000 ) {
        am_contact_result( false, 'extenso' ); return;
    }
    if ( am_contact_value( 'privacy' ) !== '1' ) {
        am_contact_result( false, 'privacidad' ); return;
    }
    // Only a salted fingerprint is stored, never the visitor's IP. Do not trust forwarded headers.
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) && is_string( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $key = 'am_contact_' . hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) );
    $attempts = (int) get_transient( $key );
    if ( $attempts >= 5 ) {
        am_contact_result( false, 'limite' ); return;
    }
    set_transient( $key, $attempts + 1, 10 * MINUTE_IN_SECONDS );
    $post_id = wp_insert_post( array(
        'post_title' => $name . ' — ' . $service,
        'post_content' => "Email: $email\nServicio: $service\n\n$message",
        'post_type' => 'lead', 'post_status' => 'private',
    ), true );
    if ( is_wp_error( $post_id ) || ! $post_id ) {
        am_contact_result( false, 'error' ); return;
    }
    update_post_meta( $post_id, '_lead_email', $email );
    update_post_meta( $post_id, '_lead_service', $service );
    update_post_meta( $post_id, '_lead_privacy_at', current_time( 'mysql', true ) );
    $recipient = apply_filters( 'am_contact_recipient', get_option( 'admin_email' ) );
    // Un error del transporte de correo (SMTP mal configurado, plugin de correo) no debe
    // ocultar al visitante que su solicitud ya quedó guardada: se registra como aviso fallido.
    try {
        $sent = wp_mail( $recipient, 'Nueva solicitud de Merchan.Dev #' . $post_id,
            "Nombre: $name\nEmail: $email\nServicio: $service\n\n$message\n\n" . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
            array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ) );
    } catch ( \Throwable $error ) {
        $sent = false;
        error_log( 'Merchan.Dev: el aviso del lead #' . $post_id . ' no se pudo enviar (' . get_class( $error ) . ').' );
    }
    update_post_meta( $post_id, '_lead_notification_status', $sent ? 'accepted' : 'failed' );
    // wp_mail acceptance does not confirm inbox delivery. The lead survives any mail failure.
    am_contact_result( true, 'enviado' );
}
add_action( 'wp_ajax_am_submit_contact', 'am_handle_contact_form' );
add_action( 'wp_ajax_nopriv_am_submit_contact', 'am_handle_contact_form' );
add_action( 'admin_post_am_submit_contact', 'am_handle_contact_form' );
add_action( 'admin_post_nopriv_am_submit_contact', 'am_handle_contact_form' );

function am_lead_columns( $columns ) { $columns['am_notification'] = 'Aviso por correo'; return $columns; }
add_filter( 'manage_lead_posts_columns', 'am_lead_columns' );
function am_lead_column( $column, $post_id ) {
    if ( $column === 'am_notification' ) {
        $status = get_post_meta( $post_id, '_lead_notification_status', true );
        echo esc_html( $status === 'accepted' ? 'Aceptado por correo (verificar entrega)' : ( $status === 'failed' ? 'Falló el aviso: revisar SMTP' : 'Sin registro' ) );
    }
}
add_action( 'manage_lead_posts_custom_column', 'am_lead_column', 10, 2 );
