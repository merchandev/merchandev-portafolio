<?php
/**
 * Template Name: Servicios
 * Description: Tres especialidades principales con sus servicios específicos (las URL existentes se conservan).
 */
get_header();
$mapped = array();
foreach ( am_specialties() as $specialty ) { $mapped = array_merge( $mapped, array( $specialty['slug'] ), $specialty['services'] ); }
$others = get_posts( array( 'post_type' => 'servicio', 'post_status' => 'publish', 'numberposts' => 20, 'post_name__not_in' => $mapped, 'orderby' => 'title', 'order' => 'ASC' ) );
?>
<section class="section-padding page-top" aria-labelledby="servicios-title">
    <div class="container">
        <header class="section-header text-center scroll-reveal">
            <span class="section-tag">Servicios</span>
            <h1 id="servicios-title" class="section-title">Desarrollo, integración y operación de software</h1>
            <p class="section-subtitle">Organizo mi trabajo en tres especialidades. Cada una agrupa servicios específicos y se apoya en proyectos reales que puedes revisar.</p>
        </header>
        <?php get_template_part( 'template-parts/specialties', null, array( 'heading' => 'h2' ) ); ?>

        <?php if ( $others ) : ?>
        <section class="other-services" aria-labelledby="otros-servicios">
            <h2 id="otros-servicios" class="catalog-heading">Otros servicios</h2>
            <ul class="link-list columns">
                <?php foreach ( $others as $service ) : ?><li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( get_the_title( $service ) ); ?></a></li><?php endforeach; ?>
            </ul>
        </section>
        <?php endif; ?>

        <div class="glass-card catalog-cta scroll-reveal">
            <div><h2>¿No sabes en qué área encaja tu proyecto?</h2><p>Descríbeme el problema y el estado actual. Te propongo por dónde empezar.</p></div>
            <div class="action-row"><a class="btn-primary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contactar <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a><a class="btn-secondary" href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>">Ver proyectos</a></div>
        </div>
    </div>
</section>
<?php get_footer(); ?>
