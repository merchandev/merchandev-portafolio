<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<article class="project-card">
    <a class="project-cover" href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr( 'Ver caso: ' . get_the_title() ); ?>">
        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large', array( 'loading' => 'lazy' ) ); } else { ?>
            <div class="project-type"><span>CASO DE ESTUDIO</span><strong><?php the_title(); ?></strong><span><?php echo esc_html( get_post_meta( get_the_ID(), '_am_sector', true ) ); ?></span></div>
        <?php } ?>
    </a>
    <div class="project-caption"><div><p class="eyebrow"><?php echo esc_html( get_post_meta( get_the_ID(), '_am_status', true ) ); ?></p><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( get_the_excerpt() ); ?></p></div><a class="case-link" href="<?php the_permalink(); ?>">Ver caso <span aria-hidden="true">↗</span></a></div>
</article>
