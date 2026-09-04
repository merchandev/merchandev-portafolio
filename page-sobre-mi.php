<?php
/**
 * Template Name: Sobre mí
 * Description: Página dedicada a la biografía y stack tecnológico
 */
get_header(); ?>

<div style="padding-top: 100px;">
    <!-- ========================================== -->
    <!-- SOBRE MI BIO                               -->
    <!-- ========================================== -->
    <section id="sobre-mi" class="section-padding bg-gradient">
        <div class="container">
            <div class="section-header text-left scroll-reveal">
                <span class="section-tag">Perfil Profesional</span>
                <h1 class="section-title">De la infraestructura al píxel.</h1>
            </div>
            
            <div class="about-content mt-4">
                <div class="glass-card scroll-reveal" style="margin-bottom: 2rem;">
                    <p style="font-size: 1.15rem; color: var(--color-text-main); margin-bottom: 1rem; line-height: 1.8;">
                        Soy Arturo Merchán, Programador Full-Stack con más de 10 años de experiencia en desarrollo web. Me especializo en el ecosistema de WordPress, PHP, y desarrollo de bases de datos, combinando habilidades en frontend (TypeScript, Tailwind CSS) y backend para crear soluciones tecnológicas a medida.
                    </p>
                    <p style="color: var(--color-text-muted); font-size: 1.1rem; line-height: 1.8;">
                        Mi enfoque se centra en la implementación de diseños precisos, optimización de rendimiento (SEO técnico) y la entrega puntual bajo un modelo de trabajo estructurado y eficiente.
                    </p>
                    <div style="margin-top: 2rem;">
                        <a href="<?php echo get_template_directory_uri(); ?>/assets/docs/CV_Arturo_Merchan.pdf" download="CV_Arturo_Merchan.pdf" class="btn-primary" target="_blank" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class='bx bxs-file-pdf' style="font-size: 1.2rem;"></i> Descargar CV (PDF)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- EXPERIENCIA Y EDUCACION                    -->
    <!-- ========================================== -->
    <section class="section-padding">
        <div class="container">
            <div class="grid-2">
                <!-- EXPERIENCIA -->
                <div>
                    <div class="section-header text-left scroll-reveal">
                        <span class="section-tag">Trayectoria</span>
                        <h2 class="section-title" style="font-size: 2rem;">Experiencia Profesional</h2>
                    </div>
                    
                    <div class="timeline" style="margin-top: 2rem;">
                        
                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Programador Full Stack</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Me Transfers (Barcelona, España)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Diciembre 2025 – Presente</span>
                            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin: 0;">Desarrollo y mantenimiento de un sistema de reservas especializado para traslados y tours desde y hacia Barcelona.</p>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Desarrollador Web</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Motores Morichal (Maturín, Venezuela)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Agosto 2025 – Presente</span>
                            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin: 0;">Desarrollo integral del tema para el sitio web, incluyendo la implementación de plugins personalizados para formularios y la administración del inventario de vehículos.</p>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Programador Full Stack & Cofundador</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Espressivo Venezuela (Maturín, Venezuela)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Octubre 2018 – Presente</span>
                            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin: 0;">Cofundador de la marca, encargado de proveer soluciones tecnológicas integrales y personalizadas para clientes.</p>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Desarrollador Full Stack WordPress</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Panalcom (Colombia)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Marzo 2025 – Noviembre 2025</span>
                            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin: 0;">Desarrollo de soluciones web personalizadas para el sector B2B, levantamiento y administración de servidores, y configuración de entornos seguros. Creación de plugins y temas a medida, integración de CRMs, pasarelas de pago y automatizaciones. Optimización de rendimiento, estructura SEO y construcción de interfaces con Elementor.</p>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Desarrollador Web</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Academy Producciones (México)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2020 – Febrero 2025</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-goku-orange); margin-bottom: 0.5rem; font-size: 1.2rem;">Diseñador de Páginas Web</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Neuropublicidad (Chile)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Febrero 2017 – Febrero 2020</span>
                            <p style="color: var(--color-text-muted); font-size: 0.95rem; margin: 0;">Desarrollo de E-commerce en WordPress utilizando WooCommerce y gestión estratégica de campañas en Google Ads.</p>
                        </div>

                    </div>
                </div>

                <!-- EDUCACION -->
                <div>
                    <div class="section-header text-left scroll-reveal">
                        <span class="section-tag">Formación</span>
                        <h2 class="section-title" style="font-size: 2rem;">Educación & Certificaciones</h2>
                    </div>
                    
                    <div class="timeline" style="margin-top: 2rem;">
                        
                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Diplomado en Database Developer</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Diplomados online</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Mayo 2026 – Septiembre 2026</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Máster SEO</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">BIG school</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2026 – Julio 2026</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Máster de Funnel de Ventas</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">BIG school</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2026 – Julio 2026</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Máster de Desarrollo con IA</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">BIG school</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2026 – Junio 2026</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Mastering Laravel Framework and PHP</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Coursera</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Agosto 2020 – Noviembre 2023</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Full Stack con WordPress</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Platzi</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2023 – Julio 2023</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Licenciatura en Contaduría Pública</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">Universidad de Oriente (VE)</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Marzo 2015 – Diciembre 2022</span>
                        </div>

                        <div class="glass-card scroll-reveal mb-4" style="padding: 2rem;">
                            <h3 style="color: var(--color-ssj-yellow); margin-bottom: 0.5rem; font-size: 1.1rem;">Web Development | Front-End</h3>
                            <h4 style="color: var(--color-text-main); font-size: 1rem; margin-bottom: 0.5rem;">CodePath</h4>
                            <span style="display: inline-block; padding: 4px 10px; background: rgba(255,255,255,0.05); border-radius: 4px; font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 0;">Agosto 2020 – Octubre 2020</span>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- STACK TECNOLOGICO                          -->
    <!-- ========================================== -->
    <section id="stack" class="section-padding bg-darker">
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
</div>

<?php get_footer(); ?>
