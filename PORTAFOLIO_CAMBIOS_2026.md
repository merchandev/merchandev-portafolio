# Registro de cambios — Merchan.Dev 2026

Rama `feature/portafolio-2026-reestructuracion`. Versión de contenido `2026.10.2`.

## Punto de partida

- `main` contenía el tema publicado en merchan.dev más los recursos (`assets/`).
- Antes de esta rama, otra herramienta dejó sin commitear una auditoría documentada en `IMPLEMENTACION_AUDITORIA.md` (CPT de proyectos, formulario con persistencia, accesibilidad). Se versionó tal cual en un commit separado y sirvió de base; este documento la sustituye.
- Durante el trabajo se desplegó en producción un paquete intermedio (`mv.zip`, carpeta de tema `mv`). La migración 2026.10.2 está pensada para actualizar esa instalación sin duplicar contenido.

## Contenido y estructura

- Portada en ocho secciones con proyectos destacados justo después del hero.
- Catálogo `/proyectos/` con filtros por categoría y casos de estudio completos (`/proyectos/<slug>/`).
- Tres especialidades (`/servicio/desarrollo-software-productos-digitales/`, `/servicio/arquitectura-integraciones-automatizacion/`, `/servicio/infraestructura-seguridad-operacion/`) que agrupan los ocho servicios existentes; nuevos textos para los servicios sin editar.
- Biografía sin afirmaciones no verificables y tecnologías clasificadas por función y nivel.
- 20 proyectos con contenido verificado en sus repositorios y webs, seis destacados.
- «Espressivo Editorial» pasa a **CMS-Editorial** (marca blanca, v3.0.0): sin capturas ni enlaces de la publicación original; el marco de navegador muestra la URL del repositorio cuando no hay web pública.
- Plantilla genérica para categorías, etiquetas y búsquedas (antes mostraba una portada antigua).

## Sistema administrable

- CPT `proyecto`, taxonomía `tipo_proyecto` y campos con `register_post_meta`, galería, relaciones, columnas y fuente única de datos.
- Importador idempotente con huellas de los textos del tema, migraciones, informe en el escritorio y `wp merchandev importar`.
- Capturas automáticas cada 24 h si cambia la interfaz pública, con `wp merchandev capturas` y acción manual en el listado.

## Correcciones técnicas (revisión del punto 13 del encargo)

| Problema | Estado al revisar | Corrección |
|---|---|---|
| Campos del formulario distintos de los que esperaba PHP (`nombre`/`servicio`/`mensaje`) | Ya corregido en la base: el formulario usa `name`/`service`/`message` y PHP acepta ambos | Se mantiene y se prueba. |
| URL y permalinks de servicios | Los botones «Solicitar presupuesto» usaban `/contacto/?servicio=…`, que WordPress interpreta como el tipo `servicio`: **404 en producción** | Parámetro `?interes=<slug>` con preselección en el servidor; enlaces a `/servicio/<slug>/` y 301 desde `/servicios/<slug>/`. |
| Avisos por correo de leads | Ya enviaba `wp_mail` | Un error del transporte de correo ya no muestra un fallo al visitante; el estado del aviso queda en el lead. |
| Acción AJAX de consentimiento | Ya retirada (preferencia local) | Sin cambios. |
| Accesibilidad de navegación | Faltaban textos para lectores, iconos marcados como decorativos y foco en el pie | Iconos `aria-hidden`, avisos «se abre en una pestaña nueva», menú expandible con teclado, foco visible, pie sin JavaScript en línea. |
| Legibilidad y móvil | Barra móvil sin Inicio destacado y orden roto al añadir Proyectos | Orden por clases: Proyectos · Servicios · Inicio · Sobre mí · Contacto; contraste revisado con axe. |
| Nonces en páginas cacheadas (detectado) | LiteSpeed/CDN servía nonces caducados: búsqueda y formulario podían fallar | Búsqueda pública sin nonce; el formulario pide uno nuevo antes de enviar. |
| Importador (detectado en QA) | Duplicaba borradores desde WP-CLI y recreaba proyectos en la papelera | Búsqueda por `post_name__in` en todos los estados. |

## Rendimiento

- Fondo Canvas: líneas agrupadas por opacidad (un trazo por grupo), distancias al cuadrado, redimensionado con espera y densidad menor en equipos modestos. Mismo aspecto.
- Material Symbols cargado como subconjunto de los 7 iconos usados.
- Imágenes de proyectos en WebP/JPEG con carga diferida, salvo la portada del caso.

## Commits

1. `chore`: auditoría previa sin commitear.
2. `feat(proyectos)`: catálogo administrable e importador.
3. `feat(contenido)`: portada, catálogo, casos y servicios.
4. `style`: integración visual y fondo optimizado.
5. `fix(contacto)`: enlaces de servicios, nonce con caché y fallos de correo.
6. `test`: pruebas de contacto, importador y navegador.
7. `feat(capturas)`: CMS-Editorial, migración 2026.10.2 y capturas automáticas.
8. `docs`: documentación 2026.

## Cómo desplegar

1. Copia de seguridad de archivos y base de datos.
2. Sustituir los archivos de la carpeta del tema activo (en producción, `wp-content/themes/mv/`) por los de esta rama, sin `tests/` si se prefiere. No hace falta reactivar el tema.
3. Entrar al escritorio como administrador: la migración se aplica sola y muestra un informe.
4. Configurar el cron real del servidor (ver `PORTAFOLIO_ARQUITECTURA_2026.md`) y, opcionalmente, `AM_MICROLINK_API_KEY`.
5. Revisar **Proyectos**, purgar la caché de LiteSpeed y comprobar el formulario con un envío real.
