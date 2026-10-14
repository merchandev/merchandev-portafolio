# Calidad y pruebas — Merchan.Dev 2026

Pruebas ejecutadas el 13 y 14 de octubre de 2026 en Windows 11 con PHP 8.4, Node 24, Chrome (Playwright) y un WordPress 7.1.3 local con SQLite y Yoast SEO, desechable y fuera del repositorio. No se probó contra el servidor de producción.

## Resultados

| Prueba | Resultado |
|---|---|
| Sintaxis PHP (`php -l`, 36 archivos) | Sin errores |
| Sintaxis JavaScript (`node --check`) | Sin errores |
| CSS (stylelint: propiedades, selectores, unidades, duplicados) | Sin errores |
| `tests/contact-test.php` | 16 escenarios del formulario + 14 de preselección: PASS |
| `tests/wp-import-test.php` | Importación, revisiones, idempotencia, ediciones, borradores, papelera, textos del tema y migración: PASS |
| `tests/wp-capture-test.php` | Huella, sustitución de escritorio y móvil, mantenimiento, antibots, desactivación y tarea diaria: PASS (15 comprobaciones) |
| `tests/browser-qa.cjs` | 17 páginas × 5 anchos (1440, 1024, 768, 390 y 320 px), 54 enlaces internos, axe-core, teclado, filtros, movimiento reducido y envío real del formulario: PASS |
| Migración real de producción simulada | PASS (ver abajo) |
| Capturas automáticas contra las webs reales | PASS (ver abajo) |

## Migración simulada

1. WordPress limpio con el tema anterior publicado (crea los 8 servicios).
2. Tema intermedio desplegado en producción (`mv.zip`) con el proyecto «Espressivo Editorial» y sus capturas: 20 proyectos importados.
3. Dos ediciones del administrador simuladas.
4. Archivos sustituidos por esta versión sin reactivar el tema y visita al escritorio.

Resultado: migración automática a 2026.10.2; el mismo registro pasa a CMS-Editorial con sus capturas; `/proyectos/espressivo-editorial/` redirige con 301; ediciones conservadas; 20 proyectos sin duplicados; capturas antiguas borradas y ninguna mención a la publicación original en los datos. El caso «CMS-Editorial ya creado» envía el duplicado antiguo a la papelera y redirige su URL.

## Capturas automáticas contra las webs reales

| Proyecto | 1.ª revisión | 2.ª revisión |
|---|---|---|
| Merchan.Dev | Captura inicial (no tenía imagen) | Sin cambios |
| Motores Morichal | Huella registrada | Sin cambios |
| Guía Médica Monagas | Huella registrada | Sin cambios |
| MeTransfers Barcelona (bloquea a servidores con HTTP 202) | Huella registrada con el navegador remoto | Sin cambios |
| Diario Mercantil (en mantenimiento) | Omitida | Omitida |

Con un cambio de huella simulado, Motores Morichal recibió capturas nuevas de escritorio (1280×800) y móvil (780×1688) en la misma posición, y las anteriores quedaron en la biblioteca.

## Accesibilidad y diseño

- axe-core (WCAG 2 A/AA) en todas las páginas de la batería: sin incidencias graves ni críticas.
- Revisión visual por secciones a 1440 px y comprobación automática a 1440, 1024, 768, 390 y 320 px: sin desbordamiento horizontal, un `<main>` y un `<h1>` por página, imágenes con texto alternativo, IDs únicos.
- Fondo de partículas, menú lateral, barra móvil, efecto de escritura y animaciones verificados; con movimiento reducido el contenido es visible.

## Fuera del alcance de estas pruebas

- Entrega real de correo desde el servidor (SMTP, SPF, DKIM y DMARC): el envío se verificó hasta `wp_mail` con un capturador local.
- WP-Cron en producción con LiteSpeed/CDN: requiere el cron real del servidor.
- Lighthouse y Core Web Vitals con datos de campo.
- Comportamiento con los plugins exactos de producción, salvo Yoast.
- Envío del formulario a través de la caché LiteSpeed real (se probó el mecanismo de renovación del nonce).

## Cómo repetir las pruebas

```bash
php tests/contact-test.php
AM_TEST_ENV=1 wp eval-file tests/wp-import-test.php
AM_TEST_ENV=1 wp eval-file tests/wp-capture-test.php
BASE_URL=http://127.0.0.1:8890 PLAYWRIGHT_MODULE=/ruta/a/playwright AXE_PATH=/ruta/a/axe.min.js node tests/browser-qa.cjs
```

Las pruebas `wp-*` solo deben ejecutarse en una instalación de pruebas: crean y borran contenido.
