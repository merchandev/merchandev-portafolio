<?php
/**
 * Proyectos: tipo de contenido, categorías, campos administrables y datos para las plantillas.
 *
 * Fuente única: las tarjetas, el catálogo y los casos de estudio leen siempre de
 * am_project_data(), que normaliza un post `proyecto` (o una entrada del contenido
 * inicial mientras no se haya importado).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_register_projects() {
    register_post_type( 'proyecto', array(
        'labels' => array(
            'name' => 'Proyectos', 'singular_name' => 'Proyecto', 'menu_name' => 'Proyectos',
            'add_new_item' => 'Añadir proyecto', 'edit_item' => 'Editar proyecto', 'view_item' => 'Ver caso de estudio',
            'all_items' => 'Todos los proyectos', 'search_items' => 'Buscar proyectos', 'not_found' => 'No hay proyectos.',
        ),
        'public' => true, 'show_in_rest' => true, 'menu_icon' => 'dashicons-portfolio', 'menu_position' => 4,
        'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes', 'custom-fields' ),
        'has_archive' => 'proyectos', 'rewrite' => array( 'slug' => 'proyectos', 'with_front' => false ),
    ) );
    register_taxonomy( 'tipo_proyecto', 'proyecto', array(
        'labels' => array( 'name' => 'Categorías de proyecto', 'singular_name' => 'Categoría de proyecto', 'menu_name' => 'Categorías' ),
        'hierarchical' => true, 'public' => true, 'show_in_rest' => true, 'show_admin_column' => true,
        'rewrite' => array( 'slug' => 'tipo-de-proyecto', 'with_front' => false ),
    ) );
    foreach ( am_project_fields() as $key => $field ) {
        register_post_meta( 'proyecto', '_am_' . $key, array(
            'type' => 'string', 'single' => true, 'show_in_rest' => true,
            'sanitize_callback' => function ( $value ) use ( $key ) { return am_sanitize_project_field( $key, $value ); },
            'auth_callback' => function ( $allowed, $meta_key, $post_id ) { return current_user_can( 'edit_post', $post_id ); },
        ) );
    }
}
add_action( 'init', 'am_register_projects', 0 );

/** Campos del caso de estudio en el orden del formulario de edición. */
function am_project_fields() {
    return array(
        'featured'          => array( 'label' => 'Proyecto destacado en la portada', 'type' => 'checkbox' ),
        'primary_category'  => array( 'label' => 'Categoría principal', 'type' => 'category' ),
        'status'            => array( 'label' => 'Estado del proyecto', 'type' => 'status' ),
        'sector'            => array( 'label' => 'Sector o contexto', 'type' => 'text' ),
        'period'            => array( 'label' => 'Período', 'type' => 'text', 'help' => 'Ejemplo: Dic. 2025 – actualidad.' ),
        'role'              => array( 'label' => 'Mi participación (verificable)', 'type' => 'textarea' ),
        'context'           => array( 'label' => 'Contexto del proyecto', 'type' => 'textarea' ),
        'problem'           => array( 'label' => 'Problema o necesidad', 'type' => 'textarea' ),
        'objective'         => array( 'label' => 'Objetivo de la solución', 'type' => 'textarea' ),
        'architecture'      => array( 'label' => 'Arquitectura general', 'type' => 'textarea' ),
        'features'          => array( 'label' => 'Funcionalidades implementadas', 'type' => 'lines', 'help' => 'Una por línea. Solo lo que existe en el código o en producción.' ),
        'challenges'        => array( 'label' => 'Desafíos técnicos y decisiones', 'type' => 'lines', 'help' => 'Uno por línea.' ),
        'security'          => array( 'label' => 'Seguridad y aspectos operativos', 'type' => 'textarea' ),
        'results'           => array( 'label' => 'Resultados comprobables', 'type' => 'textarea', 'help' => 'Sin métricas inventadas. Vacío = «pendiente de documentar».' ),
        'stack'             => array( 'label' => 'Tecnologías principales', 'type' => 'text', 'help' => 'Separadas por comas. Las primeras cinco aparecen en las tarjetas.' ),
        'specialties'       => array( 'label' => 'Especialidades que demuestra', 'type' => 'specialties' ),
        'demo'              => array( 'label' => 'URL pública o demo autorizada', 'type' => 'url' ),
        'repository'        => array( 'label' => 'URL del repositorio público', 'type' => 'url' ),
        'links'             => array( 'label' => 'Otros enlaces', 'type' => 'lines', 'help' => 'Uno por línea con el formato «Texto | https://…».' ),
        'gallery'           => array( 'label' => 'Galería de capturas', 'type' => 'gallery', 'help' => 'Capturas reales y sin datos personales. La imagen destacada se usa como portada.' ),
        'capture_auto'      => array( 'label' => 'Actualizar capturas automáticamente', 'type' => 'checkbox', 'help' => 'Cada 24 horas se revisa la URL pública: si la interfaz cambió, se toman capturas nuevas (escritorio y móvil); si no, se conservan. Desactívalo si prefieres elegir las capturas a mano.' ),
        'related'           => array( 'label' => 'Proyectos relacionados', 'type' => 'related' ),
    );
}

function am_project_statuses() {
    return array(
        'produccion'  => array( 'En producción', 'El sistema está publicado y en uso en el enlace indicado.' ),
        'mvp'         => array( 'MVP funcional', 'El flujo principal funciona y está publicado; quedan tareas de endurecimiento y ampliación.' ),
        'beta'        => array( 'Piloto / beta', 'Desplegado con alcance limitado y declarado como prueba.' ),
        'desarrollo'  => array( 'En desarrollo', 'El código existe y avanza. Todavía no hay una operación comercial que pueda verificarse.' ),
        'entregado'   => array( 'Entregado', 'Trabajo entregado al cliente. El estado actual del sitio depende de su administración.' ),
        'laboratorio' => array( 'Laboratorio', 'Prototipo o experimento técnico. No representa resultados comerciales.' ),
    );
}

function am_specialty_keys() {
    return array( 'desarrollo' => 'Desarrollo de software', 'arquitectura' => 'Arquitectura e integraciones', 'infraestructura' => 'Infraestructura y operación' );
}

function am_sanitize_project_field( $key, $value ) {
    $fields = am_project_fields();
    $type = $fields[ $key ]['type'] ?? 'text';
    if ( is_array( $value ) ) { $value = implode( ',', array_map( 'strval', $value ) ); }
    $value = is_scalar( $value ) ? (string) $value : '';
    switch ( $type ) {
        case 'checkbox': return $value ? '1' : '';
        case 'url': return esc_url_raw( trim( $value ), array( 'https', 'http' ) );
        case 'status': return array_key_exists( $value, am_project_statuses() ) ? $value : '';
        case 'category': return sanitize_title( $value );
        case 'specialties': return implode( ',', array_intersect( array_map( 'sanitize_key', explode( ',', $value ) ), array_keys( am_specialty_keys() ) ) );
        case 'gallery':
        case 'related': return implode( ',', array_filter( array_map( 'absint', explode( ',', $value ) ) ) );
        case 'lines':
        case 'textarea': return sanitize_textarea_field( $value );
        default: return sanitize_text_field( $value );
    }
}

/* -------------------------------------------------------------------------- */
/* Formulario de edición                                                       */
/* -------------------------------------------------------------------------- */
add_action( 'add_meta_boxes_proyecto', function () {
    add_meta_box( 'am-project-details', 'Caso de estudio', 'am_project_meta_box', 'proyecto', 'normal', 'high' );
} );

function am_project_meta_box( $post ) {
    wp_nonce_field( 'am_project_details', 'am_project_nonce' );
    echo '<p>Publica solo información comprobable. El extracto es la descripción breve de las tarjetas; el editor queda para notas adicionales del caso.</p>';
    echo '<table class="form-table am-project-fields" role="presentation"><tbody>';
    foreach ( am_project_fields() as $key => $field ) {
        $id = 'am-' . $key;
        $value = (string) get_post_meta( $post->ID, '_am_' . $key, true );
        echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $field['label'] ) . '</label></th><td>';
        switch ( $field['type'] ) {
            case 'checkbox':
                echo '<input type="checkbox" id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']" value="1"' . checked( $value, '1', false ) . '>';
                break;
            case 'status':
                echo '<select id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']"><option value="">Sin indicar</option>';
                foreach ( am_project_statuses() as $status => $data ) {
                    echo '<option value="' . esc_attr( $status ) . '"' . selected( $value, $status, false ) . '>' . esc_html( $data[0] ) . '</option>';
                }
                echo '</select>';
                break;
            case 'category':
                echo '<select id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']"><option value="">Primera categoría asignada</option>';
                foreach ( get_terms( array( 'taxonomy' => 'tipo_proyecto', 'hide_empty' => false ) ) as $term ) {
                    echo '<option value="' . esc_attr( $term->slug ) . '"' . selected( $value, $term->slug, false ) . '>' . esc_html( $term->name ) . '</option>';
                }
                echo '</select>';
                break;
            case 'specialties':
                $current = array_filter( explode( ',', $value ) );
                foreach ( am_specialty_keys() as $specialty => $label ) {
                    echo '<label style="margin-right:16px"><input type="checkbox" name="am_project[' . esc_attr( $key ) . '][]" value="' . esc_attr( $specialty ) . '"' . checked( in_array( $specialty, $current, true ), true, false ) . '> ' . esc_html( $label ) . '</label>';
                }
                break;
            case 'gallery':
                echo '<input type="hidden" id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '">';
                echo '<div class="am-gallery-preview" data-target="' . esc_attr( $id ) . '">';
                foreach ( array_filter( explode( ',', $value ) ) as $attachment_id ) { echo wp_get_attachment_image( (int) $attachment_id, array( 80, 80 ) ); }
                echo '</div><button type="button" class="button am-gallery-select" data-target="' . esc_attr( $id ) . '">Seleccionar capturas</button> <button type="button" class="button-link am-gallery-clear" data-target="' . esc_attr( $id ) . '">Quitar todas</button>';
                break;
            case 'related':
                $current = array_map( 'intval', array_filter( explode( ',', $value ) ) );
                $others = get_posts( array( 'post_type' => 'proyecto', 'post_status' => array( 'publish', 'draft', 'private' ), 'numberposts' => 100, 'exclude' => array( $post->ID ), 'orderby' => 'title', 'order' => 'ASC' ) );
                echo '<select multiple size="6" style="min-width:320px" id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . '][]">';
                foreach ( $others as $other ) {
                    echo '<option value="' . esc_attr( $other->ID ) . '"' . selected( in_array( $other->ID, $current, true ), true, false ) . '>' . esc_html( get_the_title( $other ) ) . '</option>';
                }
                echo '</select>';
                break;
            case 'text':
            case 'url':
                echo '<input class="widefat" type="' . ( $field['type'] === 'url' ? 'url' : 'text' ) . '" id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']" value="' . esc_attr( $value ) . '">';
                break;
            default:
                echo '<textarea class="widefat" rows="' . ( $field['type'] === 'lines' ? 5 : 3 ) . '" id="' . esc_attr( $id ) . '" name="am_project[' . esc_attr( $key ) . ']">' . esc_textarea( $value ) . '</textarea>';
        }
        if ( ! empty( $field['help'] ) ) { echo '<p class="description">' . esc_html( $field['help'] ) . '</p>'; }
        if ( $key === 'capture_auto' && function_exists( 'am_capture_status_text' ) && am_capture_status_text( $post->ID ) ) { echo '<p class="description"><strong>Última revisión:</strong> ' . esc_html( am_capture_status_text( $post->ID ) ) . '</p>'; }
        echo '</td></tr>';
    }
    echo '</tbody></table>';
}

add_action( 'save_post_proyecto', function ( $post_id ) {
    if ( ! isset( $_POST['am_project_nonce'] ) || ! is_string( $_POST['am_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['am_project_nonce'] ) ), 'am_project_details' ) ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) || wp_is_post_revision( $post_id ) || ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ) { return; }
    $input = isset( $_POST['am_project'] ) && is_array( $_POST['am_project'] ) ? wp_unslash( $_POST['am_project'] ) : array();
    foreach ( am_project_fields() as $key => $field ) {
        $raw = $input[ $key ] ?? '';
        if ( is_array( $raw ) ) { $raw = implode( ',', array_filter( $raw, 'is_scalar' ) ); }
        $value = am_sanitize_project_field( $key, $raw );
        if ( $value === '' ) { delete_post_meta( $post_id, '_am_' . $key ); } else { update_post_meta( $post_id, '_am_' . $key, $value ); }
    }
} );

add_action( 'admin_enqueue_scripts', function ( $hook ) {
    $screen = get_current_screen();
    if ( ! $screen || $screen->post_type !== 'proyecto' || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) { return; }
    wp_enqueue_media();
    wp_add_inline_script( 'media-editor', "jQuery(function($){\n$(document).on('click','.am-gallery-select',function(e){e.preventDefault();var t=$('#'+$(this).data('target')),p=$('.am-gallery-preview[data-target='+$(this).data('target')+']');var f=wp.media({title:'Capturas del proyecto',multiple:'add',library:{type:'image'}});f.on('open',function(){var s=f.state().get('selection');(t.val()||'').split(',').filter(Boolean).forEach(function(id){var a=wp.media.attachment(id);a.fetch();s.add(a);});});f.on('select',function(){var ids=[];p.empty();f.state().get('selection').each(function(a){ids.push(a.id);var u=(a.get('sizes')&&a.get('sizes').thumbnail)?a.get('sizes').thumbnail.url:a.get('url');p.append($('<img>',{src:u,width:80,height:80,alt:''}));});t.val(ids.join(','));});f.open();});\n$(document).on('click','.am-gallery-clear',function(e){e.preventDefault();$('#'+$(this).data('target')).val('');$('.am-gallery-preview[data-target='+$(this).data('target')+']').empty();});\n});" );
    wp_add_inline_style( 'wp-admin', '.am-gallery-preview{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:8px}.am-gallery-preview img{border-radius:4px;object-fit:cover}' );
} );

/* Columnas del listado */
add_filter( 'manage_proyecto_posts_columns', function ( $columns ) {
    $columns['am_featured'] = 'Destacado';
    $columns['am_status'] = 'Estado';
    $columns['am_capture'] = 'Captura';
    $columns['menu_order'] = 'Orden';
    return $columns;
} );
add_action( 'manage_proyecto_posts_custom_column', function ( $column, $post_id ) {
    if ( $column === 'am_featured' ) { echo get_post_meta( $post_id, '_am_featured', true ) ? '★' : '—'; }
    if ( $column === 'am_status' ) { echo esc_html( am_project_status_label( get_post_meta( $post_id, '_am_status', true ) ) ?: '—' ); }
    if ( $column === 'am_capture' ) {
        echo has_post_thumbnail( $post_id ) ? 'Sí' : '<span style="color:#b26200">Pendiente</span>';
        if ( get_post_meta( $post_id, '_am_capture_auto', true ) === '1' ) { $status = function_exists( 'am_capture_status' ) ? am_capture_status( $post_id ) : array(); echo '<br><small>Automática' . ( $status ? ' · ' . esc_html( $status['estado'] . ' ' . mysql2date( 'j M', $status['fecha'] ) ) : '' ) . '</small>'; }
    }
    if ( $column === 'menu_order' ) { echo (int) get_post_field( 'menu_order', $post_id ); }
}, 10, 2 );

/* -------------------------------------------------------------------------- */
/* Consultas y datos normalizados                                              */
/* -------------------------------------------------------------------------- */
add_action( 'pre_get_posts', function ( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) { return; }
    if ( $query->is_post_type_archive( 'proyecto' ) || $query->is_tax( 'tipo_proyecto' ) ) {
        $query->set( 'posts_per_page', 60 );
        $query->set( 'orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ) );
    }
} );

function am_project_status_label( $status ) {
    $statuses = am_project_statuses();
    return isset( $statuses[ $status ] ) ? $statuses[ $status ][0] : '';
}

/** Proyectos publicados. $featured: true = destacados, false = no destacados, null = todos. */
function am_get_projects( $featured = null, $limit = -1, $extra = array() ) {
    $args = array_merge( array(
        'post_type' => 'proyecto', 'post_status' => 'publish', 'posts_per_page' => $limit,
        'orderby' => array( 'menu_order' => 'ASC', 'title' => 'ASC' ), 'no_found_rows' => true,
    ), $extra );
    if ( $featured === true ) {
        $args['meta_query'] = array( array( 'key' => '_am_featured', 'value' => '1' ) );
    } elseif ( $featured === false ) {
        $args['meta_query'] = array( 'relation' => 'OR', array( 'key' => '_am_featured', 'compare' => 'NOT EXISTS' ), array( 'key' => '_am_featured', 'value' => '1', 'compare' => '!=' ) );
    }
    return get_posts( $args );
}

function am_project_has_published() {
    static $has = null;
    if ( $has === null ) { $has = (bool) get_posts( array( 'post_type' => 'proyecto', 'post_status' => 'publish', 'numberposts' => 1, 'fields' => 'ids' ) ); }
    return $has;
}

/**
 * Proyectos listos para las plantillas. Mientras no se haya importado el contenido, usa
 * los datos iniciales del tema (sin enlace a caso de estudio) para no mostrar secciones vacías.
 *
 * @param bool|null $featured true, false o null (todos).
 */
function am_projects_for_display( $featured = null, $limit = -1 ) {
    if ( am_project_has_published() ) {
        return array_map( 'am_project_data', am_get_projects( $featured, $limit ) );
    }
    $seeds = function_exists( 'am_seed_projects' ) ? am_seed_projects() : array();
    if ( $featured !== null ) {
        $seeds = array_filter( $seeds, function ( $seed ) use ( $featured ) { return ! empty( $seed['featured'] ) === $featured; } );
    }
    $seeds = array_values( $seeds );
    return array_map( 'am_project_data', $limit > 0 ? array_slice( $seeds, 0, $limit ) : $seeds );
}

function am_parse_lines( $text ) {
    return array_values( array_filter( array_map( 'trim', preg_split( '/\R/', (string) $text ) ) ) );
}

function am_parse_links( $text ) {
    $links = array();
    foreach ( am_parse_lines( $text ) as $line ) {
        $parts = array_map( 'trim', explode( '|', $line, 2 ) );
        if ( count( $parts ) === 2 && wp_http_validate_url( $parts[1] ) ) { $links[] = array( 'label' => $parts[0], 'url' => $parts[1] ); }
    }
    return $links;
}

function am_project_category_label( $slug ) {
    $term = $slug ? get_term_by( 'slug', $slug, 'tipo_proyecto' ) : false;
    if ( $term ) { return $term->name; }
    $seed = function_exists( 'am_seed_categories' ) ? am_seed_categories() : array();
    return $seed[ $slug ][0] ?? '';
}

/**
 * Datos normalizados de un proyecto para cualquier plantilla.
 *
 * @param WP_Post|array $source Post `proyecto` o entrada de am_seed_projects().
 */
function am_project_data( $source ) {
    if ( is_array( $source ) ) { return am_project_data_from_seed( $source ); }
    $post = get_post( $source );
    $meta = function ( $key ) use ( $post ) { return (string) get_post_meta( $post->ID, '_am_' . $key, true ); };
    $terms = get_the_terms( $post, 'tipo_proyecto' );
    $categories = $terms && ! is_wp_error( $terms ) ? wp_list_pluck( $terms, 'slug' ) : array();
    $primary = $meta( 'primary_category' );
    if ( ! $primary || ! in_array( $primary, $categories, true ) ) { $primary = $categories[0] ?? ''; }
    $gallery = array_filter( array_map( 'absint', explode( ',', $meta( 'gallery' ) ) ), 'wp_attachment_is_image' );
    $thumbnail = get_post_thumbnail_id( $post );
    return array(
        'id' => $post->ID, 'slug' => $post->post_name, 'title' => get_the_title( $post ), 'url' => get_permalink( $post ), 'has_case' => true,
        'excerpt' => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 30 ),
        'featured' => $meta( 'featured' ) === '1', 'status' => $meta( 'status' ), 'status_label' => am_project_status_label( $meta( 'status' ) ),
        'primary_category' => $primary, 'category_label' => am_project_category_label( $primary ), 'categories' => $categories,
        'sector' => $meta( 'sector' ), 'period' => $meta( 'period' ), 'role' => $meta( 'role' ),
        'context' => $meta( 'context' ), 'problem' => $meta( 'problem' ), 'objective' => $meta( 'objective' ), 'architecture' => $meta( 'architecture' ),
        'features' => am_parse_lines( $meta( 'features' ) ), 'challenges' => am_parse_lines( $meta( 'challenges' ) ),
        'security' => $meta( 'security' ), 'results' => $meta( 'results' ),
        'stack' => array_values( array_filter( array_map( 'trim', explode( ',', $meta( 'stack' ) ) ) ) ),
        'specialties' => array_filter( explode( ',', $meta( 'specialties' ) ) ),
        'demo' => $meta( 'demo' ), 'repository' => $meta( 'repository' ), 'links' => am_parse_links( $meta( 'links' ) ),
        'image_id' => $thumbnail ? (int) $thumbnail : ( $gallery ? (int) reset( $gallery ) : 0 ), 'image_src' => '', 'image_alt' => '',
        'gallery' => array_values( $gallery ),
        'capture_updated' => $meta( 'capture_auto' ) === '1' ? (string) get_post_meta( $post->ID, '_am_capture_updated', true ) : '',
        'related' => array_filter( array_map( 'absint', explode( ',', $meta( 'related' ) ) ) ),
    );
}

function am_project_data_from_seed( array $seed ) {
    $images = $seed['images'] ?? array();
    $first = $images ? reset( $images ) : null;
    $defaults = array_fill_keys( array( 'status', 'sector', 'period', 'role', 'demo', 'repository' ), '' );
    $seed = array_merge( $defaults, $seed );
    return array(
        'id' => 0, 'slug' => $seed['slug'], 'title' => $seed['title'], 'excerpt' => $seed['excerpt'],
        'url' => $seed['repository'] ?: $seed['demo'], 'has_case' => false, 'featured' => ! empty( $seed['featured'] ),
        'status' => $seed['status'], 'status_label' => am_project_status_label( $seed['status'] ),
        'primary_category' => $seed['primary'] ?? '', 'category_label' => am_project_category_label( $seed['primary'] ?? '' ), 'categories' => $seed['categories'] ?? array(),
        'sector' => $seed['sector'], 'period' => $seed['period'], 'role' => $seed['role'],
        'stack' => array_values( array_filter( array_map( 'trim', explode( ',', $seed['stack'] ?? '' ) ) ) ),
        'specialties' => $seed['specialties'] ?? array(), 'demo' => $seed['demo'], 'repository' => $seed['repository'],
        'features' => array(), 'challenges' => array(), 'links' => array(), 'related' => array(), 'gallery' => array(),
        'capture_updated' => '', 'image_id' => 0, 'image_src' => $first ? get_theme_file_uri( 'assets/image/proyectos/' . $first['file'] ) : '', 'image_alt' => $first['alt'] ?? '',
    );
}

/** Portada del proyecto: captura real o presentación tipográfica marcada como pendiente. */
function am_project_cover( array $project, $size = 'large', $attrs = array() ) {
    $attrs = array_merge( array( 'loading' => 'lazy', 'decoding' => 'async', 'class' => 'project-shot' ), $attrs );
    if ( $project['image_id'] ) {
        return wp_get_attachment_image( $project['image_id'], $size, false, $attrs );
    }
    if ( $project['image_src'] ) {
        return sprintf( '<img src="%s" alt="%s" class="%s" loading="%s" decoding="async" width="1280" height="800">', esc_url( $project['image_src'] ), esc_attr( $project['image_alt'] ), esc_attr( $attrs['class'] ), esc_attr( $attrs['loading'] ) );
    }
    $initials = '';
    foreach ( array_slice( preg_split( '/\s+/', remove_accents( $project['title'] ) ), 0, 2 ) as $word ) { $initials .= mb_substr( $word, 0, 1 ); }
    return sprintf(
        '<div class="project-cover-type" data-category="%1$s"><span class="cover-initials" aria-hidden="true">%2$s</span><span class="cover-title">%3$s</span><span class="cover-meta">%4$s</span></div>',
        esc_attr( $project['primary_category'] ), esc_html( strtoupper( $initials ) ), esc_html( $project['title'] ),
        esc_html( implode( ' · ', array_slice( (array) $project['stack'], 0, 3 ) ) )
    );
}

/** Texto del marco de navegador: dominio público o, si no hay, la URL del repositorio. */
function am_project_frame_label( array $project ) {
    if ( $project['demo'] ) { return (string) wp_parse_url( $project['demo'], PHP_URL_HOST ); }
    if ( $project['repository'] ) { return preg_replace( '~^https?://(www\.)?~', '', untrailingslashit( $project['repository'] ) ); }
    return $project['title'];
}

function am_project_status_badge( array $project ) {
    if ( ! $project['status_label'] ) { return ''; }
    return '<span class="status-badge status-' . esc_attr( $project['status'] ) . '">' . esc_html( $project['status_label'] ) . '</span>';
}
