# Implementación de la auditoría — Merchan.Dev

13 de octubre de 2026. Tema 2.0.1. Rama `codex/auditoria-redesign`.

Alcance confirmado: mejorar información, jerarquía y funcionamiento conservando el estilo visual original. La propuesta editorial anterior queda sustituida por esta versión.

## Cambios

- Se conserva el fondo azul oscuro, Orbitron e Inter, paleta naranja/azul/amarillo, gradientes, auras, partículas, botones, tarjetas de cristal, logos y navegación lateral original. `main.css` permanece sin cambios; `portfolio.css` añade estilos para contenido y accesibilidad.
- Portada con propuesta más concreta y proyectos inmediatamente después del hero. Se conservan las ocho especialidades y todo el stack, complementados con método, trayectoria y canales de contacto.
- Navegación lateral con búsqueda segura y proyectos. Barra móvil original con proyectos y contacto visibles, teclado, enlace de salto y estado actual. GitHub e Inicio siguen en escritorio; GitHub también aparece en portada y pie.
- CPT `proyecto`, archivo y página individual; imagen destacada responsive y campos de problema, solución, rol, estado, evidencia, tecnologías y enlaces. Solo aparecen proyectos publicados.
- Preparación de cinco borradores: MeTransfers, Diario Mercantil, Guía Médica Monagas, Motores Morichal y Mínima Organics. Sin casos publicados, portada y archivo muestran cuatro reseñas basadas en el contenido anterior: MeTransfers, Motores Morichal, Grupo La Verdad de Monagas y Mínima Organics. Incluyen contexto y alcance desplegables. No se inventan capturas ni métricas.
- Contacto compartido y alternativa POST sin JavaScript: validación de correo, tamaños, consentimiento, honeypot y límite orientativo de cinco intentos por diez minutos mediante fingerprint salado de IP en transients. No sustituye protección ante ataques concurrentes.
- Leads privados, errores de inserción comprobados, aviso con `wp_mail()` al correo de administración y columna de estado de aviso. Un fallo de correo conserva el lead. «Aceptado» no acredita entrega al buzón.
- Permalinks nativos de servicios y redirección 301 de `/servicios/<slug>/` para servicios publicados existentes.
- Contenido visible sin JavaScript y movimiento reducido. Se conservan typewriter, auras, entradas y Canvas 2D con pausa cuando la pestaña está oculta. Sin dependencias GSAP/ScrollTrigger. Los contadores sin evidencia se sustituyen por un panel de áreas de trabajo; GitHub mantiene un acceso directo sin consultas externas al cargar.
- Preferencias locales de consentimiento con reapertura; retirada la llamada AJAX inexistente. No se instalan herramientas de tracking.
- Hooks locales `am:cta_click`, `am:contact_start`, `am:contact_success`, `am:contact_error`, `am:consent_change` para instrumentación futura. No transmiten mensajes del formulario.
- Un solo `main`, IDs únicos, datos estructurados Person/CreativeWork y biografías consolidadas.
- Creación de contenido movida a activación o acción administrativa protegida. Eliminada la rutina que sobrescribía contenido SEO. Blog sin filtro obligatorio de año y búsqueda con texto escapado.

## Instalación y verificación en WordPress

1. Respaldar archivos y base de datos e instalar primero en staging. El ZIP contiene solo el tema, sin Git, pruebas ni vistas de QA.
2. Activar el tema en una instalación nueva. Si ya está activo, usar **Preparar portafolio** en el aviso de administración. Registra rutas, conserva contenido existente y crea borradores ausentes. Revisar que una página anterior `/proyectos/` no compita con el archivo.
3. Completar los cinco borradores en **Proyectos** con información comprobada, capturas originales sanitizadas, permisos de divulgación, rol y estado. MeTransfers y Motores Morichal tienen referencias en la biografía existente; los demás requieren documentación adicional. Publicar después de revisar. El campo Orden controla la selección.
4. Revisar el destinatario en **Ajustes → Generales → Correo de administración**. Personalización disponible con `am_contact_recipient`. Configurar SMTP y comprobar recepción real; no añadir credenciales al repositorio.
5. Enviar desde portada y contacto, con y sin JavaScript. Verificar lead privado, estado de aviso y correo recibido. Probar fallo controlado de SMTP y persistencia.
6. Probar redirecciones de servicios, navegación, proyectos, blog, legales y CV con plugins reales. Revisar contenido SEO administrado, canonical y sitemap con el plugin SEO instalado.
7. Inventariar scripts/cookies de plugins: el tema no bloquea scripts inyectados por terceros. Integrar el gestor de consentimiento antes de añadir analítica y revisar privacidad contra el tratamiento real.
8. Medir Lighthouse/CWV y establecer una línea base de conversiones. No hay mediciones de producción en este cambio.

## Verificación local

`php tests/contact-test.php`: contratos aislados de validación, errores de guardado, límite y conservación ante fallo de correo. No confirma base de datos, permisos efectivos ni SMTP.

`php tests/render-preview.php`: plantillas reales con funciones WordPress sustituidas y consulta vacía. Se revisó la portada a 320, 390, 768 y 1440 px sin desbordamiento, navegación móvil a contacto/proyectos, detalles de casos, consentimiento, respuestas de formulario y contenido/POST sin JavaScript. AJAX usa fixtures. Las capturas adicionales con fuentes e iconos cargados muestran el estilo original recuperado.

PHP y JavaScript pasan sintaxis. Quedan pendientes las pruebas de WordPress, HTTP/SEO y entrega SMTP en staging. No se ha publicado en merchan.dev.
