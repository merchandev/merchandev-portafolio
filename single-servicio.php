<?php
/**
 * Template for Single Service (Landing Page)
 */
get_header(); ?>

<main class="service-landing">
    <?php while ( have_posts() ) : the_post(); ?>
        
        <!-- Hero Section para el Servicio -->
        <section class="hero-section" style="min-height: 60vh; padding: 160px 0 60px;">
            <div class="aura-effect"></div>
            <div class="container" style="position: relative; z-index: 2;">
                <div class="hero-content" style="max-width: 900px; text-align: left; align-items: flex-start;">
                    <a href="<?php echo home_url('/'); ?>" style="display: inline-flex; align-items: center; gap: 0.5rem; color: var(--color-text-muted); margin-bottom: 2rem; font-size: 0.9rem;">
                        <i class='bx bx-left-arrow-alt'></i> Volver al inicio
                    </a>
                    <span class="hero-tag fade-up">Servicio Especializado</span>
                    <h1 class="hero-title fade-up delay-100" style="font-size: clamp(2.5rem, 5vw, 4rem); text-align: left;">
                        <?php the_title(); ?>
                    </h1>
                </div>
            </div>
        </section>

        <!-- Contenido del Servicio -->
        <section class="section-padding bg-darker">
            <div class="container">
                <div class="grid-2">
                    <div class="service-content glass-card scroll-reveal" style="padding: 3rem;">
                        <?php 
                        // The content is pre-formatted with H2, H3 and paragraphs
                        the_content(); 
                        ?>
                    </div>
                    
                    <div class="service-sidebar scroll-reveal delay-100">
                        <div class="glass-card flex-column" style="position: sticky; top: 120px;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 1.5rem;">¿Necesitas este servicio?</h3>
                            <p style="color: var(--color-text-muted); margin-bottom: 2rem;">Hablemos sobre los requerimientos técnicos de tu proyecto y cómo puedo ayudarte a escalarlo.</p>
                            
                            <a href="<?php echo home_url('/contacto/?servicio=' . urlencode(get_the_title())); ?>" class="btn-primary" style="width: 100%; justify-content: center;">
                                <i class='bx bx-envelope'></i> Solicitar Presupuesto
                            </a>
                            
                            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--color-border);">
                                <p style="font-size: 0.9rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 0.5rem;">
                                    <i class='bx bx-check-circle' style="color: var(--color-goku-orange);"></i> Respuesta en 24-48 horas
                                </p>
                                <p style="font-size: 0.9rem; color: var(--color-text-muted); display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                                    <i class='bx bx-check-circle' style="color: var(--color-goku-orange);"></i> Consultoría técnica inicial
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?php endwhile; ?>
</main>

<style>
.service-content h2 { font-size: 2rem; margin-bottom: 2rem; line-height: 1.3; color: var(--color-text-main); }
.service-content h3 { font-size: 1.5rem; margin-top: 2.5rem; margin-bottom: 1rem; color: var(--color-goku-orange); }
.service-content p { color: var(--color-text-muted); font-size: 1.1rem; line-height: 1.8; margin-bottom: 1.5rem; }
.service-content ul { color: var(--color-text-muted); font-size: 1.1rem; line-height: 1.8; padding-left: 1.5rem; margin-bottom: 1.5rem; }
.service-content li { margin-bottom: 0.8rem; }
</style>

<?php get_footer(); ?>
