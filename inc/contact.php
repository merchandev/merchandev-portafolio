<?php
/** Contact delivery: persistence first, then a separately tracked notification. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_contact_value( $key, $legacy = '' ) {
    $value = $_POST[$key] ?? ( $legacy ? ( $_POST[$legacy] ?? '' ) : '' );
    return is_string( $value ) ? trim( wp_unslash( $value ) ) : '';
}

function am_contact_result( $success, $message ) {
    if ( wp_doing_ajax() ) {
        if ( $success ) { wp_send_json_success( $message ); }
        wp_send_json_error( $message );
    }
    wp_die( esc_html( $message ), $success ? 'Solicitud recibida' : 'Revisa tu solicitud', array( 'response' => $success ? 200 : 400, 'back_link' => true ) );
}

function am_handle_contact_form() {
    $nonce = am_contact_value( 'security' );
    if ( ! wp_verify_nonce( $nonce, 'am_nonce' ) ) {
        am_contact_result( false, 'La sesión ha caducado. Recarga la página e inténtalo de nuevo.' );
        return;
    }
    if ( am_contact_value( 'website' ) !== '' ) {
        am_contact_result( false, 'No se pudo procesar la solicitud.' ); return;
    }
    $name = sanitize_text_field( am_contact_value( 'name', 'nombre' ) );
    $raw_email = am_contact_value( 'email' );
    $email = sanitize_email( $raw_email );
    $service = sanitize_text_field( am_contact_value( 'service', 'servicio' ) );
    $message = sanitize_textarea_field( am_contact_value( 'message', 'mensaje' ) );
    if ( ! $name || ! $message || ! is_email( $raw_email ) || $raw_email !== $email ) {
        am_contact_result( false, 'Indica tu nombre, un correo válido y un mensaje.' ); return;
    }
    if ( strlen( $name ) > 150 || strlen( $email ) > 254 || strlen( $service ) > 150 || strlen( $message ) > 10000 ) {
        am_contact_result( false, 'El mensaje o alguno de los campos supera el tamaño permitido.' ); return;
    }
    if ( am_contact_value( 'privacy' ) !== '1' ) {
        am_contact_result( false, 'Acepta el tratamiento de tus datos para responder a la consulta.' ); return;
    }
    // Only a salted fingerprint is stored, never the visitor's IP. Do not trust forwarded headers.
    $ip = isset( $_SERVER['REMOTE_ADDR'] ) && is_string( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    $key = 'am_contact_' . hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) );
    $attempts = (int) get_transient( $key );
    if ( $attempts >= 5 ) {
        am_contact_result( false, 'Has enviado varias solicitudes. Espera unos minutos o escríbeme por correo.' ); return;
    }
    set_transient( $key, $attempts + 1, 10 * MINUTE_IN_SECONDS );
    $post_id = wp_insert_post( array(
        'post_title' => $name . ' — ' . $service,
        'post_content' => "Email: $email\nServicio: $service\n\n$message",
        'post_type' => 'lead', 'post_status' => 'private',
    ), true );
    if ( is_wp_error( $post_id ) || ! $post_id ) {
        am_contact_result( false, 'No se pudo guardar tu solicitud. Inténtalo de nuevo o escribe a soporte@merchan.dev.' ); return;
    }
    update_post_meta( $post_id, '_lead_email', $email );
    update_post_meta( $post_id, '_lead_service', $service );
    update_post_meta( $post_id, '_lead_privacy_at', current_time( 'mysql', true ) );
    $recipient = apply_filters( 'am_contact_recipient', get_option( 'admin_email' ) );
    $sent = wp_mail( $recipient, 'Nueva solicitud de Merchan.Dev #' . $post_id,
        "Nombre: $name\nEmail: $email\nServicio: $service\n\n$message\n\n" . admin_url( 'post.php?post=' . $post_id . '&action=edit' ),
        array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $email ) );
    update_post_meta( $post_id, '_lead_notification_status', $sent ? 'accepted' : 'failed' );
    // wp_mail acceptance does not confirm inbox delivery. The lead survives any mail failure.
    am_contact_result( true, 'Tu solicitud quedó registrada. Gracias por contarme sobre tu proyecto.' );
}
add_action( 'wp_ajax_am_submit_contact', 'am_handle_contact_form' );
add_action( 'wp_ajax_nopriv_am_submit_contact', 'am_handle_contact_form' );
add_action( 'admin_post_am_submit_contact', 'am_handle_contact_form' );
add_action( 'admin_post_nopriv_am_submit_contact', 'am_handle_contact_form' );

function am_lead_columns( $columns ) { $columns['am_notification'] = 'Aviso por correo'; return $columns; }
add_filter( 'manage_lead_posts_columns', 'am_lead_columns' );
function am_lead_column( $column, $post_id ) {
    if ( $column === 'am_notification' ) {
        $status = get_post_meta( $post_id, '_lead_notification_status', true );
        echo esc_html( $status === 'accepted' ? 'Aceptado por correo (verificar entrega)' : ( $status === 'failed' ? 'Falló el aviso: revisar SMTP' : 'Sin registro' ) );
    }
}
add_action( 'manage_lead_posts_custom_column', 'am_lead_column', 10, 2 );
