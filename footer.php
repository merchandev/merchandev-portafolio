    </main>

    <footer id="site-footer" class="site-footer">
        <div class="container">
            <div class="footer-top">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/image/LOGO2026 - MERCHAN.DEV.png' ); ?>" alt="Merchan.Dev" class="footer-logo" height="60" loading="lazy">
                <p class="footer-tagline">
                    Arturo Merchán · Desarrollador de software Full-Stack<br>
                    Plataformas digitales · Sistemas empresariales · Infraestructura y operación
                </p>

                <nav class="footer-nav" aria-label="Enlaces del sitio">
                    <a href="<?php echo esc_url( home_url( '/proyectos/' ) ); ?>">Proyectos</a>
                    <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>">Servicios</a>
                    <a href="<?php echo esc_url( home_url( '/sobre-mi/' ) ); ?>">Sobre mí</a>
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a>
                    <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">Contacto</a>
                    <a href="<?php echo esc_url( get_template_directory_uri() . '/assets/docs/CV_Arturo_Merchan.pdf' ); ?>" download>CV (PDF)</a>
                </nav>

                <div class="footer-social">
                    <a href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer" aria-label="GitHub (se abre en una pestaña nueva)"><i class="bx bxl-github" aria-hidden="true"></i></a>
                    <a href="https://www.linkedin.com/in/merchandev/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn (se abre en una pestaña nueva)"><i class="bx bxl-linkedin" aria-hidden="true"></i></a>
                    <a href="https://www.tiktok.com/@merchan.dev" target="_blank" rel="noopener noreferrer" aria-label="TikTok (se abre en una pestaña nueva)"><i class="bx bxl-tiktok" aria-hidden="true"></i></a>
                    <a href="https://www.youtube.com/@merchandeveloper" target="_blank" rel="noopener noreferrer" aria-label="YouTube (se abre en una pestaña nueva)"><i class="bx bxl-youtube" aria-hidden="true"></i></a>
                    <a href="https://www.instagram.com/merchan.dev/" target="_blank" rel="noopener noreferrer" aria-label="Instagram (se abre en una pestaña nueva)"><i class="bx bxl-instagram" aria-hidden="true"></i></a>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="footer-legal-links">
                    <a href="<?php echo esc_url( home_url( '/politicas-de-privacidad-y-terminos/' ) ); ?>" class="footer-legal-link">
                        <i class="bx bx-shield-quarter" aria-hidden="true"></i> Aviso Legal y Términos
                    </a>
                    <span class="footer-separator" aria-hidden="true">·</span>
                    <a href="<?php echo esc_url( home_url( '/politica-de-cookies/' ) ); ?>" class="footer-legal-link">
                        <i class="bx bx-cookie" aria-hidden="true"></i> Política de Cookies
                    </a>
                    <button type="button" class="text-button" id="cookie-settings">Preferencias</button>
                </div>
                <div class="footer-copyright">
                    &copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <strong>Merchan.Dev</strong> — Todos los derechos reservados.
                </div>
            </div>
        </div>
    </footer>
</div><!-- .app-container -->

<div id="cookie-banner" class="consent-panel" role="region" aria-label="Preferencias de almacenamiento" hidden>
<p>Guardamos tu preferencia en este navegador. Puedes aceptar o rechazar el uso opcional y cambiar tu decisión en el pie de página. <a href="<?php echo esc_url( home_url( '/politica-de-cookies/' ) ); ?>">Ver política</a>.</p>
<div class="action-row"><button type="button" id="btn-reject-cookies" class="btn-secondary">Rechazar</button><button type="button" id="btn-accept-cookies" class="btn-primary">Aceptar</button></div></div>

<?php wp_footer(); ?>
</body>
</html>
