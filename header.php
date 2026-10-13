<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <!-- Fallback fonts if not queued via wp, but we use system UI generally for speed, or basic webfonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/assets/image/favicon.png">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content">Saltar al contenido</a>
<aside id="app-sidebar" class="app-sidebar">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="sidebar-header">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/ICONO2026 - MERCHAN.DEV.png" alt="Icono Merchan.Dev" class="logo-icon-img">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/image/LOGO2026 - MERCHAN.DEV.png" alt="Logo Merchan.Dev" class="logo-text-img">
    </a>
    
    <div class="sidebar-search">
        <i class='bx bx-search search-icon'></i>
        <input type="text" id="ajax-search-input" placeholder="Buscar" aria-label="Buscar en el sitio" autocomplete="off">
        <div id="ajax-search-results" class="search-results-dropdown" aria-live="polite"></div>
    </div>

    <nav class="sidebar-nav" aria-label="Navegación principal">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item nav-home" aria-label="Inicio">
            <i class='bx bx-home-alt'></i>
            <span class="nav-text">Inicio</span>
        </a>
        <a href="<?php echo esc_url(home_url('/proyectos/')); ?>" class="nav-item nav-proyectos" aria-label="Proyectos"><i class="bx bx-code-block" aria-hidden="true"></i><span class="nav-text">Proyectos</span></a>
        <a href="<?php echo esc_url(home_url('/servicios/')); ?>" class="nav-item" aria-label="Servicios">
            <i class='bx bx-shopping-bag'></i>
            <span class="nav-text">Servicios</span>
        </a>
        <a href="<?php echo esc_url(home_url('/sobre-mi/')); ?>" class="nav-item" aria-label="Sobre mí">
            <i class='bx bx-user'></i>
            <span class="nav-text">Sobre mí</span>
        </a>
        <a href="<?php echo esc_url(home_url('/contacto/')); ?>" class="nav-item nav-contacto" aria-label="Contacto">
            <i class='bx bx-envelope'></i>
            <span class="nav-text">Contacto</span>
        </a>
        <a href="<?php echo esc_url(home_url('/blog/')); ?>" class="nav-item" aria-label="Blog">
            <i class='bx bx-file'></i>
            <span class="nav-text">Blog</span>
        </a>
        <a href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer" class="nav-item nav-github" aria-label="GitHub">
            <i class='bx bxl-github'></i>
            <span class="nav-text">GitHub</span>
        </a>
    </nav>
</aside>

<div class="app-container">
    <canvas id="bg-canvas" aria-hidden="true"></canvas>
    <div class="bg-glow"></div>
    <main id="main-content" tabindex="-1">
