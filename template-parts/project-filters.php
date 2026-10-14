<?php
/** Filtros del catálogo: enlaces a las categorías (funcionan sin JavaScript). $args: current (slug o ''), counts (bool). */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$current = $args['current'] ?? '';
$terms = get_terms( array( 'taxonomy' => 'tipo_proyecto', 'hide_empty' => true ) );
$order = array_keys( am_seed_categories() );
if ( ! is_wp_error( $terms ) ) {
    usort( $terms, function ( $a, $b ) use ( $order ) {
        $pa = array_search( $a->slug, $order, true ); $pb = array_search( $b->slug, $order, true );
        return ( $pa === false ? 99 : $pa ) - ( $pb === false ? 99 : $pb );
    } );
}
?>
<nav class="project-filters" aria-label="Filtrar proyectos por categoría">
    <a href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>"<?php echo $current === '' ? ' aria-current="page"' : ''; ?>>Todos</a>
    <?php if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) : ?>
        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>"<?php echo $current === $term->slug ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $term->name ); ?><?php if ( ! empty( $args['counts'] ) ) : ?> <span class="filter-count"><?php echo (int) $term->count; ?></span><?php endif; ?></a>
    <?php endforeach; endif; ?>
</nav>
