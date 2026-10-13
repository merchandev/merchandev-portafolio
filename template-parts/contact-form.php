<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<form id="nuclearContactForm" class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
    <input type="hidden" name="action" value="am_submit_contact">
    <?php wp_nonce_field( 'am_nonce', 'security', false ); ?>
    <div class="honeypot" aria-hidden="true"><label for="website">Deja este campo vacío</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
    <div class="form-pair">
        <div><label for="name">Tu nombre *</label><input id="name" name="name" autocomplete="name" maxlength="150" required class="form-input"></div>
        <div><label for="email">Correo de contacto *</label><input type="email" id="email" name="email" autocomplete="email" maxlength="254" required class="form-input"></div>
    </div>
    <div><label for="service">¿Qué necesitas construir?</label><select id="service" name="service" class="form-input">
        <option value="Producto digital">Producto digital / sistema a medida</option><option value="Desarrollo web">Sitio web / WordPress</option><option value="E-commerce">E-commerce / integraciones</option><option value="Infraestructura">Infraestructura / streaming</option><option value="SEO Técnico">SEO técnico</option><option value="Consultoría">Consultoría / otro</option>
    </select></div>
    <div><label for="message">Cuéntame sobre tu proyecto *</label><textarea id="message" name="message" rows="5" maxlength="10000" required class="form-input" placeholder="Qué quieres resolver, en qué etapa estás y qué necesitas de mí."></textarea></div>
    <label class="privacy-check"><input type="checkbox" name="privacy" value="1" required> <span>Acepto el tratamiento de mis datos para responder a esta consulta según la <a href="<?php echo esc_url( home_url( '/politicas-de-privacidad-y-terminos/' ) ); ?>">política de privacidad</a>.</span></label>
    <button type="submit" class="btn-primary">Enviar solicitud <span aria-hidden="true">↗</span></button>
    <p id="form-response" role="status" aria-live="polite" aria-atomic="true"></p>
</form>
