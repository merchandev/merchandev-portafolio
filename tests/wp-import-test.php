<?php
/**
 * Prueba de integración del importador sobre un WordPress REAL de pruebas (nunca producción):
 *
 *   AM_TEST_ENV=1 wp eval-file tests/wp-import-test.php
 *
 * Comprueba: contenido creado, revisión del texto anterior de los servicios, idempotencia,
 * respeto de ediciones del administrador, borradores sin duplicados y proyectos en la papelera.
 * Deja la base de datos como estaba al terminar.
 */
if ( ! defined( 'ABSPATH' ) || getenv( 'AM_TEST_ENV' ) !== '1' ) { fwrite( STDERR, "Define AM_TEST_ENV=1 y ejecútalo con wp eval-file en un entorno de pruebas.\n" ); exit( 1 ); }

$failures = array();
$check = function ( $condition, $label ) use ( &$failures ) { echo ( $condition ? 'ok   ' : 'FAIL ' ) . $label . "\n"; if ( ! $condition ) { $failures[] = $label; } };
$count = function ( $type, $status = array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ) ) { return count( get_posts( array( 'post_type' => $type, 'post_status' => $status, 'numberposts' => -1, 'fields' => 'ids' ) ) ); };
$by_slug = function ( $slug, $type ) { return am_find_post_by_slug( $slug, $type ); };

am_setup_portfolio();
$projects = $count( 'proyecto' ); $services = $count( 'servicio' ); $media = $count( 'attachment', 'inherit' );
$check( $projects === count( am_seed_projects() ), "proyectos importados ($projects)" );
$check( count( am_get_projects( true ) ) === 6, 'seis proyectos destacados' );
$check( $services >= 11, "servicios: 8 existentes + 3 especialidades ($services)" );
$check( $media >= 7, "capturas en la biblioteca ($media)" );
foreach ( am_seed_categories() as $slug => $category ) { $check( (bool) term_exists( $slug, 'tipo_proyecto' ), "categoría $slug" ); }

$seo = $by_slug( 'seo-tecnico', 'servicio' );
if ( $seo && get_post_meta( $seo->ID, '_am_seed_version', true ) ) {
    $revisions = wp_get_post_revisions( $seo->ID, array( 'order' => 'ASC' ) );
    $oldest = $revisions ? reset( $revisions ) : null;
    $legacy = wp_list_pluck( am_legacy_services(), 'post_content', 'post_name' );
    $check( $oldest && am_normalize_html( $oldest->post_content ) === am_normalize_html( $legacy['seo-tecnico'] ), 'el texto original del servicio queda en Revisiones' );
}

am_setup_portfolio();
$check( $count( 'proyecto' ) === $projects && $count( 'servicio' ) === $services && $count( 'attachment', 'inherit' ) === $media, 'segunda importación sin duplicados' );

// Escenarios de edición del administrador.
$rotec = $by_slug( 'rotec', 'proyecto' );
$zyrus = $by_slug( 'zyrus', 'proyecto' );
$service = $by_slug( 'infraestructura-fisica-continuidad', 'servicio' );
$backup = array( 'rotec_sector' => get_post_meta( $rotec->ID, '_am_sector', true ), 'rotec_stack' => get_post_meta( $rotec->ID, '_am_stack', true ), 'service' => $service->post_content, 'service_version' => get_post_meta( $service->ID, '_am_seed_version', true ) );
wp_update_post( array( 'ID' => $rotec->ID, 'post_status' => 'draft' ) );
update_post_meta( $rotec->ID, '_am_sector', 'Editado por el administrador' );
delete_post_meta( $rotec->ID, '_am_stack' );
wp_trash_post( $zyrus->ID );
delete_post_meta( $service->ID, '_am_seed_version' );
wp_update_post( array( 'ID' => $service->ID, 'post_content' => '<p>Texto propio del administrador.</p>' ) );
wp_set_current_user( 0 ); // Igual que WP-CLI o cron: sin sesión, donde las consultas singulares ocultan borradores.

$report = am_run_portfolio_import();
$check( $count( 'proyecto' ) === $projects, 'sin duplicados con un borrador y un proyecto en la papelera' );
$check( get_post_status( $rotec->ID ) === 'draft', 'el borrador sigue en borrador' );
$check( get_post_meta( $rotec->ID, '_am_sector', true ) === 'Editado por el administrador', 'no se sobrescribe un campo editado' );
$check( get_post_meta( $rotec->ID, '_am_stack', true ) !== '', 'se completa un campo vacío' );
$check( get_post_status( $zyrus->ID ) === 'trash', 'el proyecto en la papelera no se recrea ni se restaura' );
$check( strpos( get_post_field( 'post_content', $service->ID ), 'Texto propio del administrador.' ) !== false, 'el servicio editado no se modifica' );
$check( in_array( get_the_title( $service ), $report['servicios_personalizados'], true ), 'el informe lista el servicio personalizado' );
$check( empty( $report['errores'] ), 'importación sin errores' );

// Restaurar el estado inicial.
wp_untrash_post( $zyrus->ID );
wp_update_post( array( 'ID' => $zyrus->ID, 'post_status' => 'publish', 'post_name' => 'zyrus' ) );
wp_update_post( array( 'ID' => $rotec->ID, 'post_status' => 'publish' ) );
update_post_meta( $rotec->ID, '_am_sector', $backup['rotec_sector'] );
update_post_meta( $rotec->ID, '_am_stack', $backup['rotec_stack'] );
wp_update_post( array( 'ID' => $service->ID, 'post_content' => $backup['service'] ) );
if ( $backup['service_version'] ) { update_post_meta( $service->ID, '_am_seed_version', $backup['service_version'] ); }

echo $failures ? 'FAIL: ' . count( $failures ) . " comprobaciones\n" : "PASS: importador idempotente y respetuoso con las ediciones\n";
exit( $failures ? 1 : 0 );
