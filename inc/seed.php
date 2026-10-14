<?php
/**
 * Contenido inicial del portafolio 2026 e importador idempotente.
 *
 * Reglas del importador:
 * - Crea lo que no existe (por slug) y nunca duplica registros.
 * - En registros existentes solo completa campos vacíos; no cambia títulos, estados ni contenido editado.
 * - Los textos de servicios solo se sustituyen si siguen siendo exactamente los generados por el tema.
 * - Las capturas se suben una vez a la biblioteca de medios y se reutilizan.
 * Todo el contenido procede de repositorios y sitios públicos revisados el 13/10/2026.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

const AM_SEED_VERSION = '2026.10.2';

function am_seed_categories() {
    return array(
        'plataformas-saas' => array( 'Plataformas y SaaS', 'Productos digitales con usuarios, roles, pagos y paneles de gestión.' ),
        'sistemas-empresariales' => array( 'Sistemas empresariales', 'Sistemas que digitalizan procesos centrales: reservas, documentos, publicación y operación.' ),
        'web-comercio' => array( 'Web y comercio digital', 'Temas WordPress y Shopify a medida, catálogos y sitios comerciales.' ),
        'aplicaciones-moviles' => array( 'Aplicaciones móviles', 'Apps nativas con Expo/React Native y aplicaciones web progresivas.' ),
        'infraestructura-multimedia' => array( 'Infraestructura y multimedia', 'Servidores, contenedores y streaming de audio y video.' ),
        'plugins-integraciones' => array( 'Plugins e integraciones', 'Módulos de WordPress y conexiones con servicios externos.' ),
        'laboratorio' => array( 'Laboratorio y experimentación', 'Prototipos, herramientas internas y experimentos técnicos.' ),
    );
}

function am_seed_projects() {
    $gh = 'https://github.com/merchandev/';
    return array(
        array(
            'slug' => 'metransfers', 'title' => 'MeTransfers Barcelona', 'order' => 1, 'featured' => true, 'status' => 'produccion',
            'primary' => 'sistemas-empresariales', 'categories' => array( 'sistemas-empresariales', 'web-comercio' ),
            'specialties' => array( 'desarrollo', 'arquitectura' ),
            'sector' => 'Movilidad y turismo · Barcelona', 'period' => 'Dic. 2025 – actualidad',
            'excerpt' => 'Plataforma de reservas de traslados privados con precio calculado en el servidor, pago con Redsys, portal privado para hoteles y herramientas de operación.',
            'role' => 'Programador Full-Stack y autor del repositorio: arquitectura de la aplicación, motor de reservas y tarifas, integración de pagos, portal de hoteles, migraciones de datos y calidad automatizada.',
            'context' => 'MeTransfers ofrece traslados privados desde y hacia el aeropuerto, el puerto y las estaciones de Barcelona, además de rutas a la Costa Brava, Girona o PortAventura. La web es su principal canal de venta y también la herramienta con la que coordina reservas y hoteles colaboradores.',
            'problem' => 'El precio de un traslado depende de la distancia, el vehículo y la capacidad. Si se calcula en el navegador puede manipularse, y si se calcula a mano frena la operación. Además, los hoteles necesitaban registrar y consultar sus reservas sin entrar al panel general de WordPress.',
            'objective' => 'Una sola plataforma que cotice de forma fiable, cobre en línea, separe el acceso de cada hotel y deje constancia de cada operación.',
            'architecture' => 'Tema WordPress que carga una aplicación PHP modular (autocarga PSR-4) organizada por dominios: reservas, tarifas, pagos Redsys, portal de hoteles, notificaciones, idiomas, SEO y analítica. Las migraciones de base de datos son versionadas y reanudables, y un outbox separa las notificaciones y eventos del flujo de reserva. Un adaptador mantiene compatible el sistema de reservas original mientras su lógica se traslada a los módulos nuevos.',
            'features' => "Motor de reservas con rutas, vehículos, capacidad y tarifas.\nDistancia, cotización y precio validados en el servidor con Google Maps Platform.\nCaché propia de direcciones y rutas para evitar consultas repetidas a Google, con caducidad acorde a sus condiciones de uso.\nPago con Redsys y recibos generados por el servidor.\nPortal privado /hoteles/ aislado por hotel: usuarios, reservas, clientes y estadísticas.\nImportación idempotente de reservas desde Excel o CSV y exportación .xlsx por hotel.\nCódigos QR para atribuir reservas a cada hotel colaborador.\nWeb pública multilingüe con páginas de rutas, destinos y tours.\nAuditoría de exportaciones y control reversible del acceso de cada responsable.",
            'challenges' => "Trasladar la autoridad del precio al servidor sin interrumpir las reservas: el flujo original sigue funcionando a través de un adaptador mientras se migra.\nAislar los datos de cada hotel dentro de WordPress, con permisos por responsable y pruebas específicas de acceso.\nActivar el cobro real con controles: el modo Live de Redsys queda bloqueado mientras falten las comprobaciones operativas registradas en la configuración.",
            'security' => 'Credenciales fuera de Git (constantes de wp-config.php), precio y capacidad calculados en el servidor, permisos por hotel y registro de auditoría. El repositorio incluye PHPUnit, PHPStan, WordPress Coding Standards, ESLint y Playwright, y GitHub Actions ejecuta una puerta de calidad sobre cada cambio.',
            'results' => 'Web en producción en metransfers.es. Repositorio público con el historial completo del proyecto (más de 300 commits). No publico cifras de negocio del cliente.',
            'stack' => 'PHP 8.2, WordPress, MySQL, JavaScript, Redsys, Google Maps Platform, PHPUnit, PHPStan, Playwright, GitHub Actions',
            'capture_auto' => true, 'demo' => 'https://metransfers.es/', 'repository' => $gh . 'metransfers.es',
            'images' => array( array( 'file' => 'metransfers-portada.webp', 'alt' => 'Portada de metransfers.es con el formulario «Calcula tu traslado» (origen, destino, fecha y hora)' ) ),
            'related' => array( 'metransfers-saas' ),
        ),
        array(
            'slug' => 'guia-medica-monagas', 'title' => 'Guía Médica Monagas', 'order' => 2, 'featured' => true, 'status' => 'mvp',
            'primary' => 'plataformas-saas', 'categories' => array( 'plataformas-saas', 'aplicaciones-moviles' ),
            'specialties' => array( 'desarrollo', 'arquitectura', 'infraestructura' ),
            'sector' => 'Salud · Directorio profesional', 'period' => 'Sept. 2026 – actualidad',
            'excerpt' => 'Directorio médico del estado Monagas con verificación documental de profesionales, planes y pagos, panel de administración y una app Android e iOS conectada a la misma API.',
            'role' => 'Arquitectura y desarrollo completo: API, web, base de datos, app móvil, configuración de despliegue con Docker y flujos de integración continua.',
            'context' => 'Encontrar un médico de confianza en Monagas dependía de recomendaciones y redes sociales. La plataforma reúne médicos, especialidades, farmacias, laboratorios y clínicas en un directorio público por municipio.',
            'problem' => 'Un directorio de salud necesita confianza: comprobar que cada profesional es quien dice ser, proteger los datos de los pacientes y permitir que los profesionales gestionen su perfil y su plan sin trabajo manual constante.',
            'objective' => 'Perfiles verificados, áreas privadas por rol, cobro de planes en bolívares con la tasa del BCV y una app móvil que siga funcionando con mala conectividad.',
            'architecture' => 'Monorepo con frontend Next.js (App Router, renderizado en servidor) y API NestJS sobre Fastify. PostgreSQL con Prisma, Redis, Meilisearch para la búsqueda, almacenamiento compatible con S3 (MinIO) y Caddy como proxy con HTTPS, todo definido en Docker Compose. La app Expo/React Native es un cliente de la misma API y comparte el canal en tiempo real (Socket.IO): no tiene base de datos propia.',
            'features' => "Directorio público de médicos, especialidades y organizaciones por municipio.\nRegistro, verificación de correo, recuperación de contraseña y autenticación en dos pasos.\nVerificación documental de profesionales con cola de revisión administrativa.\nPlanes por nivel con pagos por Pago Móvil revisados por administración y tasa BCV sincronizable.\nAgenda, citas y notificaciones; permisos del paciente sobre sus datos de salud.\nPanel de administración con analítica, auditoría, SEO y gestión de cookies.\nApp móvil para Android e iOS (Expo) con modo sin conexión y datos locales cifrados.",
            'challenges' => "Compartir una API entre la web y la app sin duplicar reglas: el contrato de endpoints se documenta en la plataforma y la API rechaza campos desconocidos.\nFuncionar sin conexión en el móvil: los cambios quedan en una cola ordenada, se reintentan con esperas crecientes y los datos se guardan cifrados (AES-GCM) con la clave en el almacén seguro del sistema.\nExponer el mínimo de datos de salud: sin conexión solo se guarda la ficha básica del paciente, y los permisos sobre datos clínicos pueden revocarse.",
            'security' => 'JWT con refresh tokens, contraseñas con Argon2, autenticación en dos pasos, cifrado de campos sensibles con AES-256-GCM y rotación de claves, auditoría, límites de peticiones y cabeceras de seguridad. GitHub Actions ejecuta integración continua, búsqueda de secretos con Gitleaks, auditoría de dependencias, análisis con OWASP ZAP y una comprobación periódica de disponibilidad. El repositorio incluye scripts de backup y de prueba de restauración. La plataforma no es un dispositivo médico ni cuenta con certificaciones.',
            'results' => 'Web pública en guiamedicamonagas.com. La app Android está generada como build de pruebas con firma de desarrollo y no está publicada en las tiendas.',
            'stack' => 'Next.js, NestJS, TypeScript, PostgreSQL, Prisma, React Native, Expo, Redis, Meilisearch, Socket.IO, Docker, Caddy, Vitest, Playwright',
            'capture_auto' => true, 'demo' => 'https://guiamedicamonagas.com/', 'repository' => $gh . 'guiamedicamonagas',
            'links' => 'App móvil (repositorio) | ' . $gh . 'GUIAMEDICA_APP',
            'images' => array(
                array( 'file' => 'guia-medica-portada.webp', 'alt' => 'Portada de guiamedicamonagas.com: «Encuentra un médico de confianza en Monagas» con buscador por especialidad y municipio' ),
                array( 'file' => 'guia-medica-movil.webp', 'alt' => 'Vista móvil de la portada de Guía Médica Monagas' ),
            ),
            'related' => array( 'diario-mercantil' ),
        ),
        array(
            'slug' => 'diario-mercantil', 'title' => 'Diario Mercantil de Venezuela', 'order' => 3, 'featured' => true, 'status' => 'produccion',
            'primary' => 'sistemas-empresariales', 'categories' => array( 'sistemas-empresariales', 'plataformas-saas' ),
            'specialties' => array( 'desarrollo', 'arquitectura', 'infraestructura' ),
            'sector' => 'Publicaciones legales y mercantiles', 'period' => 'Ene. 2026 – actualidad',
            'excerpt' => 'Sistema para recibir, validar, cobrar, compaginar y publicar avisos legales, con ediciones digitales y verificación pública de cada publicación mediante código QR.',
            'role' => 'Desarrollo Full-Stack y operación: backend PHP, frontend React, base de datos, despliegue en VPS con Docker y mantenimiento.',
            'context' => 'El Diario Mercantil publica documentos legales y avisos mercantiles. Cada publicación implica recibir documentos, calcular la tarifa, verificar el pago, maquetar la edición y permitir su consulta posterior.',
            'problem' => 'Con un proceso manual, cada solicitud pasa por correo, revisión de comprobantes, cálculo de folios e impuestos y montaje de la edición, y después es difícil demostrar públicamente que un aviso fue publicado.',
            'objective' => 'Digitalizar el ciclo completo con estados claros, montos calculados por el sistema, ediciones compaginadas y una forma pública de verificar cada publicación.',
            'architecture' => 'API PHP con enrutador propio, repositorios PDO sobre MySQL y migraciones versionadas; frontend React + TypeScript con Vite. Procesos en segundo plano para la cola de tareas y para el envío de correo desde un outbox. Despliegue en VPS con Docker Compose detrás de Traefik o Caddy con TLS, y scripts de healthcheck, readiness y actualización.',
            'features' => "Flujo de solicitudes: borrador, enviada, verificada, en edición y publicada.\nCálculo de montos por folios, tasa BCV e IVA, con obtención automática de la tasa y fuente alternativa.\nCarga de comprobantes y documentos en PDF; órdenes de servicio y recibos generados en PDF.\nCompaginación y publicación de la edición diaria, con visor PDF y formato revista.\nVerificación pública por código QR y rutas públicas para cada publicación y edición.\nRoles SuperAdmin, Administrador y Solicitante, con suspensión y restauración de cuentas.\nDirectorio de abogados con aprobación administrativa y gestor de páginas y noticias.\nRegistro de auditoría de las acciones críticas.",
            'challenges' => "Hacer verificable una publicación sin exponer el expediente: la consulta pública muestra la publicación, no los documentos privados de la solicitud.\nGenerar ediciones consistentes a partir de muchos documentos independientes, con herramientas de auditoría y reparación de ediciones.\nEnviar correo sin bloquear la API: los mensajes se guardan en un outbox y los procesa un worker independiente.",
            'security' => 'Roles jerárquicos con política de permisos, sesiones con expiración, política de contraseñas, auditoría con registro de IP, configuración por variables de entorno y scripts de mantenimiento separados del despliegue normal. GitHub Actions ejecuta la integración continua del repositorio.',
            'results' => 'Sistema desplegado en diariomercantil.com. Repositorio público con más de 470 commits.',
            'stack' => 'PHP 8, MySQL, React, TypeScript, Vite, Tailwind CSS, PHPMailer, FPDF, Docker, Traefik, Caddy, PHPUnit',
            'capture_auto' => true, 'demo' => 'https://diariomercantil.com/', 'repository' => $gh . 'Diario-Mercantil',
            'images' => array(),
            'related' => array( 'cms-editorial', 'guia-medica-monagas' ),
        ),
        array(
            'slug' => 'metransfers-saas', 'title' => 'MeTransfers SaaS', 'order' => 4, 'featured' => true, 'status' => 'desarrollo',
            'primary' => 'plataformas-saas', 'categories' => array( 'plataformas-saas', 'sistemas-empresariales' ),
            'specialties' => array( 'desarrollo', 'arquitectura', 'infraestructura' ),
            'sector' => 'Software para empresas de traslados (B2B2C)', 'period' => 'Jun. 2026 – actualidad',
            'excerpt' => 'Producto SaaS para operadores de traslados: reservas, flota, conductores, tarifas, facturación, portales para hoteles y agencias, e infraestructura de correo propia. La arquitectura multiempresa está en desarrollo.',
            'role' => 'Producto propio: diseño de la arquitectura y del modelo de datos, y desarrollo completo del repositorio.',
            'context' => 'Después de construir la plataforma de MeTransfers, la misma necesidad aparece en otras empresas de traslados, VTC y chóferes privados: gestionar reservas, flota, conductores y colaboradores sin herramientas dispersas.',
            'problem' => 'Las empresas pequeñas del sector suelen combinar formularios web, hojas de cálculo y mensajería. Así es difícil asignar conductores, controlar cobros y ofrecer a hoteles y agencias un canal propio.',
            'objective' => 'Un producto configurable por empresa que reúna reservas, operación, facturación, comunicaciones y portales para colaboradores, con roles diferenciados.',
            'architecture' => 'Aplicación Next.js (App Router) en TypeScript con la lógica de negocio separada por dominios (reservas, tarifas, pagos, conductores, vehículos, facturas, B2B, notificaciones, informes). PostgreSQL con Prisma, autenticación con JWT y roles. Un worker independiente procesa la cola de correo, y la configuración incluye un servidor Postfix con OpenDKIM. Despliegue definido con Docker Compose.',
            'features' => "Roles para superadministración, empresa, operador, conductor, hotel, agencia y cliente.\nMotor de reservas con tarifas por distancia o zona, suplementos y selección de vehículo.\nGestión de conductores, disponibilidad y flota.\nPagos con Redsys, facturas y recibos en PDF.\nPortales para hoteles y agencias con reservas por referido y códigos QR.\nCola de correo con reintentos, lectura de rebotes, lista de supresión y bajas.\nCampañas, listas y segmentos de contactos con métricas de envío.\nInformes y panel de analítica.",
            'challenges' => "Aislar los datos de cada empresa en todas las lecturas, escrituras y tareas en segundo plano. El modelo ya asocia las entidades a una empresa, pero el aislamiento multiempresa sigue en desarrollo y debe validarse con pruebas.\nDiseñar un sistema de correo que distinga entre mensaje en cola, aceptado por el servidor, rebotado o dado de baja.\nMantener las reglas de negocio fuera de las rutas de la interfaz para reutilizarlas desde la API y los portales.",
            'security' => 'JWT (jose), contraseñas con bcrypt, validación con Zod, limitación de peticiones, registro de auditoría y sanitización de HTML. La integración continua (lint, tipos, validación de Prisma, build y pruebas con Vitest y Playwright) está configurada en GitHub Actions y se revisa como parte del desarrollo.',
            'results' => 'Sin despliegue público ni clientes que pueda citar todavía. Lo descrito corresponde al código del repositorio, no a una operación comercial verificada.',
            'stack' => 'Next.js, React, TypeScript, PostgreSQL, Prisma, Tailwind CSS, Nodemailer, React Email, Redsys, Docker, Vitest, Playwright',
            'demo' => '', 'repository' => $gh . 'SAAS--MT',
            'images' => array(),
            'related' => array( 'metransfers' ),
        ),
        array(
            'slug' => 'motores-morichal', 'title' => 'Motores Morichal · Toyota Monagas', 'order' => 5, 'featured' => true, 'status' => 'produccion',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ),
            'specialties' => array( 'desarrollo', 'infraestructura' ),
            'sector' => 'Automoción · Concesionario en Maturín', 'period' => 'Ago. 2025 – actualidad',
            'excerpt' => 'Tema WordPress a medida para un concesionario Toyota: inventario de vehículos nuevos y usados, formularios de atención y un proceso de build, control de calidad y empaquetado reproducible.',
            'role' => 'Desarrollador web: tema completo, tipos de contenido para el inventario, sistema de contacto, herramientas de administración y proceso de calidad.',
            'context' => 'Motores Morichal es el concesionario Toyota de Maturín. Su web presenta modelos nuevos, vehículos usados, accesorios y canales de atención al cliente.',
            'problem' => 'El inventario cambia con frecuencia y el equipo comercial necesita actualizarlo sin depender de un desarrollador. Además, cada actualización del tema debía poder instalarse sin riesgo de romper el sitio en producción.',
            'objective' => 'Un tema administrable desde WordPress, rápido en móvil y con un proceso de publicación verificable.',
            'architecture' => 'Tema WordPress con módulos PHP separados (tipos de contenido, metaboxes, AJAX, sistema de contacto, herramientas de administración) y frontend compilado con Vite, Tailwind CSS y TypeScript. El build genera archivos con nombres estables y el empaquetado produce un ZIP reproducible con checksum y manifiesto.',
            'features' => "Tipos de contenido para vehículos nuevos, usados y diapositivas de portada.\nFormularios de contacto, atención al cliente y buzón de sugerencias, con registro de solicitudes en WordPress.\nHerramientas de administración y ajustes desde el personalizador.\nNavegación y sistema de diseño propios, iconos SVG en línea y fuente local.\nCarga condicional de recursos según el contenido de cada página.\nPaquete de instalación con verificación SHA-256.",
            'challenges' => "Garantizar que los archivos compilados versionados coinciden con el código fuente: la integración continua falla si el build no es idéntico.\nTener un plan de respaldo: si falta un archivo compilado, el tema usa los recursos fuente y avisa a los administradores en lugar de dejar el sitio roto.",
            'security' => 'La activación del tema registra tipos de contenido y permisos sin crear ni sobrescribir contenido editorial. La integración continua audita dependencias, revisa la sintaxis de PHP, JavaScript y CSS, comprueba los tipos y verifica el paquete.',
            'results' => 'Sitio en producción en mmorichal.com. Los últimos runs de control de calidad en GitHub Actions finalizaron correctamente (septiembre de 2026).',
            'stack' => 'WordPress, PHP, TypeScript, Vite, Tailwind CSS, Swiper, GitHub Actions',
            'capture_auto' => true, 'demo' => 'https://mmorichal.com/', 'repository' => $gh . 'tema-motoresmorichal',
            'images' => array(
                array( 'file' => 'motores-morichal-portada.webp', 'alt' => 'Portada de mmorichal.com con el menú del concesionario y el carrusel principal' ),
                array( 'file' => 'motores-morichal-movil.webp', 'alt' => 'Vista móvil de la web de Motores Morichal' ),
            ),
            'related' => array( 'rotec' ),
        ),
        array(
            'slug' => 'cms-editorial', 'title' => 'CMS-Editorial', 'order' => 6, 'featured' => true, 'status' => 'produccion',
            'primary' => 'sistemas-empresariales', 'categories' => array( 'sistemas-empresariales', 'web-comercio' ),
            'specialties' => array( 'desarrollo', 'arquitectura' ),
            'sector' => 'Medios digitales · Producto de marca blanca', 'period' => 'May. 2026 – actualidad',
            'excerpt' => 'Tema WordPress de marca blanca para publicaciones digitales: identidad, colores, SEO, publicidad, clasificados, portada impresa del día y reportes PDF administrables desde WordPress, sin tocar el código.',
            'role' => 'Diseño y desarrollo del tema y de sus módulos (configuración de marca blanca, SEO, publicidad, clasificados, reportes PDF y seguridad), y su conversión en producto reutilizable.',
            'context' => 'El tema nació como el portal de noticias de un diario regional de Monagas. En su versión 3.0.0 se convirtió en CMS-Editorial: un producto de marca blanca que cualquier publicación puede instalar y configurar con su propia identidad.',
            'problem' => 'Los portales de noticias en WordPress suelen acumular plugins para SEO, anuncios y formularios, y un tema hecho para un medio lleva su logo, sus enlaces y sus textos legales escritos en el código. Reutilizarlo para otra publicación obliga a modificarlo a mano.',
            'objective' => 'Un tema editorial con las funciones clave integradas en el que la identidad, los colores, las redes, la radio y los textos legales se configuran desde WordPress, para replicarlo en otros medios sin cambiar el código.',
            'architecture' => 'Tema WordPress con módulos independientes: ajustes de marca blanca con validación y CSS dinámico (pantalla propia y personalizador con vista previa compartiendo los mismos valores), SEO con barra lateral en el editor de bloques, gestor de publicidad y patrocinadores, clasificados, portada del día, reportes PDF con Dompdf y seguridad. Navegación sin recarga con Swup.',
            'features' => "Identidad configurable: logos (principal, modo oscuro, acceso, pie y reportes PDF), favicon, imagen para compartir y textos del pie.\nPaletas de color administrables para fondos, navegación, enlaces, banners, botones y modo oscuro.\nRadio en vivo opcional, desactivada hasta configurarla.\nPortada por secciones, búsqueda AJAX, navegación móvil y modo oscuro.\nPublicidad por ubicación, patrocinadores por categoría y carruseles de banners.\nCarteles, clasificados y portada impresa del día programable.\nMetadatos SEO, Open Graph y controles por artículo en el editor de bloques.\nRoles editoriales, reportes PDF y publicación por lotes para administradores.",
            'challenges' => "Retirar del código toda la identidad de la publicación original (logos, enlaces, textos legales y datos de contacto) sin perder la información ya guardada: las claves de opciones, metadatos y tablas anteriores se conservan.\nQue la pantalla de ajustes y el personalizador compartan los mismos valores y que la paleta guardada alimente banners, navegación y acentos.\nGuardar los datos SEO en una tabla propia en lugar de wp_postmeta para consultarlos con más eficiencia.",
            'security' => 'Los ajustes se validan antes de guardarse: los colores deben ser válidos, las URL usan protocolos permitidos y los textos de marca no admiten HTML ejecutable. Roles editoriales y módulo de seguridad propio. Las pruebas de integración del repositorio cubren los ajustes compartidos, el rechazo de nonces inválidos, los colores y la publicación por lotes, y solo se ejecutan en una instalación local desechable.',
            'results' => 'La primera instalación funciona en producción en el portal de un diario regional. La versión 3.0.0 de marca blanca se publicó en el repositorio el 14 de octubre de 2026; las capturas proceden de una instalación local de prueba con contenido e imágenes de ejemplo.',
            'stack' => 'WordPress, PHP, JavaScript, Swup, Dompdf, Composer, @wordpress/scripts',
            'demo' => '', 'repository' => $gh . 'espressivo-editorial',
            'images' => array(
                array( 'file' => 'cms-editorial-portada.webp', 'alt' => 'Portada de CMS-Editorial en una instalación de prueba con identidad neutra, menú de secciones, banda de última hora y noticia principal de ejemplo' ),
                array( 'file' => 'cms-editorial-ajustes.webp', 'alt' => 'Pantalla Apariencia → CMS-Editorial con los ajustes de identidad: título, descripción y logos configurables' ),
                array( 'file' => 'cms-editorial-movil.webp', 'alt' => 'Vista móvil de CMS-Editorial en la instalación de prueba' ),
            ),
            'related' => array( 'diario-mercantil', 'streaming-radio-tv' ),
        ),
        // ------------------------------------------------------------------ Fichas resumidas
        array(
            'slug' => 'streaming-radio-tv', 'title' => 'Infraestructura de streaming de radio y TV', 'order' => 10, 'status' => 'entregado',
            'primary' => 'infraestructura-multimedia', 'categories' => array( 'infraestructura-multimedia' ), 'specialties' => array( 'infraestructura' ),
            'sector' => 'Medios · Radio y televisión', 'period' => '2025 – 2026',
            'excerpt' => 'Servidor de ingesta RTMP con conversión a HLS y servidor de radio Icecast, en contenedores Docker detrás de Nginx con HTTPS y con reproductores web para insertar en WordPress.',
            'role' => 'Configuración de los servidores, contenedores, certificados y reproductores web.',
            'architecture' => 'Video: Nginx con el módulo RTMP y FFmpeg recibe la señal de OBS o vMix y genera la lista HLS. Audio: Icecast recibe la señal de encoders como Opticodec. Ambos se publican detrás de un proxy con HTTPS y se instalan con Docker Compose y scripts de configuración.',
            'features' => "Ingesta RTMP compatible con OBS, vMix y Wirecast.\nConversión a HLS y reproductor web con selector de calidad.\nServidor de radio Icecast con montajes de respaldo.\nScripts de instalación y emisión de certificados TLS.\nWidget e integración con WordPress.",
            'security' => 'Los valores de acceso de los encoders deben configurarse fuera del repositorio. La capacidad real (oyentes, latencia) se mide en cada instalación; no se deduce de la configuración.',
            'stack' => 'Docker, Nginx RTMP, FFmpeg, HLS, Icecast, Nginx, Let\'s Encrypt, Bash',
            'repository' => $gh . 'rtmp-streaming-server_TV', 'links' => 'Servidor de radio (repositorio) | ' . $gh . 'radio-streaming-server',
            'related' => array( 'cms-editorial' ),
        ),
        array(
            'slug' => 'asistencia-offline', 'title' => 'Control de asistencia offline-first', 'order' => 11, 'status' => 'desarrollo',
            'primary' => 'aplicaciones-moviles', 'categories' => array( 'aplicaciones-moviles', 'sistemas-empresariales' ), 'specialties' => array( 'desarrollo', 'arquitectura' ),
            'sector' => 'Gestión de personal', 'period' => 'May. 2026',
            'excerpt' => 'Aplicación web progresiva para registrar entradas y salidas con geolocalización, incluso sin conexión: guarda los fichajes en el dispositivo y los sincroniza mediante una cola en Redis.',
            'role' => 'Diseño y desarrollo del frontend, la API y la infraestructura en contenedores.',
            'architecture' => 'Frontend React 19 con Vite y Tailwind CSS; IndexedDB (Dexie) y Service Workers (Workbox) para el modo sin conexión. API PHP con MySQL, Redis como cola de sincronización y un worker en segundo plano. Todo el entorno se levanta con Docker Compose detrás de Nginx.',
            'features' => "Registro de entradas y salidas sin conexión.\nCaptura de coordenadas GPS en cada fichaje.\nSincronización automática al recuperar la conexión.\nProcesamiento en segundo plano con Redis.",
            'stack' => 'React, Vite, Tailwind CSS, Dexie, Workbox, PHP, MySQL, Redis, Docker, Nginx',
            'repository' => $gh . 'asistencia', 'related' => array( 'guia-medica-monagas' ),
        ),
        array(
            'slug' => 'micatalogo-ve', 'title' => 'MiCatálogo VE', 'order' => 12, 'status' => 'desarrollo',
            'primary' => 'plataformas-saas', 'categories' => array( 'plataformas-saas', 'web-comercio' ), 'specialties' => array( 'desarrollo', 'arquitectura' ),
            'sector' => 'Comercio · Catálogos en línea', 'period' => 'Jun. 2026',
            'excerpt' => 'Plataforma para que los comercios creen su catálogo o tienda en línea: API en Laravel con autenticación Sanctum y panel en React.',
            'role' => 'Desarrollo de la API, el frontend y la configuración con Docker.',
            'architecture' => 'API Laravel con Sanctum y panel React con Vite, ejecutados con Docker Compose y Nginx.',
            'features' => "Registro de comercios y flujo de alta completo.\nAutenticación por tokens con Laravel Sanctum.\nDemos de catálogo por comercio.",
            'stack' => 'Laravel, PHP, React, Vite, Sanctum, Docker, Nginx',
            'repository' => $gh . 'micatalogove',
        ),
        array(
            'slug' => 'rotec', 'title' => 'Rotec Corporativo', 'order' => 13, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Empresa industrial · Catálogo comercial',
            'excerpt' => 'Tema WordPress corporativo con catálogo WooCommerce orientado a cotización por WhatsApp, sucursales en mapa y registro de solicitudes en WordPress.',
            'role' => 'Desarrollo del tema, el catálogo y los formularios.',
            'features' => "Catálogo WooCommerce sin checkout, con cierre por WhatsApp.\nSucursales administrables con mapa (Leaflet y OpenStreetMap).\nFormularios con admin-post.php y registro de solicitudes en un tipo de contenido propio.\nPáginas y galerías administrables.",
            'stack' => 'WordPress, WooCommerce, PHP, Alpine.js, Leaflet, Vite',
            'repository' => $gh . 'rotec', 'related' => array( 'motores-morichal' ),
        ),
        array(
            'slug' => 'minima-organics', 'title' => 'Mínima Organics', 'order' => 14, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Comercio electrónico · Higiene sostenible',
            'excerpt' => 'Tema Shopify a medida para una marca de higiene dental sostenible: secciones editoriales en Liquid, páginas legales reutilizables y validación con Shopify Theme Check en GitHub Actions.',
            'role' => 'Desarrollo del tema y de sus secciones personalizadas.',
            'features' => "Hero editorial con soporte de video.\nSistema reutilizable de páginas legales y de contenido.\nPie de página y contacto personalizados.\nRevisión automática del tema en cada cambio.",
            'stack' => 'Shopify, Liquid, CSS, JavaScript, Shopify CLI, GitHub Actions',
            'repository' => $gh . 'minima-organics-theme',
        ),
        array(
            'slug' => 'reservas-qr-evento', 'title' => 'Reservas con QR para un evento en cine', 'order' => 15, 'status' => 'entregado',
            'primary' => 'plugins-integraciones', 'categories' => array( 'plugins-integraciones' ), 'specialties' => array( 'desarrollo', 'arquitectura' ),
            'sector' => 'Eventos · Control de acceso',
            'excerpt' => 'Plugin WordPress para reservar butacas en una función de cine: mapa interactivo de la sala, datos únicos por asistente, entrada con código QR enviada por correo y escáner que valida cada entrada una sola vez.',
            'role' => 'Desarrollo completo del plugin.',
            'features' => "Selector visual de asientos con zoom.\nValidación de cédula, correo y teléfono únicos.\nCódigo QR por asiento y comprobante en PDF.\nEscáner con cámara para validar la entrada.\nPanel de administración de reservas.",
            'stack' => 'WordPress, PHP, MySQL, JavaScript, QR',
            'repository' => $gh . 'Evento-Conexi-n-Publicitaria',
        ),
        array(
            'slug' => 'forja-de-diosas-foro', 'title' => 'Forja de Diosas · Foro', 'order' => 16, 'status' => 'entregado',
            'primary' => 'plugins-integraciones', 'categories' => array( 'plugins-integraciones' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Comunidad en línea',
            'excerpt' => 'Plugin de comunidad para WordPress: foros por categorías, perfiles, roles, moderación, acceso propio y notificaciones por SMTP.',
            'role' => 'Desarrollo del plugin con programación orientada a objetos.',
            'features' => "Debates y categorías de foro.\nPerfiles, roles y restricciones de contenido.\nInicio de sesión personalizado.\nNotificaciones por correo con SMTP.",
            'stack' => 'WordPress, PHP, JavaScript, jQuery, SMTP',
            'repository' => $gh . 'Plugin-forjadediosas-FORO',
        ),
        array(
            'slug' => 'biotsept', 'title' => 'BIOTSEPT', 'order' => 17, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Producto de consumo · Captación',
            'excerpt' => 'Landing WordPress orientada a la venta: pedidos de kits por WhatsApp, formulario AJAX, registro de visitas y eventos, y una vista protegida de actividades con exportación CSV.',
            'role' => 'Desarrollo del tema y del registro de actividad.',
            'stack' => 'WordPress, PHP, JavaScript, AJAX, CSV',
            'repository' => $gh . 'biotsept-theme',
        ),
        array(
            'slug' => 'zyrus', 'title' => 'Zyrus', 'order' => 18, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio', 'plugins-integraciones' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Landing comercial',
            'excerpt' => 'Tema WordPress de landing page con Tailwind CSS y envío de formularios a Google Sheets mediante un webhook.',
            'stack' => 'WordPress, PHP, Tailwind CSS, Google Apps Script',
            'repository' => $gh . 'zyrus-theme',
        ),
        array(
            'slug' => 'vet', 'title' => 'Tema veterinario', 'order' => 19, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Salud animal',
            'excerpt' => 'Tema WordPress para un sitio del sector veterinario, con páginas legales, barra superior y ajustes de diseño para móvil.',
            'stack' => 'WordPress, PHP, CSS, JavaScript',
            'repository' => $gh . 'vet',
        ),
        array(
            'slug' => 'divine', 'title' => 'Divine', 'order' => 20, 'status' => 'entregado',
            'primary' => 'web-comercio', 'categories' => array( 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Landing de marca',
            'excerpt' => 'Tema WordPress de landing page con video de portada y controles de reproducción.',
            'stack' => 'WordPress, PHP, CSS',
            'repository' => $gh . 'Divine---Tema',
        ),
        array(
            'slug' => 'luxury-bento-gallery', 'title' => 'Luxury Bento Gallery', 'order' => 21, 'status' => 'laboratorio',
            'primary' => 'plugins-integraciones', 'categories' => array( 'plugins-integraciones', 'laboratorio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Componente reutilizable',
            'excerpt' => 'Plugin de galería tipo bento para entradas de WordPress, con estilos y scripts separados para administración y portada.',
            'stack' => 'WordPress, PHP, JavaScript, CSS',
            'repository' => $gh . 'Galer-a-bento-para-entradas-en-WordPress-',
        ),
        array(
            'slug' => 'trafficlab', 'title' => 'TrafficLab GA4', 'order' => 22, 'status' => 'laboratorio',
            'primary' => 'laboratorio', 'categories' => array( 'laboratorio' ), 'specialties' => array( 'arquitectura' ),
            'sector' => 'Medición y pruebas de navegador',
            'excerpt' => 'Laboratorio en Python para probar navegación automatizada contra una propiedad de pruebas de GA4 y analizar registros de tráfico en modo de solo lectura. Las sesiones son artificiales y no representan tráfico real.',
            'stack' => 'Python, Playwright, PowerShell, GA4',
            'repository' => $gh . 'botito',
        ),
        array(
            'slug' => 'merchandev-portafolio', 'title' => 'Merchan.Dev', 'order' => 23, 'status' => 'produccion',
            'primary' => 'laboratorio', 'categories' => array( 'laboratorio', 'web-comercio' ), 'specialties' => array( 'desarrollo' ),
            'sector' => 'Marca personal',
            'excerpt' => 'Tema WordPress propio de este portafolio: fondo animado en Canvas 2D, catálogo de proyectos administrable, casos de estudio y formulario con registro de solicitudes y aviso por correo.',
            'stack' => 'WordPress, PHP, JavaScript, CSS, Canvas 2D',
            'capture_auto' => true, 'demo' => 'https://merchan.dev/', 'repository' => $gh . 'merchandev-portafolio',
        ),
    );
}

/* -------------------------------------------------------------------------- */
/* Servicios: tres especialidades nuevas y textos de los servicios existentes   */
/* -------------------------------------------------------------------------- */

/** Convierte una lista simple en marcado de bloques de WordPress. */
function am_blocks( array $parts ) {
    $html = '';
    foreach ( $parts as $part ) {
        list( $type, $value ) = $part;
        if ( $type === 'h2' || $type === 'h3' ) {
            $level = (int) substr( $type, 1 );
            $html .= '<!-- wp:heading' . ( $level === 3 ? ' {"level":3}' : '' ) . ' --><' . $type . ' class="wp-block-heading">' . esc_html( $value ) . '</' . $type . '><!-- /wp:heading -->';
        } elseif ( $type === 'ul' ) {
            $html .= '<!-- wp:list --><ul class="wp-block-list">';
            foreach ( $value as $item ) { $html .= '<!-- wp:list-item --><li>' . esc_html( $item ) . '</li><!-- /wp:list-item -->'; }
            $html .= '</ul><!-- /wp:list -->';
        } else {
            $html .= '<!-- wp:paragraph --><p>' . esc_html( $value ) . '</p><!-- /wp:paragraph -->';
        }
    }
    return $html;
}

/** Contenido estructurado de un servicio: problema, solución, procesos y proyectos que encajan. */
function am_service_blocks( $headline, $problem, $solution, array $processes, $fit ) {
    return am_blocks( array(
        array( 'h2', $headline ),
        array( 'h3', 'Qué problema resuelvo' ), array( 'p', $problem ),
        array( 'h3', 'Qué solución desarrollo' ), array( 'p', $solution ),
        array( 'h3', 'Procesos que se pueden integrar' ), array( 'ul', $processes ),
        array( 'h3', 'Qué proyectos encajan' ), array( 'p', $fit ),
    ) );
}

function am_seed_specialty_services() {
    return array(
        'desarrollo-software-productos-digitales' => array(
            'title' => 'Desarrollo de software y productos digitales',
            'excerpt' => 'Aplicaciones web, plataformas SaaS, sistemas administrativos y apps móviles construidos sobre los procesos reales de cada negocio.',
            'content' => am_service_blocks(
                'Productos digitales completos, de la idea a la operación',
                'Necesitas un sistema que todavía no existe: una plataforma para tus clientes, un panel para tu equipo, una app conectada o un portal para tus socios. Las herramientas genéricas no se ajustan a tu proceso y combinarlas genera trabajo manual.',
                'Desarrollo Full-Stack de aplicaciones web, plataformas SaaS, sistemas administrativos, portales privados y aplicaciones móviles. Diseño la interfaz, la lógica de negocio, la API y la base de datos como un solo sistema, y lo entrego desplegado y documentado.',
                array( 'Reservas, disponibilidad y tarifas.', 'Pagos, facturas y recibos.', 'Gestión documental con estados y verificación pública.', 'Directorios con perfiles verificados.', 'Roles, permisos y paneles por tipo de usuario.', 'Apps móviles conectadas a la misma API que la web.' ),
                'Empresas de servicios, medios, salud, turismo y comercio que quieren digitalizar un proceso central. Podemos empezar por un MVP y evolucionarlo por etapas.'
            ),
        ),
        'arquitectura-integraciones-automatizacion' => array(
            'title' => 'Arquitectura, integraciones y automatización',
            'excerpt' => 'Modelo de datos, APIs, autenticación, pagos, correo transaccional, colas e integraciones con WordPress, Shopify y servicios externos.',
            'content' => am_service_blocks(
                'Sistemas que se comunican entre sí sin trabajo manual',
                'Datos repetidos en varias herramientas, pagos que se concilian a mano, correos que no llegan o se envían duplicados, y aplicaciones que no pueden crecer porque nadie definió su arquitectura.',
                'Diseño la arquitectura y el modelo de datos, construyo APIs y conecto los servicios que necesitas: pasarelas de pago, mapas, correo transaccional, WordPress, Shopify y sistemas externos. Automatizo tareas con colas y procesos en segundo plano, con validación del lado del servidor.',
                array( 'Modelado de base de datos y migraciones versionadas.', 'Autenticación, roles y permisos.', 'Pasarelas de pago con el importe validado en el servidor.', 'Correo transaccional, notificaciones y campañas con consentimiento.', 'Colas, workers y tareas programadas.', 'Importación y exportación de datos (Excel, CSV, PDF).' ),
                'Plataformas en crecimiento, sistemas heredados que hay que conectar con servicios nuevos y equipos que necesitan automatizar procesos repetitivos.'
            ),
        ),
        'infraestructura-seguridad-operacion' => array(
            'title' => 'Infraestructura, seguridad y operación',
            'excerpt' => 'Servidores Linux, Docker, proxies con HTTPS, despliegues, copias de seguridad y seguridad integrada en el desarrollo.',
            'content' => am_service_blocks(
                'Software preparado para funcionar en producción',
                'Una aplicación no termina cuando se programa: necesita un servidor, despliegues repetibles, HTTPS, copias de seguridad, control de accesos y alguien que entienda cómo está montada.',
                'Configuro servidores Linux con Docker y Docker Compose, proxies inversos con TLS, despliegues e integración continua, copias de seguridad, healthchecks y registros. Integro la seguridad en el desarrollo: gestión de secretos, validación, permisos, auditoría y control de dependencias.',
                array( 'Despliegue en VPS y separación de entornos.', 'Proxy inverso (Nginx, Caddy o Traefik) y certificados TLS.', 'Integración continua con pruebas y análisis.', 'Copias de seguridad, restauración y comprobaciones de estado.', 'Revisión de secretos expuestos y dependencias.', 'Servidores de streaming de audio y video.' ),
                'Aplicaciones que necesitan un despliegue ordenado y mantenimiento técnico. No ofrezco certificaciones de seguridad ni disponibilidad garantizada; sí un trabajo documentado y verificable.'
            ),
        ),
    );
}

/** Nuevos textos de los servicios existentes. Solo se aplican si el contenido sigue siendo el original del tema. */
function am_seed_service_rewrites() {
    return array(
        'desarrollo-saas-microservicios' => am_service_blocks(
            'Plataformas web y SaaS construidas alrededor de tu proceso',
            'Empresas que operan con hojas de cálculo, formularios sueltos y mensajería, o que quieren convertir un servicio en un producto digital con usuarios, roles y pagos.',
            'Aplicaciones web con paneles por rol, APIs, base de datos relacional, pagos y notificaciones. Uso una arquitectura modular por dominios y separo servicios —por ejemplo, un worker de correo— solo cuando aporta algo concreto: no todo producto necesita microservicios.',
            array( 'Reservas y disponibilidad.', 'Cobros, facturas y recibos.', 'Gestión de clientes, proveedores y colaboradores.', 'Portales privados para socios: hoteles, agencias o profesionales.', 'Notificaciones y correo transaccional.', 'Informes y exportaciones.' ),
            'Productos SaaS en fase inicial, plataformas internas para empresas medianas y portales B2B. Si el alcance aún no está claro, empezamos por un análisis y un MVP.'
        ),
        'streaming-audio-video' => am_service_blocks(
            'Infraestructura propia para emitir radio y televisión por internet',
            'Emisoras y canales que dependen de servicios de terceros para su señal en vivo, o que necesitan integrar su emisión en su propia web.',
            'Servidores en VPS Linux con Docker: ingesta RTMP desde OBS o vMix y conversión a HLS para video, e Icecast para audio desde encoders como Opticodec. Todo detrás de un proxy con HTTPS y con reproductores web listos para insertar en WordPress.',
            array( 'Señal de TV en vivo hacia la web.', 'Radio en línea con reproductor propio.', 'Integración del reproductor en el portal del medio.', 'Certificados TLS y su renovación.', 'Documentación de puertos, montajes y encoders.' ),
            'Medios regionales, productoras y emisoras que quieren controlar su infraestructura. La capacidad real —oyentes simultáneos o latencia— se mide con pruebas en cada caso; no la garantizo de antemano.'
        ),
        'administracion-vps-devops' => am_service_blocks(
            'Llevar tu aplicación a producción y mantenerla en orden',
            'Aplicaciones que funcionan en local pero no tienen un despliegue repetible, servidores configurados a mano o sin copias de seguridad probadas.',
            'Contenedores con Docker y Docker Compose, proxy inverso (Nginx, Caddy o Traefik) con HTTPS, variables de entorno fuera del código, scripts de despliegue, comprobaciones de estado e integración continua en GitHub Actions.',
            array( 'Despliegue en VPS Linux.', 'Entornos de desarrollo y producción separados.', 'Migraciones de base de datos.', 'Copias de seguridad y pruebas de restauración.', 'Revisión básica de seguridad del servidor y de secretos expuestos.' ),
            'Equipos con software propio que necesitan ordenar su operación, y proyectos que desarrollo y entrego funcionando. No ofrezco hosting ni disponibilidad garantizada 24/7.'
        ),
        'desarrollo-avanzado-wordpress' => am_service_blocks(
            'WordPress como plataforma de negocio, no como plantilla',
            'Sitios que acumulan plugins para resolver procesos concretos —reservas, catálogos, formularios o SEO— y se vuelven lentos y difíciles de mantener.',
            'Temas y plugins a medida con tipos de contenido, campos administrables, AJAX y REST, roles y permisos, y una base de código versionada con pruebas y empaquetado reproducible.',
            array( 'Motores de reserva y portales privados.', 'Inventarios y catálogos administrables.', 'Formularios con registro de solicitudes y avisos por correo.', 'SEO técnico integrado en el tema.', 'Migración de contenido sin romper las URL existentes.' ),
            'Empresas que ya usan WordPress o necesitan un gestor que su equipo pueda administrar. Si WordPress no es la herramienta adecuada, te lo digo y propongo otra arquitectura.'
        ),
        'ecommerce-migraciones' => am_service_blocks(
            'Comercio electrónico e integraciones de pago',
            'Tiendas y catálogos que necesitan cobrar en línea, cotizar por WhatsApp o migrar de plataforma sin perder productos, URL ni posicionamiento.',
            'Desarrollo sobre WooCommerce o Shopify (Liquid), integración de pasarelas como Redsys con el importe validado en el servidor, y migraciones planificadas con mapa de redirecciones.',
            array( 'Pasarela de pago y confirmación del pedido.', 'Catálogo con cotización por WhatsApp.', 'Recibos y notificaciones.', 'Mapa de URL y redirecciones 301.', 'Tema a medida de la marca.' ),
            'Marcas de producto, comercios locales y empresas de servicios que venden en línea. Cada integración de pago se prueba primero en un entorno de pruebas.'
        ),
        'seo-tecnico' => am_service_blocks(
            'SEO técnico integrado en el desarrollo',
            'Sitios con buen contenido que no se indexan bien por problemas de estructura, rendimiento, URL duplicadas o migraciones mal resueltas.',
            'Revisión técnica y correcciones en el código: jerarquía de encabezados, datos estructurados, sitemaps, canónicas, redirecciones, rendimiento y páginas locales generadas a partir de datos.',
            array( 'Auditoría técnica del sitio.', 'Migración de slugs con redirecciones verificadas.', 'Páginas de destino por ubicación o servicio.', 'Integración con Yoast SEO o un módulo SEO propio.', 'Seguimiento con Google Search Console.' ),
            'Negocios locales y medios que dependen de las búsquedas orgánicas. No prometo posiciones concretas en Google.'
        ),
        'consultoria-ia-rendimiento' => am_service_blocks(
            'Revisión técnica, rendimiento e integración de IA con criterio',
            'Plataformas lentas, código heredado difícil de mantener o equipos que quieren usar modelos de lenguaje sin exponer datos ni crear dependencias innecesarias.',
            'Auditoría del código y de la infraestructura con un informe priorizado, mejoras de rendimiento y, cuando aporta valor, integración de APIs de IA en tareas concretas —redacción asistida, clasificación o extracción de datos— con control de costes y privacidad.',
            array( 'Auditoría de código y dependencias.', 'Optimización de carga y de consultas.', 'Integración de APIs de IA en flujos internos.', 'Automatización de tareas repetitivas.', 'Documentación para el equipo.' ),
            'Empresas con software propio que necesitan una segunda opinión técnica o un plan de mejora.'
        ),
        'infraestructura-fisica-continuidad' => am_service_blocks(
            'Continuidad operativa del equipo técnico',
            'Profesionales, estudios de streaming y oficinas pequeñas que pierden trabajo por cortes eléctricos o fallos de hardware.',
            'Asesoría práctica: configuración de equipos para que arranquen solos tras un corte (restauración tras pérdida de corriente en BIOS/UEFI), dimensionamiento de respaldo energético y organización de puestos con varios equipos.',
            array( 'Revisión de equipos y conexiones.', 'Configuración de arranque automático.', 'Cálculo de autonomía de UPS o generador.', 'Procedimiento ante cortes de energía.' ),
            'Servicio complementario a la infraestructura de streaming y a los entornos de trabajo remoto. No sustituye la intervención de un electricista certificado.'
        ),
    );
}

/* -------------------------------------------------------------------------- */
/* Importador                                                                  */
/* -------------------------------------------------------------------------- */

function am_normalize_html( $html ) {
    return trim( preg_replace( '/\s+/', ' ', (string) $html ) );
}

/**
 * Busca por slug en cualquier estado, incluida la papelera (un proyecto que el administrador
 * descartó no se vuelve a crear). Se usa post_name__in y no `name`: una consulta singular oculta
 * los borradores cuando no hay usuario con sesión (WP-CLI, cron) y provocaba duplicados.
 * WordPress añade «__trashed» al slug al enviar a la papelera, por eso se buscan ambos.
 */
function am_find_post_by_slug( $slug, $post_type ) {
    $found = get_posts( array(
        'post_name__in' => array( $slug, $slug . '__trashed' ),
        'post_type' => $post_type, 'numberposts' => 1, 'suppress_filters' => true,
        'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future', 'trash' ), 'orderby' => 'ID', 'order' => 'ASC',
    ) );
    return $found ? $found[0] : null;
}

/** Sube (una sola vez) una captura del tema a la biblioteca de medios. */
function am_seed_attachment( $file, $alt, $parent_id ) {
    $existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => 1, 'meta_key' => '_am_seed_image', 'meta_value' => $file, 'fields' => 'ids' ) );
    if ( $existing ) {
        // Instalaciones anteriores: marca el tipo de captura para la actualización automática.
        if ( ! get_post_meta( $existing[0], '_am_capture_kind', true ) && preg_match( '~-(portada|movil)\.~', $file, $kind ) ) { update_post_meta( $existing[0], '_am_capture_kind', $kind[1] === 'movil' ? 'mobile' : 'desktop' ); }
        return (int) $existing[0];
    }
    $path = get_theme_file_path( 'assets/image/proyectos/' . $file );
    if ( ! is_readable( $path ) ) { return 0; }
    $upload = wp_upload_bits( $file, null, file_get_contents( $path ) );
    if ( ! empty( $upload['error'] ) ) { return 0; }
    $id = wp_insert_attachment( array(
        'post_mime_type' => wp_check_filetype( $upload['file'] )['type'] ?: 'image/webp',
        'post_title' => $alt, 'post_excerpt' => $alt, 'post_status' => 'inherit',
    ), $upload['file'], $parent_id, true );
    if ( is_wp_error( $id ) ) { return 0; }
    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
    update_post_meta( $id, '_wp_attachment_image_alt', $alt );
    update_post_meta( $id, '_am_seed_image', $file );
    if ( preg_match( '~-(portada|movil)\.~', $file, $kind ) ) { update_post_meta( $id, '_am_capture_kind', $kind[1] === 'movil' ? 'mobile' : 'desktop' ); }
    return (int) $id;
}

/** Marcador que dejaba la versión anterior del importador en borradores vacíos. */
/**
 * Escribe los campos sembrados de un proyecto respetando las ediciones del administrador:
 * - campo vacío: se completa;
 * - campo que conserva el valor que escribió el tema (huella en _am_seed_snapshot): se
 *   actualiza al texto nuevo del tema, o se vacía si el tema ya no lo usa;
 * - cualquier otro valor es una edición del administrador y no se toca.
 *
 * @return bool true si se cambió algún valor que ya existía.
 */
function am_seed_apply_fields( WP_Post $post, array $seed, array $values, $placeholder = false ) {
    $snapshot = json_decode( (string) get_post_meta( $post->ID, '_am_seed_snapshot', true ), true );
    $snapshot = is_array( $snapshot ) ? $snapshot : array();
    $fields = array( 'post_title' => $seed['title'], 'post_excerpt' => $seed['excerpt'] );
    foreach ( $values as $key => $value ) { $fields[ $key ] = am_sanitize_project_field( $key, $value ); }
    $changed = false;
    $post_update = array( 'ID' => $post->ID );
    foreach ( $fields as $key => $value ) {
        $is_post_field = strpos( $key, 'post_' ) === 0;
        $current = $is_post_field ? (string) $post->$key : (string) get_post_meta( $post->ID, '_am_' . $key, true );
        if ( $current === $value ) { $snapshot[ $key ] = md5( $value ); continue; }
        $theme_wrote = isset( $snapshot[ $key ] ) && hash_equals( (string) $snapshot[ $key ], md5( $current ) );
        if ( $current !== '' && ! $placeholder && ! $theme_wrote ) { continue; } // Edición del administrador.
        if ( $current !== '' ) { $changed = true; }
        if ( $is_post_field ) { $post_update[ $key ] = $value; }
        elseif ( $value === '' ) { delete_post_meta( $post->ID, '_am_' . $key ); }
        else { update_post_meta( $post->ID, '_am_' . $key, $value ); }
        $snapshot[ $key ] = md5( $value );
    }
    if ( $placeholder ) { $post_update += array( 'post_content' => '', 'post_status' => 'publish', 'menu_order' => $seed['order'] ); }
    if ( count( $post_update ) > 1 ) { wp_update_post( $post_update ); }
    update_post_meta( $post->ID, '_am_seed_snapshot', wp_json_encode( $snapshot ) );
    return $changed;
}

/** Valores que importó la versión 2026.10.1 de proyectos que después cambiaron de identidad. */
function am_seed_legacy_values() {
    return array(
        'espressivo-editorial' => array(
            'post_title' => 'Espressivo Editorial',
            'post_excerpt' => 'Tema y framework WordPress para diarios digitales: portada editorial, SEO propio sin plugins, gestor de publicidad, clasificados, portada impresa del día y radio en vivo. Instalación de referencia: Diario El Oriental.',
            'sector' => 'Medios digitales · Prensa regional',
            'role' => 'Desarrollo del tema y de sus módulos (SEO, publicidad, clasificados, reportes y seguridad) como base reutilizable de Espressivo Venezuela.',
            'context' => 'Diario El Oriental, un periódico de Monagas con más de cuatro décadas de trayectoria, necesitaba un portal de noticias propio. El trabajo se planteó como una base reutilizable para otras editoriales.',
            'problem' => 'Los portales de noticias en WordPress suelen acumular plugins para SEO, anuncios, formularios y rendimiento, lo que complica el mantenimiento y la velocidad.',
            'objective' => 'Un tema editorial con las funciones clave integradas, administrable por la redacción y replicable para otros medios con su propia marca.',
            'architecture' => 'Tema WordPress con módulos independientes (SEO, gestor de anuncios, clasificados, portada del día, reportes, seguridad y marca blanca), tipos de contenido para carteles y clasificados, y navegación sin recarga con Swup. Generación de PDF con Dompdf instalado mediante Composer.',
            'features' => "Portada editorial con noticia principal, portada impresa del día y bloques por sección.\nScroll infinito y carga incremental sin repetir ni saltar noticias.\nNavegación sin recarga que mantiene la radio en vivo sonando entre páginas.\nMódulo SEO propio con tabla indexable, Open Graph y panel con datos de Google Site Kit.\nGestor de publicidad y clasificados por tipo.\nBuscador predictivo, modo oscuro y barra de progreso de lectura.",
            'challenges' => "Guardar los datos SEO en una tabla propia en lugar de wp_postmeta para consultarlos y mantenerlos de forma más eficiente.\nMantener el reproductor de radio activo entre páginas sin convertir el sitio en una aplicación de una sola página.",
            'security' => 'Roles editoriales y un módulo de seguridad propio del tema. Las dependencias de Composer están versionadas en el repositorio.',
            'results' => 'En producción en diarioeloriental.com.',
            'stack' => 'WordPress, PHP, JavaScript, Swup, Dompdf, Composer, Google Site Kit',
            'demo' => 'https://diarioeloriental.com/',
        ),
    );
}

/** Capturas de la publicación original que dejaron de representar a CMS-Editorial. */
function am_seed_legacy_image_ids() {
    return get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'numberposts' => -1, 'fields' => 'ids',
        'meta_query' => array( array( 'key' => '_am_seed_image', 'value' => array( 'espressivo-el-oriental-portada.webp', 'espressivo-el-oriental-movil.webp' ), 'compare' => 'IN' ) ) ) );
}

/**
 * 2026.10.2: «Espressivo Editorial» pasa a ser «CMS-Editorial» (tema de marca blanca).
 * Se conserva el mismo registro (la URL antigua redirige con 301), se marcan como escritos
 * por el tema los valores importados en 2026.10.1 para que el bucle general los actualice
 * (lo que hayas editado se conserva) y se retiran del proyecto las capturas de la
 * publicación original.
 */
function am_seed_migrate_legacy( array &$report ) {
    $old = am_find_post_by_slug( 'espressivo-editorial', 'proyecto' );
    if ( ! $old || $old->post_status === 'trash' ) { return; }
    $new = am_find_post_by_slug( 'cms-editorial', 'proyecto' );
    if ( $new ) {
        // Ya existe CMS-Editorial (lo creó una importación anterior): el registro antiguo, si sigue
        // como lo generó el tema, va a la papelera (recuperable) y su URL redirige al nuevo.
        $legacy = am_seed_legacy_values()['espressivo-editorial'];
        if ( $old->post_title === $legacy['post_title'] && get_post_meta( $old->ID, '_am_demo', true ) === $legacy['demo'] ) {
            wp_trash_post( $old->ID );
            if ( ! in_array( 'espressivo-editorial', (array) get_post_meta( $new->ID, '_wp_old_slug' ), true ) ) { add_post_meta( $new->ID, '_wp_old_slug', 'espressivo-editorial' ); }
            $report['migrados'][] = 'Espressivo Editorial (duplicado) enviado a la papelera; su URL redirige a /proyectos/cms-editorial/';
        }
        return;
    }
    $snapshot = json_decode( (string) get_post_meta( $old->ID, '_am_seed_snapshot', true ), true );
    $snapshot = is_array( $snapshot ) ? $snapshot : array();
    foreach ( am_seed_legacy_values()['espressivo-editorial'] as $key => $value ) {
        if ( ! isset( $snapshot[ $key ] ) ) { $snapshot[ $key ] = md5( strpos( $key, 'post_' ) === 0 ? $value : am_sanitize_project_field( $key, $value ) ); }
    }
    update_post_meta( $old->ID, '_am_seed_snapshot', wp_json_encode( $snapshot ) );
    wp_update_post( array( 'ID' => $old->ID, 'post_name' => 'cms-editorial' ) ); // WordPress guarda el slug anterior y redirige.
    $legacy = array_map( 'intval', am_seed_legacy_image_ids() );
    if ( in_array( (int) get_post_thumbnail_id( $old->ID ), $legacy, true ) ) { delete_post_thumbnail( $old->ID ); }
    $gallery = array_diff( array_map( 'intval', array_filter( explode( ',', (string) get_post_meta( $old->ID, '_am_gallery', true ) ) ) ), $legacy );
    if ( $gallery ) { update_post_meta( $old->ID, '_am_gallery', implode( ',', $gallery ) ); } else { delete_post_meta( $old->ID, '_am_gallery' ); }
    $report['migrados'][] = 'Espressivo Editorial → CMS-Editorial (la URL anterior redirige a /proyectos/cms-editorial/)';
}

/** Borra las capturas de la publicación original si ya no se usan en ningún contenido. */
function am_seed_remove_unused_legacy_images( array &$report ) {
    global $wpdb;
    $in_galleries = array();
    foreach ( $wpdb->get_col( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_am_gallery'" ) as $list ) {
        $in_galleries = array_merge( $in_galleries, array_map( 'intval', explode( ',', (string) $list ) ) );
    }
    foreach ( am_seed_legacy_image_ids() as $id ) {
        $file = basename( (string) get_attached_file( $id ) );
        $as_thumbnail = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %s", (string) $id ) );
        $in_content = $file ? (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type NOT IN ('attachment','revision') AND post_content LIKE %s", '%' . $wpdb->esc_like( $file ) . '%' ) ) : 0;
        if ( ! $as_thumbnail && ! $in_content && ! in_array( (int) $id, $in_galleries, true ) && wp_delete_attachment( $id, true ) ) { $report['retirados'][] = $file; }
    }
}

function am_seed_is_placeholder( WP_Post $post ) {
    return strpos( $post->post_content, 'Documentar el proceso anterior y la necesidad real.' ) !== false;
}

function am_run_portfolio_import() {
    $report = array( 'creados' => array(), 'migrados' => array(), 'textos_actualizados' => array(), 'completados' => array(), 'servicios_actualizados' => array(), 'servicios_personalizados' => array(), 'retirados' => array(), 'errores' => array() );
    if ( ! post_type_exists( 'proyecto' ) ) { am_register_projects(); }
    if ( ! post_type_exists( 'servicio' ) ) { am_register_servicios_cpt(); }

    foreach ( am_seed_categories() as $slug => $category ) {
        if ( ! term_exists( $slug, 'tipo_proyecto' ) ) {
            $term = wp_insert_term( $category[0], 'tipo_proyecto', array( 'slug' => $slug, 'description' => $category[1] ) );
            if ( is_wp_error( $term ) ) { $report['errores'][] = 'Categoría ' . $slug . ': ' . $term->get_error_message(); }
        }
    }

    // Servicios: especialidades nuevas (se crean si faltan) y textos originales del tema (se actualizan si no se editaron).
    foreach ( am_seed_specialty_services() as $slug => $service ) {
        if ( am_find_post_by_slug( $slug, 'servicio' ) ) { continue; }
        $id = wp_insert_post( array( 'post_type' => 'servicio', 'post_status' => 'publish', 'post_name' => $slug, 'post_title' => $service['title'], 'post_excerpt' => $service['excerpt'], 'post_content' => $service['content'] ), true );
        if ( is_wp_error( $id ) ) { $report['errores'][] = 'Servicio ' . $slug . ': ' . $id->get_error_message(); } else { update_post_meta( $id, '_am_seed_version', AM_SEED_VERSION ); $report['creados'][] = 'Especialidad: ' . $service['title']; }
    }
    $legacy = array();
    foreach ( am_legacy_services() as $service ) { $legacy[ $service['post_name'] ] = $service['post_content']; }
    foreach ( am_seed_service_rewrites() as $slug => $content ) {
        $post = am_find_post_by_slug( $slug, 'servicio' );
        if ( ! $post || $post->post_status === 'trash' || get_post_meta( $post->ID, '_am_seed_version', true ) ) { continue; }
        if ( isset( $legacy[ $slug ] ) && am_normalize_html( $post->post_content ) === am_normalize_html( $legacy[ $slug ] ) ) {
            wp_save_post_revision( $post->ID ); // Conserva el texto anterior en Revisiones para poder restaurarlo.
            $result = wp_update_post( array( 'ID' => $post->ID, 'post_content' => $content ), true );
            if ( is_wp_error( $result ) ) { $report['errores'][] = 'Servicio ' . $slug . ': ' . $result->get_error_message(); continue; }
            update_post_meta( $post->ID, '_am_seed_version', AM_SEED_VERSION );
            $report['servicios_actualizados'][] = get_the_title( $post );
        } else {
            $report['servicios_personalizados'][] = get_the_title( $post );
        }
    }

    // Proyectos.
    am_seed_migrate_legacy( $report );
    $ids = array();
    foreach ( am_seed_projects() as $seed ) {
        $post = am_find_post_by_slug( $seed['slug'], 'proyecto' );
        if ( $post && $post->post_status === 'trash' ) { continue; } // Descartado por el administrador.
        $placeholder = $post && am_seed_is_placeholder( $post );
        if ( ! $post ) {
            $id = wp_insert_post( array(
                'post_type' => 'proyecto', 'post_status' => 'publish', 'post_name' => $seed['slug'], 'post_title' => $seed['title'],
                'post_excerpt' => $seed['excerpt'], 'post_content' => '', 'menu_order' => $seed['order'],
            ), true );
            if ( is_wp_error( $id ) ) { $report['errores'][] = $seed['title'] . ': ' . $id->get_error_message(); continue; }
            $post = get_post( $id );
            $report['creados'][] = $seed['title'];
        } else {
            $report['completados'][] = $seed['title'];
        }
        $ids[ $seed['slug'] ] = $post->ID;
        if ( ! get_the_terms( $post->ID, 'tipo_proyecto' ) ) { wp_set_object_terms( $post->ID, $seed['categories'], 'tipo_proyecto' ); }
        $values = array(
            'featured' => ! empty( $seed['featured'] ) ? '1' : '', 'primary_category' => $seed['primary'], 'specialties' => implode( ',', $seed['specialties'] ?? array() ),
        );
        if ( ! empty( $seed['demo'] ) && ! empty( $seed['capture_auto'] ) ) { $values['capture_auto'] = '1'; }
        foreach ( array( 'status', 'sector', 'period', 'role', 'context', 'problem', 'objective', 'architecture', 'features', 'challenges', 'security', 'results', 'stack', 'demo', 'repository', 'links' ) as $key ) {
            $values[ $key ] = $seed[ $key ] ?? '';
        }
        $updated = am_seed_apply_fields( $post, $seed, $values, $placeholder );
        if ( $updated && ! in_array( $seed['title'], $report['creados'], true ) ) { $report['textos_actualizados'][] = $seed['title']; }
        $gallery = array();
        foreach ( $seed['images'] ?? array() as $image ) {
            $attachment = am_seed_attachment( $image['file'], $image['alt'], $post->ID );
            if ( $attachment ) { $gallery[] = $attachment; } else { $report['errores'][] = 'Captura no disponible: ' . $image['file']; }
        }
        if ( $gallery && ! has_post_thumbnail( $post->ID ) ) { set_post_thumbnail( $post->ID, $gallery[0] ); }
        if ( $gallery && ! get_post_meta( $post->ID, '_am_gallery', true ) ) { update_post_meta( $post->ID, '_am_gallery', implode( ',', $gallery ) ); }
        update_post_meta( $post->ID, '_am_seed_version', AM_SEED_VERSION );
    }
    foreach ( am_seed_projects() as $seed ) {
        if ( empty( $seed['related'] ) || empty( $ids[ $seed['slug'] ] ) || get_post_meta( $ids[ $seed['slug'] ], '_am_related', true ) ) { continue; }
        $related = array_filter( array_map( function ( $slug ) use ( $ids ) { return $ids[ $slug ] ?? 0; }, $seed['related'] ) );
        if ( $related ) { update_post_meta( $ids[ $seed['slug'] ], '_am_related', implode( ',', $related ) ); }
    }

    am_seed_remove_unused_legacy_images( $report );
    flush_rewrite_rules( false );
    if ( $report['creados'] || $report['migrados'] || $report['textos_actualizados'] || $report['servicios_actualizados'] || $report['retirados'] ) { am_purge_page_cache(); }
    if ( ! $report['errores'] ) { update_option( 'am_portfolio_seed_version', AM_SEED_VERSION ); }
    return $report;
}

/**
 * Vacía la caché de páginas (LiteSpeed Cache) cuando el tema cambia contenido por su cuenta:
 * si no, la portada cacheada puede mostrar proyectos o capturas antiguos durante días.
 * La caché del CDN de Hostinger se vacía desde hPanel si no la purga LiteSpeed.
 */
function am_purge_page_cache() {
    do_action( 'litespeed_purge_all' );
    if ( function_exists( 'wp_cache_flush' ) ) { wp_cache_flush(); }
}

/** Preparación completa: tipos de contenido, páginas base y contenido inicial. */
function am_setup_portfolio() {
    am_register_servicios_cpt();
    am_register_projects();
    am_auto_create_services();
    am_auto_create_pages();
    am_create_legal_pages();
    return am_run_portfolio_import();
}
add_action( 'after_switch_theme', 'am_setup_portfolio' );

// Al actualizar los archivos del tema no se vuelve a activar: si la instalación ya importó una
// versión anterior, la migración se aplica en la siguiente visita de un administrador.
add_action( 'admin_init', function () {
    if ( wp_doing_ajax() || ! current_user_can( 'manage_options' ) ) { return; }
    $stored = (string) get_option( 'am_portfolio_seed_version' );
    if ( $stored === '' || $stored === AM_SEED_VERSION || get_transient( 'am_portfolio_seed_attempt' ) ) { return; }
    set_transient( 'am_portfolio_seed_attempt', 1, HOUR_IN_SECONDS ); // Si falla, no se reintenta en cada página.
    set_transient( 'am_portfolio_import_report', am_setup_portfolio(), 5 * MINUTE_IN_SECONDS );
} );

add_action( 'admin_notices', function () {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $report = get_transient( 'am_portfolio_import_report' );
    if ( $report ) {
        delete_transient( 'am_portfolio_import_report' );
        echo '<div class="notice notice-success is-dismissible"><p><strong>Merchan.Dev:</strong> importación terminada.</p><ul style="list-style:disc;padding-left:20px">';
        $labels = array( 'creados' => 'Creados', 'migrados' => 'Migrados', 'textos_actualizados' => 'Textos del tema actualizados (sin ediciones tuyas)', 'retirados' => 'Capturas antiguas retiradas', 'completados' => 'Revisados (tus ediciones se conservan)', 'servicios_actualizados' => 'Servicios con textos nuevos', 'servicios_personalizados' => 'Servicios editados por ti que no se modificaron', 'errores' => 'Errores' );
        foreach ( $labels as $key => $label ) {
            if ( ! empty( $report[ $key ] ) ) { echo '<li>' . esc_html( $label . ': ' . implode( ', ', $report[ $key ] ) ) . '</li>'; }
        }
        echo '</ul></div>';
        return;
    }
    if ( get_option( 'am_portfolio_seed_version' ) === AM_SEED_VERSION ) { return; }
    echo '<div class="notice notice-info"><p><strong>Merchan.Dev 2026:</strong> importa los proyectos, las categorías, las tres especialidades y los textos actualizados de los servicios. Es seguro repetirlo: no duplica registros ni sobrescribe contenido que hayas editado.</p><form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
    wp_nonce_field( 'am_setup_portfolio' );
    echo '<input type="hidden" name="action" value="am_setup_portfolio"><p><button class="button button-primary">Importar contenido del portafolio</button></p></form></div>';
} );

add_action( 'admin_post_am_setup_portfolio', function () {
    if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Acceso denegado.', 403 ); }
    check_admin_referer( 'am_setup_portfolio' );
    set_transient( 'am_portfolio_import_report', am_setup_portfolio(), 5 * MINUTE_IN_SECONDS );
    wp_safe_redirect( admin_url( 'edit.php?post_type=proyecto' ) );
    exit;
} );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    WP_CLI::add_command( 'merchandev importar', function () {
        $report = am_setup_portfolio();
        foreach ( $report as $key => $items ) { WP_CLI::log( $key . ': ' . ( $items ? implode( ', ', $items ) : '—' ) ); }
        $report['errores'] ? WP_CLI::error( 'Importación con errores.' ) : WP_CLI::success( 'Contenido del portafolio importado.' );
    } );
}
