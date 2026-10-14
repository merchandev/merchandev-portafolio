<?php
/**
 * Especialidades: estructura de los tres servicios principales y su relación con
 * los servicios específicos existentes (cuyos slugs y URL se conservan).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_specialties() {
    return array(
        'desarrollo' => array(
            'slug' => 'desarrollo-software-productos-digitales',
            'title' => 'Desarrollo de software y productos digitales',
            'short' => 'Desarrollo de software',
            'icon' => 'bx-code-block',
            'principal' => true,
            'summary' => 'Aplicaciones web, plataformas SaaS, sistemas administrativos y apps móviles construidos sobre los procesos reales de cada negocio: reservas, pagos, documentos, roles y paneles de gestión.',
            'scope' => array( 'Desarrollo Full-Stack', 'Aplicaciones web', 'Plataformas SaaS', 'Sistemas empresariales y administrativos', 'Portales privados', 'Aplicaciones móviles', 'Reservas y pagos', 'Gestión documental', 'Productos a medida' ),
            'services' => array( 'desarrollo-saas-microservicios', 'desarrollo-avanzado-wordpress', 'seo-tecnico' ),
        ),
        'arquitectura' => array(
            'slug' => 'arquitectura-integraciones-automatizacion',
            'title' => 'Arquitectura, integraciones y automatización',
            'short' => 'Arquitectura e integraciones',
            'icon' => 'bx-git-merge',
            'principal' => false,
            'summary' => 'Diseño cómo se conectan las piezas: modelo de datos, APIs, autenticación y permisos, pagos, correo transaccional, colas de trabajo e integraciones con WordPress, Shopify y servicios externos.',
            'scope' => array( 'Arquitectura de aplicaciones', 'APIs REST', 'Modelado de bases de datos', 'Autenticación, roles y permisos', 'Pasarelas de pago', 'Correo transaccional y notificaciones', 'Colas y procesos asíncronos', 'Integraciones WordPress y Shopify' ),
            'services' => array( 'ecommerce-migraciones', 'consultoria-ia-rendimiento' ),
        ),
        'infraestructura' => array(
            'slug' => 'infraestructura-seguridad-operacion',
            'title' => 'Infraestructura, seguridad y operación',
            'short' => 'Infraestructura y operación',
            'icon' => 'bx-server',
            'principal' => false,
            'summary' => 'Llevo las aplicaciones a producción y las mantengo: servidores Linux, Docker, proxies con HTTPS, despliegues, copias de seguridad y controles de seguridad integrados en el desarrollo.',
            'scope' => array( 'VPS y Linux', 'Docker y Docker Compose', 'Nginx, Caddy y Traefik', 'HTTPS/TLS', 'Despliegues y CI/CD', 'Copias de seguridad', 'Gestión de secretos', 'Streaming de audio y video' ),
            'services' => array( 'administracion-vps-devops', 'streaming-audio-video', 'infraestructura-fisica-continuidad' ),
        ),
    );
}

/** Especialidad a la que pertenece un servicio (por slug), o null si es una especialidad o no está mapeado. */
function am_specialty_for_service( $slug ) {
    foreach ( am_specialties() as $key => $specialty ) {
        if ( in_array( $slug, $specialty['services'], true ) ) { return $key; }
    }
    return null;
}

function am_specialty_by_slug( $slug ) {
    foreach ( am_specialties() as $key => $specialty ) {
        if ( $specialty['slug'] === $slug ) { return $key; }
    }
    return null;
}

/** URL canónica de un servicio publicado; si no existe, la página de servicios. */
function am_service_url( $slug ) {
    $service = get_page_by_path( $slug, OBJECT, 'servicio' );
    return $service && $service->post_status === 'publish' ? get_permalink( $service ) : home_url( '/servicios/' );
}

function am_service_exists( $slug ) {
    $service = get_page_by_path( $slug, OBJECT, 'servicio' );
    return $service && $service->post_status === 'publish';
}

/** Proyectos publicados que demuestran una especialidad (destacados primero). */
function am_specialty_projects( $key, $limit = 4 ) {
    $posts = am_get_projects( null, 40 );
    $matches = array();
    foreach ( $posts as $post ) {
        $data = am_project_data( $post );
        if ( in_array( $key, $data['specialties'], true ) ) { $matches[] = $data; }
    }
    usort( $matches, function ( $a, $b ) { return (int) $b['featured'] - (int) $a['featured']; } );
    return array_slice( $matches, 0, $limit );
}

// Las URL antiguas /servicios/<slug>/ redirigen con 301 a la URL nativa /servicio/<slug>/.
add_action( 'template_redirect', function () {
    if ( ! is_404() ) { return; }
    $request = $GLOBALS['wp']->request ?? '';
    if ( preg_match( '~^servicios/([^/]+)/?$~', $request, $matches ) ) {
        $service = get_page_by_path( sanitize_title( $matches[1] ), OBJECT, 'servicio' );
        if ( $service && $service->post_status === 'publish' ) { wp_safe_redirect( get_permalink( $service ), 301 ); exit; }
    }
} );
