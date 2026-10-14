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
    if ( ! is_singular( 'proyecto' ) ) {
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
    }
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

    wp_enqueue_style('am-portfolio', get_template_directory_uri() . '/assets/css/portfolio.css', array('am-main-css'), filemtime(get_template_directory() . '/assets/css/portfolio.css'));
    wp_enqueue_script('am-main-js', get_template_directory_uri() . '/assets/js/main.js', array(), filemtime(get_template_directory() . '/assets/js/main.js'), true);
    wp_enqueue_script('am-bg-anim-js', get_template_directory_uri() . '/assets/js/bg-animation.js', array(), filemtime(get_template_directory() . '/assets/js/bg-animation.js'), true);
    // Endpoint configuration
    wp_localize_script('am-main-js', 'amData', array(
        'ajax_url'    => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('am_nonce'),

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
        'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ),
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

}
add_action( 'init', 'am_register_servicios_cpt', 0 );

// -----------------------------------------------------------------------------
// 4.1 AJAX Search
// -----------------------------------------------------------------------------
function am_ajax_search() {
    // Búsqueda pública de solo lectura: sin nonce, porque las páginas cacheadas (LiteSpeed/CDN)
    // sirven nonces caducados y la búsqueda dejaba de funcionar. Solo devuelve contenido publicado.
    $query = sanitize_text_field(wp_unslash(isset($_POST['query']) && is_string($_POST['query']) ? $_POST['query'] : ''));
    if(strlen($query) < 2) {
        wp_send_json_success(array());
    }

    $args = array(
        'post_type' => array('post', 'page', 'servicio', 'proyecto'),
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
            if (get_post_type() == 'proyecto') $icon = 'bx-code-block';
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

    foreach ( am_legacy_services() as $svc ) {
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

/**
 * Textos originales con los que el tema creó los servicios. Se conservan para que el
 * importador 2026 detecte si un servicio sigue sin editar antes de actualizar su texto.
 */
function am_legacy_services() {
    return array(
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
}


// -----------------------------------------------------------------------------
// 6. Auto-Generate Main Pages (One-time)
// -----------------------------------------------------------------------------
function am_auto_create_pages() {
    if ( get_option( 'am_pages_created' ) ) { return; }
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
require_once get_template_directory() . '/inc/contact.php';
require_once get_template_directory() . '/inc/projects.php';
require_once get_template_directory() . '/inc/specialties.php';
require_once get_template_directory() . '/inc/capabilities.php';
require_once get_template_directory() . '/inc/seed.php';
require_once get_template_directory() . '/inc/seo.php';
require_once get_template_directory() . '/inc/captures.php';



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



// -----------------------------------------------------------------------------
// 13. Poblar post_content de páginas para Yoast SEO (una sola vez)
// -----------------------------------------------------------------------------
// Page content is edited in WordPress; never overwritten by this theme.


// -----------------------------------------------------------------------------
// 14. Filtro por Año en el Blog
// -----------------------------------------------------------------------------
function am_filter_blog_by_year( $query ) {
    if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
        // Obtener el año de la URL o por defecto 2026
        $year = isset($_GET['filtro_ano']) ? absint($_GET['filtro_ano']) : 0;
        if ($year) { $query->set('year', $year); }
    }
}
add_action( 'pre_get_posts', 'am_filter_blog_by_year' );

// -----------------------------------------------------------------------------
// 15. AJAX Endpoint para filtrar el blog por año sin recargar
// -----------------------------------------------------------------------------
function am_ajax_filter_blog() {
    // Filtro público de solo lectura (ver am_ajax_search): no requiere nonce.
    $year = isset($_POST['year']) ? absint($_POST['year']) : 0;
    $paged = isset($_POST['paged']) ? max(1, absint($_POST['paged'])) : 1;

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
