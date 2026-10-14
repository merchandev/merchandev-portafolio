document.addEventListener('DOMContentLoaded', () => {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    document.querySelectorAll('.fade-up').forEach(element => element.classList.add('is-visible'));
    if ('IntersectionObserver' in window && !reducedMotion.matches) {
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
            });
        }, { rootMargin:'0px 0px 60px 0px' });
        document.querySelectorAll('.scroll-reveal').forEach(element => observer.observe(element));
    }
    document.querySelectorAll('.sidebar-nav .nav-item').forEach(link => {
        const target = new URL(link.href, location.href);
        const path = location.pathname;
        const active = target.origin === location.origin && (target.pathname === path || (target.pathname !== '/' && path.startsWith(target.pathname)) || (target.pathname.endsWith('/servicios/') && path.includes('/servicio/')) || (target.pathname.endsWith('/proyectos/') && path.includes('/tipo-de-proyecto/')));
        if (active) { link.classList.add('active-nav'); link.setAttribute('aria-current','page'); }
    });
    const typing = document.getElementById('typing-text');
    if (typing && !reducedMotion.matches) {
        const words = ['Arturo Merchán','Merchan.Dev'];
        let index = 0, position = words[0].length, deleting = true;
        const tick = () => {
            if (document.hidden || reducedMotion.matches) { setTimeout(tick,1000); return; }
            position += deleting ? -1 : 1;
            typing.textContent = words[index].slice(0,position);
            let delay = deleting ? 50 : 110;
            if (position === 0) { deleting = false; index = (index + 1) % words.length; delay = 400; }
            else if (position === words[index].length) { deleting = true; delay = 3000; }
            setTimeout(tick,delay);
        };
        setTimeout(tick,3000);
    }
    const search = document.getElementById('ajax-search-input');
    const results = document.getElementById('ajax-search-results');
    if (search && results && typeof amData !== 'undefined') {
        let timer, controller, generation = 0;
        search.addEventListener('input', () => {
            clearTimeout(timer); controller?.abort(); generation += 1;
            const current = generation, query = search.value.trim();
            if (query.length < 2) { results.replaceChildren(); results.style.display='none'; return; }
            timer = setTimeout(async () => {
                controller = new AbortController();
                const body = new FormData(); body.set('action','am_search'); body.set('query',query); body.set('security',amData.nonce);
                try {
                    const response = await fetch(amData.ajax_url,{ method:'POST',body,signal:controller.signal });
                    if (!response.ok) throw new Error('network');
                    const data = await response.json();
                    if (current !== generation) return;
                    results.replaceChildren();
                    const items = data.success && Array.isArray(data.data) ? data.data : [];
                    for (const item of items) {
                        const url = new URL(item.url,location.href);
                        if (url.origin !== location.origin) continue;
                        const link = document.createElement('a'); link.className='search-result-item'; link.href=url.href; link.textContent=item.title; results.append(link);
                    }
                    if (!results.childElementCount) { const empty = document.createElement('p'); empty.className='search-result-item'; empty.textContent='No hay resultados.'; results.append(empty); }
                    results.style.display='flex';
                } catch(error) {
                    if (error.name === 'AbortError' || current !== generation) return;
                    results.textContent='No se pudo buscar. Inténtalo de nuevo.'; results.style.display='flex';
                }
            },300);
        });
        document.addEventListener('click',event => { if (!search.contains(event.target) && !results.contains(event.target)) results.style.display='none'; });
        search.addEventListener('keydown',event => { if (event.key==='Escape') results.style.display='none'; });
    }
    const emit = (name, detail = {}) => document.dispatchEvent(new CustomEvent('am:' + name, { detail }));
    document.querySelectorAll('.project-card a, .case-link, .btn-primary, a[href*="wa.me"]').forEach(link => {
        link.addEventListener('click', () => emit('cta_click', { href: link.getAttribute('href') }));
    });
    const form = document.getElementById('nuclearContactForm');
    const status = document.getElementById('form-response');
    if (form && status && typeof amData !== 'undefined') {
        let started = false, sending = false;
        // Las páginas cacheadas (LiteSpeed/CDN) pueden traer un nonce caducado: se pide uno nuevo antes de enviar.
        const refreshNonce = async signal => {
            const body = new FormData(); body.set('action', 'am_contact_token');
            const response = await fetch(amData.ajax_url, { method:'POST', body, credentials:'same-origin', signal });
            const data = response.ok ? await response.json() : null;
            if (data && data.success && typeof data.data === 'string' && form.elements.security) form.elements.security.value = data.data;
        };
        form.addEventListener('input', () => { if (!started) { started = true; emit('contact_start'); } });
        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (sending || !form.reportValidity()) return;
            sending = true;
            const button = form.querySelector('button[type="submit"]'), original = button.textContent;
            button.disabled = true; button.textContent = 'Enviando…';
            status.textContent = 'Registrando tu solicitud…'; status.dataset.state = '';
            form.setAttribute('aria-busy','true');
            const controller = new AbortController(), timeout = setTimeout(() => controller.abort(), 20000);
            try {
                await refreshNonce(controller.signal).catch(error => { if (error.name === 'AbortError') throw error; });
                const response = await fetch(amData.ajax_url, { method:'POST', body:new FormData(form), credentials:'same-origin', signal:controller.signal });
                if (!response.ok) throw new Error('network');
                const result = await response.json();
                status.textContent = typeof result.data === 'string' ? result.data : 'No se pudo procesar la solicitud. Recarga la página.';
                status.dataset.state = result.success ? 'success' : 'error';
                emit(result.success ? 'contact_success' : 'contact_error');
                if (result.success) { form.reset(); started = false; }
            } catch {
                status.textContent = 'No se pudo confirmar la recepción. Puedes escribir a soporte@merchan.dev o por WhatsApp.';
                status.dataset.state = 'error'; emit('contact_error');
            } finally {
                clearTimeout(timeout); sending = false; button.disabled = false; button.textContent = original; form.removeAttribute('aria-busy');
            }
        });
    }
    const banner = document.getElementById('cookie-banner'), settings = document.getElementById('cookie-settings');
    if (banner) {
        let decision = null;
        try { decision = localStorage.getItem('cookie_consent'); } catch {}
        banner.hidden = decision === 'accepted' || decision === 'rejected';
        const decide = value => {
            try { localStorage.setItem('cookie_consent',value); } catch {}
            banner.hidden = true; emit('consent_change',{ decision:value }); settings?.focus();
        };
        document.getElementById('btn-accept-cookies')?.addEventListener('click', () => decide('accepted'));
        document.getElementById('btn-reject-cookies')?.addEventListener('click', () => decide('rejected'));
        settings?.addEventListener('click', () => { banner.hidden = false; document.getElementById('btn-reject-cookies')?.focus(); });
    }
    const menu = document.querySelector('.mobile-menu');
    menu?.addEventListener('keydown', event => { if (event.key === 'Escape') { menu.open = false; menu.querySelector('summary').focus(); } });
    menu?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => { menu.open = false; }));
});
