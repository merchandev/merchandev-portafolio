<?php
/**
 * Single Post Template (single.php)
 */
get_header(); ?>

<div style="padding-top: 100px; min-height: 100vh;">
    <style>
        /* Reduce espacio superior en móvil */
        @media (max-width: 768px) {
            .single-hero {
                padding-top: 2rem !important;
            }

            div[style*="padding-top: 100px"] {
                padding-top: 70px !important;
            }
        }
    </style>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <!-- ============================== -->
        <!-- POST HEADER: Título + Fecha    -->
        <!-- ============================== -->
        <header class="single-hero scroll-reveal" style="padding: 5rem 0 1.5rem;">
            <div class="container" style="max-width: 1100px; padding: 0 2rem;">

                <!-- Categoría principal como section-tag (sobre el título) -->
                <?php
                $categories = get_the_category();
                $cat_name = !empty($categories) ? esc_html($categories[0]->name) : 'Artículo';
                ?>
                <span class="section-tag"><?php echo $cat_name; ?></span>

                <!-- Título principal -->
                <h1 class="single-post-title">
                    <?php the_title(); ?>
                </h1>

                <!-- Separador decorativo -->
                <div class="post-title-divider"></div>

                <!-- Fecha sola, sin autor -->
                <div class="post-meta-top">
                    <span class="material-symbols-outlined"
                        style="font-size: 18px; vertical-align: middle; color: var(--color-goku-orange);">calendar_today</span>
                    <time datetime="<?php echo get_the_date('Y-m-d'); ?>"
                        style="color: var(--color-text-muted); font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em;">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>

            </div>
        </header>

        <!-- ============================== -->
        <!-- POST CONTENT                   -->
        <!-- ============================== -->
        <section class="single-content scroll-reveal delay-100" style="padding: 0 0 6rem; margin-top: 0;">
            <div class="container" style="max-width: 1100px; padding: 0 2rem;">
                <div class="post-body">
                    <?php the_content(); ?>
                </div>

                <!-- Navegación entre posts -->
                <nav class="post-navigation"
                    style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; gap: 1rem;">
                    <?php
                    $prev = get_previous_post();
                    $next = get_next_post();
                    if ($prev): ?>
                        <a href="<?php echo get_permalink($prev); ?>" class="post-nav-link">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span><?php echo get_the_title($prev); ?></span>
                        </a>
                    <?php else: ?>
                        <span></span>
                    <?php endif;
                    if ($next): ?>
                        <a href="<?php echo get_permalink($next); ?>" class="post-nav-link post-nav-right">
                            <span><?php echo get_the_title($next); ?></span>
                            <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    <?php endif; ?>
                </nav>

            </div>
        </section>

    </article>
</div>

<style>
    /* ============================================
   SINGLE POST — Premium Reading Experience
   ============================================ */

    /* Meta line (fecha + categoría) */
    .post-meta-top {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1.5rem;
    }

    /* Título del post */
    .single-post-title {
        font-size: clamp(2.2rem, 5vw, 3.8rem);
        font-family: var(--font-display, 'Orbitron', sans-serif);
        line-height: 1.15;
        color: var(--color-text-main);
        margin: 0 0 2rem;
        font-weight: 900;
    }

    /* Línea divisora naranja bajo el título */
    .post-title-divider {
        width: 64px;
        height: 4px;
        background: var(--color-goku-orange);
        border-radius: 999px;
        margin-bottom: 0;
    }

    /* Cuerpo del post */
    .post-body {
        font-size: 1.15rem;
        line-height: 1.85;
        color: var(--color-text-main);
    }

    .post-body p {
        margin-bottom: 1.75rem;
        color: rgba(255, 255, 255, 0.82);
    }

    .post-body h2 {
        font-size: 2rem;
        margin: 3rem 0 1.25rem;
        color: var(--color-goku-orange);
        font-family: var(--font-display, 'Orbitron', sans-serif);
    }

    .post-body h3 {
        font-size: 1.5rem;
        margin: 2.5rem 0 1rem;
        color: var(--color-ssj-yellow, #facc15);
    }

    .post-body ul,
    .post-body ol {
        margin-bottom: 1.75rem;
        padding-left: 1.75rem;
    }

    .post-body li {
        margin-bottom: 0.5rem;
        color: rgba(255, 255, 255, 0.82);
    }

    .post-body a {
        color: var(--color-vegeta-blue);
        text-decoration: underline;
        text-underline-offset: 4px;
        transition: color 0.2s;
    }

    .post-body a:hover {
        color: var(--color-goku-orange);
    }

    .post-body blockquote {
        border-left: 4px solid var(--color-goku-orange);
        margin: 2.5rem 0;
        padding: 1.25rem 1.75rem;
        background: rgba(255, 122, 0, 0.05);
        border-radius: 0 12px 12px 0;
        font-style: italic;
        font-size: 1.2rem;
        color: var(--color-text-muted);
    }

    .post-body img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 2rem 0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        display: block;
    }

    .post-body pre {
        background: rgba(10, 10, 10, 0.85);
        padding: 1.5rem;
        border-radius: 12px;
        overflow-x: auto;
        margin: 2rem 0;
        border: 1px solid rgba(255, 255, 255, 0.08);
        font-size: 0.93rem;
    }

    .post-body code {
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.93em;
        color: #a5d6ff;
    }

    /* Tags */
    .post-tags a {
        background: rgba(255, 255, 255, 0.05);
        padding: 0.35rem 0.8rem;
        border-radius: 6px;
        font-size: 0.82rem;
        text-decoration: none;
        color: var(--color-text-muted);
        transition: all 0.2s;
        border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .post-tags a:hover {
        background: var(--color-vegeta-blue);
        color: #fff;
        border-color: var(--color-vegeta-blue);
    }

    /* Navegación entre posts */
    .post-nav-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--color-text-muted);
        text-decoration: none;
        font-size: 0.9rem;
        max-width: 45%;
        transition: color 0.2s;
        line-height: 1.4;
    }

    .post-nav-link:hover {
        color: var(--color-goku-orange);
    }

    .post-nav-right {
        text-align: right;
        justify-content: flex-end;
    }

    .post-nav-link .material-symbols-outlined {
        font-size: 20px;
        flex-shrink: 0;
    }
</style>

<?php get_footer(); ?>