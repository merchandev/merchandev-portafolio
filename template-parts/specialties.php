<?php
/** Las tres especialidades con sus servicios específicos y proyectos que las demuestran. $args: heading (h2|h3). */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$heading = in_array( $args['heading'] ?? 'h3', array( 'h2', 'h3' ), true ) ? $args['heading'] ?? 'h3' : 'h3';
$number = 0;
?>
<div class="specialties">
<?php foreach ( am_specialties() as $key => $specialty ) : $number++; $evidence = am_specialty_projects( $key, 4 ); ?>
    <article class="glass-card specialty-card<?php echo $specialty['principal'] ? ' is-principal' : ''; ?> scroll-reveal" id="especialidad-<?php echo esc_attr( $key ); ?>">
        <div class="specialty-head">
            <span class="specialty-number" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $number ) ); ?></span>
            <div class="icon-box"><i class="bx <?php echo esc_attr( $specialty['icon'] ); ?>" aria-hidden="true"></i></div>
            <?php if ( $specialty['principal'] ) : ?><span class="specialty-flag">Especialidad principal</span><?php endif; ?>
        </div>
        <<?php echo $heading; ?> class="specialty-title"><?php echo esc_html( $specialty['title'] ); ?></<?php echo $heading; ?>>
        <p class="specialty-summary"><?php echo esc_html( $specialty['summary'] ); ?></p>
        <ul class="tags specialty-scope" aria-label="Alcance"><?php foreach ( $specialty['scope'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
        <div class="specialty-links">
            <?php $services = array_filter( $specialty['services'], 'am_service_exists' ); if ( $services ) : ?>
            <div>
                <p class="mini-heading">Servicios específicos</p>
                <ul class="link-list">
                    <?php foreach ( $services as $slug ) : $service = get_page_by_path( $slug, OBJECT, 'servicio' ); ?>
                        <li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( get_the_title( $service ) ); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            <?php if ( $evidence ) : ?>
            <div>
                <p class="mini-heading">Evidencia en proyectos</p>
                <ul class="link-list">
                    <?php foreach ( $evidence as $project ) : ?><li><a href="<?php echo esc_url( $project['url'] ); ?>"><?php echo esc_html( $project['title'] ); ?></a></li><?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        </div>
        <a class="btn-secondary specialty-cta" href="<?php echo esc_url( am_service_url( $specialty['slug'] ) ); ?>">Ver especialidad<span class="screen-reader-text">: <?php echo esc_html( $specialty['title'] ); ?></span> <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a>
    </article>
<?php endforeach; ?>
</div>
