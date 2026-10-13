<?php
// Render actual theme templates with an empty project fixture for visual QA only.
if (PHP_SAPI !== 'cli') { exit; }
define('ABSPATH',__DIR__); define('OBJECT','OBJECT');
function language_attributes() { echo 'lang="es"'; } function bloginfo($v) { echo 'UTF-8'; }
function esc_url($v) { return htmlspecialchars($v,ENT_QUOTES); } function esc_html($v) { return htmlspecialchars($v,ENT_QUOTES); }
function esc_attr($v) { return htmlspecialchars($v,ENT_QUOTES); }
function home_url($path='/') { return 'http://127.0.0.1:8765'.$path; }
function get_template_directory_uri() { return 'http://127.0.0.1:8765'; }
function get_template_part($path) { include __DIR__.'/../'.$path.'.php'; }
function get_header() { include __DIR__.'/../header.php'; } function get_footer() { include __DIR__.'/../footer.php'; }
function wp_head() { echo '<title>Merchan.Dev · Vista de diseño</title><link rel="stylesheet" href="/assets/css/main.css"><link rel="stylesheet" href="/assets/css/portfolio.css">'; }
function wp_footer() { echo '<script>var amData={ajax_url:"/contact-fixture",nonce:"valid"};</script><script src="/assets/js/main.js"></script><script src="/assets/js/bg-animation.js"></script>'; }
function wp_body_open() {} function body_class() { echo 'class="home"'; }
function wp_date($v) { return '2026'; } function is_page($v) { return false; } function is_post_type_archive($v) { return false; }
function is_singular($v) { return false; } function is_home() { return false; } function is_front_page() { return true; }
function admin_url($v) { return home_url('/wp-admin/'.$v); }
function wp_nonce_field(...$args) { echo '<input type="hidden" name="security" value="valid">'; }
function get_post_type_archive_link($v) { return home_url('/proyectos/'); }
function am_service_url($v) { return home_url('/servicio/'.$v.'/'); }
function wp_reset_postdata() {}
class WP_Query { function __construct($args) {} function have_posts() { return false; } }
include __DIR__.'/../'.(($argv[1] ?? '')==='contact' ? 'page-contacto.php' : 'front-page.php');
