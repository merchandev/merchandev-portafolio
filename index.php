<?php
/**
 * Plantilla genérica: categorías, etiquetas, autores, búsquedas y cualquier listado sin
 * plantilla propia. (Antes mostraba una portada antigua con otro posicionamiento.)
 */
get_header();
if ( is_search() ) {
    $title = sprintf( 'Resultados para «%s»', get_search_query() );
} elseif ( is_archive() ) {
    $title = wp_strip_all_tags( get_the_archive_title() );
} else {
    $title = 'Publicaciones';
}
$description = is_archive() ? get_the_archive_description() : '';
?>
<section class="section-padding page-top" aria-labelledby="listado-title">
    <div class="container">
        <header class="section-header text-center scroll-reveal">
            <span class="section-tag"><?php echo is_search() ? 'Búsqueda' : 'Archivo'; ?></span>
            <h1 id="listado-title" class="section-title"><?php echo esc_html( $title ); ?></h1>
            <?php if ( $description ) : ?><div class="section-subtitle"><?php echo wp_kses_post( $description ); ?></div><?php endif; ?>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid-3 mt-4">
                <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'glass-card flex-column scroll-reveal listing-card' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a class="listing-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?></a>
                    <?php endif; ?>
                    <p class="listing-meta"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?> · <time datetime="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
                    <h2 class="listing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <p class="listing-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24, '…' ) ); ?></p>
                    <a class="case-link mt-auto" href="<?php the_permalink(); ?>">Leer <i class="bx bx-right-arrow-alt" aria-hidden="true"></i><span class="screen-reader-text">: <?php the_title(); ?></span></a>
                </article>
                <?php endwhile; ?>
            </div>
            <div class="mt-6 text-center"><?php the_posts_pagination( array( 'mid_size' => 2, 'prev_text' => 'Anterior', 'next_text' => 'Siguiente' ) ); ?></div>
        <?php else : ?>
            <div class="glass-card text-center empty-state">
                <p>No hay resultados. Prueba con otra búsqueda o explora las secciones principales.</p>
                <div class="action-row" style="justify-content:center">
                    <a class="btn-secondary" href="<?php echo esc_url( home_url( '/proyectos/' ) ); ?>">Proyectos</a>
                    <a class="btn-secondary" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
                    <a class="btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
