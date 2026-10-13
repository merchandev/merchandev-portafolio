<?php
/** Summaries sourced from the existing portfolio and biography; no outcome metrics. */
$cases = array(
    array('MeTransfers Barcelona', 'Reservas y operación de traslados', 'bx-trip',
        'El proceso de reserva es una parte central de la operación de un servicio de traslados.',
        'Desarrollo y mantenimiento de un sistema de reservas especializado. Trabajo Full-Stack para conectar la plataforma con las necesidades del servicio.',
        'Desarrollo y mantenimiento del sistema de reservas, según la trayectoria del portafolio.', array('Reservas', 'Full-Stack', 'Integraciones')),
    array('Motores Morichal', 'WordPress e inventario de vehículos', 'bx-car',
        'El sitio de un concesionario necesita presentar vehículos y facilitar la gestión de su inventario y consultas.',
        'Desarrollo integral de un tema WordPress e implementación de plugins personalizados para formularios y administración del inventario.',
        'Tema, plugins, formularios e inventario de vehículos.', array('WordPress', 'PHP', 'Inventario')),
    array('Grupo La Verdad de Monagas', 'Infraestructura de medios y streaming', 'bx-broadcast',
        'Modernizar la infraestructura de transmisión digital de Monagas Visión y Sonora 99.3 FM.',
        'Implementación de servidores VPS y RTMP para la transmisión digital de audio y video.',
        'Infraestructura de servidores y transmisión digital.', array('Streaming', 'VPS', 'RTMP')),
    array('Mínima Organics', 'Migración e integraciones de comercio electrónico', 'bx-cart-alt',
        'Adaptar la plataforma de comercio electrónico y sus flujos de pago a las necesidades del negocio.',
        'Trabajo de migración de plataforma e integración de pasarelas de pago, referido en los casos del portafolio original.',
        'Migración e integraciones de pagos. Las métricas de impacto se incorporarán cuando estén documentadas.', array('E-commerce', 'Migración', 'Pagos')),
);
?>
<div class="grid-2 experience-cases">
<?php foreach ($cases as $case) : ?>
<article class="glass-card flex-column scroll-reveal">
    <div class="case-eyebrow"><div class="icon-box"><i class="bx <?php echo esc_attr($case[2]); ?>" aria-hidden="true"></i></div><span>Experiencia aplicada</span></div>
    <h3><?php echo esc_html($case[0]); ?></h3><p class="case-specialty"><?php echo esc_html($case[1]); ?></p>
    <p class="project-desc"><?php echo esc_html($case[4]); ?></p>
    <details class="case-details"><summary>Contexto y alcance <span aria-hidden="true">＋</span></summary><div><h4>Contexto</h4><p><?php echo esc_html($case[3]); ?></p><h4>Participación y alcance</h4><p><?php echo esc_html($case[5]); ?></p></div></details>
    <div class="tags mt-auto"><?php foreach ($case[6] as $tag) : ?><span><?php echo esc_html($tag); ?></span><?php endforeach; ?></div>
</article>
<?php endforeach; ?>
</div>
