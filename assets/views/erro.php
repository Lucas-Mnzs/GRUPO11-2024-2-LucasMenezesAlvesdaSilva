<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Erro 404 - Página não encontrada</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&family=Roboto&display=swap"
        rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            overflow: hidden;
            font-family: 'Roboto', sans-serif;
            background: radial-gradient(ellipse at bottom, #000015 0%, #000000 100%);
        }

        .container {
            position: relative;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            z-index: 2;
            padding: 0 20px;
            color: #fff;
        }

        .container img {
            width: 200px;
            margin-bottom: 20px;
            animation: float 4s ease-in-out infinite;
        }

        h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 5rem;
            margin-bottom: 10px;
            color: #00ffff;
            text-shadow: 0 0 10px #00ffffaa;
        }

        p {
            font-size: 1.2rem;
            max-width: 500px;
            margin-bottom: 30px;
            color: #ccc;
        }

        a {
            color: #00ffff;
            text-decoration: none;
            border: 2px solid #00ffff;
            padding: 10px 20px;
            border-radius: 10px;
            transition: 0.3s;
            font-family: 'Roboto', sans-serif;
        }

        a:hover {
            background: #00ffff;
            color: #000;
        }

        @keyframes float {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .comet {
            position: absolute;
            width: 4px;
            height: 80px;
            background: linear-gradient(to bottom, #00ffff, transparent);
            opacity: 0.7;
            transform: rotate(135deg);
            /* Sempre descendo na diagonal */
            animation: comet-fall 6s linear infinite;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes comet-fall {
            0% {
                opacity: 0;
                transform: translate(var(--startX), var(--startY)) rotate(135deg);
            }

            10% {
                opacity: 1;
            }

            100% {
                transform: translate(100vw, 100vh) rotate(135deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="assets/image/astronauta.png" alt="Astronauta perdido" />
        <h1>404</h1>
        <p>Ih, cria... essa página se perdeu no espaço! 🪐</p>
        <a href="/grupo11-2024-2-lucasmenezesalvesdasilva">Voltar pro início</a>
    </div>

    <script>
        const totalComets = 100;

        for (let i = 0; i < totalComets; i++) {
            const comet = document.createElement('div');
            comet.classList.add('comet');

            const fromTop = Math.random() < 0.5;

            const startX = fromTop ?
                `${Math.random() * 150}vw` // X aleatório, se vem de cima
                :
                `${-Math.random() * 150}vw`; // até -100vw pra vir bem da esquerda

            const startY = fromTop ?
                `${-Math.random() * 150}vh` // até -100vh pra vir bem de cima
                :
                `${Math.random() * 150}vh`; // Y aleatório, se vem da esquerda

            comet.style.setProperty('--startX', startX);
            comet.style.setProperty('--startY', startY);
            comet.style.animationDelay = `${Math.random() * 10}s`;

            document.body.appendChild(comet);
        }
    </script>

</body>

</html>