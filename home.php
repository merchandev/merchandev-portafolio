<?php
/**
 * Blog Template (home.php)
 */
get_header(); ?>

<style>
    .blog-page-wrapper { padding-top: 100px; min-height: 100vh; }
    @media (max-width: 768px) {
        .blog-page-wrapper { padding-top: 60px; }
        .blog-hero-section { padding-top: 1rem !important; }
    }
</style>
<div class="blog-page-wrapper">
    <!-- ========================================== -->
    <!-- BLOG HERO                                  -->
    <!-- ========================================== -->
    <section class="section-padding bg-gradient blog-hero-section" style="padding-bottom: 20px; position: relative; z-index: 999;">
        <div class="container">
            <div class="section-header text-center scroll-reveal">
                <span class="section-tag">Artículos y Tutoriales</span>
                <h1 class="section-title">El Blog</h1>
                <p class="section-subtitle mx-auto">Explorando el desarrollo Full-Stack, infraestructuras en la nube, y las mejores prácticas en el ecosistema digital.</p>
                
                <!-- Buscador AJAX -->
                <div style="max-width: 600px; margin: 2rem auto 0; position: relative; text-align: left; z-index: 50;">
                    <div style="display: flex; align-items: center; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 50px; padding: 0.5rem 1.5rem; backdrop-filter: blur(10px);">
                        <span class="material-symbols-outlined" style="color: var(--color-text-muted); margin-right: 10px;">search</span>
                        <input type="text" id="blog-ajax-search" placeholder="Buscar artículos..." style="background: transparent; border: none; color: var(--color-text-main); width: 100%; outline: none; font-size: 1rem; padding: 0.5rem 0;">
                    </div>
                    <div id="blog-search-results" style="position: absolute; top: 100%; left: 0; right: 0; background: #0f111a; border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; margin-top: 10px; z-index: 9999; max-height: 400px; overflow-y: auto; display: none; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                        <!-- Resultados generados por JS -->
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ========================================== -->
    <!-- POSTS GRID                                 -->
    <!-- ========================================== -->
    <section class="section-padding" style="padding-top: 0;">
        <div class="container">
            
            <style>
                /* Layout para mover el menú de años en móvil */
                .blog-years-content-wrapper {
                    display: flex;
                    flex-direction: column;
                }
                .years-filter-menu-container {
                    order: 2; /* En móvil, va después de los posts */
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 12px;
                    margin-top: 3rem;
                }
                .blog-posts-container {
                    order: 1; /* En móvil, los posts van primero */
                }
                @media (min-width: 768px) {
                    .years-filter-menu-container {
                        order: 1; /* En escritorio, menú arriba */
                        margin-top: 0;
                        margin-bottom: 3rem;
                    }
                    .blog-posts-container {
                        order: 2;
                    }
                }

                /* Estilos de botones de año */
                .year-filter-btn:hover { background: rgba(255,255,255,0.1) !important; transform: translateY(-2px); }
                .year-filter-btn.active { background: var(--color-goku-orange) !important; color: #fff !important; border-color: var(--color-goku-orange) !important; box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3); }
                #blog-grid { transition: opacity 0.3s ease; }
                #blog-grid.loading { opacity: 0.5; pointer-events: none; }

                /* Paginación Mejorada */
                #blog-pagination {
                    margin-top: 4rem; /* Más despegado de los artículos */
                    padding-bottom: 2rem;
                }
                #blog-pagination .screen-reader-text {
                    display: block;
                    margin-bottom: 1.5rem;
                    font-size: 1.8rem;
                    font-weight: 700;
                    color: var(--color-text-main);
                }
                #blog-pagination .nav-links {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                #blog-pagination .page-numbers {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    min-width: 45px;
                    height: 45px;
                    padding: 0 15px;
                    border-radius: 12px;
                    background: rgba(255, 255, 255, 0.05);
                    color: var(--color-text-main);
                    text-decoration: none;
                    font-weight: 600;
                    border: 1px solid rgba(255, 255, 255, 0.1);
                    transition: all 0.3s ease;
                }
                #blog-pagination .page-numbers:hover {
                    background: rgba(255, 255, 255, 0.1);
                    transform: translateY(-2px);
                }
                #blog-pagination .page-numbers.current {
                    background: var(--color-goku-orange);
                    color: #fff;
                    border-color: var(--color-goku-orange);
                    box-shadow: 0 4px 15px rgba(255, 107, 0, 0.3);
                }
                #blog-pagination .next, #blog-pagination .prev {
                    padding: 0 20px;
                    background: transparent;
                }
                #blog-pagination .next:hover, #blog-pagination .prev:hover {
                    background: rgba(255,255,255,0.05);
                }
            </style>

            <div class="blog-years-content-wrapper">
                
                <!-- Menú de Años -->
                <div class="years-filter-menu-container" id="years-filter-menu">
                    <?php 
                    $current_year = isset($_GET['filtro_ano']) ? intval($_GET['filtro_ano']) : 2026;
                    $years = array(2026, 2025, 2024, 2023, 2022, 2021, 2020);
                    foreach($years as $y) : 
                        $active_class = ($current_year === $y) ? 'active' : '';
                    ?>
                        <button class="year-filter-btn <?php echo $active_class; ?>" data-year="<?php echo $y; ?>" style="padding: 10px 24px; border-radius: 50px; background: rgba(255,255,255,0.05); color: var(--color-text-main); border: 1px solid rgba(255,255,255,0.1); cursor: pointer; font-weight: 600; font-size: 1rem; transition: all 0.3s ease;">
                            <?php echo $y; ?>
                        </button>
                    <?php endforeach; ?>
                </div>

                <div id="blog-content-wrapper" class="blog-posts-container">
                <?php if ( have_posts() ) : ?>
                    <div class="grid-3" id="blog-grid">
                        <?php while ( have_posts() ) : the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="glass-card flex-column scroll-reveal" style="text-decoration: none;">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div style="margin: -2.5rem -2.5rem 1.5rem -2.5rem; border-radius: 24px 24px 0 0; overflow: hidden; height: 200px;">
                                        <?php the_post_thumbnail( 'large', array( 'style' => 'width: 100%; height: 100%; object-fit: cover;' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body" style="flex-grow: 1;">
                                    <div style="font-size: 0.85rem; color: var(--color-goku-orange); margin-bottom: 0.5rem; font-weight: 600;">
                                        <?php echo get_the_date(); ?>
                                    </div>
                                    <h3 style="color: var(--color-text-main); font-size: 1.3rem; margin-bottom: 1rem; line-height: 1.3;">
                                        <?php the_title(); ?>
                                    </h3>
                                    <p style="color: var(--color-text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
                                        <?php echo wp_trim_words( get_the_excerpt(), 20, '...' ); ?>
                                    </p>
                                </div>
                                <div style="display: flex; align-items: center; color: var(--color-ssj-yellow); font-weight: 600; font-size: 0.9rem; margin-top: auto;">
                                    Leer artículo <span class="material-symbols-outlined" style="font-size: 18px; margin-left: 4px;">arrow_forward</span>
                                </div>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="mt-5 text-center" id="blog-pagination">
                        <?php 
                        the_posts_pagination( array(
                            'mid_size'  => 2,
                            'prev_text' => __( 'Anterior', 'textdomain' ),
                            'next_text' => __( 'Siguiente', 'textdomain' ),
                            'screen_reader_text' => __( 'Más artículos', 'textdomain' ),
                        ) ); 
                        ?>
                    </div>
                <?php else : ?>
                    <div class="glass-card text-center" id="blog-grid" style="grid-column: 1 / -1;">
                        <h3 style="color: var(--color-text-main);">Aún no hay artículos</h3>
                        <p style="color: var(--color-text-muted);">Pronto publicaré contenido aquí.</p>
                    </div>
                    <div id="blog-pagination"></div>
                <?php endif; ?>
            </div> <!-- End blog-content-wrapper -->
            </div> <!-- End blog-years-content-wrapper -->
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ----------------------------------------------------
    // LÓGICA DE BÚSQUEDA AJAX
    // ----------------------------------------------------
    const searchInput = document.getElementById('blog-ajax-search');
    const resultsContainer = document.getElementById('blog-search-results');
    let searchTimeoutId;

    if(searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeoutId);
            const query = this.value;

            if (query.length < 2) {
                resultsContainer.style.display = 'none';
                return;
            }

            searchTimeoutId = setTimeout(() => {
                const formData = new FormData();
                formData.append('action', 'am_search');
                formData.append('security', amData.nonce);
                formData.append('query', query);

                fetch(amData.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        resultsContainer.innerHTML = '';
                        data.data.forEach(item => {
                            resultsContainer.innerHTML += `
                                <a href="${item.url}" style="display: flex; align-items: flex-start; padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.05); text-decoration: none; color: var(--color-text-main); transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.05)'" onmouseout="this.style.background='transparent'">
                                    <span class="material-symbols-outlined" style="margin-right: 16px; margin-top: 2px; color: var(--color-goku-orange); font-size: 1.8rem; flex-shrink: 0;">article</span>
                                    <span style="font-weight: 500; font-size: 1.2rem; line-height: 1.4; text-align: left;">${item.title}</span>
                                </a>
                            `;
                        });
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div style="padding: 16px 24px; color: var(--color-text-muted); font-size: 1.1rem;">No se encontraron resultados.</div>';
                        resultsContainer.style.display = 'block';
                    }
                })
                .catch(err => console.error('Error in AJAX search:', err));
            }, 400);
        });

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    }

    // ----------------------------------------------------
    // LÓGICA DE FILTRADO POR AÑO AJAX
    // ----------------------------------------------------
    const yearButtons = document.querySelectorAll('.year-filter-btn');
    const blogGrid = document.getElementById('blog-grid');
    const blogPagination = document.getElementById('blog-pagination');
    let currentYear = <?php echo isset($_GET['filtro_ano']) ? intval($_GET['filtro_ano']) : 2026; ?>;

    function loadBlogPosts(year, paged = 1) {
        if (!blogGrid) return;
        
        // Efecto visual de carga
        blogGrid.classList.add('loading');
        if(blogPagination) blogPagination.style.opacity = '0.5';
        
        const formData = new FormData();
        formData.append('action', 'am_filter_blog');
        formData.append('security', amData.nonce);
        formData.append('year', year);
        formData.append('paged', paged);

        fetch(amData.ajax_url, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                blogGrid.innerHTML = data.data.cards;
                if (blogPagination) {
                    blogPagination.innerHTML = data.data.pagination;
                    blogPagination.style.opacity = '1';
                }
                
                // Actualizar URL sin recargar
                const newUrl = new URL(window.location);
                newUrl.searchParams.set('filtro_ano', year);
                window.history.pushState({}, '', newUrl);
            }
        })
        .catch(err => console.error('Error fetching blog posts:', err))
        .finally(() => {
            blogGrid.classList.remove('loading');
            
            // Scroll suave hacia el inicio de los artículos (para móviles y escritorio)
            const wrapper = document.getElementById('blog-content-wrapper');
            if(wrapper) {
                const yOffset = -100; 
                const y = wrapper.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({top: y, behavior: 'smooth'});
            }
        });
    }

    // Eventos clic en botones de año
    yearButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            yearButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const selectedYear = this.getAttribute('data-year');
            currentYear = selectedYear;
            loadBlogPosts(selectedYear, 1);
        });
    });

    // Interceptar clics en la paginación para evitar recarga
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('#blog-pagination a.page-numbers');
        if (paginationLink) {
            e.preventDefault();
            let paged = 1;
            const url = new URL(paginationLink.href, window.location.origin);
            if (url.searchParams.has('paged')) {
                paged = url.searchParams.get('paged');
            } else {
                const match = url.pathname.match(/\/page\/(\d+)/);
                if (match) {
                    paged = match[1];
                }
            }
            loadBlogPosts(currentYear, paged);
        }
    });
});
</script>

<?php get_footer(); ?>
