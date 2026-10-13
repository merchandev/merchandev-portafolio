<?php get_header(); while ( have_posts() ) : the_post(); ?>
<article class="container editorial-section case-study">
    <a href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>">← Todos los proyectos</a>
    <p class="eyebrow"><?php echo esc_html( get_post_meta( get_the_ID(), '_am_sector', true ) ); ?> / <?php echo esc_html( get_post_meta( get_the_ID(), '_am_status', true ) ); ?></p>
    <h1 class="display-title"><?php the_title(); ?></h1><p class="intro-copy"><?php echo esc_html( get_the_excerpt() ); ?></p>
    <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'full', array( 'class' => 'case-image', 'fetchpriority' => 'high' ) ); } ?>
    <div class="case-facts"><?php foreach ( array( 'role' => 'Mi participación', 'stack' => 'Tecnologías', 'problem' => 'El problema', 'solution' => 'La solución', 'evidence' => 'Resultado y evidencia' ) as $key => $label ) : $value = get_post_meta( get_the_ID(), '_am_' . $key, true ); if ( ! $value ) { continue; } ?>
        <section><h2><?php echo esc_html( $label ); ?></h2><p><?php echo nl2br( esc_html( $value ) ); ?></p></section>
    <?php endforeach; ?></div>
    <div class="case-content"><?php the_content(); ?></div>
    <div class="action-row"><?php foreach ( array( 'demo' => 'Ver producto', 'repository' => 'Ver código' ) as $key => $label ) : $url = get_post_meta( get_the_ID(), '_am_' . $key, true ); if ( ! $url ) { continue; } ?><a class="btn-secondary" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $label ); ?> ↗</a><?php endforeach; ?><a class="btn-primary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Construyamos tu próximo proyecto ↗</a></div>
</article>
<?php endwhile; get_footer(); ?>
