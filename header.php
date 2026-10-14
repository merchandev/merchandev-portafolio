<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#020617">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://unpkg.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&family=Orbitron:wght@700;900&display=swap" rel="stylesheet">
    <?php // Solo los iconos de Material Symbols que usa el tema (subconjunto: menos peso de fuente). ?>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back,arrow_forward,article,calendar_today,home,keyboard_arrow_down,search&display=block" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="<?php echo esc_url( get_template_directory_uri() . '/assets/image/favicon.png' ); ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content">Saltar al contenido</a>
<aside id="app-sidebar" class="app-sidebar">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sidebar-header" aria-label="Merchan.Dev, inicio">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/image/ICONO2026 - MERCHAN.DEV.png' ); ?>" alt="" class="logo-icon-img" width="52" height="52">
        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/image/LOGO2026 - MERCHAN.DEV.png' ); ?>" alt="" class="logo-text-img" height="42">
    </a>

    <div class="sidebar-search" role="search">
        <i class="bx bx-search search-icon" aria-hidden="true"></i>
        <input type="search" id="ajax-search-input" placeholder="Buscar" aria-label="Buscar en el sitio" autocomplete="off" aria-controls="ajax-search-results">
        <div id="ajax-search-results" class="search-results-dropdown" aria-live="polite"></div>
    </div>

    <nav class="sidebar-nav" aria-label="Navegación principal">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-item nav-home">
            <i class="bx bx-home-alt" aria-hidden="true"></i>
            <span class="nav-text">Inicio</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/proyectos/' ) ); ?>" class="nav-item nav-proyectos">
            <i class="bx bx-code-block" aria-hidden="true"></i>
            <span class="nav-text">Proyectos</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/servicios/' ) ); ?>" class="nav-item nav-servicios">
            <i class="bx bx-shopping-bag" aria-hidden="true"></i>
            <span class="nav-text">Servicios</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/sobre-mi/' ) ); ?>" class="nav-item nav-sobre">
            <i class="bx bx-user" aria-hidden="true"></i>
            <span class="nav-text">Sobre mí</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>" class="nav-item nav-contacto">
            <i class="bx bx-envelope" aria-hidden="true"></i>
            <span class="nav-text">Contacto</span>
        </a>
        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="nav-item nav-blog">
            <i class="bx bx-file" aria-hidden="true"></i>
            <span class="nav-text">Blog</span>
        </a>
        <a href="https://github.com/merchandev" target="_blank" rel="noopener noreferrer" class="nav-item nav-github">
            <i class="bx bxl-github" aria-hidden="true"></i>
            <span class="nav-text">GitHub<span class="screen-reader-text"> (se abre en una pestaña nueva)</span></span>
        </a>
    </nav>
</aside>

<div class="app-container">
    <canvas id="bg-canvas" aria-hidden="true"></canvas>
    <div class="bg-glow"></div>
    <main id="main-content" tabindex="-1">
