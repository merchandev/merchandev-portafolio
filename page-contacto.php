<?php /** Template Name: Contacto */ get_header(); ?>
<section id="contacto" class="section-padding page-top" aria-labelledby="contacto-title">
    <div class="container contact-layout">
        <div class="contact-info scroll-reveal">
            <span class="section-tag">Contacto</span>
            <h1 id="contacto-title" class="section-title">Cuéntame qué quieres construir</h1>
            <p class="contact-copy">Una plataforma, un sistema interno, una integración o el despliegue de una aplicación que ya tienes. Empecemos por el problema que necesitas resolver.</p>
            <ol class="contact-steps">
                <li><strong>Me escribes</strong> con el contexto, el estado actual y los plazos.</li>
                <li><strong>Te respondo personalmente</strong> con preguntas concretas o una propuesta de siguiente paso.</li>
                <li><strong>Definimos el alcance</strong> antes de hablar de desarrollo.</li>
            </ol>
            <div class="contact-alternatives">
                <a href="mailto:soporte@merchan.dev"><i class="bx bx-envelope" aria-hidden="true"></i> soporte@merchan.dev</a>
                <a href="https://wa.me/584249677293" target="_blank" rel="noopener noreferrer"><i class="bx bxl-whatsapp" aria-hidden="true"></i> Escríbeme por WhatsApp<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a>
                <a href="<?php echo esc_url( home_url( '/proyectos/' ) ); ?>"><i class="bx bx-code-block" aria-hidden="true"></i> Antes, revisa mis proyectos</a>
            </div>
        </div>
        <div class="glass-card scroll-reveal"><?php get_template_part( 'template-parts/contact-form' ); ?></div>
    </div>
</section>
<?php get_footer(); ?>
