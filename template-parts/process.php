<?php
/** Cómo desarrollo un producto: siete etapas con su entrega concreta. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$steps = array(
    array( 'Análisis', 'bx-search-alt', 'Entiendo la operación, los usuarios y las restricciones.', 'Alcance priorizado y criterios de aceptación.' ),
    array( 'Arquitectura', 'bx-network-chart', 'Defino datos, roles, flujos e infraestructura antes de programar.', 'Esquema técnico revisable.' ),
    array( 'Desarrollo', 'bx-code-alt', 'Construyo por módulos con avances que puedes revisar.', 'Entregas parciales en un entorno de pruebas.' ),
    array( 'Integraciones', 'bx-git-merge', 'Conecto pagos, correo, mapas y servicios externos, validando en el servidor.', 'Flujos completos de extremo a extremo.' ),
    array( 'Pruebas', 'bx-check-shield', 'Automatizo lo crítico: tipos, lint y pruebas de los recorridos principales.', 'Informe de pruebas y errores conocidos.' ),
    array( 'Despliegue', 'bx-rocket', 'Contenedores, proxy con HTTPS, secretos fuera del código y migraciones.', 'Sistema en producción verificado.' ),
    array( 'Evolución', 'bx-trending-up', 'Mantengo y mejoro el sistema con datos e incidencias reales.', 'Documentación y plan de mejoras.' ),
);
?>
<p class="process-flow scroll-reveal" aria-hidden="true">
    <?php foreach ( $steps as $index => $step ) : ?><span><?php echo esc_html( $step[0] ); ?></span><?php if ( $index < count( $steps ) - 1 ) : ?><i class="bx bx-right-arrow-alt"></i><?php endif; endforeach; ?>
</p>
<ol class="process-chain" aria-label="Etapas del proceso">
    <?php foreach ( $steps as $index => $step ) : ?>
    <li class="process-step scroll-reveal">
        <span class="process-index" aria-hidden="true"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
        <i class="bx <?php echo esc_attr( $step[1] ); ?>" aria-hidden="true"></i>
        <h3><?php echo esc_html( $step[0] ); ?></h3>
        <p><?php echo esc_html( $step[2] ); ?></p>
        <p class="process-output"><span>Entrega:</span> <?php echo esc_html( $step[3] ); ?></p>
    </li>
    <?php endforeach; ?>
</ol>
