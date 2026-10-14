<?php
/** Capacidades técnicas por función, con nivel de uso. $args: heading (h3|h4). */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$heading = in_array( $args['heading'] ?? 'h3', array( 'h3', 'h4' ), true ) ? $args['heading'] ?? 'h3' : 'h3';
$levels = am_capability_levels();
?>
<ul class="capability-legend" aria-label="Niveles">
    <li class="level-p"><span aria-hidden="true"></span><?php echo esc_html( $levels['p'] ); ?>: uso recurrente en varios proyectos</li>
    <li class="level-a"><span aria-hidden="true"></span><?php echo esc_html( $levels['a'] ); ?>: implementada en proyectos concretos</li>
    <li class="level-c"><span aria-hidden="true"></span><?php echo esc_html( $levels['c'] ); ?>: parte de un sistema o herramienta de apoyo</li>
</ul>
<div class="capability-grid">
    <?php foreach ( am_capabilities() as $group ) : list( $title, $icon, $items ) = $group; ?>
    <section class="glass-card capability-group scroll-reveal" aria-label="<?php echo esc_attr( $title ); ?>">
        <<?php echo $heading; ?> class="capability-title"><i class="bx <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i><?php echo esc_html( $title ); ?></<?php echo $heading; ?>>
        <ul class="capability-list">
            <?php foreach ( $items as $name => $level ) : ?>
                <li class="level-<?php echo esc_attr( $level ); ?>"><?php echo esc_html( $name ); ?><span class="screen-reader-text"> (<?php echo esc_html( strtolower( $levels[ $level ] ) ); ?>)</span></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endforeach; ?>
</div>
