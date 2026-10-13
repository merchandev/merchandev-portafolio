<?php get_header(); ?>
<section class="hero-section">
    <div class="aura-effect"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="hero-flex">
            <!-- Left Column: Text & CTA -->
            <div class="hero-content">
                <h1 class="hero-title fade-up delay-100" style="min-height: 1.2em; display: flex; align-items: center; flex-wrap: wrap;">
                    <span id="typing-text" class="text-gradient">Arturo Merchán</span>
                    <span class="typing-cursor" style="display:inline-block; width:5px; height:1em; background-color:var(--color-goku-orange); margin-left:8px; border-radius:2px; animation: cursorBlink 1s step-end infinite;"></span>
                </h1>
                <h2 class="hero-subtitle fade-up delay-200">
                    Desarrollo de software, web e infraestructura
                </h2>

                <p class="hero-text fade-up delay-300">
                    Soy Arturo Merchán, desarrollador Full-Stack. Construyo sistemas de reservas, herramientas de gestión, sitios WordPress a medida e integraciones. Conecto la experiencia de usuario, la lógica del negocio y la infraestructura para llevar tu proyecto a producción.
                </p>



                <div class="hero-cta fade-up delay-500">
                    <a href="#proyectos" class="btn-primary">
                        <span class="material-symbols-outlined icon-margin">terminal</span>
                        Ver Proyectos
                    </a>
                    <a href="https://wa.me/584249677293?text=Hola%20Arturo.%20Quiero%20hablar%20sobre%20un%20proyecto." target="_blank" rel="noopener noreferrer" class="btn-secondary" style="border-color: #25D366; color: #fff;">
                        <i class='bx bxl-whatsapp icon-margin' style="font-size: 22px; color: #25D366;"></i>
                        Hablemos de tu proyecto
                    </a>
                </div>

                <div class="scroll-indicator bounce fade-up delay-500">
                    <span class="material-symbols-outlined" style="font-size: 36px; color: var(--color-text-muted);">keyboard_arrow_down</span>
                </div>
            </div>

        </div>
    </div>
</section>
<section id="proyectos" class="section-padding bg-darker"><div class="container">
<div class="section-header text-center scroll-reveal"><span class="section-tag">Trabajo y experiencia</span><h2 class="section-title">Lo que construyo, en proyectos reales</h2><p class="section-subtitle">Reservas, inventarios, comercio electrónico e infraestructura de medios. El contexto y mi participación detrás de cada solución.</p></div>
<?php $projects = new WP_Query(array('post_type'=>'proyecto','post_status'=>'publish','posts_per_page'=>4,'orderby'=>array('menu_order'=>'ASC','date'=>'DESC')));
if ($projects->have_posts()) : ?><div class="project-grid"><?php while ($projects->have_posts()) : $projects->the_post(); get_template_part('template-parts/project-card'); endwhile; ?></div><?php wp_reset_postdata(); else : get_template_part('template-parts/experience-cases'); endif; ?>
<div class="text-center mt-4"><a class="btn-secondary" href="<?php echo esc_url(get_post_type_archive_link('proyecto')); ?>">Explorar proyectos <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a></div>
</div></section>

<section id="servicios" class="section-padding bg-darker">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">Lo que ofrezco</span>
            <h2 class="section-title">Soluciones para cada etapa de tu proyecto</h2><p class="section-subtitle" style="margin:1rem auto;">Producto digital · Desarrollo web avanzado · Infraestructura y operación. Estas son las especialidades que conecto para resolver necesidades concretas.</p>
        </div>

        <div class="grid-3 mt-4">
            <a href="<?php echo esc_url( am_service_url('desarrollo-saas-microservicios') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-cloud' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Desarrollo SaaS y Arquitectura de Microservicios</h3>
                    <p>Diseño de bases de datos multi-tenant, orquestación con Docker y Traefik, y desarrollo de sistemas en la nube escalables.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('streaming-audio-video') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-broadcast' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Configuración de Servidores para Streaming de Audio y Video</h3>
                    <p>Servidores RTMP, distribución de audio y video y arquitecturas simulcast para emisoras, medios y transmisiones en vivo.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('administracion-vps-devops') ); ?>" class="glass-card flex-column scroll-reveal delay-200" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-server' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Administración de Servidores VPS y DevOps</h3>
                    <p>Gestión técnica, hardening de seguridad, proxies inversos y mantenimiento de la infraestructura web subyacente.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('desarrollo-avanzado-wordpress') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bxl-wordpress' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Desarrollo Avanzado en WordPress</h3>
                    <p>Programación de Custom Themes, plugins personalizados y arquitecturas offline-first superando las limitaciones tradicionales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('ecommerce-migraciones') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-cart-alt' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>E-commerce y Migración de Plataformas</h3>
                    <p>Migraciones planificadas, inventario e integración de pasarelas de pago y TPV virtuales para modernizar operaciones comerciales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('seo-tecnico') ); ?>" class="glass-card flex-column scroll-reveal delay-200" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-trending-up' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>SEO Técnico y Crecimiento Orgánico Hiperlocal</h3>
                    <p>Auditorías de rendimiento técnico, consolidación de dominios y posicionamiento en motores de búsqueda para dominar mercados locales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('consultoria-ia-rendimiento') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-brain' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Consultoría en Integración de IA y Rendimiento Web</h3>
                    <p>Auditorías de código, optimización de carga e integración de flujos potenciados por Inteligencia Artificial.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( am_service_url('infraestructura-fisica-continuidad') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-power-off' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Continuidad Operativa e Infraestructura Física</h3>
                    <p>Asesoría en configuraciones multi-PC, recuperación ante fallos y sistemas de respaldo energético para entornos de alta exigencia.</p>
                </div>
            </a>
        </div>
    </div>
</section>
<section id="metodo" class="section-padding bg-gradient"><div class="container"><div class="section-header text-center scroll-reveal"><span class="section-tag">Cómo trabajo</span><h2 class="section-title">Un proceso claro, de principio a fin</h2><p class="section-subtitle">Cada etapa tiene una pregunta que resolver y una entrega que revisar.</p></div><div class="grid-2">
<?php $steps=array(
array('01','Diagnóstico y alcance','Entender tu operación, los usuarios y las restricciones. Identificar el problema y acordar prioridades, alcance y criterios de entrega.','bx-search-alt'),
array('02','Diseño y arquitectura','Definir flujos, datos, integraciones e infraestructura. Revisar la solución propuesta antes de avanzar con su implementación.','bx-network-chart'),
array('03','Desarrollo y validación','Construir por etapas y revisar avances. Probar los recorridos principales y ajustar con el contexto de uso real.','bx-code-alt'),
array('04','Despliegue y entrega','Preparar la puesta en producción, documentación y configuración. Acordar el mantenimiento y los siguientes pasos del producto.','bx-rocket'));
foreach($steps as $step): ?><article class="glass-card scroll-reveal method-card"><div class="icon-box"><i class="bx <?php echo esc_attr($step[3]); ?>" aria-hidden="true"></i></div><span class="step-number"><?php echo esc_html($step[0]); ?></span><h3><?php echo esc_html($step[1]); ?></h3><p><?php echo esc_html($step[2]); ?></p></article><?php endforeach; ?></div></div></section>

<section id="sobre-mi" class="section-padding bg-gradient">
    <div class="container">
        <div class="section-header text-left scroll-reveal">
            <span class="section-tag">Sobre Mí</span>
            <h2 class="section-title">De la infraestructura al píxel.</h2>
        </div>

        <div class="about-content grid-2 mt-4">
            <div class="glass-card scroll-reveal">
                <p style="font-size: 1.1rem; color: var(--color-text-main); margin-bottom: 1rem;">Soy Arturo Merchán, desarrollador Full-Stack en Maturín, Venezuela. Mi trabajo combina interfaces, lógica de negocio e infraestructura: desde plataformas de reservas hasta WordPress personalizado, integraciones y operación de servidores.</p>
                <p style="color: var(--color-text-muted);">Trabajo sobre el problema y el contexto de cada cliente. Defino el alcance, construyo por etapas y preparo la entrega con documentación y una base mantenible. Puedes conocer mi trayectoria, responsabilidades y formación en la página sobre mí.</p>
            </div>
<div class="glass-card scroll-reveal delay-100 expertise-panel"><span class="material-symbols-outlined expertise-icon" aria-hidden="true">hub</span><h3>Del producto a producción</h3><p>Un mismo enfoque para conectar las piezas de tu proyecto.</p><div class="tags"><span>Interfaces y experiencia</span><span>Backend e integraciones</span><span>Despliegue y operación</span></div><a class="btn-secondary mt-4" href="<?php echo esc_url(home_url('/sobre-mi/')); ?>">Conoce mi trayectoria <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a></div></div></div></section>
<section id="stack" class="section-padding">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">Tecnologías</span>
            <h2 class="section-title">Stack Tecnológico</h2>
        </div>

        <div class="grid-2 mt-4">
            <div class="glass-card scroll-reveal">
                <h3 class="mb-3" style="color: var(--color-goku-orange);">
                    <span class="material-symbols-outlined icon-margin" style="vertical-align: middle;">code</span>Lenguajes & Front-End
                </h3>
                <div class="tags">
                    <span>TypeScript</span><span>JavaScript</span><span>React</span><span>Astro</span><span>PHP</span><span>HTML5</span><span>CSS3</span><span>Tailwind CSS</span><span>JSON</span><span>WebGL</span><span>Liquid</span><span>Twig</span>
                </div>
            </div>
            <div class="glass-card scroll-reveal delay-100">
                <h3 class="mb-3" style="color: var(--color-goku-orange);">
                    <span class="material-symbols-outlined icon-margin" style="vertical-align: middle;">database</span>Back-End & Bases de Datos
                </h3>
                <div class="tags">
                    <span>Python</span><span>Microservicios</span><span>MySQL/MariaDB</span><span>MongoDB</span>
                </div>
            </div>
            <div class="glass-card scroll-reveal">
                <h3 class="mb-3" style="color: var(--color-goku-orange);">
                    <span class="material-symbols-outlined icon-margin" style="vertical-align: middle;">merge_type</span>Versiones & CI/CD
                </h3>
                <div class="tags">
                    <span>Git</span><span>GitHub Actions</span>
                </div>
            </div>
            <div class="glass-card scroll-reveal delay-100">
                <h3 class="mb-3" style="color: var(--color-goku-orange);">
                    <span class="material-symbols-outlined icon-margin" style="vertical-align: middle;">router</span>DevOps & Servidores
                </h3>
                <div class="tags">
                    <span>Linux</span><span>Bash</span><span>PowerShell</span><span>Docker</span><span>Dockerfile</span><span>Traefik</span><span>Nginx</span><span>Hostinger VPS</span>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="codigo" class="section-padding"><div class="container"><div class="glass-card code-banner scroll-reveal"><div><span class="section-tag">Código abierto</span><h2 class="section-title">Las decisiones también están en el código.</h2><p>Explora mis repositorios, experimentos y herramientas. GitHub complementa los proyectos con una mirada a su implementación técnica.</p></div><a class="btn-primary" href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> Explorar GitHub</a></div></div></section>

<section id="contacto" class="section-padding bg-gradient"><div class="container"><div class="grid-2"><div class="contact-info scroll-reveal"><span class="section-tag">Hablemos de tu proyecto</span><h2 class="section-title mb-4">¿Qué necesitas construir o mejorar?</h2><p class="contact-copy">Cuéntame el problema, el estado actual y los plazos que tienes en mente. Revisemos qué solución necesitas y cómo llevarla a producción.</p><div class="contact-details"><p><i class="bx bx-code-alt" aria-hidden="true"></i> Productos digitales y desarrollo a medida</p><p><i class="bx bx-server" aria-hidden="true"></i> Infraestructura, integraciones y operación</p><p><i class="bx bx-search-alt" aria-hidden="true"></i> Consultoría y auditorías técnicas</p></div><div class="contact-alternatives"><a href="mailto:soporte@merchan.dev"><i class="bx bx-envelope" aria-hidden="true"></i> soporte@merchan.dev</a><a href="https://wa.me/584249677293" target="_blank" rel="noopener noreferrer"><i class="bx bxl-whatsapp" aria-hidden="true"></i> Escríbeme por WhatsApp</a></div></div><div class="glass-card scroll-reveal"><?php get_template_part('template-parts/contact-form'); ?></div></div></div></section>

<?php get_footer(); ?>
