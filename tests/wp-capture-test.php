<?php
/**
 * Prueba de las capturas automáticas sobre un WordPress REAL de pruebas, sin red:
 *
 *   AM_TEST_ENV=1 wp eval-file tests/wp-capture-test.php
 *
 * Las respuestas HTTP de la web del proyecto y de Microlink se simulan con pre_http_request
 * y la captura con el filtro am_capture_screenshot. Crea un proyecto temporal y lo borra.
 */
if ( ! defined( 'ABSPATH' ) || getenv( 'AM_TEST_ENV' ) !== '1' ) { fwrite( STDERR, "Define AM_TEST_ENV=1 y ejecútalo con wp eval-file en un entorno de pruebas.\n" ); exit( 1 ); }

$failures = array();
$check = function ( $condition, $label ) use ( &$failures ) { echo ( $condition ? 'ok   ' : 'FAIL ' ) . $label . "\n"; if ( ! $condition ) { $failures[] = $label; } };

// Web simulada del proyecto.
$site = array( 'status' => 200, 'title' => 'Proyecto de prueba', 'css' => 'body{color:#111}', 'microlink' => 0, 'shots' => 0 );
add_filter( 'pre_http_request', function ( $pre, $args, $url ) use ( &$site ) {
    $reply = function ( $code, $body ) { return array( 'headers' => array(), 'body' => $body, 'response' => array( 'code' => $code, 'message' => '' ), 'cookies' => array(), 'filename' => null ); };
    if ( strpos( $url, 'https://demo.test/style.css' ) === 0 ) { return $reply( $site['status'] === 202 ? 202 : 200, $site['css'] ); }
    if ( strpos( $url, 'https://demo.test/' ) === 0 ) {
        return $reply( $site['status'], '<html><head><title>' . $site['title'] . '</title><link rel="stylesheet" href="/style.css?ver=1"><script src="https://demo.test/app.js?ver=1"></script><link rel="stylesheet" href="https://fonts.example/x.css"></head><body><h1>Hola</h1><p>Noticia ' . wp_rand() . '</p></body></html>' );
    }
    if ( strpos( $url, 'microlink.io' ) !== false ) {
        $site['microlink']++;
        return $reply( 200, wp_json_encode( array( 'status' => 'success', 'data' => array( 'title' => $site['title'], 'styles' => array( 'https://demo.test/style.css?ver=1' ), 'scripts' => array( 'https://demo.test/app.js?ver=1' ) ) ) ) );
    }
    return $pre;
}, 10, 3 );
$image = file_get_contents( get_theme_file_path( 'assets/image/proyectos/guia-medica-movil.webp' ) );
add_filter( 'am_capture_screenshot', function ( $pre, $url, $kind ) use ( &$site, $image ) { $site['shots']++; return $image; }, 10, 3 );

// Proyecto temporal con portada (escritorio) y captura móvil.
$post_id = wp_insert_post( array( 'post_type' => 'proyecto', 'post_status' => 'publish', 'post_title' => 'Proyecto de prueba de capturas', 'post_name' => 'prueba-capturas-qa' ) );
update_post_meta( $post_id, '_am_demo', 'https://demo.test/' );
update_post_meta( $post_id, '_am_capture_auto', '1' );
$created = array();
foreach ( array( 'desktop', 'mobile' ) as $kind ) {
    $upload = wp_upload_bits( "qa-$kind.webp", null, $image );
    $created[ $kind ] = wp_insert_attachment( array( 'post_mime_type' => 'image/webp', 'post_title' => "qa $kind", 'post_status' => 'inherit' ), $upload['file'], $post_id );
    update_post_meta( $created[ $kind ], '_am_capture_kind', $kind );
}
set_post_thumbnail( $post_id, $created['desktop'] );
update_post_meta( $post_id, '_am_gallery', $created['desktop'] . ',' . $created['mobile'] );

$check( in_array( $post_id, am_capture_project_ids(), true ), 'el proyecto entra en la revisión diaria' );
$result = am_refresh_project_capture( $post_id );
$check( $result['estado'] === 'referencia' && $site['shots'] === 0, 'primera revisión: guarda la huella y conserva la captura' );
$result = am_refresh_project_capture( $post_id );
$check( $result['estado'] === 'sin_cambios' && $site['shots'] === 0, 'sin cambios de interfaz (aunque cambie el contenido): no captura' );

$site['css'] = 'body{color:#222}';
$result = am_refresh_project_capture( $post_id );
$thumb = (int) get_post_thumbnail_id( $post_id );
$gallery = array_map( 'intval', explode( ',', get_post_meta( $post_id, '_am_gallery', true ) ) );
$check( $result['estado'] === 'actualizada' && $site['shots'] === 1, 'cambio de estilos: toma una captura nueva' );
$check( $thumb !== $created['desktop'] && $gallery[0] === $thumb && $gallery[1] === $created['mobile'], 'la nueva captura sustituye a la portada en su posición' );
$check( (bool) wp_next_scheduled( AM_CAPTURE_HOOK, array( $post_id, 'mobile' ) ), 'programa la captura móvil en una tarea aparte' );
$check( get_post( $created['desktop'] ) instanceof WP_Post && get_post_field( 'post_type', $created['desktop'] ) === 'attachment', 'la captura anterior se conserva en la biblioteca' );
am_capture_mobile( $post_id );
$gallery = array_map( 'intval', explode( ',', get_post_meta( $post_id, '_am_gallery', true ) ) );
$check( $gallery[1] !== $created['mobile'] && get_post_meta( $gallery[1], '_am_capture_kind', true ) === 'mobile', 'la captura móvil también se sustituye' );
$check( get_post_meta( $post_id, '_am_capture_updated', true ) !== '' && am_project_data( get_post( $post_id ) )['capture_updated'] !== '', 'se registra la fecha de actualización para mostrarla' );

$site['title'] = 'Mantenimiento programado';
$site['css'] = 'body{color:#333}';
$shots = $site['shots'];
$result = am_refresh_project_capture( $post_id );
$check( $result['estado'] === 'omitida' && $site['shots'] === $shots, 'página de mantenimiento: no captura' );

$site['title'] = 'Proyecto de prueba';
$site['status'] = 202; // Verificación antibots para clientes que no son navegadores.
$result = am_refresh_project_capture( $post_id );
$check( $site['microlink'] > 0 && in_array( $result['estado'], array( 'actualizada', 'sin_cambios' ), true ), 'web con verificación antibots: lee los recursos con el navegador remoto' );

update_post_meta( $post_id, '_am_capture_auto', '' );
$check( ! in_array( $post_id, am_capture_project_ids(), true ) && am_refresh_project_capture( $post_id )['estado'] === 'desactivada', 'desactivada: el proyecto no se revisa' );
$check( (bool) wp_next_scheduled( AM_CAPTURE_DAILY_HOOK ), 'la revisión diaria está programada' );

// Limpieza.
wp_unschedule_hook( AM_CAPTURE_HOOK );
foreach ( get_posts( array( 'post_type' => 'attachment', 'post_parent' => $post_id, 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids' ) ) as $attachment ) { wp_delete_attachment( $attachment, true ); }
wp_delete_post( $post_id, true );

echo $failures ? 'FAIL: ' . count( $failures ) . " comprobaciones\n" : "PASS: capturas automáticas (huella, sustitución, móvil, mantenimiento, antibots)\n";
exit( $failures ? 1 : 0 );
