<?php
get_header();
$featured = am_projects_for_display( true, 6 );
$others = am_projects_for_display( false, 6 );
$cv_url = get_template_directory_uri() . '/assets/docs/CV_Arturo_Merchan.pdf';
?>
<section class="hero-section" aria-labelledby="hero-title">
    <div class="aura-effect"></div>
    <div class="container" style="position: relative; z-index: 2;">
        <div class="hero-flex">
            <div class="hero-content">
                <p class="hero-signature fade-up">De la infraestructura al píxel</p>
                <h1 id="hero-title" class="hero-title fade-up delay-100">
                    <span id="typing-text" class="text-gradient">Arturo Merchán</span><span class="typing-cursor" aria-hidden="true"></span>
                </h1>
                <p class="hero-subtitle fade-up delay-200">Desarrollador de software Full-Stack<span class="hero-subtitle-detail"> · Plataformas digitales, sistemas empresariales y soluciones a medida</span></p>
                <p class="hero-text fade-up delay-300">Diseño, desarrollo y pongo en marcha productos digitales completos: la interfaz, la lógica de negocio, las APIs y bases de datos, las integraciones y el servidor donde todo funciona. He construido sistemas de reservas y pagos, plataformas documentales, un directorio médico con app móvil y portales privados para empresas.</p>
                <div class="hero-cta fade-up delay-400">
                    <a href="#proyectos" class="btn-primary"><i class="bx bx-code-block icon-margin" aria-hidden="true"></i> Ver proyectos</a>
                    <a href="https://wa.me/584249677293?text=Hola%20Arturo.%20Quiero%20hablar%20sobre%20un%20proyecto." target="_blank" rel="noopener noreferrer" class="btn-secondary btn-whatsapp">
                        <i class="bx bxl-whatsapp icon-margin" aria-hidden="true"></i> Hablemos de tu proyecto<span class="screen-reader-text"> (WhatsApp, se abre en una pestaña nueva)</span>
                    </a>
                </div>
                <ul class="hero-domains fade-up delay-500" aria-label="Sectores con proyectos">
                    <li>Movilidad y turismo</li><li>Salud</li><li>Publicaciones legales</li><li>Medios</li><li>Comercio</li>
                </ul>
                <a class="scroll-indicator bounce fade-up delay-500" href="#proyectos" aria-label="Ir a proyectos destacados">
                    <span class="material-symbols-outlined" aria-hidden="true">keyboard_arrow_down</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section id="proyectos" class="section-padding bg-darker" aria-labelledby="proyectos-title">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">02 · Proyectos destacados</span>
            <h2 id="proyectos-title" class="section-title">Sistemas construidos para operar procesos reales</h2>
            <p class="section-subtitle">Plataformas en las que he trabajado de principio a fin. Cada caso explica el problema, las decisiones técnicas y el estado actual, sin cifras infladas.</p>
        </div>
        <?php if ( $featured ) : ?>
        <div class="featured-projects">
            <?php foreach ( $featured as $index => $project ) :
                get_template_part( 'template-parts/project-card', null, array( 'project' => $project, 'variant' => $index < 2 ? 'feature' : 'standard', 'reverse' => $index === 1, 'heading' => 'h3' ) );
            endforeach; ?>
        </div>
        <?php endif; ?>
        <div class="text-center mt-4"><a class="btn-secondary" href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ?: home_url( '/proyectos/' ) ); ?>">Ver todos los proyectos <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a></div>
    </div>
</section>

<section id="especialidades" class="section-padding bg-gradient" aria-labelledby="especialidades-title">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">03 · Especialidades</span>
            <h2 id="especialidades-title" class="section-title">Tres áreas, un mismo proceso de ingeniería</h2>
            <p class="section-subtitle">No son servicios sueltos: cada área cubre una parte del ciclo de vida de un producto digital y se apoya en las otras dos.</p>
        </div>
        <?php get_template_part( 'template-parts/specialties', null, array( 'heading' => 'h3' ) ); ?>
    </div>
</section>

<section id="capacidades" class="section-padding" aria-labelledby="capacidades-title">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">04 · Capacidades técnicas</span>
            <h2 id="capacidades-title" class="section-title">Tecnologías organizadas por función</h2>
            <p class="section-subtitle">Distingo entre lo que uso a diario, lo que he aplicado en proyectos concretos y las herramientas de apoyo. Todo aparece en repositorios públicos o en proyectos documentados.</p>
        </div>
        <?php get_template_part( 'template-parts/capabilities', null, array( 'heading' => 'h3' ) ); ?>
    </div>
</section>

<section id="metodo" class="section-padding bg-gradient" aria-labelledby="metodo-title">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">05 · Cómo desarrollo un producto</span>
            <h2 id="metodo-title" class="section-title">Del análisis a la evolución</h2>
            <p class="section-subtitle">Un proceso completo en el que cada etapa termina en algo que puedes revisar.</p>
        </div>
        <?php get_template_part( 'template-parts/process' ); ?>
    </div>
</section>

<section id="sobre-mi" class="section-padding" aria-labelledby="sobre-mi-title">
    <div class="container">
        <div class="section-header text-left scroll-reveal">
            <span class="section-tag">06 · Sobre mí</span>
            <h2 id="sobre-mi-title" class="section-title">Una persona, el ciclo completo</h2>
        </div>
        <div class="about-content grid-2 mt-4">
            <div class="glass-card scroll-reveal about-card">
                <p class="about-lead">Soy Arturo Merchán, desarrollador de software Full-Stack en Maturín, Venezuela. Desde 2017 trabajo en proyectos web y en los últimos años me he centrado en plataformas completas: reservas y pagos para traslados en Barcelona, un sistema documental para un diario de avisos legales, un directorio médico con app móvil y temas WordPress a medida para medios y comercio.</p>
                <p>Trabajo de forma directa con cada cliente: analizo el proceso, propongo la arquitectura, desarrollo, despliego y documento. También soy cofundador de Espressivo Venezuela, desde donde colaboro en proyectos para medios y empresas.</p>
                <div class="action-row mt-4">
                    <a class="btn-secondary" href="<?php echo esc_url( home_url( '/sobre-mi/' ) ); ?>">Trayectoria y formación <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a>
                    <a class="text-link" href="<?php echo esc_url( $cv_url ); ?>" download><i class="bx bxs-file-pdf" aria-hidden="true"></i> Descargar CV (PDF)</a>
                </div>
            </div>
            <div class="glass-card scroll-reveal delay-100 work-principles">
                <h3>Cómo trabajo</h3>
                <ul>
                    <li><i class="bx bx-user-voice" aria-hidden="true"></i><span><strong>Trato directo.</strong> Hablas con quien diseña y escribe el código, no con intermediarios.</span></li>
                    <li><i class="bx bx-git-branch" aria-hidden="true"></i><span><strong>Código versionado.</strong> Repositorio con historial, documentación y pruebas desde el inicio.</span></li>
                    <li><i class="bx bx-test-tube" aria-hidden="true"></i><span><strong>Entorno de pruebas.</strong> Revisas los avances antes de que lleguen a producción.</span></li>
                    <li><i class="bx bx-lock-alt" aria-hidden="true"></i><span><strong>Secretos fuera del código.</strong> Credenciales en el servidor, nunca en el repositorio.</span></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="catalogo" class="section-padding bg-darker" aria-labelledby="catalogo-title">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">07 · Otros proyectos</span>
            <h2 id="catalogo-title" class="section-title">Más trabajo, organizado por tipo</h2>
            <p class="section-subtitle">Temas y plugins de WordPress, comercio en Shopify, infraestructura de streaming, aplicaciones sin conexión y experimentos. Los principales tienen caso de estudio; el resto, una ficha breve.</p>
        </div>
        <?php if ( am_project_has_published() ) { get_template_part( 'template-parts/project-filters', null, array( 'current' => '' ) ); } ?>
        <?php if ( $others ) : ?>
        <div class="project-grid-compact">
            <?php foreach ( $others as $project ) { get_template_part( 'template-parts/project-card', null, array( 'project' => $project, 'variant' => 'compact', 'heading' => 'h3' ) ); } ?>
        </div>
        <?php endif; ?>
        <div class="catalog-actions">
            <a class="btn-primary" href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ?: home_url( '/proyectos/' ) ); ?>">Explorar el catálogo completo <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a>
            <a class="btn-secondary" href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> Repositorios en GitHub<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a>
        </div>
    </div>
</section>

<section id="contacto" class="section-padding bg-gradient" aria-labelledby="contacto-title">
    <div class="container">
        <div class="grid-2">
            <div class="contact-info scroll-reveal">
                <span class="section-tag">08 · Contacto</span>
                <h2 id="contacto-title" class="section-title mb-4">¿Qué necesitas construir o mejorar?</h2>
                <p class="contact-copy">Cuéntame el problema, cómo funciona hoy y qué plazos manejas. Te respondo personalmente con preguntas concretas o con una propuesta de siguiente paso.</p>
                <div class="contact-details">
                    <p><i class="bx bx-code-block" aria-hidden="true"></i> Plataformas, sistemas y apps a medida</p>
                    <p><i class="bx bx-git-merge" aria-hidden="true"></i> Integraciones, pagos y automatización</p>
                    <p><i class="bx bx-server" aria-hidden="true"></i> Despliegue, infraestructura y mantenimiento</p>
                </div>
                <div class="contact-alternatives">
                    <a href="mailto:soporte@merchan.dev"><i class="bx bx-envelope" aria-hidden="true"></i> soporte@merchan.dev</a>
                    <a href="https://wa.me/584249677293" target="_blank" rel="noopener noreferrer"><i class="bx bxl-whatsapp" aria-hidden="true"></i> Escríbeme por WhatsApp<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a>
                </div>
            </div>
            <div class="glass-card scroll-reveal"><?php get_template_part( 'template-parts/contact-form' ); ?></div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
