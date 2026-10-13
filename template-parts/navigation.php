<?php
$links = array('proyectos'=>'Proyectos','servicios'=>'Servicios','sobre-mi'=>'Sobre mí','blog'=>'Artículos','contacto'=>'Hablemos ↗');
foreach ($links as $slug=>$label) :
$active = is_page($slug) || ($slug==='proyectos' && (is_post_type_archive('proyecto') || is_singular('proyecto'))) || ($slug==='servicios' && is_singular('servicio')) || ($slug==='blog' && (is_home() || is_singular('post')));
?>
<a href="<?php echo esc_url(home_url('/'.$slug.'/')); ?>"<?php if ($active) echo ' aria-current="page"'; ?> class="<?php echo $slug==='contacto' ? 'nav-cta' : 'nav-link'; ?>"><?php echo esc_html($label); ?></a>
<?php endforeach; ?>
