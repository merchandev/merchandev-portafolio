# Arquitectura del tema — Merchan.Dev 2026

Tema WordPress clásico (PHP, CSS y JavaScript nativos, sin dependencias de build). Conserva la identidad visual original y añade un sistema de contenido administrable para proyectos, especialidades y casos de estudio.

## Qué se mantiene intacto

- Fondo Canvas 2D de partículas y nebulosas (`assets/js/bg-animation.js`): mismo aspecto, solo optimizado.
- Paleta naranja/amarillo/azul, Orbitron + Inter, tarjetas de cristal, auras y gradientes (`assets/css/main.css`).
- Menú lateral expandible con logo animado y búsqueda; barra inferior en móvil con Inicio destacado.
- Efecto de escritura del nombre, animaciones de entrada y transiciones.
- URL existentes: páginas, servicios (`/servicio/<slug>/`), blog, legales y CV.

## Estructura

| Archivo | Responsabilidad |
|---|---|
| `functions.php` | Carga de recursos, CPT `servicio` y `lead`, búsqueda AJAX, blog por año, carga de los módulos `inc/`. |
| `inc/projects.php` | CPT `proyecto`, taxonomía `tipo_proyecto`, campos del caso (`register_post_meta`), formulario de edición, columnas, consultas y **fuente única de datos** (`am_project_data()`). |
| `inc/specialties.php` | Las tres especialidades, su relación con los servicios existentes y proyectos de evidencia; redirección 301 de `/servicios/<slug>/`. |
| `inc/capabilities.php` | Tecnologías por función y nivel (principal, aplicada, complementaria). |
| `inc/seed.php` | Contenido inicial, importador idempotente, migraciones de versiones anteriores, aviso de administración y `wp merchandev importar`. |
| `inc/captures.php` | Actualización diaria de capturas de los proyectos con web pública y `wp merchandev capturas`. |
| `inc/contact.php` | Formulario de contacto: validación, límite de envíos, lead privado, aviso por correo, nonce renovable y envío sin JavaScript. |
| `inc/seo.php` | Metadatos y JSON-LD solo si no hay plugin SEO; con Yoast añade el nodo `CreativeWork` del proyecto a su grafo. |
| `template-parts/` | Tarjeta de proyecto (variantes `feature`, `standard`, `compact`), filtros, especialidades, capacidades, proceso y formulario. |
| `front-page.php` | Portada en ocho secciones (ver `PORTAFOLIO_CONTENIDO_2026.md`). |
| `archive-proyecto.php`, `taxonomy-tipo_proyecto.php` | Catálogo `/proyectos/` y categorías `/tipo-de-proyecto/<categoría>/`. |
| `single-proyecto.php` | Caso de estudio con 15 apartados y ficha técnica. |
| `page-servicios.php`, `single-servicio.php` | Servicios organizados por especialidad, con evidencias y contacto. |
| `assets/css/portfolio.css` | Componentes nuevos con los tokens de `main.css`, accesibilidad y responsive. |
| `assets/js/main.js` | Animaciones de entrada, navegación activa, búsqueda, formulario y consentimiento. |

## Modelo de datos de proyectos

- **Tipo de contenido** `proyecto`: archivo en `/proyectos/`, ficha en `/proyectos/<slug>/`. Título, extracto (descripción breve de las tarjetas), imagen destacada (portada), editor (notas adicionales) y orden.
- **Taxonomía** `tipo_proyecto`: Plataformas y SaaS, Sistemas empresariales, Web y comercio digital, Aplicaciones móviles, Infraestructura y multimedia, Plugins e integraciones, Laboratorio y experimentación. Un proyecto tiene una categoría principal (campo propio) y puede tener varias.
- **Campos** (`_am_*`): destacado, categoría principal, estado, sector, período, participación, contexto, problema, objetivo, arquitectura, funcionalidades, desafíos, seguridad y operación, resultados, tecnologías, especialidades, URL pública, repositorio, otros enlaces, galería, captura automática y relacionados.
- **Estados**: En producción, MVP funcional, Piloto/beta, En desarrollo, Entregado, Laboratorio. Cada uno tiene una explicación visible en el caso.
- Sanitización por tipo de campo, nonce y `current_user_can( 'edit_post' )` al guardar; todo se escapa al mostrarse.

## Importador y migraciones (`inc/seed.php`)

Se ejecuta al activar el tema, con el botón **Importar contenido del portafolio**, con `wp merchandev importar` y, en instalaciones que ya importaron una versión anterior, automáticamente en la siguiente visita de un administrador (como máximo un intento por hora si hay errores).

- Busca por slug en cualquier estado, incluida la papelera: no duplica ni recrea lo que descartaste.
- Campos vacíos: se completan. Campos que conservan el texto que escribió el tema (huella en `_am_seed_snapshot`): se actualizan a la versión nueva. Cualquier otro valor es una edición tuya y se conserva.
- Servicios: los ocho textos originales solo se sustituyen si siguen sin editar; el texto anterior queda en **Revisiones**.
- Capturas: se suben una sola vez a la biblioteca de medios.
- Versión 2026.10.3: portadas de Diario Mercantil y MeTransfers SaaS, y texto de resultados del SaaS actualizado (página del producto publicada).
- Migración 2026.10.2: el proyecto «Espressivo Editorial» importado en 2026.10.1 pasa a **CMS-Editorial** en el mismo registro (la URL anterior redirige con 301), se retiran sus capturas antiguas y se borran si ya no se usan.

## Capturas automáticas (`inc/captures.php`)

Tarea diaria de WP-Cron (`am_daily_project_captures`) para los proyectos con URL pública y la casilla **Actualizar capturas automáticamente**:

1. Calcula una huella de la interfaz: contenido de las hojas de estilo y URL versionadas de los scripts del propio dominio. Las noticias, fechas o carruseles no la cambian; un rediseño o una actualización del tema sí.
2. Sin cambios: conserva la captura. Con cambios (o si el proyecto aún no tiene imagen): toma capturas nuevas de escritorio (1280×800) y móvil (390×844 a 2×) con Microlink y sustituyen a las anteriores en la portada y la galería; las anteriores quedan en la biblioteca.
3. No captura páginas de mantenimiento ni verificaciones antibots (comprobado en el HTML y en la página renderizada). Si la web bloquea a los servidores, lee sus recursos con el navegador remoto de Microlink.
4. Cada proyecto se procesa en una tarea separada (y la móvil en otra) para no superar el tiempo máximo de PHP en hosting compartido.
5. Tras sustituir una captura (y tras una importación o migración con cambios) el tema vacía la caché de LiteSpeed (`litespeed_purge_all`) para que la portada no muestre la versión anterior. La caché del CDN de Hostinger, si no la purga LiteSpeed, se vacía desde hPanel.

En el listado de proyectos hay una acción **Actualizar captura ahora** y una columna con el último resultado.

## Configuración en producción

- **Cron real recomendado**: con LiteSpeed/CDN muchas visitas no ejecutan PHP, así que WP-Cron puede retrasarse. En Hostinger, crear una tarea cron cada hora: `wget -q -O - https://merchan.dev/wp-cron.php?doing_wp_cron >/dev/null 2>&1`.
- **Microlink**: funciona sin clave con una cuota gratuita diaria limitada. Para más margen: `define( 'AM_MICROLINK_API_KEY', '…' );` en `wp-config.php` (nunca en el repositorio).
- **Correo**: el aviso de cada solicitud va al correo de administración (filtro `am_contact_recipient`). Configurar SMTP y comprobar entrega real.
- **SEO**: con Yoast activo el tema no imprime títulos ni descripciones. Recomendado: en Yoast, representar el sitio como *Persona* (Arturo Merchán).

## Decisiones

- Sin ACF ni constructores: campos nativos con `register_post_meta` y un formulario propio.
- Filtros del catálogo como enlaces a categorías: funcionan sin JavaScript y son indexables.
- La búsqueda y el filtro del blog no exigen nonce (solo leen contenido público); el formulario de contacto sí, renovado antes de enviar porque la caché puede servir uno caducado.
- `?interes=` en lugar de `?servicio=` para preseleccionar el servicio: `servicio` es la variable de consulta del tipo de contenido y llevaba a un 404.
- Imágenes de proyectos en WebP/JPEG reales; las fotografías de terceros se difuminan antes de publicarse.
