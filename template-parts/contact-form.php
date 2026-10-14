<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
// Preselección según el servicio de origen (?interes=<slug>); funciona sin JavaScript.
$options = am_contact_service_options();
$selected = am_contact_preselected_service();
// Resultado de un envío sin JavaScript (?contacto=<código>).
$code = isset( $_GET['contacto'] ) && is_string( $_GET['contacto'] ) ? sanitize_key( wp_unslash( $_GET['contacto'] ) ) : '';
$messages = am_contact_messages();
$result = $messages[ $code ] ?? '';
?>
<form id="nuclearContactForm" class="contact-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
    <input type="hidden" name="action" value="am_submit_contact">
    <?php wp_nonce_field( 'am_nonce', 'security', false ); ?>
    <div class="honeypot" aria-hidden="true"><label for="website">Deja este campo vacío</label><input id="website" name="website" tabindex="-1" autocomplete="off"></div>
    <div class="form-pair">
        <div><label for="name">Tu nombre *</label><input id="name" name="name" autocomplete="name" maxlength="150" required class="form-input"></div>
        <div><label for="email">Correo de contacto *</label><input type="email" id="email" name="email" autocomplete="email" maxlength="254" required class="form-input"></div>
    </div>
    <div><label for="service">¿Qué necesitas?</label><select id="service" name="service" class="form-input">
        <?php foreach ( $options as $value => $label ) : ?>
            <option value="<?php echo esc_attr( $value ); ?>"<?php selected( $selected, $value ); ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
    </select></div>
    <div><label for="message">Cuéntame sobre tu proyecto *</label><textarea id="message" name="message" rows="5" maxlength="10000" required class="form-input" placeholder="Qué quieres resolver, en qué punto estás y qué plazos manejas."></textarea></div>
    <label class="privacy-check"><input type="checkbox" name="privacy" value="1" required> <span>Acepto el tratamiento de mis datos para responder a esta consulta según la <a href="<?php echo esc_url( home_url( '/politicas-de-privacidad-y-terminos/' ) ); ?>">política de privacidad</a>.</span></label>
    <button type="submit" class="btn-primary">Enviar solicitud <span aria-hidden="true">↗</span></button>
    <p id="form-response" role="status" aria-live="polite" aria-atomic="true"<?php if ( $result ) { echo ' data-state="' . ( $code === 'enviado' ? 'success' : 'error' ) . '"'; } ?>><?php echo esc_html( $result ); ?></p>
</form>
