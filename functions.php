<?php
/**
 * Theme Functions
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// -----------------------------------------------------------------------------
// 0. Disable WP Blocks, Global Styles & Emojis to have 100% css control
// -----------------------------------------------------------------------------
function am_disable_wp_bloat() {
    // Remove block library css
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' ); // If woocommerce is active
    
    // Remove global styles
    wp_dequeue_style( 'global-styles' );
    
    // Remove classic theme styles
    wp_dequeue_style( 'classic-theme-styles' );
    
    // Remove emojis
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'wp_enqueue_scripts', 'am_disable_wp_bloat', 100 );

// Remove SVG filters from body
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

// -----------------------------------------------------------------------------
// 1. Enqueue local assets
// -----------------------------------------------------------------------------
function am_enqueue_assets() {
    $theme_version = wp_get_theme()->get( 'Version' );
    
    // Main CSS
    wp_enqueue_style( 'am-main-css', get_template_directory_uri() . '/assets/css/main.css', array(), filemtime(get_template_directory() . '/assets/css/main.css') );
    
    // Main JS (with GSAP dependencies)
    wp_enqueue_script( 'gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true );
    wp_enqueue_script( 'gsap-scroll', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', true );
    wp_enqueue_script( 'am-main-js', get_template_directory_uri() . '/assets/js/main.js', array('gsap', 'gsap-scroll'), filemtime(get_template_directory() . '/assets/js/main.js'), true );
    
    // Background Animation JS
    wp_enqueue_script( 'am-bg-anim-js', get_template_directory_uri() . '/assets/js/bg-animation.js', array(), null, true );
    
    // We can also pass data to JS
    wp_localize_script('am-main-js', 'amData', array(
        'ajax_url'    => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('am_nonce'),
        'github_repos' => array(
            'https://api.github.com/repos/merchandev/Evento-Conexi-n-Publicitaria',
            'https://api.github.com/repos/merchandev/radio-streaming-server',
            'https://api.github.com/repos/merchandev/Galer-a-bento-para-entradas-en-WordPress-'
        )
    ));
}
add_action( 'wp_enqueue_scripts', 'am_enqueue_assets' );

// -----------------------------------------------------------------------------
// 2. Theme Setup
// -----------------------------------------------------------------------------
function am_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'am_theme_setup' );

// -----------------------------------------------------------------------------
// 3. Cleanup `<head>`
// -----------------------------------------------------------------------------
remove_action('wp_head', 'rsd_link'); // Remove Really Simple Discovery link
remove_action('wp_head', 'wlwmanifest_link'); // Remove Windows Live Writer
remove_action('wp_head', 'wp_generator'); // Remove WP version
remove_action('wp_head', 'wp_shortlink_wp_head'); // Remove shortlink
remove_action('wp_head', 'rest_output_link_wp_head'); // Remove REST API link
remove_action('wp_head', 'wp_oembed_add_discovery_links'); // Remove oEmbed links
remove_action('wp_head', 'wp_oembed_add_host_js'); // Remove oEmbed script

// -----------------------------------------------------------------------------
// 4. Custom Post Type: Servicios
// -----------------------------------------------------------------------------
function am_register_servicios_cpt() {
    $labels = array(
        'name'                  => 'Servicios',
        'singular_name'         => 'Servicio',
        'menu_name'             => 'Servicios',
        'add_new'               => 'Añadir Nuevo',
        'add_new_item'          => 'Añadir Nuevo Servicio',
        'edit_item'             => 'Editar Servicio',
        'view_item'             => 'Ver Servicio',
        'all_items'             => 'Todos los Servicios',
    );
    $args = array(
        'label'                 => 'Servicio',
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-hammer',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'rewrite'               => array( 'slug' => 'servicio' ),
        'show_in_rest'          => true,
    );
    register_post_type( 'servicio', $args );

    // Flush rewrite rules once to make sure /servicios/ works
    if ( ! get_option( 'am_cpt_flushed' ) ) {
        flush_rewrite_rules();
        update_option( 'am_cpt_flushed', true );
    }
}
add_action( 'init', 'am_register_servicios_cpt', 0 );

// -----------------------------------------------------------------------------
// 4.1 AJAX Search
// -----------------------------------------------------------------------------
function am_ajax_search() {
    check_ajax_referer('am_nonce', 'security');
    
    $query = sanitize_text_field($_POST['query']);
    if(strlen($query) < 2) {
        wp_send_json_success(array());
    }

    $args = array(
        'post_type' => array('post', 'page', 'servicio'),
        'post_status' => 'publish',
        's' => $query,
        'posts_per_page' => 5
    );

    $search_query = new WP_Query($args);
    $results = array();

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();
            
            $icon = 'bx-file';
            if (get_post_type() == 'servicio') $icon = 'bx-cube-alt';
            if (get_post_type() == 'post') $icon = 'bx-news';

            $results[] = array(
                'title' => get_the_title(),
                'url' => get_permalink(),
                'type' => get_post_type(),
                'icon' => $icon
            );
        }
    }

    wp_reset_postdata();
    wp_send_json_success($results);
}
add_action('wp_ajax_am_search', 'am_ajax_search');
add_action('wp_ajax_nopriv_am_search', 'am_ajax_search');

// -----------------------------------------------------------------------------
// 5. Auto-Generate Services Content (One-time)
// -----------------------------------------------------------------------------
function am_auto_create_services() {
    if ( get_option( 'am_services_created' ) ) {
        return;
    }

    $services = array(
        array(
            'post_title' => 'Desarrollo SaaS y Arquitectura de Microservicios',
            'post_name'  => 'desarrollo-saas-microservicios',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Transforma tu idea de negocio en una plataforma escalable en la nube.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Diseño de bases de datos multi-tenant, orquestación de contenedores con Docker y Traefik, y desarrollo de sistemas modulares.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Startups o empresas medianas que necesitan digitalizar sus procesos internos o lanzar un producto digital al mercado.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Configuración de Servidores para Streaming de Audio y Video',
            'post_name'  => 'streaming-audio-video',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Infraestructura propia para transmisiones en vivo sin interrupciones ni latencia.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:list --><ul><li>Instalación, configuración y administración de servidores VPS (entornos Linux) dedicados exclusivamente al alojamiento de medios.</li><li>Despliegue y optimización de servidores RTMP para la ingesta y distribución de video y audio en alta calidad.</li><li>Implementación de arquitecturas simulcast, permitiendo la transmisión simultánea de una misma señal a múltiples plataformas.</li><li>Contenerización de los servicios de streaming utilizando Docker y gestión del tráfico de red y certificados de seguridad mediante proxies inversos (Traefik/Nginx).</li><li>Configuración de la continuidad operativa para garantizar transmisiones 24/7 a prueba de caídas.</li></ul><!-- /wp:list --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Canales de televisión, emisoras de radio, productoras de contenido audiovisual y corporaciones que necesitan total control, estabilidad y propiedad sobre su infraestructura de transmisión en vivo.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Administración de Servidores VPS y DevOps',
            'post_name'  => 'administracion-vps-devops',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Entornos web ultra-rápidos, seguros y siempre disponibles.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Configuración de entornos Linux (como Hostinger), hardening de seguridad, gestión de proxies inversos (Nginx), control de versiones con Git/GitHub y automatización de despliegues.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Agencias, desarrolladores o empresas que tienen su propio software pero necesitan un experto que mantenga sus servidores rápidos, seguros y siempre en línea.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Desarrollo Avanzado en WordPress',
            'post_name'  => 'desarrollo-avanzado-wordpress',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Soluciones a medida que rompen los límites tradicionales del CMS.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Programación de Custom Themes desde cero (incluyendo simulaciones WebGL y efectos visuales modernos), desarrollo de plugins personalizados para funciones específicas y arquitecturas offline-first para integración con aplicaciones móviles.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Marcas y empresas que requieren un sitio web único, de alto rendimiento y con funciones que no se encuentran en plantillas prefabricadas.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Soluciones E-commerce y Migración de Plataformas',
            'post_name'  => 'ecommerce-migraciones',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Tiendas online de alto rendimiento orientadas a la conversión y escalabilidad.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Migraciones complejas sin pérdida de datos (ej. Webnode a Shopify o PrestaShop), desarrollo de dashboards a medida para control de inventario, e integración segura de pasarelas de pago y sistemas de TPV virtual.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Negocios de retail, marcas de productos o servicios que necesitan escalar sus ventas online o modernizar su flujo de pagos.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'SEO Técnico y Crecimiento Orgánico Hiperlocal',
            'post_name'  => 'seo-tecnico',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Domina los motores de búsqueda mediante código optimizado y estrategia.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Auditorías de rendimiento técnico web, consolidación orgánica de dominios, investigación de palabras clave, estructuración de calendarios de contenido y optimización en mapas y búsquedas locales.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Negocios físicos (concesionarios automotrices, restaurantes, academias) que necesitan capturar clientes locales a través de Google.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Consultoría en Integración de IA y Rendimiento Web',
            'post_name'  => 'consultoria-ia-rendimiento',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Moderniza tu plataforma web con Inteligencia Artificial y código de vanguardia.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Revisión de arquitecturas web existentes, optimización de tiempos de carga e integración de flujos de trabajo o funciones potenciadas por Inteligencia Artificial para mejorar la eficiencia del software.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Empresas con plataformas web lentas, desactualizadas o que buscan implementar nuevas tecnologías de IA en sus procesos diarios.</p><!-- /wp:paragraph -->'
        ),
        array(
            'post_title' => 'Continuidad Operativa e Infraestructura Física',
            'post_name'  => 'infraestructura-fisica-continuidad',
            'post_content' => '<!-- wp:heading {"level":2} --><h2>Resiliencia de hardware para que tu operación técnica nunca se detenga.</h2><!-- /wp:heading --><!-- wp:heading {"level":3} --><h3>Qué ofreces aquí</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Asesoría en ensamblaje de configuraciones multi-PC, recuperación ante fallos (configuraciones de pérdida de CA en BIOS/UEFI) y cálculo/despliegue de sistemas de respaldo energético portátiles o a gasolina.</p><!-- /wp:paragraph --><!-- wp:heading {"level":3} --><h3>Cliente ideal</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Profesionales independientes, streamers o pequeñas oficinas que no pueden permitirse tiempos de inactividad por fallos eléctricos o de hardware.</p><!-- /wp:paragraph -->'
        )
    );

    foreach ( $services as $svc ) {
        $existing = get_page_by_path( $svc['post_name'], OBJECT, 'servicio' );
        if ( ! $existing ) {
            wp_insert_post( array(
                'post_title'    => $svc['post_title'],
                'post_name'     => $svc['post_name'],
                'post_content'  => $svc['post_content'],
                'post_status'   => 'publish',
                'post_type'     => 'servicio',
            ) );
        }
    }

    update_option( 'am_services_created', true );
}
add_action( 'init', 'am_auto_create_services' );

// -----------------------------------------------------------------------------
// 6. Auto-Generate Main Pages (One-time)
// -----------------------------------------------------------------------------
function am_auto_create_pages() {
    // Commented out to force run this time
    // if ( get_option( 'am_pages_created_v2' ) ) {
    //     return;
    // }

    $pages = array(
        array(
            'post_title' => 'Servicios',
            'post_name'  => 'servicios',
        ),
        array(
            'post_title' => 'Sobre mí',
            'post_name'  => 'sobre-mi',
        ),
        array(
            'post_title' => 'Contacto',
            'post_name'  => 'contacto',
        ),
        array(
            'post_title' => 'Blog',
            'post_name'  => 'blog',
        )
    );

    foreach ( $pages as $p ) {
        $existing = get_page_by_path( $p['post_name'] );
        if ( ! $existing ) {
            $page_id = wp_insert_post( array(
                'post_title'    => $p['post_title'],
                'post_name'     => $p['post_name'],
                'post_status'   => 'publish',
                'post_type'     => 'page',
            ) );

            // If it's the blog page, set it as the posts page in WP settings
            if ( $p['post_name'] === 'blog' && $page_id ) {
                update_option( 'page_for_posts', $page_id );
            }
        }
    }

    update_option( 'am_pages_created', true );
}
add_action( 'init', 'am_auto_create_pages' );

// -----------------------------------------------------------------------------
// 7. Order Servicios Archive
// -----------------------------------------------------------------------------
function am_order_servicios_archive( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'servicio' ) ) {
        $query->set( 'post_name__in', array(
            'desarrollo-avanzado-wordpress',
            'desarrollo-saas-microservicios',
            'administracion-vps-devops',
            'streaming-audio-video',
            'ecommerce-migraciones',
            'seo-tecnico',
            'consultoria-ia-rendimiento',
            'infraestructura-fisica-continuidad'
        ) );
        $query->set( 'orderby', 'post_name__in' );
        $query->set( 'posts_per_page', 12 );
    }
}
add_action( 'pre_get_posts', 'am_order_servicios_archive' );

// -----------------------------------------------------------------------------
// 8. Custom Post Type: Leads (Contactos)
// -----------------------------------------------------------------------------
function am_register_leads_cpt() {
    $labels = array(
        'name'                  => 'Leads',
        'singular_name'         => 'Lead',
        'menu_name'             => 'Leads (Contactos)',
        'all_items'             => 'Todos los Leads',
        'view_item'             => 'Ver Lead',
    );
    $args = array(
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'custom-fields' ),
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 6,
        'menu_icon'             => 'dashicons-email-alt',
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
    );
    register_post_type( 'lead', $args );
}
add_action( 'init', 'am_register_leads_cpt', 0 );

// -----------------------------------------------------------------------------
// 9. AJAX Form Submission for Leads
// -----------------------------------------------------------------------------
function am_handle_contact_form() {
    check_ajax_referer('am_nonce', 'security');

    $name    = isset($_POST['name']) ? sanitize_text_field( $_POST['name'] ) : '';
    $email   = isset($_POST['email']) ? sanitize_email( $_POST['email'] ) : '';
    $service = isset($_POST['service']) ? sanitize_text_field( $_POST['service'] ) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field( $_POST['message'] ) : '';

    if ( empty($name) || empty($email) || empty($message) ) {
        wp_send_json_error( 'Faltan campos obligatorios.' );
    }

    $post_id = wp_insert_post( array(
        'post_title'   => $name . ' (' . $service . ')',
        'post_content' => "Email de contacto: $email\nServicio de interés: $service\n\nContenido del mensaje:\n$message",
        'post_status'  => 'publish', // Publish so it's visible in admin immediately
        'post_type'    => 'lead'
    ) );

    if ( $post_id && !is_wp_error($post_id) ) {
        update_post_meta( $post_id, '_lead_email', $email );
        update_post_meta( $post_id, '_lead_service', $service );
        wp_send_json_success( 'Mensaje enviado correctamente. Me pondré en contacto pronto.' );
    } else {
        wp_send_json_error( 'Error interno al procesar el mensaje.' );
    }
}
add_action('wp_ajax_am_submit_contact', 'am_handle_contact_form');
add_action('wp_ajax_nopriv_am_submit_contact', 'am_handle_contact_form');



// -----------------------------------------------------------------------------
// 12. Auto-create Legal Pages on Theme Activation
// -----------------------------------------------------------------------------
function am_create_legal_pages() {
    $pages = array(
        array(
            'title'    => 'Políticas de Privacidad y Términos',
            'slug'     => 'politicas-de-privacidad-y-terminos',
            'template' => 'page-legal.php',
            'content'  => '<!-- El contenido se gestiona desde la plantilla del tema page-legal.php -->',
        ),
        array(
            'title'    => 'Política de Cookies',
            'slug'     => 'politica-de-cookies',
            'template' => 'page-cookies.php',
            'content'  => '<!-- El contenido se gestiona desde la plantilla del tema page-cookies.php -->',
        ),
    );

    foreach ( $pages as $page ) {
        // Check if the page already exists by slug
        $existing = get_page_by_path( $page['slug'] );

        if ( ! $existing ) {
            $post_id = wp_insert_post( array(
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => $page['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1,
            ) );

            if ( $post_id && ! is_wp_error( $post_id ) ) {
                // Assign the custom template
                update_post_meta( $post_id, '_wp_page_template', $page['template'] );
            }
        }
    }
}
// Run on theme activation
add_action( 'after_switch_theme', 'am_create_legal_pages' );

// Also run once on init (guarded by option) so already-active installs get the pages too
function am_create_legal_pages_once() {
    if ( ! get_option( 'am_legal_pages_created' ) ) {
        am_create_legal_pages();
        update_option( 'am_legal_pages_created', '1' );
    }
}
add_action( 'init', 'am_create_legal_pages_once' );

// -----------------------------------------------------------------------------
// 13. Poblar post_content de páginas para Yoast SEO (una sola vez)
// -----------------------------------------------------------------------------
function am_populate_seo_content() {
    if ( get_option( 'am_seo_content_v1' ) ) return;

    $pages_content = array(

        // ---- HOME / FRONT PAGE ----
        'front-page' => array(
            'title'   => 'Inicio',
            'content' =>
                '<!-- wp:heading {"level":1} --><h1>Arturo Merchán — Programador Full-Stack</h1><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Diseñando arquitecturas escalables, ecosistemas SaaS y experiencias web de alto rendimiento. 10 años transformando complejidad técnica en soluciones de negocio robustas.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Sobre mí</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Soy un Programador Full-Stack con una década de experiencia en el sector tecnológico. Mi enfoque se centra en construir puentes entre el desarrollo front-end avanzado y las arquitecturas de servidores resilientes. No solo escribo código; diseño infraestructuras preparadas para el futuro.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Mis Servicios</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Desarrollo SaaS y Arquitectura de Microservicios. Configuración de Servidores para Streaming de Audio y Video. Administración de Servidores VPS y DevOps. Desarrollo Avanzado en WordPress. Soluciones E-commerce y Migración de Plataformas. SEO Técnico y Crecimiento Orgánico Hiperlocal. Consultoría en Integración de IA y Rendimiento Web. Continuidad Operativa e Infraestructura Física.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Stack Tecnológico</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Lenguajes y Front-End: TypeScript, JavaScript, React, Astro, PHP, HTML5, CSS3, Tailwind CSS, WebGL. Back-End y Bases de Datos: Python, Microservicios, MySQL, MariaDB, MongoDB. DevOps y Servidores: Linux, Bash, Docker, Traefik, Nginx, Hostinger VPS.</p><!-- /wp:paragraph -->',
        ),

        // ---- SOBRE MÍ ----
        'sobre-mi' => array(
            'title'   => 'Sobre mí',
            'content' =>
                '<!-- wp:heading {"level":1} --><h1>De la infraestructura al píxel — Perfil Profesional</h1><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Soy Arturo Merchán, Programador Full-Stack con más de 10 años de experiencia en desarrollo web. Me especializo en el ecosistema de WordPress, PHP, y desarrollo de bases de datos, combinando habilidades en frontend (TypeScript, Tailwind CSS) y backend para crear soluciones tecnológicas a medida.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Mi enfoque se centra en la implementación de diseños precisos, optimización de rendimiento (SEO técnico) y la entrega puntual bajo un modelo de trabajo estructurado y eficiente.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Experiencia Profesional</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Programador Full Stack en Me Transfers (Barcelona, España) — Diciembre 2025 – Presente. Desarrollo y mantenimiento de un sistema de reservas especializado para traslados y tours desde y hacia Barcelona.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Desarrollador Web en Motores Morichal (Maturín, Venezuela) — Agosto 2025 – Presente. Desarrollo integral del tema para el sitio web, incluyendo la implementación de plugins personalizados para formularios y la administración del inventario de vehículos.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Programador Full Stack y Cofundador en Espressivo Venezuela (Maturín, Venezuela) — Octubre 2018 – Presente. Cofundador de la marca, encargado de proveer soluciones tecnológicas integrales y personalizadas para clientes.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Desarrollador Full Stack WordPress en Panalcom (Colombia) — Marzo 2025 – Noviembre 2025. Desarrollo de soluciones web personalizadas para el sector B2B, levantamiento y administración de servidores, configuración de entornos seguros. Creación de plugins y temas a medida, integración de CRMs, pasarelas de pago y automatizaciones. Optimización de rendimiento, estructura SEO y construcción de interfaces con Elementor.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Desarrollador Web en Academy Producciones (México) — Marzo 2020 – Febrero 2025.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Diseñador de Páginas Web en Neuropublicidad (Chile) — Febrero 2017 – Febrero 2020. Desarrollo de E-commerce en WordPress utilizando WooCommerce y gestión estratégica de campañas en Google Ads.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Educación y Certificaciones</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Diplomado en Database Developer — Diplomados online (Mayo 2026 – Septiembre 2026). Máster SEO — BIG school (Marzo 2026 – Julio 2026). Máster de Funnel de Ventas — BIG school (Marzo 2026 – Julio 2026). Máster de Desarrollo con IA — BIG school (Marzo 2026 – Junio 2026). Mastering Laravel Framework and PHP — Coursera (2020 – 2023). Full Stack con WordPress — Platzi (2023). Licenciatura en Contaduría Pública — Universidad de Oriente, Venezuela (2015 – 2022).</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Stack Tecnológico</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Lenguajes y Front-End: TypeScript, JavaScript, React, Astro, PHP, HTML5, CSS3, Tailwind CSS, JSON, WebGL, Liquid, Twig. Back-End y Bases de Datos: Python, Microservicios, MySQL, MariaDB, MongoDB. Versiones y CI/CD: Git, GitHub Actions. DevOps y Servidores: Linux, Bash, PowerShell, Docker, Dockerfile, Traefik, Nginx, Hostinger VPS.</p><!-- /wp:paragraph -->',
        ),

        // ---- CONTACTO ----
        'contacto' => array(
            'title'   => 'Contacto',
            'content' =>
                '<!-- wp:heading {"level":1} --><h1>¿Listo para escalar tu infraestructura o construir tu próximo SaaS?</h1><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Ya sea que necesites una administración experta de tu VPS, una migración compleja o el desarrollo de una plataforma desde cero, hablemos de cómo la tecnología adecuada puede acelerar tu negocio.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Disponible para consultoría. Clases de programación. Auditorías técnicas de sitios web.</p><!-- /wp:paragraph -->' .
                '<!-- wp:heading {"level":2} --><h2>Servicios disponibles para contratar</h2><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>SaaS y Web App, Administración de Servidores y VPS, Migración E-commerce, SEO Técnico, Desarrollo Avanzado en WordPress, Consultoría general.</p><!-- /wp:paragraph -->',
        ),

        // ---- SERVICIOS (archive/listado) ----
        'servicios' => array(
            'title'   => 'Servicios',
            'content' =>
                '<!-- wp:heading {"level":1} --><h1>Servicios Profesionales de Desarrollo Web</h1><!-- /wp:heading -->' .
                '<!-- wp:paragraph --><p>Explora el catálogo completo de soluciones tecnológicas, desde arquitectura SaaS hasta continuidad operativa. Ofrezco servicios especializados en desarrollo web, administración de servidores, SEO técnico, e-commerce, inteligencia artificial y streaming digital.</p><!-- /wp:paragraph -->' .
                '<!-- wp:paragraph --><p>Desarrollo SaaS y Arquitectura de Microservicios. Configuración de Servidores para Streaming de Audio y Video. Administración de Servidores VPS y DevOps. Desarrollo Avanzado en WordPress. Soluciones E-commerce y Migración de Plataformas. SEO Técnico y Crecimiento Orgánico Hiperlocal. Consultoría en Integración de IA y Rendimiento Web. Continuidad Operativa e Infraestructura Física.</p><!-- /wp:paragraph -->',
        ),
    );

    foreach ( $pages_content as $slug => $data ) {
        // Try by slug first
        $page = get_page_by_path( $slug );

        // For front-page, get by the page_on_front option
        if ( ! $page && $slug === 'front-page' ) {
            $front_id = get_option( 'page_on_front' );
            if ( $front_id ) {
                $page = get_post( $front_id );
            }
        }

        if ( $page && $page->ID ) {
            wp_update_post( array(
                'ID'           => $page->ID,
                'post_content' => $data['content'],
            ) );
        }
    }

    update_option( 'am_seo_content_v1', '1' );
}
add_action( 'init', 'am_populate_seo_content' );

// -----------------------------------------------------------------------------
// 14. Filtro por Año en el Blog
// -----------------------------------------------------------------------------
function am_filter_blog_by_year( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
        // Obtener el año de la URL o por defecto 2026
        $year = isset($_GET['filtro_ano']) ? intval($_GET['filtro_ano']) : 2026;
        $query->set( 'year', $year );
    }
}
add_action( 'pre_get_posts', 'am_filter_blog_by_year' );

// -----------------------------------------------------------------------------
// 15. AJAX Endpoint para filtrar el blog por año sin recargar
// -----------------------------------------------------------------------------
function am_ajax_filter_blog() {
    check_ajax_referer('am_nonce', 'security');
    $year = isset($_POST['year']) ? intval($_POST['year']) : 2026;
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'year' => $year,
        'paged' => $paged,
    );
    $query = new WP_Query($args);

    $cards = '';
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            ?>
            <a href="<?php the_permalink(); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none; opacity: 1; transform: none;">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div style="margin: -2.5rem -2.5rem 1.5rem -2.5rem; border-radius: 24px 24px 0 0; overflow: hidden; height: 200px;">
                        <?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                    </div>
                <?php endif; ?>
                <div class="card-body" style="flex-grow: 1;">
                    <div style="font-size: 0.85rem; color: var(--color-goku-orange); margin-bottom: 0.5rem; font-weight: 600;">
                        <?php echo get_the_date(); ?>
                    </div>
                    <h3 style="color: var(--color-text-main); font-size: 1.3rem; margin-bottom: 1rem; line-height: 1.3;">
                        <?php the_title(); ?>
                    </h3>
                    <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                    </p>
                </div>
                <div style="display: flex; align-items: center; color: var(--color-ssj-yellow); font-weight: 600; font-size: 0.9rem; margin-top: auto;">
                    Leer artículo <span class="material-symbols-outlined" style="font-size: 18px; margin-left: 4px;">arrow_forward</span>
                </div>
            </a>
            <?php
        }
    } else {
        echo '<div class="glass-card text-center" style="grid-column: 1 / -1;"><h3 style="color: var(--color-text-main);">Aún no hay artículos para este año</h3></div>';
    }
    $cards = ob_get_clean();

    $pagination = '';
    $total_pages = $query->max_num_pages;
    if ($total_pages > 1) {
        $current_page = max(1, $paged);
        ob_start();
        echo paginate_links(array(
            'base' => '%_%',
            'format' => '?paged=%#%',
            'current' => $current_page,
            'total' => $total_pages,
            'prev_text' => __('Anterior', 'textdomain'),
            'next_text' => __('Siguiente', 'textdomain'),
        ));
        $pagination_links = ob_get_clean();
        
        $pagination = '<h2 class="screen-reader-text">Más artículos</h2><div class="nav-links">' . $pagination_links . '</div>';
    }

    wp_reset_postdata();

    wp_send_json_success(array(
        'cards' => $cards,
        'pagination' => $pagination
    ));
}
add_action('wp_ajax_am_filter_blog', 'am_ajax_filter_blog');
add_action('wp_ajax_nopriv_am_filter_blog', 'am_ajax_filter_blog');
