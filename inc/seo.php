<?php
/**
 * SEO complementario. Si hay un plugin SEO activo (Yoast en producción), el tema no
 * imprime metadatos propios: solo añade el nodo del proyecto al grafo de Yoast.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_seo_plugin_active() {
    return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

function am_person_schema() {
    return array( '@type' => 'Person', 'name' => 'Arturo Merchán', 'url' => home_url( '/' ), 'jobTitle' => 'Desarrollador de software Full-Stack',
        'sameAs' => array( 'https://github.com/merchandev', 'https://www.linkedin.com/in/merchandev/' ) );
}

/** Nodo CreativeWork de un caso de estudio, construido solo con datos publicados. */
function am_project_schema( $post_id ) {
    $project = am_project_data( get_post( $post_id ) );
    $node = array(
        '@type' => 'CreativeWork', '@id' => $project['url'] . '#proyecto', 'name' => $project['title'], 'url' => $project['url'],
        'description' => wp_strip_all_tags( $project['excerpt'] ), 'creator' => am_person_schema(),
        'dateModified' => get_post_modified_time( 'c', true, $post_id ), 'inLanguage' => 'es',
    );
    if ( $project['stack'] ) { $node['keywords'] = implode( ', ', $project['stack'] ); }
    if ( $project['repository'] ) { $node['sameAs'] = $project['repository']; }
    if ( $project['image_id'] ) { $node['image'] = wp_get_attachment_image_url( $project['image_id'], 'large' ); }
    return $node;
}

add_filter( 'wpseo_schema_graph', function ( $graph ) {
    if ( is_singular( 'proyecto' ) && is_array( $graph ) ) {
        $node = am_project_schema( get_queried_object_id() );
        $node['mainEntityOfPage'] = array( '@id' => get_permalink( get_queried_object_id() ) );
        $graph[] = $node;
    }
    return $graph;
} );

add_action( 'wp_head', function () {
    if ( am_seo_plugin_active() ) { return; }
    $description = '';
    if ( is_front_page() ) {
        $description = 'Arturo Merchán, desarrollador de software Full-Stack: plataformas digitales, sistemas empresariales y aplicaciones a medida, de la arquitectura a la puesta en producción.';
    } elseif ( is_singular( array( 'proyecto', 'servicio', 'page', 'post' ) ) && has_excerpt() ) {
        $description = get_the_excerpt();
    } elseif ( is_post_type_archive( 'proyecto' ) ) {
        $description = 'Catálogo de proyectos de Arturo Merchán: plataformas SaaS, sistemas empresariales, aplicaciones móviles, WordPress, comercio digital e infraestructura.';
    }
    if ( $description ) { echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $description ) ) . '">' . "\n"; }
    $data = null;
    if ( is_front_page() ) { $data = array_merge( array( '@context' => 'https://schema.org' ), am_person_schema() ); }
    if ( is_singular( 'proyecto' ) ) { $data = array_merge( array( '@context' => 'https://schema.org' ), am_project_schema( get_queried_object_id() ) ); }
    if ( $data ) { echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP ) . '</script>' . "\n"; }
}, 5 );
