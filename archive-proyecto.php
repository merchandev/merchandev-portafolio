<?php get_header(); ?>
<section class="editorial-section container">
    <p class="eyebrow">Portafolio / trabajo seleccionado</p><h1 class="display-title">Del problema<br>al producto.</h1>
    <p class="intro-copy">Contexto, decisiones y trabajo detrás de cada proyecto.</p>
    <div class="project-grid">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); get_template_part( 'template-parts/project-card' ); endwhile; else : ?>
        <div class="empty-projects"><?php get_template_part('template-parts/experience-cases'); ?></div>
    <?php endif; ?>
    </div>
    <?php the_posts_pagination( array( 'prev_text' => 'Anterior', 'next_text' => 'Siguiente' ) ); ?>
    <a class="btn-primary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Hablemos de tu proyecto ↗</a>
</section>
<?php get_footer(); ?>
