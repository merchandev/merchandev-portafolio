# Inventario y selección de proyectos — Merchan.Dev 2026

Revisión del 13–14 de octubre de 2026 de los 25 repositorios de `github.com/merchandev` (23 públicos y 2 privados): estructura, dependencias, documentación, pruebas, CI y despliegue, más la comprobación de las webs públicas. Los repositorios ajenos al portafolio se usaron solo como evidencia y no se modificaron.

## Destacados (casos de estudio completos)

| Orden | Proyecto | Estado publicado | Evidencia |
|---|---|---|---|
| 1 | **MeTransfers Barcelona** (`metransfers.es`) | En producción | Web pública metransfers.es; más de 300 commits; PHPUnit, PHPStan, WPCS, Playwright y puerta de calidad en GitHub Actions (último run de `main` correcto, 02/10/2026). |
| 2 | **Guía Médica Monagas** (`guiamedicamonagas` + `GUIAMEDICA_APP`) | MVP funcional | Web pública guiamedicamonagas.com; README «MVP funcional»; CI, Gitleaks, ZAP y chequeo de disponibilidad en GitHub Actions (CI correcto 07/10/2026; un run de seguridad falló el 12/10/2026). App Android solo como build de pruebas, no publicada en tiendas. |
| 3 | **Diario Mercantil de Venezuela** | En producción | Aplicación desplegada en diariomercantil.com (en «mantenimiento programado» durante la revisión); más de 470 commits; CI correcto (12/10/2026). Sin captura: pendiente hasta que la web salga del mantenimiento (la actualización automática la tomará). |
| 4 | **MeTransfers SaaS** (`SAAS--MT`) | En desarrollo | README: arquitectura multiempresa «en desarrollo»; los últimos runs de CI fallan; sin despliegue público. Sin captura. |
| 5 | **Motores Morichal · Toyota Monagas** | En producción | Web pública mmorichal.com; QA, build verificado y empaquetado en GitHub Actions (correcto, 28/09/2026). |
| 6 | **CMS-Editorial** (`espressivo-editorial`) | En producción | Versión 3.0.0 de marca blanca publicada el 14/10/2026; la primera instalación funciona en el portal de un diario regional. Capturas de una instalación local de prueba con contenido e imágenes de ejemplo. |

Guía Médica web y app se presentan como un solo caso. MeTransfers web y SaaS son casos separados con estados distintos.

## Fichas breves

| Proyecto | Categoría | Estado |
|---|---|---|
| Infraestructura de streaming de radio y TV (`rtmp-streaming-server_TV`, `radio-streaming-server`) | Infraestructura y multimedia | Entregado |
| Control de asistencia offline-first (`asistencia`) | Aplicaciones móviles | En desarrollo |
| MiCatálogo VE (`micatalogove`) | Plataformas y SaaS | En desarrollo |
| Rotec Corporativo (`rotec`) | Web y comercio digital | Entregado |
| Mínima Organics (`minima-organics-theme`) | Web y comercio digital | Entregado |
| Reservas con QR para un evento en cine (`Evento-Conexi-n-Publicitaria`) | Plugins e integraciones | Entregado |
| Forja de Diosas · Foro (`Plugin-forjadediosas-FORO`) | Plugins e integraciones | Entregado |
| BIOTSEPT (`biotsept-theme`), Zyrus (`zyrus-theme`), tema veterinario (`vet`), Divine (`Divine---Tema`) | Web y comercio digital | Entregado |
| Luxury Bento Gallery | Plugins e integraciones | Laboratorio |
| TrafficLab GA4 (`botito`) | Laboratorio | Laboratorio (sesiones artificiales, no tráfico real) |
| Merchan.Dev (`merchandev-portafolio`) | Laboratorio | En producción |

## No publicados

- **Dos repositorios privados**: se usaron solo para identificar experiencia. No se publica su nombre, código, capturas, clientes ni detalles sin autorización.
- **`server-e`**: excluido del portafolio por el riesgo de seguridad descrito abajo.

## Riesgo de seguridad: `server-e`

El repositorio público `server-e` contiene en `README.md` (sección de credenciales) y en su configuración de Icecast valores con formato de credenciales. No se reproducen aquí. Deben tratarse como comprometidos aunque no se haya verificado si siguen activos.

1. Rotar las contraseñas de fuente, relay y administración en el servidor **y** en los encoders que las usan.
2. Sacar los secretos del repositorio (variables de entorno o plantilla de despliegue).
3. Limpiar el historial de Git (borrar el archivo en `main` no revoca un secreto expuesto) y revisar forks y clones.
4. Revisar puertos expuestos, acceso administrativo y registros de acceso.
5. Añadir escaneo de secretos (Gitleaks) en CI de todos los repositorios.

`radio-streaming-server` documenta una contraseña de ejemplo para el origen: si se reutilizó en producción, también debe cambiarse.

## Capturas

| Proyecto | Origen | Tratamiento |
|---|---|---|
| MeTransfers | metransfers.es (escritorio) | Aviso de cookies rechazado antes de capturar |
| Guía Médica | guiamedicamonagas.com (escritorio y móvil) | Recortado el aviso de cookies |
| Motores Morichal | mmorichal.com (escritorio y móvil) | — |
| CMS-Editorial | Instalación local de prueba (portada, ajustes y móvil) | Contenido e imágenes de ejemplo, identidad neutra |

Las capturas de MeTransfers, Guía Médica, Motores Morichal, Diario Mercantil y Merchan.Dev se actualizan solas cada 24 h cuando cambia su interfaz. Conviene confirmar con cada cliente el permiso para mostrar su web en el portafolio.

## Evidencias pendientes

- Capturas de paneles internos y flujos (con datos anonimizados) de MeTransfers, Guía Médica, Diario Mercantil y MeTransfers SaaS.
- Métricas de negocio solo con fuente y permiso de publicación.
- Estado de publicación de la app de Guía Médica en tiendas.
- Corrección de los runs fallidos de CI en MeTransfers SaaS y del run de seguridad de Guía Médica.
