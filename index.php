<?php
/**
 * Main Template File
 */
get_header(); ?>

<!-- ========================================== -->
<!-- HERO SECTION                               -->
<!-- ========================================== -->
<section class="hero-section text-center" style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center; position: relative; overflow: hidden;">
    <div class="aura-effect"></div>

    <div class="container animate-fade-in visible" style="z-index: 1; position: relative;">
        <div>
            <div class="hero-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#121212" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
            </div>

            <h1 style="font-size: 3.5rem; font-weight: 900; margin-bottom: 1rem; text-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                Arturo <span style="color: var(--color-goku-orange);">Merchán</span>
            </h1>
            <h2 class="animate-fade-in-up delay-100" style="font-size: 1.5rem; color: var(--color-text-muted); margin-bottom: 2rem; font-weight: 400;">
                Senior Full-Stack Software Engineer & <br />
                <span style="color: var(--color-vegeta-blue);">WordPress Solutions Architect</span>
            </h2>
            
            <p class="animate-fade-in-up delay-200" style="max-width: 800px; margin: 0 auto 3rem; font-size: 1.1rem; line-height: 1.8;">
                Con una década de experiencia en el desarrollo de software, me defino como un ingeniero Full-Stack con una visión orientada a resultados y estabilidad. Mi carrera se ha centrado en resolver problemas complejos mediante el desarrollo de arquitectura personalizada, evitando soluciones genéricas y priorizando la escalabilidad y el rendimiento.
                <br/><br/>
                A diferencia de un desarrollador convencional, mi perfil integra la <strong>administración avanzada de sistemas</strong> con el desarrollo de aplicaciones, garantizando que cada línea de código esté optimizada para su entorno de ejecución.
            </p>
            
            <div class="animate-fade-in-up delay-300" style="display: flex; gap: 1rem; justify-content: center;">
                <a href="#proyectos" class="btn-primary">
                    <svg class="icon-margin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                    Ver Proyectos
                </a>
                <a href="#contacto" class="btn-secondary">
                    <svg class="icon-margin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>
                    Contáctame
                </a>
            </div>
        </div>
    </div>

    <div class="bounce" style="position: absolute; bottom: 2rem; left: 50%; transform: translateX(-50%); color: var(--color-ssj-yellow);">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
    </div>
</section>

<!-- ========================================== -->
<!-- SKILLS SECTION                             -->
<!-- ========================================== -->
<section id="ecosistema" style="padding: 6rem 0;">
    <div class="container">
        <h2 class="section-title animate-fade-in-up">Ecosistema Técnico</h2>

        <div class="grid-3 mt-4">
            
            <!-- Category 1 -->
            <div class="glass-card animate-fade-in-up delay-100">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(255,122,0,0.1); padding: 1rem; border-radius: 12px; box-shadow: inset 0 0 10px rgba(255,122,0,0.2); display: flex; align-items: center; justify-content: center; width: 64px; height: 64px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: var(--color-goku-orange);">terminal</span>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600;">Desarrollo Backend & WordPress</h3>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">PHP Avanzado</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Lógica de negocio, APIs (REST/SOAP), optimización SQL</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">WordPress Engineering</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Temas/plugins puro código, Hooks, Filters, WP-CLI</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">E-commerce</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">WooCommerce, Shopify, Magento</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Soluciones a medida</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Plugin Profit-Woocommerce</span>
                    </li>
                </ul>
            </div>

            <!-- Category 2 -->
            <div class="glass-card animate-fade-in-up delay-200">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(255,122,0,0.1); padding: 1rem; border-radius: 12px; box-shadow: inset 0 0 10px rgba(255,122,0,0.2); display: flex; align-items: center; justify-content: center; width: 64px; height: 64px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: var(--color-goku-orange);">devices</span>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600;">Frontend & Mobile</h3>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Interfaces Modernas</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">React.js y JavaScript moderno (ES6+)</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Desarrollo Móvil</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Flutter (Android/iOS)</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Diseño Responsivo</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">SASS/LESS, metodologías BEM</span>
                    </li>
                </ul>
            </div>

            <!-- Category 3 -->
            <div class="glass-card animate-fade-in-up delay-300">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(255,122,0,0.1); padding: 1rem; border-radius: 12px; box-shadow: inset 0 0 10px rgba(255,122,0,0.2); display: flex; align-items: center; justify-content: center; width: 64px; height: 64px;">
                        <span class="material-symbols-outlined" style="font-size: 32px; color: var(--color-goku-orange);">dns</span>
                    </div>
                    <h3 style="font-size: 1.25rem; font-weight: 600;">Infraestructura & DevOps</h3>
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 1rem;">
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Sistemas & Servidores</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Linux (Ubuntu Server, Rocky Linux)</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">DevOps</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Docker, Nginx/Apache, SSL, Firewalls, WAF</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Automatización</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">n8n (Low-Code/No-Code)</span>
                    </li>
                    <li style="display: flex; flex-direction: column; gap: 0.25rem;">
                        <span style="font-weight: 600; color: var(--color-ssj-yellow);">Scripting</span>
                        <span style="color: var(--color-text-muted); font-size: 0.9rem;">Shell (mantenimientos, respaldos)</span>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- PHILOSOPHY SECTION                         -->
<!-- ========================================== -->
<section id="filosofia" style="padding: 6rem 0; background: linear-gradient(to bottom, transparent, rgba(0, 51, 128, 0.1), transparent);">
    <div class="container">
        <h2 class="section-title text-center animate-fade-in-up">Filosofía de Trabajo</h2>

        <div style="text-align: center; margin-bottom: 4rem; max-width: 800px; margin: 0 auto 4rem auto;" class="animate-fade-in-up delay-100">
            <p style="font-size: 1.2rem; color: var(--color-text-muted);">
                Mi enfoque no es solo técnico, sino estratégico. <br />
                <span style="color: var(--color-goku-orange); font-weight: bold;">Me aseguro de que la tecnología sea un motor de crecimiento, no un gasto.</span>
            </p>
        </div>

        <div class="grid-2">
            <!-- Point 1 -->
            <div class="glass-card animate-fade-in-up delay-100" style="display: flex; flex-direction: row; gap: 1.5rem; align-items: flex-start; background: rgba(20, 20, 20, 0.8);">
                <div style="background: rgba(255,215,0,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span class="material-symbols-outlined" style="font-size: 28px; color: var(--color-ssj-yellow);">price_check</span>
                </div>
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--color-text-main);">Precios Cerrados</h3>
                    <p style="color: var(--color-text-muted); line-height: 1.6;">Trabajo bajo presupuestos fijos y objetivos claros. Esto garantiza transparencia total, cumplimiento de plazos y una planificación rigurosa desde el día uno.</p>
                </div>
            </div>

            <!-- Point 2 -->
            <div class="glass-card animate-fade-in-up delay-200" style="display: flex; flex-direction: row; gap: 1.5rem; align-items: flex-start; background: rgba(20, 20, 20, 0.8);">
                <div style="background: rgba(255,215,0,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span class="material-symbols-outlined" style="font-size: 28px; color: var(--color-ssj-yellow);">troubleshoot</span>
                </div>
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--color-text-main);">Resolución de Problemas</h3>
                    <p style="color: var(--color-text-muted); line-height: 1.6;">Capacidad para realizar auditorías de código, detectar cuellos de botella y optimizar sistemas lentos o vulnerables para máximo rendimiento.</p>
                </div>
            </div>

            <!-- Point 3 -->
            <div class="glass-card animate-fade-in-up delay-100" style="display: flex; flex-direction: row; gap: 1.5rem; align-items: flex-start; background: rgba(20, 20, 20, 0.8);">
                <div style="background: rgba(255,215,0,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span class="material-symbols-outlined" style="font-size: 28px; color: var(--color-ssj-yellow);">query_stats</span>
                </div>
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--color-text-main);">Visión de Negocio</h3>
                    <p style="color: var(--color-text-muted); line-height: 1.6;">Aplico criterios de SEO técnico y analítica para que el software desarrollado tenga un impacto real en la conversión y visibilidad del cliente.</p>
                </div>
            </div>

            <!-- Point 4 -->
            <div class="glass-card animate-fade-in-up delay-200" style="display: flex; flex-direction: row; gap: 1.5rem; align-items: flex-start; background: rgba(20, 20, 20, 0.8);">
                <div style="background: rgba(255,215,0,0.1); width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <span class="material-symbols-outlined" style="font-size: 28px; color: var(--color-ssj-yellow);">security</span>
                </div>
                <div>
                    <h3 style="font-size: 1.3rem; margin-bottom: 0.5rem; color: var(--color-text-main);">Estabilidad y Escalabilidad</h3>
                    <p style="color: var(--color-text-muted); line-height: 1.6;">Evito las soluciones genéricas en favor de una arquitectura personalizada, asegurando que la tecnología sea un motor de crecimiento sostenible.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========================================== -->
<!-- PROJECTS SECTION                           -->
<!-- ========================================== -->
<section id="proyectos" style="padding: 6rem 0; background: var(--color-bg-dark);">
    <div class="container">
        
        <!-- Featured Projects -->
        <h2 class="section-title text-center animate-fade-in-up">Proyectos Destacados</h2>

        <div class="grid-3 mb-4 mt-4">
            
            <div class="glass-card animate-fade-in-up delay-100" style="display: flex; flex-direction: column; height: 100%;">
                <div style="margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-ssj-yellow)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m2 4 3 12h14l3-12-6 7-4-7-4 7-6-7zm3 16h14"/></svg>
                </div>
                <h3 style="font-size: 1.4rem; margin-bottom: 1rem; color: var(--color-text-main);">Forja de Diosas</h3>
                <p style="color: var(--color-text-muted); flex-grow: 1; margin-bottom: 1.5rem;">Plataforma de comunidad privada con sistemas de membresía y gestión de contenido exclusivo.</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">PHP</span>
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">WordPress</span>
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">Membresías</span>
                </div>
            </div>

            <div class="glass-card animate-fade-in-up delay-200" style="display: flex; flex-direction: column; height: 100%;">
                <div style="margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-vegeta-blue)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3h12l4 6-10 13L2 9Z"/><path d="M11 3 8 9l4 13 4-13-3-6"/><path d="M2 9h20"/></svg>
                </div>
                <h3 style="font-size: 1.4rem; margin-bottom: 1rem; color: var(--color-text-main);">MeTransfers</h3>
                <p style="color: var(--color-text-muted); flex-grow: 1; margin-bottom: 1.5rem;">Gestión integral de infraestructura, SEO y desarrollo web para agencia de transporte de lujo.</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">Infraestructura</span>
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">SEO</span>
                </div>
            </div>

            <div class="glass-card animate-fade-in-up delay-300" style="display: flex; flex-direction: column; height: 100%;">
                <div style="margin-bottom: 1rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--color-goku-orange)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="m10 10-2 2 2 2"/><path d="m14 14 2-2-2-2"/></svg>
                </div>
                <h3 style="font-size: 1.4rem; margin-bottom: 1rem; color: var(--color-text-main);">Plugin Profit-Woocommerce</h3>
                <p style="color: var(--color-text-muted); flex-grow: 1; margin-bottom: 1.5rem;">Lógica financiera personalizada para gestión de rentabilidad en tiendas virtuales.</p>
                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">PHP</span>
                    <span style="background: rgba(255,255,255,0.1); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.8rem; color: var(--color-text-main);">WooCommerce</span>
                </div>
            </div>

        </div>

        <!-- Open Source / GitHub section -->
        <h2 class="section-title text-center animate-fade-in-up" style="margin-top: 6rem;">Código Abierto (GitHub)</h2>

        <div class="text-center mb-4 animate-fade-in-up">
            <p style="color: var(--color-text-muted); margin-bottom: 2rem;">Explora mis contribuciones, experimentos y arquitectura de código en mi perfil oficial.</p>
        </div>

        <!-- Grid populated by main.js fetch() request to GitHub API -->
        <div id="github-repos-grid" class="grid-2">
            <!-- Loader -->
            <div style="grid-column: 1/-1; text-align: center; color: var(--color-text-muted); padding: 2rem;">
                Cargando repositorios...
            </div>
        </div>

        <div class="text-center mt-4 animate-fade-in-up" style="padding-top: 2rem;">
            <a href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer" class="btn-secondary">
                <svg class="icon-margin" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 22v-4a4.8 4.8 0 0 0-1-3.02c3.18-.35 6.5-1.53 6.5-7.46C20.5 5.5 19.5 4.39 18.5 3.5c.24-.6.82-2.15-.08-4.02C17.42-1 16.5 2 16.5 2c-1-.28-2.1-.42-3.25-.42C12.1-.42 11 .14 10 .42c0 0-.92-3-2.42-3-.9.18-1.58.62-1.92 1.34C4.66 1 3.25 4.37 3.5 10c0 5.91 3.22 7.12 6.42 7.46-1.58 1.4-1.58 3.5-1.58 3.5v4"/><path d="M5 19c-3 1-3-4-3-4"/></svg>
                Ver todo en GitHub
            </a>
        </div>

    </div>
</section>

<?php get_footer(); ?>
