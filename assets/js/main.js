document.addEventListener('DOMContentLoaded', () => {

    // -------------------------------------------------------------
    // CSS SPRING ANIMATIONS (RELIABLE REACT STYLE)
    // -------------------------------------------------------------
    // We use native IntersectionObserver to guarantee elements appear,
    // avoiding the GSAP opacity:0 bug where elements stay invisible if scroll breaks.
    
    // 1. Initial Hero Reveal
    setTimeout(() => {
        document.querySelectorAll('.fade-up').forEach(el => {
            el.classList.add('is-visible');
        });
    }, 100);

    // 2. Scroll Reveal for Sections
    const observerOptions = {
        root: null,
        rootMargin: '0px 0px 60px 0px', // Activa 60px ANTES de entrar al viewport
        threshold: 0
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));


    // -------------------------------------------------------------
    // SIDEBAR ACTIVE STATE (PC ONLY)
    // -------------------------------------------------------------
    // Get current path and highlight the correct menu item if not on home
    const currentPath = window.location.pathname;
    
    // We only want to apply this if we are NOT on the root/home page
    if (currentPath !== '/' && currentPath !== '/index.php') {
        const navItems = document.querySelectorAll('.sidebar-nav .nav-item');
        navItems.forEach(item => {
            if (item.classList.contains('nav-home')) return; // Ensure Home is NEVER highlighted

            const linkHref = item.getAttribute('href');
            if (!linkHref) return;

            try {
                const linkUrl = new URL(linkHref);
                if (linkUrl.pathname !== '/' && currentPath.startsWith(linkUrl.pathname)) {
                    item.classList.add('active-nav');
                }
            } catch (e) {
                // Ignore relative/anchor parsing errors
            }
        });
    }

    // -------------------------------------------------------------
    // GITHUB REPOS FETCH (3 repos específicos + lenguajes)
    // -------------------------------------------------------------
    const githubContainer = document.getElementById('github-repos-grid');
    if (githubContainer && typeof amData !== 'undefined' && Array.isArray(amData.github_repos)) {

        // Mapa de colores por lenguaje (colores oficiales de GitHub Linguist)
        const LANG_COLORS = {
            'JavaScript': '#f1e05a',
            'TypeScript': '#3178c6',
            'PHP':        '#4f5d95',
            'HTML':       '#e34c26',
            'CSS':        '#563d7c',
            'SCSS':       '#c6538c',
            'Python':     '#3572A5',
            'Shell':      '#89e051',
            'Dockerfile': '#384d54',
            'Nginx':      '#009639',
            'Vue':        '#41b883',
            'React':      '#61dafb',
        };
        const getLangColor = (lang) => LANG_COLORS[lang] || 'var(--color-vegeta-blue)';

        const getSvgIcon = (type) => {
            if (type === 'github')   return `<i class='bx bxl-github'         style='vertical-align:middle;font-size:22px;'></i>`;
            if (type === 'external') return `<i class='bx bx-link-external'   style='font-size:18px;vertical-align:middle;'></i>`;
            return '';
        };

        // Fetch repos y sus lenguajes en paralelo
        Promise.all(
            amData.github_repos.map(url =>
                fetch(url)
                    .then(r => r.json())
                    .then(repo => {
                        if (!repo || !repo.languages_url) return { repo, langs: {} };
                        return fetch(repo.languages_url)
                            .then(r => r.json())
                            .then(langs => ({ repo, langs }))
                            .catch(() => ({ repo, langs: {} }));
                    })
                    .catch(() => null)
            )
        )
        .then(results => {
            githubContainer.innerHTML = '';

            results.forEach((result, idx) => {
                if (!result || !result.repo || !result.repo.html_url) return;

                const { repo, langs } = result;
                const desc       = repo.description || 'Sin descripción disponible.';
                const delayClass = `delay-${(idx % 4) * 100 + 100}`;

                // Top 3 lenguajes ordenados por bytes
                const topLangs = Object.entries(langs)
                    .sort((a, b) => b[1] - a[1])
                    .slice(0, 3);

                // Si no hay datos de lenguajes, usar el principal del repo
                const langsHTML = topLangs.length > 0
                    ? topLangs.map(([lang]) => `
                        <span style="
                            display:inline-flex;align-items:center;gap:0.35rem;
                            background:rgba(255,255,255,0.06);
                            border:1px solid rgba(255,255,255,0.1);
                            border-radius:999px;padding:0.2rem 0.65rem;
                            font-size:0.78rem;color:var(--color-text-main);
                        ">
                            <span style="width:8px;height:8px;border-radius:50%;background:${getLangColor(lang)};flex-shrink:0;"></span>
                            ${lang}
                        </span>`).join('')
                    : `<span style="
                            display:inline-flex;align-items:center;gap:0.35rem;
                            background:rgba(255,255,255,0.06);
                            border:1px solid rgba(255,255,255,0.1);
                            border-radius:999px;padding:0.2rem 0.65rem;
                            font-size:0.78rem;color:var(--color-text-main);">
                            <span style="width:8px;height:8px;border-radius:50%;background:${getLangColor(repo.language)};flex-shrink:0;"></span>
                            ${repo.language || 'N/A'}
                        </span>`;

                const cardHTML = `
                    <a href="${repo.html_url}" target="_blank" rel="noopener noreferrer"
                       class="glass-card flex-column scroll-reveal ${delayClass}"
                       style="text-decoration:none;">

                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:1rem;">
                            <div style="display:flex;align-items:center;gap:0.5rem;color:var(--color-goku-orange);">
                                ${getSvgIcon('github')}
                                <h3 style="font-size:1.2rem;margin:0;color:var(--color-text-main);">${repo.name}</h3>
                            </div>
                            <div style="color:var(--color-text-muted);">${getSvgIcon('external')}</div>
                        </div>

                        <p style="color:var(--color-text-muted);font-size:0.95rem;flex-grow:1;margin-bottom:1.5rem;">${desc}</p>

                        <div style="display:flex;flex-wrap:wrap;gap:0.4rem;">
                            ${langsHTML}
                        </div>
                    </a>
                `;
                githubContainer.innerHTML += cardHTML;
            });

            // Activar scroll-reveal en las nuevas tarjetas
            githubContainer.querySelectorAll('.scroll-reveal')
                .forEach(el => setTimeout(() => observer.observe(el), 50));
        })
        .catch(err => {
            console.error('Error fetching github repos:', err);
            githubContainer.innerHTML = '<p class="text-center" style="grid-column:1/-1;">Error cargando repositorios.</p>';
        });
    }

    // -------------------------------------------------------------
    // AJAX SEARCH IN SIDEBAR
    // -------------------------------------------------------------
    const searchInput = document.getElementById('ajax-search-input');
    const searchResults = document.getElementById('ajax-search-results');
    let searchTimeout;

    if (searchInput && searchResults && typeof amData !== 'undefined') {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            clearTimeout(searchTimeout);

            if (query.length < 2) {
                searchResults.style.display = 'none';
                searchResults.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                const formData = new FormData();
                formData.append('action', 'am_search');
                formData.append('query', query);
                formData.append('security', amData.nonce);

                fetch(amData.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(response => {
                    if (response.success && response.data.length > 0) {
                        searchResults.innerHTML = response.data.map(item => `
                            <a href="${item.url}" class="search-result-item">
                                <i class='bx ${item.icon}'></i>
                                <span>${item.title}</span>
                            </a>
                        `).join('');
                        searchResults.style.display = 'flex';
                    } else {
                        searchResults.innerHTML = '<div class="search-result-item" style="color: var(--color-text-muted);">No hay resultados</div>';
                        searchResults.style.display = 'flex';
                    }
                })
                .catch(err => {
                    console.error("Search error:", err);
                });
            }, 300); // 300ms debounce
        });

        // Hide results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });
    }

    // -------------------------------------------------------------
    // CINEMATIC DYNAMIC STATS COUNTER
    // -------------------------------------------------------------
    const statsCard = document.getElementById('animated-stats-card');
    const statContentWrapper = document.getElementById('stat-content-wrapper');
    const statIconWrapper = document.getElementById('stat-icon-wrapper');
    const statIcon = document.getElementById('stat-icon');
    const statNumber = document.getElementById('stat-number');
    const statSuffix = document.getElementById('stat-suffix');
    const statLabel = document.getElementById('stat-label');

    if (statsCard && statNumber && typeof gsap !== 'undefined') {
        const statsData = [
            { num: 10, suffix: '+', label: 'Años de Experiencia', icon: 'bx-time-five' },
            { num: 100, suffix: '%', label: 'Satisfacción', icon: 'bx-star' },
            { num: 86, suffix: '+', label: 'Proyectos Entregados', icon: 'bx-rocket' },
            { num: 24, suffix: '/7', label: 'Soporte Activo', icon: 'bx-support' }
        ];

        let currentStatIndex = 0;
        let isAnimating = false;

        const playStatAnimation = (index) => {
            if (isAnimating) return;
            isAnimating = true;

            const stat = statsData[index];
            
            // Create a highly cinematic GSAP timeline
            const tl = gsap.timeline({
                onComplete: () => {
                    isAnimating = false;
                    // Trigger next animation safely after 5 seconds
                    gsap.delayedCall(5, () => {
                        currentStatIndex = (currentStatIndex + 1) % statsData.length;
                        playStatAnimation(currentStatIndex);
                    });
                }
            });

            // 1. Current Text scales down and fades out (if visible)
            tl.to(statContentWrapper, {
                opacity: 0,
                scale: 0.5,
                duration: 0.4,
                ease: "power2.in"
            }, 0);

            // 2. Setup the NEW icon and text data behind the scenes
            tl.call(() => {
                statIcon.className = `bx ${stat.icon}`;
                statSuffix.innerText = stat.suffix;
                statLabel.innerText = stat.label;
                statNumber.innerText = "0";
                
                // Fix: Reset blur filters from previous cycle
                gsap.set(statIconWrapper, { filter: "blur(0px)" });
            });

            // 3. New Icon pops in beautifully (Text is hidden)
            tl.fromTo(statIconWrapper, 
                { opacity: 0, scale: 0.1, rotation: -180 },
                { opacity: 1, scale: 1, rotation: 0, duration: 0.6, ease: "back.out(2)" }
            );

            // 4. Icon holds for a split second, then pulses
            tl.to(statIconWrapper, {
                scale: 1.15,
                duration: 0.2,
                yoyo: true,
                repeat: 1,
                ease: "power1.inOut"
            }, "+=0.3");

            // 5. Icon explodes/zooms OUT
            tl.to(statIconWrapper, {
                opacity: 0,
                scale: 3,
                filter: "blur(10px)",
                duration: 0.4,
                ease: "power2.in"
            }, "+=0.1");

            // 6. Text (Information) zooms IN from behind the icon explosion
            tl.fromTo(statContentWrapper,
                { opacity: 0, scale: 0.5, filter: "blur(10px)" },
                { opacity: 1, scale: 1, filter: "blur(0px)", duration: 0.6, ease: "back.out(1.5)" },
                "-=0.2" // Overlap slightly with the icon explosion
            );

            // 7. Number counts up (Fluid, no sticky end)
            let proxy = { val: 0 };
            tl.to(proxy, {
                val: stat.num,
                duration: 1.2, // Faster to avoid dragging
                ease: "power1.out", // Gentler ease so it doesn't stall at the last digit
                onUpdate: () => {
                    statNumber.innerText = Math.round(proxy.val);
                }
            }, "-=0.4");
        };

        // Start animation loop when the card comes into view
        ScrollTrigger.create({
            trigger: statsCard,
            start: "top 80%",
            onEnter: () => {
                // Instantly hide both so the timeline handles the grand entrance perfectly
                gsap.set(statContentWrapper, { opacity: 0 });
                gsap.set(statIconWrapper, { opacity: 0 });
                playStatAnimation(0);
            },
            once: true
        });
    }

    // -------------------------------------------------------------
    // CONTACT FORM AJAX SUBMISSION
    // -------------------------------------------------------------
    const contactForm = document.getElementById('nuclearContactForm');
    const formResponse = document.getElementById('form-response');

    if (contactForm && formResponse) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerText;
            
            submitBtn.innerText = 'Enviando...';
            submitBtn.disabled = true;
            formResponse.innerHTML = '';

            const formData = new FormData(contactForm);
            formData.append('action', 'am_submit_contact');
            formData.append('security', amData.nonce);

            fetch(amData.ajax_url, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    formResponse.innerHTML = `<span style="color: #4ade80;">${data.data}</span>`;
                    contactForm.reset();
                } else {
                    formResponse.innerHTML = `<span style="color: #f87171;">${data.data || 'Error al enviar.'}</span>`;
                }
            })
            .catch(err => {
                formResponse.innerHTML = `<span style="color: #f87171;">Error de conexión. Inténtalo más tarde.</span>`;
            })
            .finally(() => {
                submitBtn.innerText = originalBtnText;
                submitBtn.disabled = false;
            });
        });
    }

    // -------------------------------------------------------------
    // TYPING ANIMATION (HERO)
    // -------------------------------------------------------------
    const typingTextEl = document.getElementById('typing-text');
    if (typingTextEl) {
        const words = ["Arturo Merchán", "Merchan.Dev"];
        let wordIndex = 0;
        let charIndex = words[wordIndex].length; // Starts with the first word fully written
        let isDeleting = true;

        const type = () => {
            const currentWord = words[wordIndex];
            
            if (isDeleting) {
                typingTextEl.innerText = currentWord.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typingTextEl.innerText = currentWord.substring(0, charIndex + 1);
                charIndex++;
            }

            // Varied speeds for realistic typing/deleting
            let typeSpeed = isDeleting ? 40 : 100 + Math.random() * 50;

            if (!isDeleting && charIndex === currentWord.length) {
                // Wait 3 seconds after fully typing
                typeSpeed = 3000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                // Switch word after fully deleting
                isDeleting = false;
                wordIndex = (wordIndex + 1) % words.length;
                typeSpeed = 500;
            }

            setTimeout(type, typeSpeed);
        };

        // Start the loop after the initial read time
        setTimeout(type, 3000);
    }

    // -------------------------------------------------------------
    // COOKIE BANNER
    // -------------------------------------------------------------
    const cookieBanner = document.getElementById('cookie-banner');
    if (cookieBanner) {
        // ✅ Verificar localStorage PRIMERO: si ya tomó decisión, ocultar y no volver a mostrar
        const cookieDecision = localStorage.getItem('cookie_consent');
        if (cookieDecision) {
            // Ya decidió antes — ocultar el banner definitivamente sin animación
            cookieBanner.style.display = 'none';
        } else {
            // Primera vez — mostrar con animación de entrada
            setTimeout(() => {
                cookieBanner.style.display = 'flex';
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        cookieBanner.classList.add('show');
                    });
                });
            }, 1500);
        }

        const sendCookieDecision = (decision) => {
            // ✅ Guardar en localStorage INMEDIATAMENTE (antes de cualquier AJAX)
            // Esto evita que el banner reaparezca al navegar entre páginas
            localStorage.setItem('cookie_consent', decision);

            // Smooth hide
            cookieBanner.classList.remove('show');
            setTimeout(() => { cookieBanner.style.display = 'none'; }, 600);

            // Registrar en la base de datos de WordPress (por IP)
            if (typeof amData !== 'undefined' && amData.ajax_url) {
                const formData = new FormData();
                formData.append('action', 'am_submit_cookie');
                formData.append('security', amData.nonce);
                formData.append('decision', decision);

                fetch(amData.ajax_url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData
                }).catch(() => { /* Silent fail — no UX impact */ });
            }
        };

        const btnAccept = document.getElementById('btn-accept-cookies');
        const btnReject = document.getElementById('btn-reject-cookies');

        if (btnAccept) btnAccept.addEventListener('click', () => sendCookieDecision('accepted'));
        if (btnReject) btnReject.addEventListener('click', () => sendCookieDecision('rejected'));
    }

});

