<?php
/**
 * Main Template File
 */
get_header(); ?>

<!-- ========================================== -->
<!-- HERO SECTION                               -->
<!-- ========================================== -->
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
                    Programador Full-Stack
                </h2>
                
                <p class="hero-text fade-up delay-300">
                    Diseñando arquitecturas escalables, ecosistemas SaaS y experiencias web de alto rendimiento. 10 años transformando complejidad técnica en soluciones de negocio robustas.
                </p>


                
                <div class="hero-cta fade-up delay-500">
                    <a href="#casos" class="btn-primary">
                        <span class="material-symbols-outlined icon-margin">terminal</span>
                        Ver Proyectos
                    </a>
                    <a href="https://wa.me/584249677293?text=Hola%20Arturo%20%F0%9F%91%8B%20Vi%20tu%20portafolio%20en%20Merchan.Dev%20y%20me%20gustar%C3%ADa%20solicitar%20una%20asesor%C3%ADa%20gratuita.%20%C2%BFTienes%20disponibilidad%3F" target="_blank" rel="noopener noreferrer" class="btn-secondary" style="border-color: #25D366; color: #fff;">
                        <i class='bx bxl-whatsapp icon-margin' style="font-size: 22px; color: #25D366;"></i>
                        Asesoría gratuita
                    </a>
                </div>
                
                <div class="scroll-indicator bounce fade-up delay-500">
                    <span class="material-symbols-outlined" style="font-size: 36px; color: var(--color-text-muted);">keyboard_arrow_down</span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- SOBRE MI                                   -->
<!-- ========================================== -->
<section id="sobre-mi" class="section-padding bg-gradient">
    <div class="container">
        <div class="section-header text-left scroll-reveal">
            <span class="section-tag">Sobre Mí</span>
            <h2 class="section-title">De la infraestructura al píxel.</h2>
        </div>
        
        <div class="about-content grid-2 mt-4">
            <div class="glass-card scroll-reveal">
                <p style="font-size: 1.1rem; color: var(--color-text-main); margin-bottom: 1rem;">Soy un Programador Full-Stack con una década de experiencia en el sector tecnológico. Mi enfoque se centra en construir puentes entre el desarrollo front-end avanzado y las arquitecturas de servidores resilientes.</p>
                <p style="color: var(--color-text-muted);">No solo escribo código; diseño infraestructuras preparadas para el futuro. Ya sea desarrollando temas a medida con simulaciones en WebGL, integrando flujos de desarrollo potenciados por Inteligencia Artificial, gestionando el ciclo de vida del software con Git y GitHub, o administrando de forma integral servidores VPS, mi objetivo es entregar plataformas rápidas, seguras y altamente escalables.</p>
            </div>
            <div class="glass-card scroll-reveal delay-100 flex-center" id="animated-stats-card" style="min-height: 300px; overflow: hidden; position: relative;">
                <!-- Primary Icon Presentation -->
                <div id="stat-icon-wrapper" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0; pointer-events: none; z-index: 3; display: flex; justify-content: center; align-items: center; width: 100%; height: 100%;">
                    <i id="stat-icon" class='bx bx-time-five' style="font-size: 9rem; color: var(--color-goku-orange); text-shadow: 0 15px 50px rgba(249, 115, 22, 0.6);"></i>
                </div>
                
                <!-- Foreground Text Content -->
                <div style="text-align: center; width: 100%; position: relative; z-index: 2;">
                    <div id="stat-content-wrapper" style="transform-origin: center;">
                        <h3 id="stat-number" style="font-size: 7rem; color: var(--color-goku-orange); font-weight: 900; margin-bottom: 0; line-height: 1; text-shadow: 0 15px 50px rgba(249, 115, 22, 0.6); display: inline-block;">0</h3><span id="stat-suffix" style="font-size: 4rem; color: var(--color-goku-orange); font-weight: 900; vertical-align: top;">+</span>
                        <p id="stat-label" style="color: var(--color-text-main); font-size: 1.5rem; margin-top: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 4px;">Iniciando...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- SERVICIOS                                  -->
<!-- ========================================== -->
<section id="servicios" class="section-padding bg-darker">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">Lo que ofrezco</span>
            <h2 class="section-title">Mis Servicios</h2>
        </div>

        <div class="grid-3 mt-4">
            <a href="<?php echo esc_url( home_url('/servicios/desarrollo-saas-microservicios/') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-cloud' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Desarrollo SaaS y Arquitectura de Microservicios</h3>
                    <p>Diseño de bases de datos multi-tenant, orquestación con Docker y Traefik, y desarrollo de sistemas en la nube escalables.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/streaming-audio-video/') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-broadcast' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Configuración de Servidores para Streaming de Audio y Video</h3>
                    <p>Infraestructura propia para transmisiones en vivo sin interrupciones. Servidores RTMP y arquitecturas simulcast.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/administracion-vps-devops/') ); ?>" class="glass-card flex-column scroll-reveal delay-200" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-server' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Administración de Servidores VPS y DevOps</h3>
                    <p>Gestión técnica, hardening de seguridad, proxies inversos y mantenimiento de la infraestructura web subyacente.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/desarrollo-avanzado-wordpress/') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bxl-wordpress' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Desarrollo Avanzado en WordPress</h3>
                    <p>Programación de Custom Themes, plugins personalizados y arquitecturas offline-first superando las limitaciones tradicionales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/ecommerce-migraciones/') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-cart-alt' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>E-commerce y Migración de Plataformas</h3>
                    <p>Migraciones complejas sin pérdida de datos, dashboards a medida e integración segura de pasarelas de pago y TPV virtuales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/seo-tecnico/') ); ?>" class="glass-card flex-column scroll-reveal delay-200" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-trending-up' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>SEO Técnico y Crecimiento Orgánico Hiperlocal</h3>
                    <p>Auditorías de rendimiento técnico, consolidación de dominios y posicionamiento en motores de búsqueda para dominar mercados locales.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/consultoria-ia-rendimiento/') ); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                <div class="icon-box mb-3">
                    <i class='bx bx-brain' style="font-size: 28px;"></i>
                </div>
                <div class="card-body">
                    <h3>Consultoría en Integración de IA y Rendimiento Web</h3>
                    <p>Auditorías de código, optimización de carga e integración de flujos potenciados por Inteligencia Artificial.</p>
                </div>
            </a>

            <a href="<?php echo esc_url( home_url('/servicios/infraestructura-fisica-continuidad/') ); ?>" class="glass-card flex-column scroll-reveal delay-100" style="text-decoration: none;">
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

<!-- ========================================== -->
<!-- STACK TECNOLOGICO                          -->
<!-- ========================================== -->
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

<!-- ========================================== -->
<!-- CASOS DE ESTUDIO                           -->
<!-- ========================================== -->
<section id="casos" class="section-padding bg-darker">
    <div class="container">
        <div class="section-header text-center scroll-reveal">
            <span class="section-tag">Portafolio</span>
            <h2 class="section-title">Casos de Estudio Destacados</h2>
        </div>

        <div class="grid-3 mt-4 mb-5">
            <div class="glass-card flex-column scroll-reveal">
                <div class="project-icon">
                    <span class="material-symbols-outlined" style="font-size: 32px;">podcasts</span>
                </div>
                <h3>Ecosistema de Medios y Streaming Digital</h3>
                <p class="project-desc"><strong>Cliente:</strong> Grupo La Verdad de Monagas (Monagas Visión, Sonora 99.3FM)</p>
                <p class="project-desc">Modernizar la infraestructura de transmisión digital e implementación de VPS para servidores RTMP.</p>
                <div class="tags mt-auto">
                    <span>Streaming</span>
                    <span>VPS</span>
                    <span>RTMP</span>
                </div>
            </div>

            <div class="glass-card flex-column scroll-reveal delay-100">
                <div class="project-icon blue">
                    <span class="material-symbols-outlined" style="font-size: 32px;">query_stats</span>
                </div>
                <h3>Dominio SEO en el Sector Automotriz</h3>
                <p class="project-desc"><strong>Cliente:</strong> Concesionario Toyota (Maturín)</p>
                <p class="project-desc">Ejecución de una estrategia de SEO técnico y de contenido hiperlocal, logrando la primera posición en Google.</p>
                <div class="tags mt-auto">
                    <span>SEO</span>
                    <span>Marketing</span>
                </div>
            </div>

            <div class="glass-card flex-column scroll-reveal delay-200">
                <div class="project-icon">
                    <span class="material-symbols-outlined" style="font-size: 32px;">storefront</span>
                </div>
                <h3>Migración y Escalabilidad E-commerce</h3>
                <p class="project-desc"><strong>Cliente:</strong> Mínima Organics / Me Transfers</p>
                <p class="project-desc">Migraciones complejas integrando pasarelas de pago y sistemas de TPV virtual, garantizando transacciones seguras.</p>
                <div class="tags mt-auto">
                    <span>E-commerce</span>
                    <span>Pagos</span>
                </div>
            </div>
        </div>

        <!-- Open Source / GitHub section -->
        <div class="section-header text-center scroll-reveal mt-6">
            <h2 class="section-title">Código Abierto (GitHub)</h2>
            <p class="section-subtitle" style="margin: 1rem auto;">Explora mis contribuciones, experimentos y arquitectura de código en mi perfil oficial.</p>
        </div>

        <div id="github-repos-grid" class="grid-2">
            <div class="loader-container">Cargando repositorios...</div>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- CONTACTO                                   -->
<!-- ========================================== -->
<section id="contacto" class="section-padding bg-gradient">
    <div class="container">
        <div class="grid-2">
            <div class="contact-info scroll-reveal">
                <span class="section-tag">Contacto</span>
                <h2 class="section-title mb-4">¿Listo para escalar tu infraestructura o construir tu próximo SaaS?</h2>
                <p style="color: var(--color-text-muted); font-size: 1.1rem; margin-bottom: 2rem;">Ya sea que necesites una administración experta de tu VPS, una migración compleja o el desarrollo de una plataforma desde cero, hablemos de cómo la tecnología adecuada puede acelerar tu negocio.</p>
                <div class="contact-details">
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <span class="material-symbols-outlined" style="color: var(--color-goku-orange);">phone_in_talk</span>
                        <span>Disponible para consultoría</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <span class="material-symbols-outlined" style="color: var(--color-goku-orange);">school</span>
                        <span>Clases de programación</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <span class="material-symbols-outlined" style="color: var(--color-goku-orange);">find_in_page</span>
                        <span>Auditorías técnicas de sitios web</span>
                    </div>
                </div>
            </div>
            
            <div class="glass-card scroll-reveal delay-100">
                <form id="nuclearContactForm" class="contact-form flex-column" style="gap: 1.5rem;">
                    <div>
                        <label for="nombre" style="display: block; margin-bottom: 0.5rem; color: var(--color-text-muted);">Nombre Completo *</label>
                        <input type="text" id="nombre" name="nombre" required placeholder="Tu nombre" class="form-input">
                    </div>
                    <div>
                        <label for="email" style="display: block; margin-bottom: 0.5rem; color: var(--color-text-muted);">Email de Contacto *</label>
                        <input type="email" id="email" name="email" required placeholder="tu@email.com" class="form-input">
                    </div>
                    <div>
                        <label for="servicio" style="display: block; margin-bottom: 0.5rem; color: var(--color-text-muted);">Servicio de Interés</label>
                        <select id="servicio" name="servicio" class="form-input">
                            <option value="SaaS / Web App">SaaS / Web App</option>
                            <option value="Servidores / VPS">Administración de Servidores / VPS</option>
                            <option value="E-commerce">Migración E-commerce</option>
                            <option value="SEO Técnico">SEO Técnico</option>
                            <option value="WordPress Avanzado">WordPress Avanzado</option>
                            <option value="Otro">Otro / Consultoría</option>
                        </select>
                    </div>
                    <div>
                        <label for="mensaje" style="display: block; margin-bottom: 0.5rem; color: var(--color-text-muted);">Mensaje *</label>
                        <textarea id="mensaje" name="mensaje" rows="4" required placeholder="Cuéntame sobre tu proyecto..." class="form-input"></textarea>
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; margin-top: 1rem;">
                        Enviar Mensaje
                    </button>
                    <div id="form-response" style="margin-top: 1rem; text-align: center;"></div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
