/*
 * QA de navegador sobre una instalación WordPress real con el tema activo.
 *
 *   BASE_URL=http://127.0.0.1:8890 PLAYWRIGHT_MODULE=/ruta/a/playwright AXE_PATH=/ruta/axe.min.js node tests/browser-qa.cjs
 *
 * Comprueba en 5 anchos: estado HTTP, un único <main> y <h1>, desbordamiento horizontal,
 * textos alternativos, errores de consola y navegación móvil. Además: enlaces internos,
 * accesibilidad con axe-core (si se indica AXE_PATH), filtros del catálogo, teclado,
 * movimiento reducido y envío real del formulario de contacto.
 * OUT_DIR guarda capturas de pantalla completas de cada página y ancho.
 */
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');
const fs = require('node:fs');
const path = require('node:path');

const BASE = (process.env.BASE_URL || 'http://127.0.0.1:8890').replace(/\/$/, '');
const OUT = process.env.OUT_DIR || '';
const AXE = process.env.AXE_PATH && fs.existsSync(process.env.AXE_PATH) ? fs.readFileSync(process.env.AXE_PATH, 'utf8') : '';
const PAGES = ['/', '/proyectos/', '/proyectos/metransfers/', '/proyectos/guia-medica-monagas/', '/proyectos/metransfers-saas/', '/proyectos/cms-editorial/', '/proyectos/rotec/',
    '/tipo-de-proyecto/plataformas-saas/', '/servicios/', '/servicio/desarrollo-software-productos-digitales/', '/servicio/seo-tecnico/',
    '/sobre-mi/', '/contacto/', '/blog/', '/?s=wordpress', '/politica-de-cookies/', '/politicas-de-privacidad-y-terminos/'];
const WIDTHS = [1440, 1024, 768, 390, 320];
const failures = [];
const notes = [];
const fail = (where, message) => failures.push(`${where}: ${message}`);

(async () => {
    const browser = await chromium.launch({ headless: true, channel: process.env.PW_CHANNEL || 'chrome' });
    const links = new Set();
    const axeSummary = {};

    for (const width of WIDTHS) {
        const context = await browser.newContext({ viewport: { width, height: 900 } });
        await context.addInitScript(() => { try { localStorage.setItem('cookie_consent', 'rejected'); } catch (e) {} });
        for (const url of PAGES) {
            const page = await context.newPage();
            const errors = [];
            page.on('pageerror', error => errors.push(error.message));
            page.on('console', message => { if (message.type() === 'error' && !/fonts\.g|unpkg|favicon|ERR_|net::/i.test(message.text())) errors.push(message.text()); });
            const where = `${url} @${width}`;
            const response = await page.goto(BASE + url, { waitUntil: 'networkidle' });
            if (!response || response.status() >= 400) { fail(where, `HTTP ${response && response.status()}`); await page.close(); continue; }
            const info = await page.evaluate(() => ({
                mains: document.querySelectorAll('main').length,
                h1: document.querySelectorAll('h1').length,
                overflow: document.documentElement.scrollWidth - window.innerWidth,
                imgsNoAlt: [...document.images].filter(img => !img.hasAttribute('alt')).map(img => img.src),
                canvas: !!document.getElementById('bg-canvas'),
                ids: (() => { const seen = {}; const dup = []; document.querySelectorAll('[id]').forEach(el => { if (seen[el.id]) dup.push(el.id); seen[el.id] = 1; }); return dup; })(),
                hrefs: [...document.querySelectorAll('a[href]')].map(a => a.href),
                hidden: Object.fromEntries(['nav-proyectos', 'nav-servicios', 'nav-home', 'nav-sobre', 'nav-contacto', 'nav-blog', 'nav-github'].map(c => {
                    const el = document.querySelector('.' + c); if (!el) return [c, null];
                    const r = el.getBoundingClientRect(); return [c, r.width > 0 && r.height > 0 && getComputedStyle(el).display !== 'none'];
                })),
            }));
            if (info.mains !== 1) fail(where, `${info.mains} elementos <main>`);
            if (info.h1 !== 1) fail(where, `${info.h1} elementos <h1>`);
            if (info.overflow > 1) fail(where, `desbordamiento horizontal de ${info.overflow}px`);
            if (info.imgsNoAlt.length) fail(where, `imágenes sin alt: ${info.imgsNoAlt.join(', ')}`);
            if (!info.canvas) fail(where, 'falta el fondo #bg-canvas');
            if (info.ids.length) fail(where, `IDs duplicados: ${[...new Set(info.ids)].join(', ')}`);
            if (errors.length) fail(where, `errores JS: ${errors.join(' | ')}`);
            if (width <= 768) {
                for (const c of ['nav-proyectos', 'nav-servicios', 'nav-home', 'nav-sobre', 'nav-contacto']) if (!info.hidden[c]) fail(where, `${c} no visible en móvil`);
                for (const c of ['nav-blog', 'nav-github']) if (info.hidden[c]) fail(where, `${c} visible en la barra móvil`);
            }
            info.hrefs.filter(h => h.startsWith(BASE) && !h.includes('/wp-admin/') && !h.includes('#')).forEach(h => links.add(h.split('#')[0]));
            if (AXE && width === 1440) {
                await page.addScriptTag({ content: AXE });
                const result = await page.evaluate(async () => (await window.axe.run(document, { runOnly: ['wcag2a', 'wcag2aa'] })).violations.map(v => ({ id: v.id, impact: v.impact, nodes: v.nodes.length, target: v.nodes.slice(0, 3).map(n => n.target.join(' ')) })));
                axeSummary[url] = result;
                result.filter(v => v.impact === 'critical' || v.impact === 'serious').forEach(v => fail(`${url} axe`, `${v.id} (${v.impact}) en ${v.nodes} nodos: ${v.target.join(' · ')}`));
            }
            if (OUT) {
                for (let y = 0; y < 40000; y += 800) { const done = await page.evaluate(v => { scrollTo(0, v); return v > document.body.scrollHeight; }, y); if (done) break; await page.waitForTimeout(60); }
                await page.waitForFunction(() => [...document.images].every(i => i.complete), null, { timeout: 15000 }).catch(() => {});
                await page.evaluate(() => scrollTo(0, 0));
                const name = (url.replace(/[^a-z0-9]+/gi, '-').replace(/^-|-$/g, '') || 'inicio') + `-${width}.png`;
                await page.screenshot({ path: path.join(OUT, name), fullPage: true });
            }
            await page.close();
        }
        await context.close();
    }

    // Enlaces internos rotos.
    const request = await browser.newContext();
    for (const href of links) {
        const res = await request.request.get(href, { maxRedirects: 5 }).catch(e => ({ status: () => 0, e }));
        if (res.status() >= 400 || res.status() === 0) fail('enlace interno', `${href} → ${res.status()}`);
    }
    notes.push(`${links.size} enlaces internos comprobados`);

    const page = await (await browser.newContext({ viewport: { width: 1440, height: 900 } })).newPage();
    // Teclado: el primer Tab va al enlace de salto; el menú lateral se expande con el foco.
    await page.goto(BASE + '/', { waitUntil: 'networkidle' });
    await page.keyboard.press('Tab');
    if (!(await page.evaluate(() => document.activeElement && document.activeElement.classList.contains('skip-link')))) fail('teclado', 'el primer Tab no enfoca «Saltar al contenido»');
    await page.focus('.nav-proyectos');
    await page.waitForTimeout(600);
    const sidebar = await page.evaluate(() => document.getElementById('app-sidebar').getBoundingClientRect().width);
    if (sidebar < 200) fail('teclado', `el menú no se expande con el foco (${sidebar}px)`);

    // Filtros del catálogo (enlaces a categorías, sin JavaScript).
    await page.goto(BASE + '/proyectos/', { waitUntil: 'networkidle' });
    const total = await page.locator('.project-card').count();
    await page.getByRole('link', { name: /Plataformas y SaaS/ }).first().click();
    await page.waitForLoadState('networkidle');
    const filtered = await page.locator('.project-card').count();
    if (!page.url().includes('/tipo-de-proyecto/plataformas-saas/') || filtered === 0 || filtered >= total) fail('filtros', `url=${page.url()} total=${total} filtrados=${filtered}`);
    notes.push(`catálogo: ${total} proyectos, ${filtered} en «Plataformas y SaaS»`);

    // Movimiento reducido: el contenido es visible y el canvas sigue presente.
    const reduced = await (await browser.newContext({ reducedMotion: 'reduce', viewport: { width: 1440, height: 900 } })).newPage();
    await reduced.goto(BASE + '/', { waitUntil: 'networkidle' });
    const opacity = await reduced.evaluate(() => getComputedStyle(document.querySelector('.project-card')).opacity);
    if (opacity !== '1') fail('movimiento reducido', `tarjetas con opacidad ${opacity}`);

    // Formulario: validación del navegador y envío real por AJAX (con nonce renovado).
    await page.goto(BASE + '/contacto/?interes=administracion-vps-devops', { waitUntil: 'networkidle' });
    if ((await page.inputValue('#service')) !== 'Infraestructura y despliegue') fail('formulario', 'no se preseleccionó el servicio de origen');
    await page.click('#nuclearContactForm button[type=submit]');
    if (await page.evaluate(() => document.getElementById('nuclearContactForm').checkValidity())) fail('formulario', 'el formulario vacío se considera válido');
    const marker = 'QA ' + Date.now();
    await page.fill('#name', marker);
    await page.fill('#email', 'qa@example.test');
    await page.fill('#message', 'Prueba automática del formulario de contacto.');
    await page.check('input[name=privacy]');
    const tokenRequest = page.waitForRequest(r => r.method() === 'POST' && r.url().includes('admin-ajax.php') && (r.postData() || '').includes('am_contact_token'), { timeout: 10000 }).catch(() => null);
    await page.click('#nuclearContactForm button[type=submit]');
    await page.waitForFunction(() => document.getElementById('form-response').dataset.state, null, { timeout: 20000 }).catch(() => {});
    const state = await page.getAttribute('#form-response', 'data-state');
    if (!(await tokenRequest)) fail('formulario', 'no se solicitó un nonce nuevo antes de enviar');
    if (state !== 'success') fail('formulario', `envío no confirmado: ${await page.textContent('#form-response')}`);
    notes.push(`lead de prueba enviado: «${marker}»`);

    await browser.close();
    const report = { base: BASE, paginas: PAGES.length, anchos: WIDTHS, fallos: failures, notas: notes, axe: axeSummary };
    if (OUT) fs.writeFileSync(path.join(OUT, 'browser-qa.json'), JSON.stringify(report, null, 2));
    console.log(JSON.stringify({ fallos: failures, notas: notes }, null, 2));
    console.log(failures.length ? `FAIL: ${failures.length} incidencias` : `PASS: ${PAGES.length} páginas × ${WIDTHS.length} anchos, enlaces, axe, teclado, filtros y formulario`);
    process.exit(failures.length ? 1 : 0);
})().catch(error => { console.error(error); process.exit(1); });
