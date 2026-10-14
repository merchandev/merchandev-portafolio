<?php
/**
 * Tarjeta de proyecto.
 * $args: project (array de am_project_data), variant (feature|standard|compact), reverse (bool), eager (bool), heading (h2|h3).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$project = $args['project'] ?? null;
if ( ! $project ) { return; }
$variant = $args['variant'] ?? 'standard';
$heading = in_array( $args['heading'] ?? 'h3', array( 'h2', 'h3' ), true ) ? $args['heading'] : 'h3';
$stack = array_slice( (array) $project['stack'], 0, $variant === 'compact' ? 3 : 5 );
$case_url = $project['has_case'] ? $project['url'] : '';
$classes = array( 'project-card', 'project-card--' . $variant );
if ( ! empty( $args['reverse'] ) ) { $classes[] = 'is-reversed'; }
if ( ! $project['image_id'] && ! $project['image_src'] ) { $classes[] = 'has-type-cover'; }
$title_id = 'proyecto-' . sanitize_html_class( $project['slug'] ) . '-' . $variant;
?>
<article class="<?php echo esc_attr( implode( ' ', $classes ) ); ?> scroll-reveal" aria-labelledby="<?php echo esc_attr( $title_id ); ?>" data-categories="<?php echo esc_attr( implode( ' ', $project['categories'] ) ); ?>">
    <?php if ( $variant !== 'compact' ) : ?>
    <div class="project-media">
        <div class="browser-frame" aria-hidden="true"><span></span><span></span><span></span><em><?php echo esc_html( am_project_frame_label( $project ) ); ?></em></div>
        <?php if ( $case_url ) : ?><a href="<?php echo esc_url( $case_url ); ?>" tabindex="-1" aria-hidden="true"><?php endif; ?>
        <?php echo am_project_cover( $project, 'large', array( 'loading' => ! empty( $args['eager'] ) ? 'eager' : 'lazy', 'sizes' => $variant === 'feature' ? '(max-width: 992px) 100vw, 60vw' : '(max-width: 768px) 100vw, 45vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in am_project_cover(). ?>
        <?php if ( $case_url ) : ?></a><?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="project-body">
        <div class="project-meta">
            <?php if ( $project['category_label'] ) : ?><span class="project-category"><?php echo esc_html( $project['category_label'] ); ?></span><?php endif; ?>
            <?php echo am_project_status_badge( $project ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
        </div>
        <<?php echo $heading; ?> id="<?php echo esc_attr( $title_id ); ?>" class="project-title">
            <?php if ( $case_url ) : ?><a href="<?php echo esc_url( $case_url ); ?>"><?php echo esc_html( $project['title'] ); ?></a><?php else : echo esc_html( $project['title'] ); endif; ?>
        </<?php echo $heading; ?>>
        <?php if ( $project['sector'] && $variant !== 'compact' ) : ?><p class="project-sector"><?php echo esc_html( $project['sector'] ); ?></p><?php endif; ?>
        <p class="project-desc"><?php echo esc_html( $project['excerpt'] ); ?></p>
        <?php if ( $variant === 'feature' && $project['role'] ) : ?>
            <p class="project-role"><strong>Mi participación:</strong> <?php echo esc_html( wp_trim_words( $project['role'], 26, '…' ) ); ?></p>
        <?php endif; ?>
        <?php if ( $stack ) : ?>
            <ul class="tags project-stack" aria-label="Tecnologías principales"><?php foreach ( $stack as $tech ) : ?><li><?php echo esc_html( $tech ); ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
        <div class="project-actions">
            <?php if ( $case_url ) : ?>
                <a class="case-link" href="<?php echo esc_url( $case_url ); ?>"><?php echo $project['featured'] ? 'Ver caso de estudio' : 'Ver ficha'; ?> <i class="bx bx-right-arrow-alt" aria-hidden="true"></i><span class="screen-reader-text">: <?php echo esc_html( $project['title'] ); ?></span></a>
            <?php endif; ?>
            <?php if ( $project['demo'] ) : ?>
                <a class="icon-link" href="<?php echo esc_url( $project['demo'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bx-globe" aria-hidden="true"></i> Web<span class="screen-reader-text"> de <?php echo esc_html( $project['title'] ); ?> (se abre en una pestaña nueva)</span></a>
            <?php endif; ?>
            <?php if ( $project['repository'] ) : ?>
                <a class="icon-link" href="<?php echo esc_url( $project['repository'] ); ?>" target="_blank" rel="noopener noreferrer"><i class="bx bxl-github" aria-hidden="true"></i> Código<span class="screen-reader-text"> de <?php echo esc_html( $project['title'] ); ?> en GitHub (se abre en una pestaña nueva)</span></a>
            <?php endif; ?>
        </div>
    </div>
</article>
