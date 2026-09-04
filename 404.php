<?php
/**
 * The template for displaying 404 pages (not found)
 */
get_header(); ?>

<div class="error-404-wrapper">
    <div class="container text-center" style="max-width: 800px; padding: 0 20px;">
        <h1 class="error-title">404</h1>
        <h2 class="error-subtitle">Página no encontrada</h2>
        <p class="error-text">Parece que te has perdido en el ciberespacio... ¡Pero no te vayas sin jugar un rato para romper tu récord!</p>
        
        <!-- Game Container -->
        <div class="dino-game-container">
            <div id="game-score">Puntuación: <span>0</span> | Récord: <span id="high-score">0</span></div>
            <div id="game-over-msg" class="hidden">
                ¡Juego Terminado! <br><span>Toca o presiona espacio para reiniciar</span>
            </div>
            <canvas id="dinoCanvas" width="800" height="450"></canvas>
        </div>

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn" style="margin-top: 1rem; display: inline-flex; align-items: center; justify-content: center; background: var(--color-goku-orange); color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; transition: background 0.3s;">
            Volver al inicio <span class="material-symbols-outlined" style="font-size: 18px; margin-left: 8px;">home</span>
        </a>
    </div>
</div>

<style>
    .error-404-wrapper {
        padding-top: 120px;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-bottom: 5rem;
    }
    
    .error-title {
        font-family: var(--font-display, 'Orbitron', sans-serif);
        font-size: clamp(5rem, 15vw, 10rem);
        font-weight: 900;
        color: var(--color-goku-orange);
        line-height: 1;
        margin-bottom: 1rem;
        text-shadow: 0 0 20px rgba(249, 115, 22, 0.4);
    }
    
    .error-subtitle {
        font-family: var(--font-display, 'Orbitron', sans-serif);
        font-size: clamp(1.5rem, 4vw, 2.5rem);
        color: var(--color-text-main);
        margin-bottom: 1rem;
    }
    
    .error-text {
        color: var(--color-text-muted);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 3rem;
    }
    
    .dino-game-container {
        margin: 0 auto 2rem;
        position: relative;
        width: 100%;
        max-width: 800px;
    }
    
    #dinoCanvas {
        background: rgba(10, 15, 30, 0.5);
        border: 2px solid rgba(249, 115, 22, 0.3);
        border-radius: 12px;
        backdrop-filter: blur(5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        cursor: pointer;
        display: block;
        width: 100%;
        height: auto;
        max-height: 450px;
        touch-action: none; /* Previene scroll en móvil al jugar */
    }
    
    #game-score {
        text-align: right;
        font-family: 'Orbitron', monospace;
        color: var(--color-ssj-yellow);
        font-size: 0.9rem;
        margin-bottom: 10px;
        letter-spacing: 1px;
    }
    
    #game-over-msg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        background: rgba(10,15,30,0.85);
        padding: 1rem 2rem;
        border-radius: 8px;
        border: 1px solid var(--color-goku-orange);
        color: var(--color-goku-orange);
        font-family: 'Orbitron', sans-serif;
        font-weight: bold;
        pointer-events: none;
        z-index: 10;
        width: 80%;
    }
    
    #game-over-msg span {
        font-size: 0.8rem;
        color: var(--color-text-muted);
        font-family: var(--font-body, 'Inter', sans-serif);
        font-weight: normal;
    }
    
    .hidden {
        display: none !important;
    }

    @media (max-width: 768px) {
        .error-404-wrapper { padding-top: 100px; }
        .error-title { margin-bottom: 0.5rem; }
        .error-text { margin-bottom: 2rem; font-size: 0.95rem; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('dinoCanvas');
    if(!canvas) return;
    
    const ctx = canvas.getContext('2d');
    const scoreElement = document.querySelector('#game-score span');
    const highScoreElement = document.getElementById('high-score');
    const gameOverMsg = document.getElementById('game-over-msg');
    
    // Auto-escalar canvas
    const canvasWidth = 800;
    const canvasHeight = 450;
    const floorY = 280; // Suelo más arriba para dejar espacio al pulgar abajo
    
    // Cargar imagen del personaje
    const playerImg = new Image();
    playerImg.src = '<?php echo get_template_directory_uri(); ?>/assets/image/404-game-merchandev.png';
    let imgLoaded = false;
    playerImg.onload = () => { imgLoaded = true; };
    
    let isPlaying = false;
    let isGameOver = false;
    let score = 0;
    let highScore = localStorage.getItem('merchan_dino_highscore') || 0;
    let animationId;
    let gameSpeed = 4.0; // Velocidad inicial más lenta (más fácil al principio)
    let frame = 0;
    let framesUntilNextObstacle = 40; // Primer obstáculo sale muy rápido
    let currentDisplayScore = -1;
    
    highScoreElement.innerText = highScore;

    const dino = {
        x: 60,
        y: 100,
        width: 75,
        height: 85,
        dy: 0,
        gravity: 0.5,    // Gravedad muy baja para un salto más "flotante" y largo
        jumpForce: -14,  // Salto ajustado para dar ~56 frames de aire (más largo)
        grounded: true
    };

    let obstacles = [];

    function resetGame() {
        dino.y = 100;
        dino.dy = 0;
        obstacles = [];
        score = 0;
        currentDisplayScore = -1;
        gameSpeed = 4.0;
        frame = 0;
        framesUntilNextObstacle = 40; // Aparece rápido al reiniciar
        isGameOver = false;
        isPlaying = true;
        gameOverMsg.classList.add('hidden');
        updateScore();
        loop();
    }

    function updateScore() {
        let displayScore = Math.floor(score / 10);
        if (displayScore !== currentDisplayScore) {
            scoreElement.innerText = displayScore;
            currentDisplayScore = displayScore;
            if (displayScore > highScore) {
                highScore = displayScore;
                highScoreElement.innerText = highScore;
                localStorage.setItem('merchan_dino_highscore', highScore);
            }
        }
    }

    function spawnObstacle() {
        let size = 35 + Math.random() * 20; // Tamaño ajustado
        let starsCount = Math.floor(Math.random() * 7) + 1; // 1 a 7 estrellas
        obstacles.push({
            x: canvas.width,
            y: floorY - size,
            width: size,
            height: size,
            stars: starsCount
        });
    }

    function jump() {
        if (dino.grounded && !isGameOver) {
            dino.dy = dino.jumpForce;
            dino.grounded = false;
            
            if(!isPlaying) {
                resetGame();
            }
        } else if (isGameOver) {
            resetGame();
        }
    }

    window.addEventListener('keydown', (e) => {
        if (e.code === 'Space' || e.code === 'ArrowUp') {
            e.preventDefault();
            jump();
        }
    });

    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        jump();
    }, {passive: false});
    
    canvas.addEventListener('mousedown', (e) => {
        e.preventDefault();
        jump();
    });

    function loop() {
        if (!isPlaying) return;

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Suelo y zona táctil
        ctx.beginPath();
        ctx.moveTo(0, floorY);
        ctx.lineTo(canvas.width, floorY);
        ctx.strokeStyle = 'rgba(255,255,255,0.4)';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        // Área inferior (Zona del pulgar)
        ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
        ctx.fillRect(0, floorY, canvas.width, canvas.height - floorY);
        ctx.font = 'bold 20px "Orbitron", sans-serif';
        ctx.fillStyle = 'rgba(249, 115, 22, 0.15)';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('ZONA TÁCTIL', canvas.width/2, floorY + (canvas.height - floorY)/2);

        dino.dy += dino.gravity;
        dino.y += dino.dy;

        if (dino.y + dino.height > floorY) {
            dino.y = floorY - dino.height;
            dino.dy = 0;
            dino.grounded = true;
        }

        // Dibujar personaje
        if (imgLoaded) {
            ctx.drawImage(playerImg, dino.x, dino.y, dino.width, dino.height);
        } else {
            ctx.fillStyle = '#f97316';
            ctx.fillRect(dino.x, dino.y, dino.width, dino.height);
        }

        // Manejar obstáculos (Esferas del Dragón 8-bit)
        for (let i = 0; i < obstacles.length; i++) {
            let obs = obstacles[i];
            obs.x -= gameSpeed;

            // Dibujar Esfera del Dragón en Pixel Art (8x8 grid)
            let p = obs.width / 8; // Unidad de pixel
            
            // Círculo base naranja
            ctx.fillStyle = '#f97316';
            ctx.fillRect(obs.x + 2*p, obs.y + 0*p, 4*p, 8*p);
            ctx.fillRect(obs.x + 1*p, obs.y + 1*p, 6*p, 6*p);
            ctx.fillRect(obs.x + 0*p, obs.y + 2*p, 8*p, 4*p);

            // Sombra (abajo-derecha)
            ctx.fillStyle = '#c2410c';
            ctx.fillRect(obs.x + 2*p, obs.y + 7*p, 4*p, 1*p);
            ctx.fillRect(obs.x + 6*p, obs.y + 2*p, 2*p, 4*p);
            ctx.fillRect(obs.x + 5*p, obs.y + 5*p, 2*p, 3*p);

            // Brillo (arriba-izquierda)
            ctx.fillStyle = '#fcd34d';
            ctx.fillRect(obs.x + 2*p, obs.y + 1*p, 2*p, 1*p);
            ctx.fillRect(obs.x + 1*p, obs.y + 2*p, 1*p, 2*p);

            // Estrellas rojas (1 a 7)
            ctx.fillStyle = '#dc2626';
            let numStars = obs.stars || 4;
            
            // Función para dibujar estrellas relativas al centro (3.5p, 3.5p)
            let drawStar = (offsetX, offsetY) => {
                ctx.fillRect(obs.x + (3.5 + offsetX)*p, obs.y + (3.5 + offsetY)*p, 1.2*p, 1.2*p);
            };
            
            if (numStars === 1) {
                drawStar(0, 0);
            } else if (numStars === 2) {
                drawStar(-1.5, 0); drawStar(1.5, 0);
            } else if (numStars === 3) {
                drawStar(0, -1.5); drawStar(-1.5, 1); drawStar(1.5, 1);
            } else if (numStars === 4) {
                drawStar(-1.2, -1.2); drawStar(1.2, -1.2); drawStar(-1.2, 1.2); drawStar(1.2, 1.2);
            } else if (numStars === 5) {
                drawStar(0, 0); drawStar(-1.5, -1.5); drawStar(1.5, -1.5); drawStar(-1.5, 1.5); drawStar(1.5, 1.5);
            } else if (numStars === 6) {
                drawStar(-1.5, -1.5); drawStar(1.5, -1.5); drawStar(-1.5, 0); drawStar(1.5, 0); drawStar(-1.5, 1.5); drawStar(1.5, 1.5);
            } else if (numStars === 7) {
                drawStar(0, 0); drawStar(-1.5, -1.5); drawStar(1.5, -1.5); drawStar(-1.5, 0); drawStar(1.5, 0); drawStar(-1.5, 1.5); drawStar(1.5, 1.5);
            }

            // Hitboxes precisas (cajas de colisión reducidas para ignorar bordes transparentes)
            let dinoBox = {
                x: dino.x + 18,
                y: dino.y + 15,
                width: dino.width - 36,
                height: dino.height - 20
            };
            
            let obsBox = {
                x: obs.x + 4,
                y: obs.y + 4,
                width: obs.width - 8,
                height: obs.height - 8
            };

            // AABB Collision Detection
            if (dinoBox.x < obsBox.x + obsBox.width &&
                dinoBox.x + dinoBox.width > obsBox.x &&
                dinoBox.y < obsBox.y + obsBox.height &&
                dinoBox.y + dinoBox.height > obsBox.y) {
                isGameOver = true;
                isPlaying = false;
                gameOverMsg.classList.remove('hidden');
            }
        }

        // Limpiar obstáculos que ya salieron de la pantalla sin crear Garbage Collection (Array Filter causa stutters en móviles)
        for (let i = obstacles.length - 1; i >= 0; i--) {
            if (obstacles[i].x + obstacles[i].width <= 0) {
                obstacles.splice(i, 1);
            }
        }

        // Control de Spawn basado en distancia segura
        framesUntilNextObstacle--;
        if (framesUntilNextObstacle <= 0) {
            spawnObstacle();
            gameSpeed += 0.025; // Acelera más rápido para aumentar dificultad con el tiempo
            // Próxima esfera (garantiza separación justa sin importar la velocidad)
            framesUntilNextObstacle = Math.floor(Math.random() * 50 + 100); 
        }

        score++;
        if (score % 10 === 0) updateScore();

        if (!isGameOver) {
            animationId = requestAnimationFrame(loop);
        }
    }

    function drawStartScreen() {
        ctx.fillStyle = 'rgba(10, 15, 30, 0.5)';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        ctx.beginPath();
        ctx.moveTo(0, floorY);
        ctx.lineTo(canvas.width, floorY);
        ctx.strokeStyle = 'rgba(255,255,255,0.4)';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        ctx.fillStyle = 'rgba(0, 0, 0, 0.2)';
        ctx.fillRect(0, floorY, canvas.width, canvas.height - floorY);
        
        if (imgLoaded) {
            ctx.drawImage(playerImg, dino.x, floorY - dino.height, dino.width, dino.height);
        }
        
        ctx.font = 'bold 22px "Orbitron", sans-serif';
        ctx.fillStyle = '#f97316';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('PRESIONA ESPACIO O TOCA', canvas.width/2, floorY/2);
    }
    
    // Retraso para fuente e imagen
    setTimeout(drawStartScreen, 200);

});
</script>

<?php get_footer(); ?>
