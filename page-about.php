<?php
/**
 * Template Name: Sobre Mí (About)
 */
get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <section class="about-hero animate-fade-in-up" style="padding: 10rem 0 6rem; position: relative;">
        <div class="container">
            <div class="grid-2" style="align-items: center; gap: 4rem;">
                
                <!-- Left: Text content -->
                <div>
                    <h1 style="font-size: clamp(3rem, 6vw, 4.5rem); line-height: 1.1; margin-bottom: 1.5rem;">
                        Hola, soy <br>
                        <span style="color: var(--color-goku-orange);">Arturo Merchán</span>
                    </h1>
                    <h2 style="font-size: 1.5rem; color: var(--color-ssj-yellow); margin-bottom: 2rem; font-weight: 500;">
                        Ingeniero y Arquitecto de Software
                    </h2>
                    <div class="post-body" style="font-size: 1.1rem;">
                        <?php the_content(); ?>
                    </div>
                </div>

                <!-- Right: Visual/Image -->
                <div class="about-visual animate-fade-in-up delay-200" style="position: relative;">
                    <div class="aura-effect" style="width: 100%; height: 100%; opacity: 0.5;"></div>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(255,122,0,0.2); border: 2px solid rgba(255,255,255,0.05); transform: rotate(2deg); transition: transform 0.3s;" onmouseover="this.style.transform='rotate(0deg) scale(1.02)'" onmouseout="this.style.transform='rotate(2deg) scale(1)'">
                            <?php the_post_thumbnail('large', ['style' => 'width: 100%; height: auto; display: block;']); ?>
                        </div>
                    <?php else: ?>
                        <div style="aspect-ratio: 4/5; background: linear-gradient(135deg, var(--color-goku-orange), var(--color-vegeta-blue)); border-radius: 20px; display: flex; align-items: center; justify-content: center; transform: rotate(2deg);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

</article>

<style>
.post-body p { margin-bottom: 1.5rem; color: rgba(255,255,255,0.85); line-height: 1.8; }
</style>

<?php get_footer(); ?>
