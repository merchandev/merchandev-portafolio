document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('bg-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    let frame = null;

    let width, height;
    let particles = [];

    // Dragon Ball themed colors
    // Goku Orange: #FF7A00, SSJ Yellow: #FFD700, Vegeta Blue: #0052C2
    const colors = ['rgba(255, 122, 0, 0.6)', 'rgba(255, 215, 0, 0.6)', 'rgba(0, 82, 194, 0.4)', 'rgba(255, 255, 255, 0.2)'];

    const LINK_DISTANCE = 150;
    const LINK_DISTANCE_SQ = LINK_DISTANCE * LINK_DISTANCE;
    const BUCKETS = 8; // Las líneas se agrupan por opacidad: un stroke por grupo en lugar de uno por línea.
    // Equipos modestos (pocos núcleos) dibujan menos partículas; el aspecto se mantiene.
    const density = (navigator.hardwareConcurrency || 8) <= 4 ? 0.7 : 1;

    const mouse = {
        x: null,
        y: null,
        radius: 180
    };

    window.addEventListener('mousemove', (event) => {
        mouse.x = event.clientX;
        mouse.y = event.clientY;
    }, { passive: true });

    window.addEventListener('mouseout', () => {
        mouse.x = null;
        mouse.y = null;
    }, { passive: true });

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
    }

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
                if (distance > 0 && distance < mouse.radius) {
                    const force = (mouse.radius - distance) / mouse.radius;
                    // Move particle away from mouse
                    this.x -= (dx / distance) * force * 3;
                    this.y -= (dy / distance) * force * 3;
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
        const numParticles = Math.floor(Math.min(Math.floor((width * height) / 10000), 100) * density);
        for (let i = 0; i < numParticles; i++) {
            particles.push(new Particle());
        }
    }

    const linkBuckets = Array.from({ length: BUCKETS }, () => []);
    const mouseBuckets = Array.from({ length: BUCKETS }, () => []);

    function strokeBuckets(buckets, color, maxAlpha, lineWidth) {
        ctx.lineWidth = lineWidth;
        for (let b = 0; b < BUCKETS; b++) {
            const segments = buckets[b];
            if (!segments.length) continue;
            ctx.beginPath();
            for (let s = 0; s < segments.length; s += 4) {
                ctx.moveTo(segments[s], segments[s + 1]);
                ctx.lineTo(segments[s + 2], segments[s + 3]);
            }
            ctx.strokeStyle = `rgba(${color}, ${maxAlpha * (b + 0.5) / BUCKETS})`;
            ctx.stroke();
            segments.length = 0;
        }
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        const hasMouse = mouse.x != null && mouse.y != null;
        const mouseRadiusSq = mouse.radius * mouse.radius;

        for (let i = 0; i < particles.length; i++) {
            const p = particles[i];
            p.update();
            p.draw();

            // Connecting lines
            for (let j = i + 1; j < particles.length; j++) {
                const q = particles[j];
                const dx = p.x - q.x;
                const dy = p.y - q.y;
                const distSq = dx * dx + dy * dy;
                if (distSq < LINK_DISTANCE_SQ) {
                    const strength = 1 - Math.sqrt(distSq) / LINK_DISTANCE;
                    linkBuckets[Math.min(BUCKETS - 1, Math.floor(strength * BUCKETS))].push(p.x, p.y, q.x, q.y);
                }
            }

            // Connection to mouse
            if (hasMouse) {
                const dxMouse = p.x - mouse.x;
                const dyMouse = p.y - mouse.y;
                const distMouseSq = dxMouse * dxMouse + dyMouse * dyMouse;
                if (distMouseSq < mouseRadiusSq) {
                    const strength = 1 - Math.sqrt(distMouseSq) / mouse.radius;
                    mouseBuckets[Math.min(BUCKETS - 1, Math.floor(strength * BUCKETS))].push(p.x, p.y, mouse.x, mouse.y);
                }
            }
        }

        strokeBuckets(linkBuckets, '255, 255, 255', 0.1, 0.5);
        if (hasMouse) strokeBuckets(mouseBuckets, '255, 122, 0', 0.3, 1);

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

        frame = requestAnimationFrame(animate);
    }

    function drawStatic() {
        ctx.clearRect(0, 0, width, height);
        particles.forEach(p => p.draw());
    }

    // Respect user's reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const updateMotion = () => {
        if (frame !== null) { cancelAnimationFrame(frame); frame = null; }
        if (prefersReducedMotion.matches) { drawStatic(); return; }
        if (!document.hidden) animate();
    };
    document.addEventListener('visibilitychange', updateMotion);
    prefersReducedMotion.addEventListener('change', updateMotion);

    // Redimensionar regenera las partículas una sola vez cuando termina el cambio de tamaño.
    let resizeTimer = null;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => { resize(); init(); if (prefersReducedMotion.matches) drawStatic(); }, 150);
    }, { passive: true });

    init();
    updateMotion();
});
