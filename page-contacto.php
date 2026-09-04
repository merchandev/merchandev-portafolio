<?php
/**
 * Template Name: Contacto
 * Description: Página de contacto con formulario
 */
get_header(); ?>

<div style="padding-top: 100px; min-height: 100vh;">
    <!-- ========================================== -->
    <!-- CONTACTO                                   -->
    <!-- ========================================== -->
    <section id="contacto" class="section-padding bg-gradient">
        <div class="container">
            <div class="grid-2">
                <div class="contact-info scroll-reveal">
                    <span class="section-tag">Contacto</span>
                    <h1 class="section-title mb-4">¿Listo para escalar tu infraestructura o construir tu próximo SaaS?</h1>
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const params  = new URLSearchParams(window.location.search);
    const servicio = params.get('servicio');
    if (!servicio) return;

    // Mapeo de nombre de servicio a valor del select
    const map = {
        'saas'           : 'SaaS / Web App',
        'microservicio'  : 'SaaS / Web App',
        'streaming'      : 'SaaS / Web App',
        'vps'            : 'Servidores / VPS',
        'devops'         : 'Servidores / VPS',
        'administracion' : 'Servidores / VPS',
        'ecommerce'      : 'E-commerce',
        'migración'      : 'E-commerce',
        'seo'            : 'SEO Técnico',
        'wordpress'      : 'WordPress Avanzado',
        'ia'             : 'Otro / Consultoría',
        'consultoria'    : 'Otro / Consultoría',
        'infraestructura': 'Otro / Consultoría',
    };

    const select    = document.getElementById('servicio');
    const nameLower = servicio.toLowerCase();
    let matched     = 'Otro / Consultoría';

    for (const [key, val] of Object.entries(map)) {
        if (nameLower.includes(key)) { matched = val; break; }
    }

    // Pre-seleccionar la opción correcta
    Array.from(select.options).forEach(opt => {
        opt.selected = (opt.value === matched);
    });

    // Hacer scroll suave al formulario
    const form = document.getElementById('nuclearContactForm');
    if (form) {
        setTimeout(() => form.scrollIntoView({ behavior: 'smooth', block: 'center' }), 300);
    }
});
</script>

<?php get_footer(); ?>
