<?php
/**
 * Catálogo de proyectos (/proyectos/) y categorías (/tipo-de-proyecto/<categoría>/).
 * Destacados con caso de estudio primero; el resto como fichas resumidas.
 */
get_header();
$term = is_tax( 'tipo_proyecto' ) ? get_queried_object() : null;
$projects = array();
if ( have_posts() ) {
    while ( have_posts() ) { the_post(); $projects[] = am_project_data( get_post() ); }
    wp_reset_postdata();
} elseif ( ! $term ) {
    $projects = am_projects_for_display( null );
}
$featured = array_values( array_filter( $projects, function ( $p ) { return $p['featured']; } ) );
$others = array_values( array_filter( $projects, function ( $p ) { return ! $p['featured']; } ) );
?>
<section class="section-padding page-top" aria-labelledby="catalogo-title">
    <div class="container">
        <header class="section-header text-center scroll-reveal">
            <span class="section-tag"><?php echo $term ? 'Proyectos · Categoría' : 'Proyectos'; ?></span>
            <h1 id="catalogo-title" class="section-title"><?php echo $term ? esc_html( $term->name ) : 'Del problema al producto'; ?></h1>
            <p class="section-subtitle"><?php echo $term && $term->description ? esc_html( $term->description ) : 'Plataformas, sistemas, aplicaciones e infraestructura. Los proyectos principales tienen un caso de estudio con contexto, arquitectura y estado real; el resto son fichas breves con enlace al código.'; ?></p>
        </header>
        <?php if ( am_project_has_published() ) { get_template_part( 'template-parts/project-filters', null, array( 'current' => $term ? $term->slug : '', 'counts' => true ) ); } ?>

        <?php if ( $featured ) : ?>
        <h2 class="catalog-heading">Casos de estudio</h2>
        <div class="project-grid-standard">
            <?php foreach ( $featured as $project ) { get_template_part( 'template-parts/project-card', null, array( 'project' => $project, 'variant' => 'standard', 'heading' => 'h3' ) ); } ?>
        </div>
        <?php endif; ?>

        <?php if ( $others ) : ?>
        <h2 class="catalog-heading"><?php echo $featured ? 'Más proyectos' : 'Proyectos'; ?></h2>
        <div class="project-grid-compact">
            <?php foreach ( $others as $project ) { get_template_part( 'template-parts/project-card', null, array( 'project' => $project, 'variant' => 'compact', 'heading' => 'h3' ) ); } ?>
        </div>
        <?php endif; ?>

        <?php if ( ! $projects ) : ?>
            <p class="empty-state">Todavía no hay proyectos publicados en esta categoría.</p>
        <?php endif; ?>

        <div class="glass-card catalog-cta scroll-reveal">
            <div>
                <h2>¿Tienes un proyecto parecido?</h2>
                <p>Cuéntame qué proceso quieres digitalizar o qué sistema necesitas mejorar.</p>
            </div>
            <div class="action-row">
                <a class="btn-primary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Hablemos de tu proyecto <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a>
                <a class="btn-secondary" href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> GitHub<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
