<?php
/**
 * Capacidades técnicas organizadas por función.
 *
 * Niveles: p = principal (uso recurrente en varios proyectos), a = aplicada (implementada en
 * proyectos concretos), c = complementaria (parte de un sistema o herramienta de apoyo).
 * Cada tecnología aparece en al menos un repositorio revisado (ver PORTAFOLIO_CONTENIDO_2026.md).
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function am_capability_levels() {
    return array( 'p' => 'Principal', 'a' => 'Aplicada', 'c' => 'Complementaria' );
}

function am_capabilities() {
    return array(
        array( 'Lenguajes', 'bx-code-alt', array(
            'PHP' => 'p', 'TypeScript' => 'p', 'JavaScript' => 'p', 'SQL' => 'p', 'HTML' => 'p', 'CSS' => 'p',
            'Bash' => 'a', 'Liquid' => 'a', 'Python' => 'c', 'Kotlin' => 'c', 'PowerShell' => 'c',
        ) ),
        array( 'Frontend', 'bx-layout', array(
            'React' => 'p', 'Vite' => 'p', 'Tailwind CSS' => 'p', 'Next.js' => 'a', 'React Router' => 'a',
            'Alpine.js' => 'c', 'Swup' => 'c', 'GSAP' => 'c', 'Framer Motion' => 'c',
        ) ),
        array( 'Backend', 'bx-terminal', array(
            'PHP modular y POO' => 'p', 'APIs REST' => 'p', 'WordPress APIs (hooks, CPT, REST, AJAX)' => 'p',
            'Node.js' => 'a', 'NestJS + Fastify' => 'a', 'Laravel + Sanctum' => 'a', 'Socket.IO' => 'a', 'Workers y tareas programadas' => 'a',
        ) ),
        array( 'Aplicaciones móviles', 'bx-mobile-alt', array(
            'React Native' => 'a', 'Expo' => 'a', 'Integración con API compartida' => 'a', 'Modo sin conexión cifrado' => 'a',
            'PWA con Workbox' => 'a', 'Android (Gradle)' => 'c',
        ) ),
        array( 'Bases de datos', 'bx-data', array(
            'MySQL / MariaDB' => 'p', 'PostgreSQL' => 'a', 'Prisma ORM' => 'a', 'PDO' => 'p', 'Redis' => 'a',
            'IndexedDB (Dexie)' => 'a', 'Meilisearch' => 'c', 'MinIO / S3' => 'c',
        ) ),
        array( 'Plataformas comerciales y CMS', 'bx-store-alt', array(
            'WordPress (temas y plugins a medida)' => 'p', 'WooCommerce' => 'a', 'Shopify / Liquid' => 'a',
            'Redsys' => 'a', 'Google Maps Platform' => 'a', 'Google Site Kit' => 'c',
        ) ),
        array( 'DevOps e infraestructura', 'bx-server', array(
            'Docker' => 'p', 'Docker Compose' => 'p', 'Linux / VPS' => 'a', 'Nginx' => 'a', 'Caddy' => 'a', 'Traefik' => 'a',
            'Certificados TLS' => 'a', 'GitHub Actions (CI/CD)' => 'a', 'Healthchecks y scripts de despliegue' => 'a', 'Backups y prueba de restauración' => 'c',
        ) ),
        array( 'Seguridad', 'bx-shield-quarter', array(
            'Roles y permisos por recurso' => 'p', 'Nonces y capacidades de WordPress' => 'p', 'JWT y refresh tokens' => 'a', 'MFA' => 'a',
            'Argon2 / bcrypt' => 'a', 'Cifrado de campos AES-256-GCM' => 'a', 'Auditoría de acciones' => 'a', 'Validación (Zod, class-validator)' => 'a',
            'Rate limiting' => 'a', 'Gitleaks' => 'c', 'npm / Composer audit' => 'c', 'OWASP ZAP en CI' => 'c',
        ) ),
        array( 'Comunicaciones', 'bx-envelope', array(
            'SMTP' => 'p', 'PHPMailer' => 'a', 'Nodemailer' => 'a', 'Colas y workers de correo' => 'a', 'Outbox transaccional' => 'a',
            'React Email' => 'c', 'Listas, segmentos y bajas' => 'c', 'Postfix + OpenDKIM (configuración)' => 'c',
        ) ),
        array( 'Multimedia', 'bx-broadcast', array(
            'Icecast' => 'a', 'Nginx RTMP' => 'a', 'HLS' => 'a', 'FFmpeg' => 'c', 'Encoders OBS, vMix y Opticodec' => 'c',
        ) ),
        array( 'Calidad y pruebas', 'bx-check-shield', array(
            'TypeScript typecheck' => 'p', 'PHPUnit' => 'a', 'Vitest' => 'a', 'Playwright' => 'a', 'PHPStan' => 'a',
            'PHP_CodeSniffer / WPCS' => 'a', 'ESLint' => 'a', 'Shopify Theme Check' => 'c',
        ) ),
    );
}
