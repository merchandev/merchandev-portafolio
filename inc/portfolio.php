<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_register_projects() {
    register_post_type( 'proyecto', array(
        'labels' => array( 'name' => 'Proyectos', 'singular_name' => 'Proyecto', 'add_new_item' => 'Añadir caso de estudio' ),
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-portfolio',
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
        'has_archive' => 'proyectos', 'rewrite' => array( 'slug' => 'proyectos', 'with_front' => false ),
    ) );
}
add_action( 'init', 'am_register_projects', 0 );

function am_project_fields() {
    return array( 'sector' => 'Sector', 'status' => 'Estado real del proyecto', 'role' => 'Mi participación', 'problem' => 'Problema', 'solution' => 'Solución', 'evidence' => 'Resultado o evidencia verificable', 'stack' => 'Tecnologías', 'demo' => 'URL de demo autorizada', 'repository' => 'URL del repositorio público' );
}
add_action( 'add_meta_boxes', function () {
    add_meta_box( 'am-project-details', 'Datos del caso de estudio', 'am_project_meta_box', 'proyecto', 'normal', 'high' );
} );
function am_project_meta_box( $post ) {
    wp_nonce_field( 'am_project_details', 'am_project_nonce' );
    echo '<p>Publica solo información comprobada. Añade capturas originales sanitizadas como imagen destacada y en el contenido. Los borradores no aparecen en el portafolio.</p>';
    foreach ( am_project_fields() as $key => $label ) {
        echo '<p><label for="am-' . esc_attr( $key ) . '"><strong>' . esc_html( $label ) . '</strong></label><br><textarea class="widefat" rows="2" id="am-' . esc_attr( $key ) . '" name="am_project[' . esc_attr( $key ) . ']">' . esc_textarea( get_post_meta( $post->ID, '_am_' . $key, true ) ) . '</textarea></p>';
    }
}
add_action( 'save_post_proyecto', function ( $id ) {
    if ( ! isset( $_POST['am_project_nonce'] ) || ! is_string( $_POST['am_project_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['am_project_nonce'] ), 'am_project_details' ) || ! current_user_can( 'edit_post', $id ) || wp_is_post_revision( $id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
    $fields = isset( $_POST['am_project'] ) && is_array( $_POST['am_project'] ) ? $_POST['am_project'] : array();
    foreach ( am_project_fields() as $key => $label ) {
        $value = isset( $fields[$key] ) && is_string( $fields[$key] ) ? wp_unslash( $fields[$key] ) : '';
        update_post_meta( $id, '_am_' . $key, in_array( $key, array( 'demo', 'repository' ), true ) ? esc_url_raw( $value, array( 'https', 'http' ) ) : sanitize_textarea_field( $value ) );
    }
} );

function am_service_url( $slug ) {
    $service = get_page_by_path( $slug, OBJECT, 'servicio' );
    return $service && $service->post_status === 'publish' ? get_permalink( $service ) : home_url( '/servicios/' );
}

// Redirect only known legacy service URLs, preserving installations in a subdirectory.
add_action( 'template_redirect', function () {
    if ( ! is_404() ) { return; }
    $request = $GLOBALS['wp']->request ?? '';
    if ( preg_match( '~^servicios/([^/]+)/?$~', $request, $matches ) ) {
        $service = get_page_by_path( sanitize_title( $matches[1] ), OBJECT, 'servicio' );
        if ( $service && $service->post_status === 'publish' ) { wp_safe_redirect( get_permalink( $service ), 301 ); exit; }
    }
} );

function am_setup_portfolio() {
    am_register_servicios_cpt(); am_register_projects();
    am_auto_create_services(); am_auto_create_pages(); am_create_legal_pages();
    $drafts = array(
        'metransfers' => array( 'MeTransfers Barcelona', 'Movilidad y turismo' ),
        'diario-mercantil' => array( 'Diario Mercantil', 'Gestión documental' ),
        'guia-medica-monagas' => array( 'Guía Médica Monagas', 'Directorio profesional' ),
        'motores-morichal' => array( 'Motores Morichal', 'Automoción' ),
        'minima-organics' => array( 'Mínima Organics', 'Comercio electrónico' ),
    );
    $errors = false;
    foreach ( $drafts as $slug => $data ) {
        if ( get_page_by_path( $slug, OBJECT, 'proyecto' ) ) { continue; }
        $id = wp_insert_post( array( 'post_type' => 'proyecto', 'post_status' => 'draft', 'post_title' => $data[0], 'post_name' => $slug,
            'post_content' => '<h2>Contexto</h2><p>Documentar el proceso anterior y la necesidad real.</p><h2>Decisiones de implementación</h2><p>Describir los módulos entregados, las alternativas y las decisiones técnicas.</p><h2>Evidencia</h2><p>Añadir capturas originales sin datos personales, enlaces autorizados y resultados fechados.</p>',
        ), true );
        if ( $id && ! is_wp_error( $id ) ) {
            update_post_meta( $id, '_am_sector', $data[1] );
            // Existing biography is the source for these two descriptions; no outcome metrics are inferred.
            if ( $slug === 'metransfers' ) {
                update_post_meta( $id, '_am_role', 'Programador Full Stack. Desarrollo y mantenimiento del sistema de reservas.' );
                update_post_meta( $id, '_am_solution', 'Sistema de reservas especializado para traslados. Completar los módulos y decisiones de implementación con documentación del proyecto.' );
            }
            if ( $slug === 'motores-morichal' ) {
                update_post_meta( $id, '_am_role', 'Desarrollador Web. Desarrollo del tema y plugins personalizados.' );
                update_post_meta( $id, '_am_solution', 'Tema WordPress a medida, formularios e inventario de vehículos, según la trayectoria publicada en el tema.' );
            }
        }
        else { $errors = true; }
    }
    flush_rewrite_rules( false );
    if ( ! $errors ) { update_option( 'am_portfolio_setup_v2', '1' ); }
}
add_action( 'after_switch_theme', 'am_setup_portfolio' );
add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) || get_option( 'am_portfolio_setup_v2' ) ) { return; }
    echo '<div class="notice notice-info"><p>Merchan.Dev: prepara las rutas y cinco borradores de proyectos. El contenido existente se conserva.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
    wp_nonce_field( 'am_setup_portfolio' );
    echo '<input type="hidden" name="action" value="am_setup_portfolio"><p><button class="button button-primary">Preparar portafolio</button></p></form></div>';
} );
add_action( 'admin_post_am_setup_portfolio', function () {
    if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Acceso denegado.' ); }
    check_admin_referer( 'am_setup_portfolio' ); am_setup_portfolio();
    wp_safe_redirect( admin_url( 'edit.php?post_type=proyecto' ) ); exit;
} );

// Structured identity; project claims come only from published editorial content.
add_action( 'wp_head', function () {
    if ( ! is_front_page() && ! is_singular( 'proyecto' ) ) { return; }
    $data = is_front_page() ? array( '@context' => 'https://schema.org', '@type' => 'Person', 'name' => 'Arturo Merchán', 'url' => home_url( '/' ), 'jobTitle' => 'Desarrollador Full-Stack', 'sameAs' => array( 'https://github.com/merchandev', 'https://www.linkedin.com/in/merchandev/' ) ) : array( '@context' => 'https://schema.org', '@type' => 'CreativeWork', 'name' => get_the_title(), 'url' => get_permalink(), 'description' => wp_strip_all_tags( get_the_excerpt() ) );
    echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ) . '</script>';
} );
