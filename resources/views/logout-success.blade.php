<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Success - GEOTERRAID</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #1d4ed8 100%);
            overflow: hidden;
            position: relative;
        }

        /* Animated background */
        .bg-effects {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .bg-effects::before,
        .bg-effects::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotateBg 20s linear infinite;
        }

        .bg-effects::after {
            background: radial-gradient(circle at center, rgba(147, 197, 253, 0.1) 0%, transparent 40%);
            animation: rotateBg 25s linear infinite reverse;
        }

        @keyframes rotateBg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.6);
        }

        .particle:nth-child(1) { top: 15%; left: 20%; animation-delay: 0s; width: 10px; height: 10px; }
        .particle:nth-child(2) { top: 70%; left: 85%; animation-delay: 0.5s; }
        .particle:nth-child(3) { top: 85%; left: 25%; animation-delay: 1s; width: 6px; height: 6px; }
        .particle:nth-child(4) { top: 25%; left: 75%; animation-delay: 1.5s; }
        .particle:nth-child(5) { top: 55%; left: 10%; animation-delay: 2s; width: 12px; height: 12px; }
        .particle:nth-child(6) { top: 40%; left: 90%; animation-delay: 2.5s; }
        .particle:nth-child(7) { top: 10%; left: 50%; animation-delay: 0.8s; width: 7px; height: 7px; }
        .particle:nth-child(8) { top: 80%; left: 60%; animation-delay: 1.3s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.8; }
            50% { transform: translateY(-40px) scale(1.3); opacity: 1; }
        }

        /* Checkmark icon */
        .checkmark-container {
            position: relative;
            width: 120px;
            height: 120px;
            margin-bottom: 2rem;
        }

        .checkmark-circle {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 4px solid rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 30px rgba(255, 255, 255, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 0 60px rgba(255, 255, 255, 0.5); }
        }

        .checkmark {
            font-size: 3.5rem;
            color: white;
            animation: checkPop 0.5s ease forwards;
            opacity: 0;
            transform: scale(0);
        }

        @keyframes checkPop {
            0% { opacity: 0; transform: scale(0); }
            50% { transform: scale(1.3); }
            100% { opacity: 1; transform: scale(1); }
        }

        /* Orbiting rings */
        .ring {
            position: absolute;
            inset: -20px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            animation: spin 3s linear infinite;
        }

        .ring:nth-child(2) {
            inset: -35px;
            border-color: rgba(255, 255, 255, 0.1);
            animation-duration: 4s;
            animation-direction: reverse;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Main text */
        .container {
            text-align: center;
            z-index: 10;
            padding: 2rem;
        }

        .success-text {
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: center;
            gap: 3px;
            flex-wrap: wrap;
        }

        .success-letter {
            display: inline-block;
            opacity: 0;
            transform: translateY(60px) rotateX(-90deg);
            animation: letterReveal 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
            text-shadow: 0 0 40px rgba(255, 255, 255, 0.6);
        }

        .success-letter:nth-child(1) { animation-delay: 0.3s; }
        .success-letter:nth-child(2) { animation-delay: 0.35s; }
        .success-letter:nth-child(3) { animation-delay: 0.4s; }
        .success-letter:nth-child(4) { animation-delay: 0.45s; }
        .success-letter:nth-child(5) { animation-delay: 0.5s; }
        .success-letter:nth-child(6) { animation-delay: 0.55s; }
        .success-letter:nth-child(7) { animation-delay: 0.6s; }
        .success-letter:nth-child(8) { animation-delay: 0.65s; }
        .success-letter:nth-child(9) { animation-delay: 0.7s; }
        .success-letter:nth-child(10) { animation-delay: 0.75s; }
        .success-letter:nth-child(11) { animation-delay: 0.8s; }
        .success-letter:nth-child(12) { animation-delay: 0.85s; }
        .success-letter:nth-child(13) { animation-delay: 0.9s; }
        .success-letter:nth-child(14) { animation-delay: 0.95s; }
        .success-letter:nth-child(15) { animation-delay: 1s; }
        .success-letter:nth-child(16) { animation-delay: 1.05s; }
        .success-letter:nth-child(17) { animation-delay: 1.1s; }

        @keyframes letterReveal {
            0% { opacity: 0; transform: translateY(60px) rotateX(-90deg); }
            100% { opacity: 1; transform: translateY(0) rotateX(0); }
        }

        /* Goodbye text */
        .goodbye-text {
            font-size: 1.8rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            margin-top: 1rem;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .goodbye-word {
            opacity: 0;
            transform: translateX(-40px);
            animation: wordSlide 0.6s ease forwards;
        }

        .goodbye-word:nth-child(1) { animation-delay: 1.5s; }
        .goodbye-word:nth-child(2) { animation-delay: 1.7s; }
        .goodbye-word:nth-child(3) { animation-delay: 1.9s; }
        .goodbye-word:nth-child(4) { animation-delay: 2.1s; }

        @keyframes wordSlide {
            0% { opacity: 0; transform: translateX(-40px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        /* Loading bar */
        .loading-container {
            width: 250px;
            margin: 2.5rem auto 0;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards 2.5s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .loading-bar-bg {
            width: 100%;
            height: 5px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 5px;
            overflow: hidden;
        }

        .loading-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #93c5fd, #ffffff);
            background-size: 200% 100%;
            animation: shimmer 1s linear infinite;
            border-radius: 5px;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .loading-text {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            margin-top: 0.8rem;
            letter-spacing: 1px;
        }

        /* Redirect message */
        .redirect-text {
            position: absolute;
            bottom: 40px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.85rem;
            opacity: 0;
            animation: fadeIn 0.5s ease forwards 1.2s;
        }

        /* Glow effect behind text */
        .glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.3) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            animation: glowPulse 4s ease-in-out infinite;
        }

        @keyframes glowPulse {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.5; }
            50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.8; }
        }
    </style>
</head>
<body>
    <div class="bg-effects">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="glow"></div>

    <div class="container">
        <div class="checkmark-container">
            <div class="ring"></div>
            <div class="ring"></div>
            <div class="checkmark-circle">
                <span class="checkmark">✓</span>
            </div>
        </div>

        <div class="success-text">
            <span class="success-letter">A</span>
            <span class="success-letter">N</span>
            <span class="success-letter">D</span>
            <span class="success-letter">A</span>
            <span class="success-letter"> </span>
            <span class="success-letter">B</span>
            <span class="success-letter">E</span>
            <span class="success-letter">R</span>
            <span class="success-letter">H</span>
            <span class="success-letter">A</span>
            <span class="success-letter">S</span>
            <span class="success-letter">I</span>
            <span class="success-letter">L</span>
            <span class="success-letter"> </span>
            <span class="success-letter">L</span>
            <span class="success-letter">O</span>
            <span class="success-letter">G</span>
            <span class="success-letter">O</span>
            <span class="success-letter">U</span>
            <span class="success-letter">T</span>
        </div>

        <div class="goodbye-text">
            <span class="goodbye-word">SAMPAI</span>
            <span class="goodbye-word">JUMPA</span>
            <span class="goodbye-word">LAGI</span>
            <span class="goodbye-word">👋</span>
        </div>

        <div class="loading-container">
            <div class="loading-bar-bg">
                <div class="loading-bar"></div>
            </div>
            <div class="loading-text">Mengalihkan ke halaman utama...</div>
        </div>
    </div>

    <div class="redirect-text">Anda akan dialihkan secara otomatis</div>

    <script>
        // Redirect after animation
        setTimeout(function() {
            window.location.href = '/';
        }, 5000); // 5 seconds
    </script>
</body>
</html>
