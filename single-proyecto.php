<?php
/**
 * Caso de estudio. Orden: presentación, contexto, problema, objetivo, arquitectura, participación,
 * funcionalidades, desafíos, tecnologías, seguridad y operación, capturas, estado, resultados,
 * enlaces y proyectos relacionados. Las secciones sin datos se omiten o se marcan como pendientes.
 */
get_header();
while ( have_posts() ) :
    the_post();
    $project = am_project_data( get_post() );
    $statuses = am_project_statuses();
    $specialties = am_specialties();
    // La portada ya aparece al inicio: la galería muestra el resto de capturas.
    $gallery = array_values( array_diff( $project['gallery'], array( $project['image_id'] ) ) );
    $related = $project['related'] ? array_map( 'am_project_data', get_posts( array( 'post_type' => 'proyecto', 'post_status' => 'publish', 'post__in' => $project['related'], 'orderby' => 'post__in', 'numberposts' => 3 ) ) ) : array();
    $section = 0;
    $heading = function ( $title, $id ) use ( &$section ) {
        $section++;
        printf( '<h2 id="%1$s" class="case-heading"><span aria-hidden="true">%2$s</span>%3$s</h2>', esc_attr( $id ), esc_html( sprintf( '%02d', $section ) ), esc_html( $title ) );
    };
    $paragraphs = function ( $text ) { foreach ( am_parse_lines( $text ) as $line ) { echo '<p>' . esc_html( $line ) . '</p>'; } };
    ?>
<article class="case-study" aria-labelledby="case-title">
    <header class="case-hero">
        <div class="aura-effect" aria-hidden="true"></div>
        <div class="container case-hero-inner">
            <nav class="breadcrumbs" aria-label="Migas de pan">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Inicio</a><span aria-hidden="true">/</span>
                <a href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>">Proyectos</a><span aria-hidden="true">/</span>
                <span aria-current="page"><?php the_title(); ?></span>
            </nav>
            <div class="project-meta">
                <?php $category_link = $project['primary_category'] ? get_term_link( $project['primary_category'], 'tipo_proyecto' ) : ''; ?>
                <?php if ( $project['category_label'] && $category_link && ! is_wp_error( $category_link ) ) : ?><a class="project-category" href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( $project['category_label'] ); ?></a><?php endif; ?>
                <?php echo am_project_status_badge( $project ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
            </div>
            <h1 id="case-title" class="case-title"><?php the_title(); ?></h1>
            <p class="case-intro"><?php echo esc_html( $project['excerpt'] ); ?></p>
            <dl class="case-facts-inline">
                <?php if ( $project['sector'] ) : ?><div><dt>Sector</dt><dd><?php echo esc_html( $project['sector'] ); ?></dd></div><?php endif; ?>
                <?php if ( $project['period'] ) : ?><div><dt>Período</dt><dd><?php echo esc_html( $project['period'] ); ?></dd></div><?php endif; ?>
                <?php if ( $project['status_label'] ) : ?><div><dt>Estado</dt><dd><?php echo esc_html( $project['status_label'] ); ?></dd></div><?php endif; ?>
            </dl>
            <div class="action-row">
                <?php if ( $project['demo'] ) : ?><a class="btn-primary" href="<?php echo esc_url( $project['demo'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bx-globe" aria-hidden="true"></i> Ver la web<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a><?php endif; ?>
                <?php if ( $project['repository'] ) : ?><a class="btn-secondary" href="<?php echo esc_url( $project['repository'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> Ver el código<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></a><?php endif; ?>
            </div>
        </div>
    </header>

    <div class="container">
        <figure class="case-cover<?php echo $project['image_id'] ? '' : ' is-typographic'; ?>">
            <div class="browser-frame" aria-hidden="true"><span></span><span></span><span></span><em><?php echo esc_html( am_project_frame_label( $project ) ); ?></em></div>
            <?php echo am_project_cover( $project, 'full', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'sizes' => '(max-width: 1280px) 100vw, 1200px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </figure>

        <div class="case-layout">
            <div class="case-main">
                <?php if ( $project['context'] ) : ?><section aria-labelledby="contexto"><?php $heading( 'Contexto', 'contexto' ); $paragraphs( $project['context'] ); ?></section><?php endif; ?>
                <?php if ( $project['problem'] ) : ?><section aria-labelledby="problema"><?php $heading( 'Problema o necesidad', 'problema' ); $paragraphs( $project['problem'] ); ?></section><?php endif; ?>
                <?php if ( $project['objective'] ) : ?><section aria-labelledby="objetivo"><?php $heading( 'Objetivo de la solución', 'objetivo' ); $paragraphs( $project['objective'] ); ?></section><?php endif; ?>
                <?php if ( $project['architecture'] ) : ?><section aria-labelledby="arquitectura"><?php $heading( 'Arquitectura general', 'arquitectura' ); $paragraphs( $project['architecture'] ); ?></section><?php endif; ?>
                <?php if ( $project['role'] ) : ?><section aria-labelledby="participacion"><?php $heading( 'Mi participación', 'participacion' ); $paragraphs( $project['role'] ); ?></section><?php endif; ?>
                <?php if ( $project['features'] ) : ?>
                <section aria-labelledby="funcionalidades"><?php $heading( 'Funcionalidades implementadas', 'funcionalidades' ); ?>
                    <ul class="check-list"><?php foreach ( $project['features'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ul>
                </section>
                <?php endif; ?>
                <?php if ( $project['challenges'] ) : ?>
                <section aria-labelledby="desafios"><?php $heading( 'Desafíos técnicos y decisiones', 'desafios' ); ?>
                    <ol class="challenge-list"><?php foreach ( $project['challenges'] as $item ) : ?><li><?php echo esc_html( $item ); ?></li><?php endforeach; ?></ol>
                </section>
                <?php endif; ?>
                <?php if ( $project['stack'] ) : ?>
                <section aria-labelledby="tecnologias"><?php $heading( 'Tecnologías utilizadas', 'tecnologias' ); ?>
                    <ul class="tags case-stack"><?php foreach ( $project['stack'] as $tech ) : ?><li><?php echo esc_html( $tech ); ?></li><?php endforeach; ?></ul>
                </section>
                <?php endif; ?>
                <?php if ( $project['security'] ) : ?><section aria-labelledby="seguridad"><?php $heading( 'Seguridad y operación', 'seguridad' ); $paragraphs( $project['security'] ); ?></section><?php endif; ?>

                <section aria-labelledby="capturas"><?php $heading( 'Capturas', 'capturas' ); ?>
                    <?php if ( $gallery ) : ?>
                        <div class="case-gallery">
                            <?php foreach ( $gallery as $attachment_id ) : $caption = wp_get_attachment_caption( $attachment_id ) ?: get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ); ?>
                            <figure>
                                <a href="<?php echo esc_url( wp_get_attachment_url( $attachment_id ) ); ?>"><?php echo wp_get_attachment_image( $attachment_id, 'large', false, array( 'loading' => 'lazy', 'decoding' => 'async' ) ); ?><span class="screen-reader-text">Ver imagen completa</span></a>
                                <?php if ( $caption ) : ?><figcaption><?php echo esc_html( $caption ); ?></figcaption><?php endif; ?>
                            </figure>
                            <?php endforeach; ?>
                        </div>
                        <p class="case-note">Capturas reales del proyecto, sin datos personales: las imágenes con personas o datos de terceros se difuminan.</p>
                    <?php elseif ( $project['image_id'] ) : ?>
                        <p class="case-note">La captura disponible es la portada de este caso. Otras vistas (paneles internos, flujos) están pendientes de documentar con datos anonimizados.</p>
                    <?php else : ?>
                        <p class="case-note is-pending">Capturas pendientes de documentar con datos anonimizados y permiso de publicación.</p>
                    <?php endif; ?>
                    <?php if ( $project['capture_updated'] ) : ?>
                        <p class="case-note">La portada se actualiza automáticamente desde la web pública cuando cambia su interfaz. Última actualización: <?php echo esc_html( mysql2date( 'j \d\e F \d\e Y', $project['capture_updated'] ) ); ?>.</p>
                    <?php endif; ?>
                </section>

                <section aria-labelledby="estado"><?php $heading( 'Estado actual', 'estado' ); ?>
                    <?php if ( $project['status'] && isset( $statuses[ $project['status'] ] ) ) : ?>
                        <p><?php echo am_project_status_badge( $project ); // phpcs:ignore WordPress.Security.EscapeOutput ?> <?php echo esc_html( $statuses[ $project['status'] ][1] ); ?></p>
                    <?php else : ?><p class="case-note is-pending">Estado pendiente de confirmar.</p><?php endif; ?>
                </section>

                <section aria-labelledby="resultados"><?php $heading( 'Resultados comprobables', 'resultados' ); ?>
                    <?php if ( $project['results'] ) { $paragraphs( $project['results'] ); } else { echo '<p class="case-note is-pending">Pendiente de documentar. No publico cifras sin una fuente verificable.</p>'; } ?>
                </section>

                <section aria-labelledby="enlaces"><?php $heading( 'Repositorio y demo', 'enlaces' ); ?>
                    <?php if ( $project['demo'] || $project['repository'] || $project['links'] ) : ?>
                    <ul class="link-list case-links">
                        <?php if ( $project['demo'] ) : ?><li><a href="<?php echo esc_url( $project['demo'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bx-globe" aria-hidden="true"></i> <?php echo esc_html( preg_replace( '~^https?://~', '', untrailingslashit( $project['demo'] ) ) ); ?></a></li><?php endif; ?>
                        <?php if ( $project['repository'] ) : ?><li><a href="<?php echo esc_url( $project['repository'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> <?php echo esc_html( preg_replace( '~^https?://(www\.)?github\.com/~', 'github.com/', $project['repository'] ) ); ?></a></li><?php endif; ?>
                        <?php foreach ( $project['links'] as $link ) : ?><li><a href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bx-link-external" aria-hidden="true"></i> <?php echo esc_html( $link['label'] ); ?></a></li><?php endforeach; ?>
                    </ul>
                    <?php else : ?><p class="case-note">Código privado del cliente. Puedo mostrar detalles técnicos en una conversación, con su permiso.</p><?php endif; ?>
                </section>

                <?php if ( trim( get_the_content() ) !== '' ) : ?>
                    <section class="case-content" aria-label="Notas adicionales"><?php the_content(); ?></section>
                <?php endif; ?>
            </div>

            <aside class="case-aside" aria-label="Ficha técnica">
                <div class="glass-card case-sheet">
                    <h2>Ficha técnica</h2>
                    <dl>
                        <?php if ( $project['category_label'] ) : ?><div><dt>Categoría</dt><dd><?php echo esc_html( $project['category_label'] ); ?></dd></div><?php endif; ?>
                        <?php if ( $project['status_label'] ) : ?><div><dt>Estado</dt><dd><?php echo esc_html( $project['status_label'] ); ?></dd></div><?php endif; ?>
                        <?php if ( $project['period'] ) : ?><div><dt>Período</dt><dd><?php echo esc_html( $project['period'] ); ?></dd></div><?php endif; ?>
                        <?php if ( $project['stack'] ) : ?><div><dt>Base técnica</dt><dd><?php echo esc_html( implode( ', ', array_slice( $project['stack'], 0, 4 ) ) ); ?></dd></div><?php endif; ?>
                    </dl>
                    <?php $shown = array_intersect_key( $specialties, array_flip( $project['specialties'] ) ); if ( $shown ) : ?>
                        <p class="mini-heading">Especialidades</p>
                        <ul class="link-list">
                            <?php foreach ( $shown as $specialty ) : ?><li><a href="<?php echo esc_url( am_service_url( $specialty['slug'] ) ); ?>"><?php echo esc_html( $specialty['title'] ); ?></a></li><?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <a class="btn-primary case-sheet-cta" href="<?php echo esc_url( add_query_arg( 'interes', $project['primary_category'] === 'aplicaciones-moviles' ? 'movil' : 'desarrollo-software', home_url( '/contacto/' ) ) ); ?>">¿Necesitas algo similar?</a>
                </div>
            </aside>
        </div>

        <?php if ( $related ) : ?>
        <section class="case-related" aria-labelledby="relacionados">
            <?php $heading( 'Proyectos relacionados', 'relacionados' ); ?>
            <div class="project-grid-compact">
                <?php foreach ( $related as $item ) { get_template_part( 'template-parts/project-card', null, array( 'project' => $item, 'variant' => 'compact', 'heading' => 'h3' ) ); } ?>
            </div>
        </section>
        <?php endif; ?>

        <div class="glass-card catalog-cta">
            <div><h2>Construyamos tu próximo sistema</h2><p>Cuéntame el proceso que quieres resolver; te respondo con preguntas concretas o un siguiente paso.</p></div>
            <div class="action-row"><a class="btn-primary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Hablemos de tu proyecto <i class="bx bx-right-arrow-alt" aria-hidden="true"></i></a><a class="btn-secondary" href="<?php echo esc_url( get_post_type_archive_link( 'proyecto' ) ); ?>">Ver más proyectos</a></div>
        </div>
    </div>
</article>
<?php endwhile; get_footer(); ?>
