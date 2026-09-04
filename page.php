<?php
/**
 * Default Page Template (page.php)
 */
get_header(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <header class="page-hero text-center animate-fade-in-up" style="padding: 10rem 0 4rem; position: relative;">
        <div class="container" style="max-width: 800px; position: relative; z-index: 2;">
            <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); line-height: 1.2; margin-bottom: 2rem;">
                <?php the_title(); ?>
            </h1>
        </div>
    </header>

    <?php if ( has_post_thumbnail() ) : ?>
    <section class="page-featured-image animate-fade-in-up delay-100" style="padding: 0 2rem;">
        <div class="container" style="max-width: 1000px; padding: 0;">
            <div style="border-radius: 20px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.05);">
                <?php the_post_thumbnail('large', ['style' => 'width: 100%; height: auto; display: block; max-height: 500px; object-fit: cover;']); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="page-content animate-fade-in-up delay-200" style="padding: 4rem 0 6rem;">
        <div class="container" style="max-width: 768px;">
            <!-- Reusing the post-body styling for consistent reading experience -->
            <div class="post-body">
                <?php the_content(); ?>
            </div>
        </div>
    </section>

</article>

<style>
/* Scoped styles for page content - same as single.php for consistency */
.post-body { font-size: 1.15rem; line-height: 1.8; color: var(--color-text-main); }
.post-body p { margin-bottom: 2rem; color: rgba(255,255,255,0.85); }
.post-body h2 { font-size: 2.2rem; margin: 3rem 0 1.5rem; color: var(--color-goku-orange); }
.post-body h3 { font-size: 1.6rem; margin: 2.5rem 0 1rem; color: var(--color-ssj-yellow); }
.post-body ul, .post-body ol { margin-bottom: 2rem; padding-left: 2rem; }
.post-body a { text-decoration: underline; text-decoration-color: var(--color-vegeta-blue); text-underline-offset: 4px; }
.post-body a:hover { color: var(--color-goku-orange); text-decoration-color: var(--color-goku-orange); }
.post-body img { max-width: 100%; height: auto; border-radius: 12px; margin: 2rem 0; box-shadow: 0 10px 30px rgba(0,0,0,0.3); }
</style>

<?php get_footer(); ?>
