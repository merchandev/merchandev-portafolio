    </main>

    <footer id="contacto" style="background: rgba(10, 10, 10, 0.9); padding: 4rem 0 2rem 0; border-top: 1px solid rgba(255, 122, 0, 0.2); margin-top: 4rem;">
        <div class="container">
            <!-- Top: Logo + Description + Social -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 2rem;">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/image/LOGO2026 - MERCHAN.DEV.png" alt="Merchan.Dev Logo" style="height: 60px; width: auto; object-fit: contain;">
                <p style="color: var(--color-text-muted); text-align: center; max-width: 600px;">
                    Desarrollo de Arquitectura Personalizada. <br/>
                    Ingeniería de Software · WordPress Solutions Architect · DevOps
                </p>
                
                <div class="footer-social" style="display: flex; gap: 1.5rem; flex-wrap: wrap; justify-content: center;">
                    <!-- Github -->
                    <a href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer" aria-label="GitHub" style="color: var(--color-text-main); background: rgba(255, 255, 255, 0.05); padding: 0.8rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 122, 0, 0.2)'; this.style.color='var(--color-goku-orange)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; this.style.color='var(--color-text-main)';">
                        <i class='bx bxl-github' style='font-size: 24px;'></i>
                    </a>
                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/in/merchandev/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn" style="color: var(--color-text-main); background: rgba(255, 255, 255, 0.05); padding: 0.8rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 122, 0, 0.2)'; this.style.color='var(--color-goku-orange)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; this.style.color='var(--color-text-main)';">
                        <i class='bx bxl-linkedin' style='font-size: 24px;'></i>
                    </a>
                    <!-- TikTok -->
                    <a href="https://www.tiktok.com/@merchan.dev" target="_blank" rel="noopener noreferrer" aria-label="TikTok" style="color: var(--color-text-main); background: rgba(255, 255, 255, 0.05); padding: 0.8rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 122, 0, 0.2)'; this.style.color='var(--color-goku-orange)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; this.style.color='var(--color-text-main)';">
                        <i class='bx bxl-tiktok' style='font-size: 24px;'></i>
                    </a>
                    <!-- YouTube -->
                    <a href="https://www.youtube.com/@merchandeveloper" target="_blank" rel="noopener noreferrer" aria-label="YouTube" style="color: var(--color-text-main); background: rgba(255, 255, 255, 0.05); padding: 0.8rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 122, 0, 0.2)'; this.style.color='var(--color-goku-orange)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; this.style.color='var(--color-text-main)';">
                        <i class='bx bxl-youtube' style='font-size: 24px;'></i>
                    </a>
                    <!-- Instagram -->
                    <a href="https://www.instagram.com/merchan.dev/" target="_blank" rel="noopener noreferrer" aria-label="Instagram" style="color: var(--color-text-main); background: rgba(255, 255, 255, 0.05); padding: 0.8rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s;" onmouseover="this.style.backgroundColor='rgba(255, 122, 0, 0.2)'; this.style.color='var(--color-goku-orange)';" onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.05)'; this.style.color='var(--color-text-main)';">
                        <i class='bx bxl-instagram' style='font-size: 24px;'></i>
                    </a>
                </div>
            </div>
            
            <!-- Bottom bar: Legal links LEFT | Copyright RIGHT -->
            <div class="footer-bottom">
                <!-- LEFT: Legal links -->
                <div class="footer-legal-links">
                    <a href="<?php echo esc_url(home_url('/politicas-de-privacidad-y-terminos/')); ?>" class="footer-legal-link">
                        <i class='bx bx-shield-quarter' style="font-size: 0.9rem;"></i> Aviso Legal y Términos
                    </a>
                    <span class="footer-separator">·</span>
                    <a href="<?php echo esc_url(home_url('/politica-de-cookies/')); ?>" class="footer-legal-link">
                        <i class='bx bx-cookie' style="font-size: 0.9rem;"></i> Política de Cookies
                    </a>
                </div>
                <!-- RIGHT: Copyright -->
                <div class="footer-copyright">
                    &copy; <?php echo date('Y'); ?> <strong style="color: var(--color-goku-orange);">Merchan.Dev</strong> — Todos los derechos reservados.
                </div>
            </div>
        </div>
    </footer>
</div><!-- .app-container -->

<div id="cookie-banner" class="cookie-banner glass-card" style="display: none;">
    <div class="cookie-content">
        <i class='bx bx-cookie' style="font-size: 1.5rem; color: var(--color-goku-orange);"></i>
        <p>Utilizamos cookies propias y de terceros para mejorar tu experiencia y proteger el sitio. <br class="hide-mobile">Lee nuestra <a href="<?php echo esc_url(home_url('/politica-de-cookies/')); ?>" style="color: var(--color-goku-orange); text-decoration: underline;">Política de Cookies</a>.</p>
    </div>
    <div class="cookie-actions">
        <button id="btn-reject-cookies" class="btn-secondary" style="padding: 8px 16px; font-size: 0.85rem; min-width: 100px;">Rechazar</button>
        <button id="btn-accept-cookies" class="btn-primary" style="padding: 8px 16px; font-size: 0.85rem; min-width: 100px;">Aceptar</button>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>
