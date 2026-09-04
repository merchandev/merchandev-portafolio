<?php
/**
 * Template for displaying all Servicios (Archive)
 */
get_header(); ?>

<section class="section-padding bg-darker" style="min-height: 100vh;">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">Todos los Servicios</span>
            <h1 class="section-title">Servicios Profesionales</h1>
            <p class="section-subtitle" style="margin: 1rem auto;">Explora el catálogo completo de soluciones tecnológicas, desde arquitectura SaaS hasta continuidad operativa.</p>
        </div>

        <div class="grid-3 mt-4">
            <?php
            if ( have_posts() ) :
                while ( have_posts() ) : the_post();
                    // We can map some icons based on the slug or just use a generic one if not specified
                    $slug = get_post_field( 'post_name', get_post() );
                    $icon = 'bx-cube-alt';
                    
                    if(strpos($slug, 'saas') !== false) $icon = 'bx-cloud';
                    else if(strpos($slug, 'streaming') !== false) $icon = 'bx-broadcast';
                    else if(strpos($slug, 'vps') !== false) $icon = 'bx-server';
                    else if(strpos($slug, 'wordpress') !== false) $icon = 'bxl-wordpress';
                    else if(strpos($slug, 'ecommerce') !== false) $icon = 'bx-cart-alt';
                    else if(strpos($slug, 'seo') !== false) $icon = 'bx-trending-up';
                    else if(strpos($slug, 'ia') !== false) $icon = 'bx-brain';
                    else if(strpos($slug, 'infraestructura') !== false) $icon = 'bx-power-off';
            ?>
            
            <a href="<?php the_permalink(); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx <?php echo esc_attr($icon); ?>' style="font-size: 28px;"></i>
                </div>
                <div class="card-body" style="display: flex; flex-direction: column; flex: 1;">
                    <h3><?php the_title(); ?></h3>
                    <p style="margin-bottom: 1.5rem; flex: 1;"><?php echo wp_trim_words(get_the_content(), 15, '...'); ?></p>
                    <div style="margin-top: auto;">
                        <span class="btn-primary" style="padding: 8px 16px; font-size: 0.9rem; border-radius: 50px; display: inline-flex; align-items: center; gap: 8px;">
                            Ver más <i class='bx bx-right-arrow-alt'></i>
                        </span>
                    </div>
                </div>
            </a>
            
            <?php 
                endwhile;
                
                // Pagination
                the_posts_pagination( array(
                    'mid_size'  => 2,
                    'prev_text' => __( 'Anterior', 'textdomain' ),
                    'next_text' => __( 'Siguiente', 'textdomain' ),
                ) );
                
            else :
                echo '<p class="text-center" style="grid-column: 1/-1;">No hay servicios disponibles en este momento.</p>';
            endif;
            ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
