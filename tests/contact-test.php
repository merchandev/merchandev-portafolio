<?php
// Isolated contract tests. These do not simulate SMTP or a WordPress database.
define('ABSPATH', __DIR__); define('MINUTE_IN_SECONDS', 60);
class ContactResult extends Exception { public $success; function __construct($success, $message) { parent::__construct($message); $this->success=$success; } }
function add_action(...$args) {} function add_filter(...$args) {}
function wp_unslash($v) { return stripslashes($v); }
function wp_verify_nonce($v,$action) { return $v==='valid'; }
function sanitize_text_field($v) { return trim(strip_tags($v)); }
function sanitize_textarea_field($v) { return trim(strip_tags($v)); }
function sanitize_email($v) { return filter_var($v,FILTER_SANITIZE_EMAIL); }
function is_email($v) { return filter_var($v,FILTER_VALIDATE_EMAIL); }
function wp_salt($v) { return 'test-secret'; }
function get_transient($key) { return $GLOBALS['attempts']; }
function set_transient($key,$value,$ttl) { $GLOBALS['attempts']=$value; }
function wp_insert_post($post,$errors) { $GLOBALS['posts'][]=$post; return $GLOBALS['insert_ok'] ? 123 : 0; }
function is_wp_error($v) { return false; }
function update_post_meta($id,$key,$value) { $GLOBALS['meta'][$key]=$value; }
function current_time(...$v) { return '2026-10-13 12:00:00'; }
function apply_filters($name,$value) { return $value; }
function get_option($v) { return 'owner@example.com'; }
function admin_url($path) { return 'https://example.com/wp-admin/'.$path; }
function wp_mail(...$args) { if (!empty($GLOBALS['mail_throws'])) { throw new Error('SMTP transport'); } $GLOBALS['mail']=$args; return $GLOBALS['mail_ok']; }
function wp_doing_ajax() { return true; }
function wp_send_json_success($v) { throw new ContactResult(true,$v); }
function wp_send_json_error($v) { throw new ContactResult(false,$v); }
require __DIR__.'/../inc/contact.php';
function check($condition,$label) { if (!$condition) { throw new Exception($label); } }
function run_case($changes,$success,$setup=array()) {
    $_POST=array_merge(array('security'=>'valid','name'=>'Arturo','email'=>'visitor@example.com','service'=>'Producto digital','message'=>'Necesito una aplicación.','privacy'=>'1'),$changes);
    $GLOBALS['attempts']=$setup['attempts'] ?? 0; $GLOBALS['mail_ok']=$setup['mail_ok'] ?? true; $GLOBALS['mail_throws']=$setup['mail_throws'] ?? false;
    $GLOBALS['insert_ok']=$setup['insert_ok'] ?? true; $GLOBALS['posts']=array(); $GLOBALS['meta']=array(); $GLOBALS['mail']=array();
    try { am_handle_contact_form(); throw new Exception('No result'); }
    catch (ContactResult $result) { check($result->success===$success,$result->getMessage()); }
}
run_case(array(),true); check($GLOBALS['posts'][0]['post_status']==='private','Lead privacy'); check($GLOBALS['meta']['_lead_notification_status']==='accepted','Mail status');
run_case(array(),true,array('mail_ok'=>false)); check($GLOBALS['meta']['_lead_notification_status']==='failed','Mail failure must preserve lead');
ini_set('error_log', sys_get_temp_dir().'/am-contact-test.log');
run_case(array(),true,array('mail_throws'=>true)); check($GLOBALS['meta']['_lead_notification_status']==='failed','Mail transport error must preserve lead and report success');
foreach (array(array('security'=>'expired'),array('security'=>array('invalid')),array('email'=>'bad'),array('email'=>"a@example.com\r\nBcc: x@example.com"),array('name'=>array('bad')),array('message'=>''),array('message'=>str_repeat('x',10001)),array('privacy'=>'0'),array('website'=>'spam.example')) as $invalid) {
    run_case($invalid,false); check(count($GLOBALS['posts'])===0,'Rejected request persisted');
}
run_case(array(),false,array('attempts'=>5)); check(count($GLOBALS['posts'])===0,'Rate limit persisted');
run_case(array(),false,array('insert_ok'=>false)); check(count($GLOBALS['mail'])===0,'Mail sent without lead');
run_case(array('name'=>'','nombre'=>'Legacy'),false);
$_POST=array('security'=>'valid','nombre'=>'Legacy','email'=>'visitor@example.com','servicio'=>'Otro','mensaje'=>'Consulta','privacy'=>'1'); $GLOBALS['attempts']=0; $GLOBALS['insert_ok']=true;
try { am_handle_contact_form(); } catch (ContactResult $result) { check($result->success,'Legacy compatibility'); }
// Preselección del servicio de origen (?interes=<slug>), usada también sin JavaScript.
function sanitize_title($v) { return strtolower(trim(preg_replace('/[^a-z0-9]+/i','-',$v),'-')); }
$expected = array(
    'desarrollo-software-productos-digitales' => 'Producto digital o sistema a medida', 'desarrollo-saas-microservicios' => 'Producto digital o sistema a medida',
    'arquitectura-integraciones-automatizacion' => 'Integraciones y automatización', 'ecommerce-migraciones' => 'WordPress o comercio electrónico',
    'desarrollo-avanzado-wordpress' => 'WordPress o comercio electrónico', 'administracion-vps-devops' => 'Infraestructura y despliegue',
    'streaming-audio-video' => 'Infraestructura y despliegue', 'infraestructura-fisica-continuidad' => 'Infraestructura y despliegue',
    'seo-tecnico' => 'SEO técnico', 'consultoria-ia-rendimiento' => 'Consultoría', 'movil' => 'Aplicación móvil', 'cualquier-otra-cosa' => 'Consultoría',
);
foreach ($expected as $slug => $option) {
    $_GET = array('interes' => $slug);
    check(am_contact_preselected_service() === $option, 'Preselección ' . $slug);
    check(array_key_exists($option, am_contact_service_options()), 'Opción inexistente ' . $option);
}
$_GET = array(); check(am_contact_preselected_service() === '', 'Sin preselección');
$_GET = array('interes' => array('x')); check(am_contact_preselected_service() === '', 'Parámetro no textual');
check(count(am_contact_messages()) === 8, 'Códigos de resultado');
echo "PASS: 16 contact contract scenarios, including persistence and mail failure; 14 service preselection checks.\n";
