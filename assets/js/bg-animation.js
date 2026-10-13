document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('bg-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');

    let width, height;
    let particles = [];

    // Dragon Ball themed colors
    // Goku Orange: #FF7A00, SSJ Yellow: #FFD700, Vegeta Blue: #0052C2
    const colors = ['rgba(255, 122, 0, 0.6)', 'rgba(255, 215, 0, 0.6)', 'rgba(0, 82, 194, 0.4)', 'rgba(255, 255, 255, 0.2)'];

    const mouse = {
        x: null,
        y: null,
        radius: 180
    };

    window.addEventListener('mousemove', (event) => {
        mouse.x = event.clientX;
        mouse.y = event.clientY;
    });

    window.addEventListener('mouseout', () => {
        mouse.x = null;
        mouse.y = null;
    });

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }

    window.addEventListener('resize', resize);
    resize();

    class Particle {
        constructor() {
            this.x = Math.random() * width;
            this.y = Math.random() * height;
            this.vx = (Math.random() - 0.5) * 0.5;
            this.vy = (Math.random() - 0.5) * 0.5;
            this.radius = Math.random() * 2 + 1;
            this.color = colors[Math.floor(Math.random() * colors.length)];
        }

        update() {
            this.x += this.vx;
            this.y += this.vy;

            // Mouse interaction
            if (mouse.x != null && mouse.y != null) {
                const dx = mouse.x - this.x;
                const dy = mouse.y - this.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                if (distance < mouse.radius) {
                    const forceDirectionX = dx / distance;
                    const forceDirectionY = dy / distance;
                    const force = (mouse.radius - distance) / mouse.radius;
                    // Move particle away from mouse
                    this.x -= forceDirectionX * force * 3;
                    this.y -= forceDirectionY * force * 3;
                }
            }

            if (this.x < 0 || this.x > width) this.vx *= -1;
            if (this.y < 0 || this.y > height) this.vy *= -1;
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.fill();
        }
    }

    function init() {
        particles = [];
        const numParticles = Math.min(Math.floor((width * height) / 10000), 100);
        for (let i = 0; i < numParticles; i++) {
            particles.push(new Particle());
        }
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);

        for (let i = 0; i < particles.length; i++) {
            particles[i].update();
            particles[i].draw();

            // Draw connecting lines
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (dist < 150) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = `rgba(255, 255, 255, ${0.1 * (1 - dist / 150)})`;
                    ctx.lineWidth = 0.5;
                    ctx.stroke();
                }
            }

            // Connection to mouse
            if (mouse.x != null && mouse.y != null) {
                const dxMouse = particles[i].x - mouse.x;
                const dyMouse = particles[i].y - mouse.y;
                const distMouse = Math.sqrt(dxMouse * dxMouse + dyMouse * dyMouse);
                if (distMouse < mouse.radius) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(mouse.x, mouse.y);
                    ctx.strokeStyle = `rgba(255, 122, 0, ${0.3 * (1 - distMouse / mouse.radius)})`;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                }
            }
        }

        // Draw "Nebula" subtle glows
        const time = Date.now() * 0.0005;
        const gokuGlow = ctx.createRadialGradient(
            width * 0.2 + Math.sin(time) * 100, height * 0.3 + Math.cos(time) * 100, 0,
            width * 0.2, height * 0.3, width * 0.5
        );
        gokuGlow.addColorStop(0, 'rgba(255, 122, 0, 0.05)');
        gokuGlow.addColorStop(1, 'transparent');

        ctx.fillStyle = gokuGlow;
        ctx.fillRect(0, 0, width, height);

        const vegetaGlow = ctx.createRadialGradient(
            width * 0.8 + Math.cos(time * 0.8) * 100, height * 0.7 + Math.sin(time * 0.8) * 100, 0,
            width * 0.8, height * 0.7, width * 0.5
        );
        vegetaGlow.addColorStop(0, 'rgba(0, 82, 194, 0.05)');
        vegetaGlow.addColorStop(1, 'transparent');

        ctx.fillStyle = vegetaGlow;
        ctx.fillRect(0, 0, width, height);


        requestAnimationFrame(animate);
    }

    // Respect user's reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    if (!prefersReducedMotion.matches) {
        init();
        animate();
    } else {
        // Fallback static draw
        init();
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => p.draw());
    }
});
