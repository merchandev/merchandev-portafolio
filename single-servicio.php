<?php
/**
 * Servicio: especialidad principal o servicio específico. El texto se edita en WordPress;
 * la relación con la especialidad, los proyectos de evidencia y el contacto se generan aquí.
 */
get_header();
while ( have_posts() ) :
    the_post();
    $slug = get_post_field( 'post_name', get_post() );
    $specialties = am_specialties();
    $specialty_key = am_specialty_by_slug( $slug );
    $parent_key = $specialty_key ? null : am_specialty_for_service( $slug );
    $key = $specialty_key ?: $parent_key;
    $specialty = $key ? $specialties[ $key ] : null;
    $evidence = $key ? am_specialty_projects( $key, 4 ) : array();
    ?>
<article class="service-landing" aria-labelledby="service-title">
    <section class="hero-section service-hero">
        <div class="aura-effect"></div>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="hero-content service-hero-content">
                <nav class="breadcrumbs" aria-label="Migas de pan">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a><span aria-hidden="true">/</span>
                    <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>">Servicios</a><span aria-hidden="true">/</span>
                    <?php if ( $parent_key ) : ?><a href="<?php echo esc_url( am_service_url( $specialty['slug'] ) ); ?>"><?php echo esc_html( $specialty['short'] ); ?></a><span aria-hidden="true">/</span><?php endif; ?>
                    <span aria-current="page"><?php the_title(); ?></span>
                </nav>
                <span class="hero-tag fade-up"><?php echo $specialty_key ? ( $specialty['principal'] ? 'Especialidad principal' : 'Especialidad' ) : ( $parent_key ? 'Parte de: ' . esc_html( $specialty['short'] ) : 'Servicio' ); ?></span>
                <h1 id="service-title" class="hero-title fade-up delay-100"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?><p class="hero-text fade-up delay-200"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
            </div>
        </div>
    </section>

    <section class="section-padding bg-darker">
        <div class="container">
            <div class="service-layout">
                <div class="service-content glass-card scroll-reveal">
                    <?php the_content(); ?>
                    <?php if ( $evidence ) : ?>
                        <h3>Qué evidencias tengo</h3>
                        <p>Proyectos donde apliqué esta especialidad, con su estado real y enlaces verificables:</p>
                        <ul class="evidence-list">
                            <?php foreach ( $evidence as $project ) : ?>
                            <li><a href="<?php echo esc_url( $project['url'] ); ?>"><?php echo esc_html( $project['title'] ); ?></a><?php if ( $project['status_label'] ) : ?> · <span><?php echo esc_html( $project['status_label'] ); ?></span><?php endif; ?><br><small><?php echo esc_html( wp_trim_words( $project['excerpt'], 22, '…' ) ); ?></small></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <aside class="service-sidebar scroll-reveal delay-100" aria-label="Contacto y servicios relacionados">
                    <div class="glass-card flex-column service-sticky">
                        <h2 class="service-aside-title">¿Encaja con tu proyecto?</h2>
                        <p>Cuéntame qué necesitas resolver y en qué punto estás. Respondo personalmente cada solicitud.</p>
                        <a href="<?php echo esc_url( add_query_arg( 'interes', $slug, home_url( '/contacto/' ) ) ); ?>" class="btn-primary">
                            <i class="bx bx-envelope" aria-hidden="true"></i> Cómo contactar conmigo
                        </a>
                        <?php if ( $specialty ) : ?>
                        <div class="service-related">
                            <?php if ( $specialty_key ) : $services = array_filter( $specialty['services'], 'am_service_exists' ); if ( $services ) : ?>
                                <p class="mini-heading">Servicios específicos</p>
                                <ul class="link-list"><?php foreach ( $services as $service_slug ) : $service = get_page_by_path( $service_slug, OBJECT, 'servicio' ); ?><li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( get_the_title( $service ) ); ?></a></li><?php endforeach; ?></ul>
                            <?php endif; else : ?>
                                <p class="mini-heading">Especialidad</p>
                                <ul class="link-list"><li><a href="<?php echo esc_url( am_service_url( $specialty['slug'] ) ); ?>"><?php echo esc_html( $specialty['title'] ); ?></a></li></ul>
                            <?php endif; ?>
                            <p class="mini-heading">Otras especialidades</p>
                            <ul class="link-list"><?php foreach ( $specialties as $other_key => $other ) : if ( $other_key === $key ) { continue; } ?><li><a href="<?php echo esc_url( am_service_url( $other['slug'] ) ); ?>"><?php echo esc_html( $other['title'] ); ?></a></li><?php endforeach; ?></ul>
                        </div>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</article>
<?php endwhile; ?>
<?php get_footer(); ?>
