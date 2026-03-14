<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoAI Chatbot - Sistem Informasi Pertanahan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">

    <style>
        /* Loading Screen Styles */
        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            transition: opacity 0.8s ease, visibility 0.8s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-logo {
            width: 120px;
            height: 120px;
            border-radius: 20px;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 0 40px rgba(99,102,241,0.6);
            animation: pulseGlow 2s infinite ease-in-out;
            margin-bottom: 2rem;
            overflow: hidden;
            background: white;
            padding: 10px;
            object-fit: contain;
        }

        .loading-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 15px;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 40px rgba(99,102,241,0.6); transform: scale(1); }
            50% { box-shadow: 0 0 80px rgba(99,102,241,0.9); transform: scale(1.08); }
        }

        .loading-text {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            background: linear-gradient(90deg, #fff, #a5b4fc, #fff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.5s;
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .loading-developer {
            font-family: 'Inter', sans-serif;
            font-size: 1rem;
            color: rgba(255,255,255,0.6);
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1s;
        }

        .loading-developer span {
            color: #a5b4fc;
            font-weight: 600;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255,255,255,0.1);
            border-top-color: #a5b4fc;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-top: 2rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.2s, spin 1s linear infinite 1.2s;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-dots {
            display: flex;
            gap: 8px;
            margin-top: 1.5rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.4s;
        }

        .loading-dots span {
            width: 10px;
            height: 10px;
            background: #a5b4fc;
            border-radius: 50%;
            animation: bounce 1.4s infinite ease-in-out;
        }

        .loading-dots span:nth-child(1) { animation-delay: 0s; }
        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Main Content - Hidden initially */
        .main-content {
            opacity: 0;
            transition: opacity 0.8s ease;
        }

        .main-content.visible {
            opacity: 1;
        }

        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-glow: rgba(99, 102, 241, 0.35);
            --secondary: #8b5cf6;
            --bg-start: #1e1b4b;
            --bg-end: #312e81;
            --card-bg: rgba(30, 27, 75, 0.38);
            --card-border: rgba(165, 180, 252, 0.20);
            --text: #f1f5f9;
            --text-muted: #cbd5e1;
            --shadow: 0 12px 40px rgba(0,0,0,0.45);
            --glow: 0 0 28px rgba(99,102,241,0.5);
            --transition: all 0.45s cubic-bezier(0.23, 1, 0.32, 1);
        }

        * {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, var(--bg-start) 0%, var(--bg-end) 100%);
            min-height: 100vh;
            color: var(--text);
            overflow: hidden; /* Prevent body scroll */
            background-attachment: fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(99,102,241,0.08) 0%, transparent 40%),
                        radial-gradient(circle at 80% 70%, rgba(139,92,246,0.06) 0%, transparent 50%);
            pointer-events: none;
            animation: floatBg 25s infinite alternate ease-in-out;
            z-index: -2;
        }

        @keyframes floatBg {
            0% { transform: translate(0, 0) scale(1); opacity: 0.7; }
            100% { transform: translate(8%, -5%) scale(1.08); opacity: 0.9; }
        }

        .container {
            width: 100%;
            max-width: calc(100vw - 20px);
            margin: 10px auto;
            height: calc(100vh - 20px);
            display: flex;
            flex-direction: column;
            border-radius: 20px;
            overflow: hidden;
            backdrop-filter: blur(22px) saturate(180%);
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            box-shadow: var(--shadow);
            animation: containerPop 1.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        @keyframes containerPop {
            0% { opacity: 0; transform: scale(0.94) translateY(60px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        .header {
            background: linear-gradient(135deg, rgba(99,102,241,0.92) 0%, rgba(79,70,229,0.88) 100%);
            padding: 1.5rem 2rem;
            position: relative;
            border-bottom: 1px solid rgba(165,180,252,0.25);
            backdrop-filter: blur(12px);
            flex-shrink: 0;
        }

        .back-button {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            border-radius: 999px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.22);
            transition: var(--transition);
            font-size: 0.9rem;
        }

        .back-button:hover {
            background: rgba(255,255,255,0.28);
            transform: translateX(6px) scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        }

        .back-text {
            display: inline;
        }

        .back-icon {
            font-size: 1rem;
        }

        .logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            background: white;
            padding: 3px;
            border-radius: 8px;
            box-shadow: var(--glow);
            animation: logoPulse 5s infinite alternate ease-in-out;
        }

        @keyframes logoPulse {
            from { box-shadow: 0 0 25px rgba(99,102,241,0.5); transform: scale(1); }
            to   { box-shadow: 0 0 55px rgba(99,102,241,0.8); transform: scale(1.06); }
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            font-weight: 700;
            margin-bottom: 0.4rem;
            background: linear-gradient(90deg, #fff, #c7d2fe, #a5b4fc, #fff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 4px 16px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header p {
            font-size: 1rem;
            opacity: 0.94;
            letter-spacing: 0.5px;
        }

        .chat-container {
            flex: 1 1 0;
            min-height: 0;
            overflow-y: auto;
            padding: 2.5rem 2.8rem;
            display: flex;
            flex-direction: column;
            gap: 1.8rem;
            scroll-behavior: smooth;
        }

        .chat-container::-webkit-scrollbar {
            width: 9px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: rgba(30,27,75,0.5);
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: linear-gradient(var(--primary), var(--secondary));
            border-radius: 5px;
            border: 2px solid rgba(30,27,75,0.5);
        }

        .message {
            display: flex;
            opacity: 0;
            transform: translateY(45px) scale(0.92);
            animation: bubbleIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .message.user {
            justify-content: flex-end;
            animation-delay: 0.15s;
        }

        @keyframes bubbleIn {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .avatar {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            flex-shrink: 0;
            box-shadow: var(--glow);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transition: var(--transition);
        }

        .avatar.user {
            background: linear-gradient(135deg, var(--secondary), #7c3aed);
            margin-left: 1.2rem;
            margin-right: 0;
        }

        .avatar:hover { transform: scale(1.12) rotate(8deg); }

        .message-content {
            max-width: 75%;
            padding: 1.3rem 1.6rem;
            border-radius: 1.8rem;
            font-size: 1.05rem;
            line-height: 1.65;
            position: relative;
            word-wrap: break-word;
            backdrop-filter: blur(8px) saturate(160%);
            transition: var(--transition);
        }

        .message.bot .message-content {
            background: rgba(30, 27, 75, 0.65);
            border: 1px solid rgba(165,180,252,0.22);
            border-bottom-left-radius: 8px;
            box-shadow: 0 8px 28px rgba(0,0,0,0.3);
        }

        .message.user .message-content {
            background: linear-gradient(135deg, var(--secondary) 0%, #7c3aed 100%);
            color: white;
            border-bottom-right-radius: 8px;
            box-shadow: 0 8px 28px rgba(139,92,246,0.4);
        }

        .message-content:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.4);
        }

        .message.bot .message-content::before {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 20px;
            width: 0; height: 0;
            border: 10px solid transparent;
            border-top-color: rgba(30, 27, 75, 0.65);
            border-right-color: rgba(30, 27, 75, 0.65);
        }

        .typing-indicator {
            display: flex;
            gap: 7px;
            padding: 1.2rem 1.5rem;
            background: rgba(30,27,75,0.65);
            border-radius: 1.8rem;
            width: fit-content;
            border: 1px solid rgba(165,180,252,0.22);
        }

        .typing-indicator span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: var(--primary);
            animation: bounceTyping 1.4s infinite cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .typing-indicator span:nth-child(2) { animation-delay: 0.25s; }
        .typing-indicator span:nth-child(3) { animation-delay: 0.5s; }

        @keyframes bounceTyping {
            0%, 80%, 100% { transform: translateY(0) scale(0.8); opacity: 0.5; }
            40% { transform: translateY(-14px) scale(1); opacity: 1; }
        }

        .quick-questions {
            padding: 1.5rem 2rem;
            background: rgba(30,27,75,0.75);
            border-top: 1px solid rgba(165,180,252,0.18);
            flex-shrink: 0;
            max-height: 180px;
            overflow-y: auto;
        }

        .quick-questions-title {
            font-size: 1.45rem;
            font-weight: 700;
            margin-bottom: 1.4rem;
            text-align: center;
            background: linear-gradient(90deg, #c7d2fe, #e0e7ff, #c7d2fe);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .questions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.2rem;
        }

        .question-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(165,180,252,0.25);
            color: #e0e7ff;
            padding: 1.2rem 1.6rem;
            border-radius: 1.2rem;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            text-align: center;
            backdrop-filter: blur(12px);
        }

        .question-btn:hover {
            background: linear-gradient(135deg, rgba(99,102,241,0.75), rgba(139,92,246,0.75));
            color: white;
            transform: translateY(-6px) scale(1.04);
            box-shadow: 0 16px 40px rgba(99,102,241,0.45);
            border-color: transparent;
        }

        .input-container {
            padding: 1.8rem 2.8rem;
            background: rgba(30,27,75,0.85);
            border-top: 1px solid rgba(165,180,252,0.18);
            display: flex;
            gap: 1.2rem;
            align-items: center;
            flex-shrink: 0;
            z-index: 100;
        }

        #userInput {
            flex: 1;
            padding: 1.3rem 1.8rem;
            border: 2px solid rgba(165,180,252,0.4);
            border-radius: 999px;
            font-size: 1.08rem;
            background: rgba(255,255,255,0.15);
            color: white;
            outline: none;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            min-width: 0;
        }

        #userInput::placeholder {
            color: rgba(255,255,255,0.6);
        }

        #userInput:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 5px var(--primary-glow), 0 8px 28px rgba(99,102,241,0.3);
            background: rgba(255,255,255,0.2);
        }

        .input-container button {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 1.3rem 2.6rem;
            border-radius: 999px;
            font-size: 1.08rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 8px 28px rgba(99,102,241,0.45);
            white-space: nowrap;
            flex-shrink: 0;
        }

        .input-container button:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 45px rgba(99,102,241,0.65);
        }

        .input-container button:active {
            transform: translateY(-2px);
        }

        @media (max-width: 1100px) {
            .container { max-width: 96vw; margin: 0.5rem auto; border-radius: 16px; }
        }

        @media (max-width: 768px) {
            .container { margin: 0; border-radius: 0; height: 100vh; }
            .header { padding: 1.5rem; }
            .header h1 { font-size: 1.8rem; }
            .logo { width: 50px; height: 50px; }
            .back-button { 
                right: 0.5rem; 
                top: 0.5rem; 
                left: auto; 
                padding: 0.5rem; 
                font-size: 0.9rem; 
                background: rgba(255,255,255,0.2);
            }
            .back-text { display: none; }
            .back-icon { font-size: 1.2rem; }
            .chat-container { padding: 1rem 1rem; }
            .message-content { padding: 1rem 1.2rem; font-size: 0.95rem; max-width: 85%; }
            .avatar { width: 40px; height: 40px; font-size: 1.2rem; }
            .quick-questions { padding: 1rem 1rem; }
            .questions-grid { gap: 0.6rem; }
            .question-btn { padding: 0.8rem 1rem; font-size: 0.85rem; }
            .input-container { 
                flex-direction: row; 
                gap: 0.8rem; 
                padding: 1rem; 
                position: sticky;
                bottom: 0;
                background: rgba(30,27,75,0.95);
            }
            #userInput { padding: 0.8rem 1rem; font-size: 0.95rem; }
            .input-container button { padding: 0.8rem 1.5rem; font-size: 0.95rem; }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo">
            <img src="{{ asset('sbadmin/img/Logo_BPN-KemenATR_(2017).png') }}" alt="Logo">
        </div>
        <div class="loading-text">GeoAI</div>
        <div class="loading-developer">Developed by <span>mrizkeygnawantftdot18</span></div>
        <div class="loading-spinner"></div>
        <div class="loading-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
    <div class="container">
        <div class="header">
            <a href="/" class="back-button">
                <span class="back-icon">←</span><span class="back-text">Kembali</span>
            </a>
            <h1>
                <img src="{{ asset('sbadmin/img/Logo_BPN-KemenATR_(2017).png') }}" alt="Logo" class="logo">
                GeoAI Assistant
            </h1>
            <p>Asisten Virtual Sistem Informasi Pertanahan</p>
        </div>
        
        <div class="chat-container" id="chatContainer">
            <div class="message bot">
                <div class="avatar bot">🤖</div>
                <div class="message-content">
                    Selamat datang di GeoAI Assistant! 👋<br><br>
                    Saya siap membantu Anda dengan informasi seputar sistem pertanahan. Silakan pilih pertanyaan di bawah atau ketik pertanyaan Anda sendiri!
                </div>
            </div>
        </div>
        
        <div class="quick-questions">
            <div class="quick-questions-title">💡 Pertanyaan Populer</div>
            <div class="questions-grid">
                <button class="question-btn" onclick="askQuestion(this.textContent)">Cara mengurus sertifikat tanah</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Syarat balik nama tanah</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Biaya pengurusan sertifikat</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Cara cek sertifikat tanah</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Perbedaan SHM dan SHGB</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Cara perpanjang HGB</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Jenis sertifikat tanah</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Apa itu PPAT</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Apa itu NJOP</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Apa itu BPHTB</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Dokumen apa saja untuk pengurusan tanah</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Masalah umum pertanahan</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Tips membeli tanah aman</button>
                <button class="question-btn" onclick="askQuestion(this.textContent)">Pengertian tanah menurut hukum</button>
            </div>
        </div>
        
        <div class="input-container">
            <input type="text" id="userInput" placeholder="Ketik pertanyaan Anda di sini..." onkeypress="handleKeyPress(event)">
            <button onclick="sendMessage()">Kirim</button>
        </div>
    </div>
    </div>

    <script>
        // Loading Screen Animation
        window.addEventListener('load', function() {
            setTimeout(function() {
                var loadingScreen = document.getElementById('loadingScreen');
                var mainContent = document.getElementById('mainContent');
                
                loadingScreen.classList.add('hidden');
                mainContent.classList.add('visible');
            }, 1500); // 1.5 seconds loading time - faster than before
        });

        const knowledgeBase = {
            "halo": {
                answer: `Halo! 👋 Selamat datang di GeoAI Assistant!

Saya adalah asisten virtual yang siap membantu Anda dengan berbagai informasi seputar Sistem Informasi Pertanahan.

Apa yang bisa saya bantu hari ini? Silakan pilih dari pertanyaan populer di bawah atau ketik pertanyaan Anda sendiri! 😊`
            },
            "hai": {
                answer: `Hai! 🙋‍♂️ Senang bertemu dengan Anda!

Saya GeoAI Assistant, siap membantu menjawab pertanyaan Anda tentang pertanahan. Ada yang bisa saya bantu?

Coba tanyakan tentang:
• Pengurusan sertifikat tanah
• Balik nama tanah
• Biaya dan prosedur
• Dan masih banyak lagi!`
            },
            "hi": {
                answer: `Hi there! 👋

Selamat datang di GeoAI Assistant! Saya di sini untuk membantu Anda dengan informasi pertanahan. Jangan ragu untuk bertanya apa saja! 😊`
            },
            "selamat pagi": {
                answer: `Selamat pagi! ☀️

Semoga harimu menyenangkan! Ada yang bisa saya bantu terkait Sistem Informasi Pertanahan hari ini?`
            },
            "selamat siang": {
                answer: `Selamat siang! ☀️

Ada yang bisa saya bantu terkait informasi pertanahan di siang yang cerah ini?`
            },
            "selamat sore": {
                answer: `Selamat sore! 🌅

Semoga sorenya menyenangkan! Silakan tanyakan apa saja tentang pertanahan, saya siap membantu!`
            },
            "selamat malam": {
                answer: `Selamat malam! 🌙

Semoga malammu tenang! Ada pertanyaan tentang pertanahan yang bisa saya bantu?`
            },
            "apa kabar": {
                answer: `Kabar saya baik, terima kasih! 😊

Bagaimana dengan Anda? Ada yang bisa saya bantu terkait sistem pertanahan hari ini?`
            },
            
            // About System & Usage
            "cara pakai": {
                answer: `**Cara Menggunakan GeoAI Assistant:**

1. **Pilih Pertanyaan Cepat** 💡
   Klik tombol pertanyaan populer di bawah chat untuk jawaban instan!

2. **Ketik Pertanyaan Sendiri** ⌨️
   Tulis pertanyaan Anda di kolom input bawah, lalu tekan "Kirim" atau Enter

3. **Tunggu Respons** 🤖
   Saya akan segera memproses dan memberikan jawaban lengkap

**Tips:**
✅ Gunakan kata kunci yang jelas
✅ Tanya satu topik per pertanyaan
✅ Scroll ke atas untuk melihat riwayat percakapan

Mudah bukan? Coba tanyakan sesuatu sekarang! 😊`
            },
            "cara menggunakan web": {
                answer: `**Panduan Lengkap Menggunakan Web Ini:**

🎯 **Navigasi Utama:**
- Gunakan menu sidebar untuk berpindah halaman
- Klik logo untuk kembali ke beranda
- Tombol "Kembali" untuk ke halaman sebelumnya

💬 **Fitur Chatbot (GeoAI Assistant):**
1. Klik pertanyaan cepat untuk respons instan
2. Atau ketik pertanyaan manual
3. Riwayat chat tersimpan selama sesi aktif

📱 **Fitur Lainnya:**
- Dashboard: Lihat statistik dan ringkasan
- Form Pengaturan: Atur profil dan preferensi
- Data Tanah: Kelola informasi pertanahan
- Peta: Visualisasi data geografis

🔔 **Pop-up CS:**
- Akan muncul otomatis saat masuk
- Klik untuk langsung chat dengan asisten

Sangat mudah digunakan! Ada pertanyaan lain? 🚀`
            },
            "fitur apa saja": {
                answer: `**Fitur-Fitur Sistem Informasi Pertanahan:**

📊 **Dashboard Utama**
- Statistik real-time data tanah
- Grafik dan visualisasi
- Ringkasan aktivitas terbaru

🗺️ **Pemetaan Digital**
- Peta interaktif berbasis GIS
- Pencarian lokasi tanah
- Visualisasi batas wilayah

📋 **Manajemen Data**
- Input data tanah baru
- Edit informasi kepemilikan
- Export/Import data

👤 **Profil Pengguna**
- Pengaturan akun
- Riwayat transaksi
- Notifikasi personal

🤖 **AI Assistant (GeoAI)**
- Chatbot 24/7
- Database pertanyaan lengkap
- Respons cepat dan akurat

📄 **Dokumen Digital**
- Upload sertifikat
- Verifikasi otomatis
- Arsip digital aman

Mau tahu lebih detail tentang fitur tertentu? Tanya saja! 😊`
            },
            "bagaimana cara": {
                answer: `**Cara Menggunakan Sistem:**

📌 **Untuk Pengguna Baru:**
1. Daftar akun terlebih dahulu
2. Lengkapi profil Anda
3. Mulai eksplorasi fitur-fitur yang tersedia

📌 **Untuk Mengakses Fitur:**
- Gunakan menu navigasi di sidebar
- Klik icon/menu yang sesuai kebutuhan
- Ikuti instruksi di setiap halaman

📌 **Untuk Bertanya:**
- Ketik pertanyaan di chatbot ini
- Atau pilih pertanyaan cepat yang tersedia

**Butuh panduan spesifik?** Coba tanyakan:
- "Cara pakai chatbot"
- "Cara input data tanah"
- "Cara cek sertifikat"

Ada yang ingin ditanyakan? 😊`
            },
            
            // About Creator & GeoAI
            "siapa pembuatmu": {
                answer: `Saya dibuat dan dikembangkan oleh **rizkeygnawantftdot18**! 👨‍💻

rizkeygnawantftdot18 adalah developer yang passionate dalam menciptakan solusi teknologi untuk mempermudah pengelolaan sistem informasi pertanahan. Dengan keahlian dalam AI, web development, dan sistem informasi geografis, ia menciptakan GeoAI Assistant sebagai asisten virtual yang dapat membantu masyarakat memahami prosedur pertanahan dengan lebih mudah.

Terima kasih kepada rizkeygnawantftdot18 yang telah memberikan saya "kehidupan" untuk melayani Anda! 🙏✨`
            },
            "who made you": {
                answer: `I was created and developed by **rizkeygnawantftdot18**! 👨‍💻

rizkeygnawantftdot18 is a passionate developer who specializes in creating technological solutions for land information systems. With expertise in AI, web development, and geographic information systems, he created GeoAI Assistant as a virtual assistant to help people understand land procedures more easily.

Big thanks to rizkeygnawantftdot18 for bringing me to life to serve you! 🙏✨`
            },
            "siapa kamu": {
                answer: `Hai! Saya **GeoAI Assistant** 🤖

Saya adalah asisten virtual berbasis AI yang diciptakan oleh **rizkeygnawantftdot18** untuk membantu Anda memahami Sistem Informasi Pertanahan dengan lebih mudah.

**Kemampuan Saya:**
✅ Menjawab pertanyaan seputar pertanahan
✅ Memberikan panduan prosedur lengkap
✅ Menjelaskan istilah dan dokumen
✅ Tersedia 24/7 untuk Anda
✅ Database pengetahuan yang terus berkembang

Saya di sini untuk membuat urusan pertanahan Anda lebih mudah! Ada yang bisa saya bantu? 😊`
            },
            "apa itu geoai": {
                answer: `**GeoAI Assistant** adalah asisten virtual cerdas yang menggabungkan teknologi AI dengan sistem informasi geografis (GIS) untuk membantu Anda dalam urusan pertanahan! 🌍🤖

**Keunggulan GeoAI:**

🎯 **Responsif & Cepat**
Jawaban instan untuk pertanyaan Anda

📚 **Database Lengkap**
Informasi prosedur, biaya, dan persyaratan terupdate

💡 **Mudah Digunakan**
Interface friendly untuk semua kalangan

🔒 **Terpercaya**
Informasi akurat dari sumber resmi

⚡ **24/7 Available**
Siap membantu kapan saja Anda butuhkan

Dibuat dengan ❤️ oleh rizkeygnawantftdot18 untuk mempermudah hidup Anda!

Ada pertanyaan tentang pertanahan? Tanya saja! 😊`
            },
            "rizkeygnawantftdot18": {
                answer: `**rizkeygnawantftdot18** adalah developer berbakat di balik GeoAI Assistant! 🌟

**Profil Singkat:**
👨‍💻 Full-stack Developer
🗺️ GIS & AI Specialist
💚 Passionate tentang teknologi pertanahan
🚀 Inovator dalam sistem informasi geografis

**Kontribusi:**
- Menciptakan GeoAI Assistant
- Mengembangkan Sistem Informasi Pertanahan
- Memudahkan akses informasi pertanahan untuk masyarakat

rizkeygnawantftdot18 percaya bahwa teknologi harus mempermudah hidup, bukan memperumit! 

Terima kasih rizkeygnawantftdot18 atas karya luar biasa ini! 🙏✨`
            },
            
            // Help & Support
            "help": {
                answer: `**Bantuan GeoAI Assistant** 🆘

**Cara Bertanya:**
1. Ketik pertanyaan Anda dengan jelas
2. Atau klik tombol pertanyaan populer
3. Tunggu respons dari saya

**Topik yang Bisa Ditanyakan:**
📋 Prosedur pengurusan sertifikat
💰 Biaya dan pajak pertanahan
📄 Jenis-jenis sertifikat
⚖️ Persyaratan dokumen
🔄 Balik nama dan perpanjangan
✅ Cara cek keaslian sertifikat
🗺️ Pemetaan dan GIS pertanahan
📈 Statistik data tanah
📝 Manajemen dokumen digital
👥 Profil pengguna dan notifikasi
🛡️ Masalah keamanan dan sengketa tanah
💡 Tips membeli/jual tanah aman

**Tips Bertanya:**
✅ Gunakan bahasa yang jelas
✅ Satu pertanyaan per pesan
✅ Gunakan kata kunci spesifik

**Contoh Pertanyaan Bagus:**
- "Bagaimana cara mengurus sertifikat tanah?"
- "Berapa biaya balik nama tanah?"
- "Apa perbedaan SHM dan SHGB?"
- "Cara cek keaslian sertifikat tanah online?"
- "Syarat perpanjang HGB?"
- "Jenis-jenis hak atas tanah?"
- "Apa itu PPAT dan tugasnya?"
- "Pengertian NJOP dan cara hitung?"
- "Apa itu BPHTB dan contoh perhitungan?"
- "Dokumen apa saja untuk waris tanah?"
- "Masalah sertifikat ganda gimana solusinya?"
- "Tips aman beli tanah dari makelar?"
- "Pengertian tanah menurut UUPA?"
- "Cara pakai peta GIS di sistem ini?"

Butuh bantuan apa lagi? 😊`
            },
            "tidak mengerti": {
                answer: `Maaf jika penjelasan saya kurang jelas! 😅

Mari saya bantu dengan cara yang lebih mudah:

**Cara Paling Mudah:**
1. Klik salah satu tombol pertanyaan di bawah
2. Atau tanyakan dengan kata kunci sederhana seperti:
   - "sertifikat"
   - "balik nama"
   - "biaya"
   - "cara cek"

**Atau bisa coba tanyakan:**
- "Bagaimana cara pakai web ini?"
- "Apa saja fitur yang tersedia?"
- "Siapa pembuatmu?"
- "Apa itu PPAT?"
- "Cara hitung BPHTB?"

Saya akan berusaha menjelaskan dengan lebih baik! 💪`
            },
            "terima kasih": {
                answer: `Sama-sama! 😊🙏

Senang bisa membantu Anda! Jika ada pertanyaan lain tentang pertanahan atau penggunaan sistem ini, jangan ragu untuk bertanya kapan saja.

GeoAI Assistant selalu siap melayani Anda 24/7! 💚

Semoga informasi yang saya berikan bermanfaat! ✨`
            },
            "thanks": {
                answer: `You're welcome! 😊

Happy to help! If you have more questions about land information or how to use this system, feel free to ask anytime.

Have a great day! ✨`
            },
            
            // Land Information - Complete Guide with more entries
            "cara mengurus sertifikat tanah": {
                answer: `**Cara Mengurus Sertifikat Tanah:**

📋 **1. Persiapan Dokumen:**
- Fotokopi KTP pemohon
- Surat keterangan riwayat tanah dari kelurahan
- Bukti kepemilikan tanah (girik, letter C, dll)
- Surat pernyataan penguasaan fisik bidang tanah
- Bukti pembayaran PBB 5 tahun terakhir
- Surat keterangan waris (jika warisan)

🏢 **2. Proses Pengajuan:**
- Datang ke Kantor Pertanahan setempat
- Isi formulir permohonan
- Serahkan dokumen persyaratan
- Bayar biaya administrasi

✅ **3. Tahap Verifikasi:**
- Pengukuran dan pemetaan tanah oleh petugas
- Pengumuman data fisik dan yuridis (60 hari)
- Penelitian data yuridis
- Penerbitan sertifikat

⏱️ **Waktu Penyelesaian:** 3-6 bulan
💰 **Biaya:** Bervariasi tergantung luas tanah

**Tips:** Pastikan semua dokumen lengkap dan asli untuk mempercepat proses!`
            },
            "syarat balik nama tanah": {
                answer: `**Syarat Balik Nama Sertifikat Tanah:**

📄 **Dokumen yang Diperlukan:**
1. Sertifikat asli tanah
2. Fotokopi KTP penjual dan pembeli
3. Akta Jual Beli (AJB) dari PPAT
4. Bukti bayar BPHTB (Bea Perolehan Hak atas Tanah dan Bangunan)
5. Bukti bayar PPh (untuk penjual)
6. Surat Pernyataan dari Pembeli
7. Bukti pelunasan PBB 5 tahun terakhir
8. Surat Kuasa (jika dikuasakan)

🔄 **Proses:**
1. Pengecekan sertifikat di Kantor Pertanahan
2. Pembuatan AJB di hadapan PPAT
3. Pembayaran pajak (BPHTB dan PPh)
4. Pengajuan balik nama ke Kantor Pertanahan
5. Penerbitan sertifikat atas nama baru

💰 **Biaya:** Bervariasi tergantung NJOP tanah
⏱️ **Waktu:** 7-14 hari kerja

**Penting!** Pastikan tidak ada sengketa tanah sebelum proses balik nama.`
            },
            "biaya pengurusan sertifikat": {
                answer: `**Rincian Biaya Pengurusan Sertifikat Tanah:**

💵 **1. Sertifikat Tanah Baru:**
- Biaya pendaftaran: Rp 50.000 - Rp 100.000
- Biaya pengukuran: Rp 300.000 - Rp 1.000.000 (tergantung luas)
- Biaya pembuatan peta: Rp 200.000 - Rp 500.000
- Biaya penerbitan: Rp 100.000 - Rp 200.000
**Total estimasi:** Rp 650.000 - Rp 1.800.000

💵 **2. Balik Nama Sertifikat:**
- Biaya administrasi: Rp 50.000
- BPHTB: 5% x (NJOP - NJOPTKP)
- PPh: 2.5% x nilai transaksi (ditanggung penjual)
- Biaya PPAT: 1% x nilai transaksi
- Biaya balik nama: Rp 100.000 - Rp 500.000

💵 **3. Perpanjangan HGB/HP:**
- Biaya perpanjangan: 2-3% x NJOP tanah

💵 **4. Pemecahan/Penggabungan:**
- Biaya pemecahan: Rp 200.000 - Rp 500.000 per bidang
- Biaya penggabungan: Rp 300.000 - Rp 600.000

⚠️ **Catatan:** Biaya dapat berbeda di setiap daerah. Selalu konfirmasi ke Kantor Pertanahan setempat!`
            },
            "cara cek sertifikat tanah": {
                answer: `**Cara Mengecek Keaslian Sertifikat Tanah:**

🔍 **1. Pengecekan Fisik Sertifikat:**
- Cek watermark dan hologram
- Lihat keaslian tanda tangan pejabat
- Periksa cap/stempel BPN
- Cek nomor seri dan tahun penerbitan
- Periksa kerapian jahitan (untuk buku tanah)

🏢 **2. Pengecekan di Kantor Pertanahan:**
- Bawa sertifikat asli ke Kantor BPN
- Ajukan permohonan pengecekan
- Petugas akan mencocokkan dengan arsip
- Gratis dan selesai dalam 1-2 hari

💻 **3. Pengecekan Online (Jika Tersedia):**
- Akses website BPN daerah setempat
- Masukkan nomor sertifikat
- Verifikasi data yang muncul
- Download surat keterangan (jika tersedia)

📍 **4. Cek Riwayat Tanah:**
- Minta surat keterangan dari kelurahan
- Cek di Kantor PBB
- Tanyakan ke tetangga sekitar
- Periksa riwayat kepemilikan

🚨 **Tanda-tanda Sertifikat Palsu:**
- Kertas tidak ada watermark
- Cap/stempel tidak jelas
- Nomor seri tidak sesuai
- Data tidak cocok dengan arsip BPN

**Selalu cek ke BPN untuk memastikan!**`
            },
            "perbedaan shm dan shgb": {
                answer: `**Perbedaan SHM (Sertifikat Hak Milik) dan SHGB (Sertifikat Hak Guna Bangunan):**

🏠 **SHM (Hak Milik):**
✅ Hak kepemilikan terkuat dan terpenuh
✅ Berlaku selamanya (tidak ada batas waktu)
✅ Dapat dialihkan/dijual kepada siapa saja
✅ Dapat dijadikan jaminan utang
✅ Hanya untuk WNI dan badan hukum Indonesia
✅ Bisa untuk tanah pertanian atau non-pertanian
✅ Dapat diwariskan

🏢 **SHGB (Hak Guna Bangunan):**
⏱️ Berlaku terbatas: 30 tahun (dapat diperpanjang 20 tahun)
🏗️ Khusus untuk mendirikan dan memiliki bangunan
🔄 Dapat diperpanjang atau diperbaharui
💼 Bisa dimiliki WNI, WNA, badan hukum Indonesia/asing
📍 Biasanya di atas tanah negara atau tanah Hak Pengelolaan
⚠️ Setelah jatuh tempo harus diperpanjang atau menjadi tanah negara
💰 Ada biaya perpanjangan (2-3% NJOP)

**Kesimpulan:** 
SHM lebih kuat dan permanen, cocok untuk hunian jangka panjang. SHGB terbatas waktu tetapi bisa untuk WNA dan perusahaan asing.`
            },
            "cara perpanjang hgb": {
                answer: `**Cara Perpanjang HGB (Hak Guna Bangunan):**

⏰ **Waktu Pengajuan:**
- Ajukan minimal 2 tahun sebelum masa berlaku habis
- Jangan tunggu sampai jatuh tempo!
- Maksimal dapat diajukan saat sisa waktu 5 tahun

📋 **Syarat Perpanjangan:**
1. Fotokopi sertifikat HGB asli
2. Fotokopi KTP pemegang hak
3. Bukti pelunasan PBB 5 tahun terakhir
4. Surat permohonan perpanjangan
5. Bukti pembayaran uang pemasukan (2-3% x NJOP)
6. Surat pernyataan tanah tidak sengketa
7. Foto lokasi tanah terkini

🔄 **Proses Perpanjangan:**
1. Ajukan permohonan ke Kantor Pertanahan
2. Isi formulir perpanjangan
3. Serahkan dokumen persyaratan
4. Bayar biaya perpanjangan
5. Petugas melakukan pengecekan lapangan
6. Tunggu proses verifikasi (1-3 bulan)
7. Terbitnya sertifikat HGB yang diperpanjang

💰 **Biaya:** 2-3% dari NJOP tanah
⏱️ **Waktu Proses:** 1-3 bulan

⚠️ **Catatan Penting:**
- Jika terlambat mengajukan, bisa kena denda
- HGB dapat diperpanjang maksimal 1 kali (20 tahun)
- Setelah itu bisa diperbarui dengan hak baru (30 tahun lagi)
- Pastikan tanah masih digunakan sesuai peruntukannya`
            },
            "jenis sertifikat tanah": {
                answer: `**Jenis-Jenis Sertifikat Tanah di Indonesia :**

1. **Hak Milik (HM/SHM)** 🏠
   - Hak terkuat dan terpenuh
   - Berlaku selamanya
   - Hanya untuk WNI
   
2. **Hak Guna Bangunan (HGB/SHGB)** 🏢
   - Untuk mendirikan bangunan
   - Berlaku 30 tahun + 20 tahun
   - Bisa untuk WNA/perusahaan asing
   
3. **Hak Guna Usaha (HGU)** 🌾
   - Untuk usaha pertanian/perkebunan
   - Berlaku 35 tahun + 25 tahun
   - Minimal luas 5 hektar
   
4. **Hak Pakai (HP)** 🏘️
   - Untuk menggunakan tanah negara
   - Berlaku 25 tahun + 20 tahun
   - Tidak bisa diperjualbelikan bebas
   
5. **Hak Pengelolaan (HPL)** 🏛️
   - Untuk badan hukum pemerintah
   - Mengelola tanah negara
   
6. **Hak Milik Atas Satuan Rumah Susun (HMSRS)** 🏙️
   - Kepemilikan apartemen/rusun
   - Berlaku selamanya
   - Termasuk hak atas tanah bersama

**Masing-masing punya karakteristik dan ketentuan berbeda!**`
            },
            "apa itu ppat": {
                answer: `**PPAT (Pejabat Pembuat Akta Tanah) **

PPAT adalah pejabat umum yang diberi kewenangan untuk membuat akta-akta otentik mengenai perbuatan hukum tertentu atas tanah dan/atau bangunan.

🔑 **Tugas PPAT:**
- Membuat Akta Jual Beli (AJB) tanah
- Membuat Akta Hibah
- Membuat Akta Tukar Menukar
- Membuat Akta Pemberian Hak Tanggungan
- Membuat Akta Pembagian Hak Bersama

📜 **Kewenangan:**
- Hanya bisa dibuat di hadapan PPAT
- Akta yang dibuat memiliki kekuatan hukum
- Wajib terdaftar dan diangkat pemerintah

💼 **Jenis PPAT:**
1. PPAT Umum - melayani seluruh wilayah
2. PPAT Sementara - Camat di daerah tertentu
3. PPAT Khusus - untuk instansi tertentu

💰 **Biaya PPAT:**
Biasanya 1% dari nilai transaksi (bisa dinegosiasi)

⚠️ **Penting:** Pastikan menggunakan PPAT resmi yang terdaftar di BPN!`
            },
            "apa itu njop": {
                answer: `**NJOP (Nilai Jual Objek Pajak) **

NJOP adalah harga rata-rata yang diperoleh dari transaksi jual beli tanah dan bangunan. Jika tidak ada transaksi, ditentukan melalui perbandingan harga dengan objek lain yang sejenis.

📊 **Fungsi NJOP:**
- Dasar perhitungan PBB (Pajak Bumi dan Bangunan)
- Dasar perhitungan BPHTB (Bea Perolehan Hak atas Tanah)
- Referensi nilai properti
- Dasar perhitungan berbagai biaya pertanahan

💰 **Cara Mengetahui NJOP:**
1. Cek di SPPT PBB (Surat Pemberitahuan Pajak Terutang)
2. Datang ke Kantor Pajak/Dispenda setempat
3. Akses website pajak daerah (jika tersedia)
4. Tanya ke kelurahan/kecamatan

📈 **Pembaruan NJOP:**
- Ditetapkan setiap 3 tahun sekali
- Bisa lebih cepat jika ada perkembangan wilayah
- Ditetapkan oleh Kepala Daerah

🏘️ **Faktor yang Mempengaruhi:**
- Lokasi dan aksesibilitas
- Fasilitas umum di sekitar
- Perkembangan wilayah
- Kondisi ekonomi

**NJOP berbeda dengan harga pasar, biasanya lebih rendah!**`
            },
            "apa itu bphtb": {
                answer: `**BPHTB (Bea Perolehan Hak atas Tanah dan Bangunan) **

BPHTB adalah pajak yang dikenakan atas perolehan hak atas tanah dan/atau bangunan.

💰 **Tarif BPHTB:**
5% x (NJOP - NJOPTKP)

NJOPTKP = Nilai Jual Objek Pajak Tidak Kena Pajak (berbeda tiap daerah)

📋 **Kapan BPHTB Dibayar:**
- Jual beli tanah/bangunan
- Hibah/hadiah
- Waris
- Tukar menukar
- Lelang
- Pemisahan hak yang mengakibatkan peralihan

🏢 **Cara Bayar BPHTB:**
1. Hitung nilai BPHTB
2. Isi formulir SSPD BPHTB
3. Bayar di bank yang ditunjuk
4. Simpan bukti pembayaran

⏱️ **Batas Waktu:**
Paling lambat 30 hari sejak akta ditandatangani

⚠️ **Penting:**
- BPHTB harus dibayar sebelum balik nama
- Tanpa bukti bayar BPHTB, proses balik nama tidak bisa dilanjutkan
- Sanksi keterlambatan: denda 2% per bulan (maksimal 24 bulan)

**Contoh Perhitungan:**
NJOP: Rp 500.000.000
NJOPTKP: Rp 80.000.000
BPHTB = 5% x (500jt - 80jt) = Rp 21.000.000`
            },
            "dokumen apa saja": {
                answer: `**Dokumen Penting Dalam Pengurusan Tanah :**

📄 **1. Dokumen Kepemilikan:**
- Sertifikat tanah (SHM/SHGB/HGB/HP)
- Girik/Petok D (tanah lama)
- Letter C
- AJB (Akta Jual Beli)
- Akta Hibah/Waris

🆔 **2. Identitas:**
- KTP asli dan fotokopi
- KK (Kartu Keluarga)
- NPWP
- Surat Nikah (jika sudah menikah)

📋 **3. Dokumen Administratif:**
- SPPT PBB 5 tahun terakhir
- Bukti pelunasan PBB
- Surat Keterangan dari Kelurahan
- IMB (jika ada bangunan)

💼 **4. Dokumen Transaksi:**
- PPJB (Perjanjian Pengikatan Jual Beli)
- Kwitansi pembayaran
- Surat Kuasa (jika dikuasakan)
- Bukti bayar BPHTB
- Bukti bayar PPh

🗺️ **5. Dokumen Teknis:**
- Peta lokasi
- Gambar situasi
- Surat ukur
- Batas-batas tanah

📸 **6. Dokumen Pendukung:**
- Foto lokasi tanah
- Surat pernyataan tidak sengketa
- Surat persetujuan tetangga (jika perlu)

**Selalu siapkan dokumen asli + fotokopi!**`
            },
            "masalah tanah": {
                answer: `**Masalah-Masalah Umum Pertanahan & Solusinya :**

⚠️ **1. Sertifikat Ganda**
Masalah: 2 sertifikat untuk 1 bidang tanah
Solusi: Lapor ke BPN, ajukan pembatalan sertifikat palsu

⚠️ **2. Batas Tanah Tidak Jelas**
Masalah: Sengketa batas dengan tetangga
Solusi: Mediasi dengan RT/RW, pengukuran ulang oleh BPN

⚠️ **3. Tanah Warisan Belum Dibagi**
Masalah: Ahli waris tidak sepakat
Solusi: Buat Akta Pembagian Waris di Notaris, atau pengadilan

⚠️ **4. Sertifikat Hilang**
Masalah: Dokumen sertifikat hilang/rusak
Solusi: Lapor polisi, ajukan penerbitan sertifikat pengganti ke BPN

⚠️ **5. Tanah Terkena Gusur**
Masalah: Tanah untuk kepentingan umum
Solusi: Pastikan dapat ganti rugi sesuai NJOP + biaya lain

⚠️ **6. Jual Beli Tidak Sah**
Masalah: Transaksi tanpa AJB dari PPAT
Solusi: Segera buat AJB legal, risiko: bisa dibatalkan

⚠️ **7. PBB Menunggak**
Masalah: Tidak bayar PBB bertahun-tahun
Solusi: Lunasi tunggakan + denda, cek di kantor pajak

**Tips:** Konsultasikan ke ahli hukum pertanahan atau BPN setempat untuk kasus spesifik!`
            },
            "tips membeli tanah": {
                answer: `**Tips Aman Membeli Tanah :**

✅ **1. Cek Status Hukum**
- Pastikan sertifikat asli (cek ke BPN)
- Tidak dalam sengketa
- PBB lunas
- Tidak dijaminkan ke bank

✅ **2. Cek Fisik Tanah**
- Kunjungi lokasi langsung
- Cek batas-batas dengan tetangga
- Pastikan sesuai ukuran di sertifikat
- Cek aksesibilitas dan fasilitas

✅ **3. Cek Dokumen**
- Sertifikat asli (bukan fotokopi)
- Identitas pemilik sama dengan sertifikat
- Riwayat kepemilikan jelas
- Tidak ada blokir/sita

✅ **4. Proses Legal**
- Gunakan PPAT resmi
- Buat AJB (jangan hanya kwitansi)
- Bayar BPHTB dan PPh
- Segera balik nama setelah AJB

✅ **5. Harga Wajar**
- Bandingkan dengan harga sekitar
- Hati-hati harga terlalu murah
- Negosiasi dengan data NJOP

✅ **6. Periksa Perencanaan Wilayah**
- Cek RTRW (Rencana Tata Ruang Wilayah)
- Pastikan tidak untuk jalan/fasum
- Cek peruntukan lahan

❌ **Hindari:**
- Beli tanpa sertifikat
- Transaksi di bawah tangan
- Percaya makelar tidak jelas
- Terburu-buru tanpa survey

🔒 **Gunakan escrow account atau rekening bersama untuk pembayaran bertahap!**`
            },
            "pengertian tanah": {
                answer: `**Pengertian Tanah Menurut Hukum Indonesia :**

📚 **Definisi:**
Menurut UU No. 5 Tahun 1960 (UUPA), tanah adalah permukaan bumi yang dapat dimiliki dengan hak-hak tertentu.

🌍 **Komponen Tanah:**
1. **Permukaan Bumi** - bagian atas
2. **Tubuh Bumi** - bagian di bawahnya
3. **Air dan Ruang** - yang ada di atasnya
4. **Kekayaan Alam** - yang terkandung di dalamnya

⚖️ **Prinsip Dasar:**
- Tanah adalah karunia Tuhan
- Hak menguasai tertinggi ada di negara
- Fungsi sosial (tidak boleh merugikan orang lain)
- Hanya WNI yang boleh punya hak milik

🏠 **Fungsi Tanah:**
- Tempat tinggal
- Usaha/bisnis
- Pertanian
- Investasi
- Warisan

📋 **Jenis Hak Atas Tanah:**
- Hak Milik (terkuat)
- Hak Guna Usaha
- Hak Guna Bangunan
- Hak Pakai
- Hak Sewa
- Hak Membuka Tanah
- Hak Memungut Hasil Hutan

**Semua tanah di Indonesia pada dasarnya dikuasai oleh negara untuk kemakmuran rakyat!**`
            },
            "apa itu hgu": {
                answer: `**HGU (Hak Guna Usaha) **

HGU adalah hak atas tanah negara untuk usaha pertanian, perkebunan, atau peternakan.

🕒 Masa Berlaku: 35 tahun + perpanjangan 25 tahun

⚠️ Syarat: Luas minimal 5 hektar, untuk WNI/badan hukum

**Proses Pengurusan:**
- Ajukan ke BPN
- Dokumen: KTP, NPWP, rencana usaha, dll
- Biaya: Tergantung luas & NJOP

Penting untuk bisnis agribisnis!`
            },
            "cara urus imb": {
                answer: `**Cara Mengurus IMB (Izin Mendirikan Bangunan) :**

📋 **Dokumen:**
- KTP & NPWP
- Sertifikat tanah
- Gambar denah bangunan
- Bukti bayar retribusi

🔄 **Proses:**
1. Ajukan ke Dinas Tata Kota
2. Verifikasi dokumen
3. Bayar retribusi
4. Tunggu terbit (1-3 bulan)

💰 Biaya: Bervariasi berdasarkan luas bangunan

Tanpa IMB, bangunan bisa dibongkar!`
            },
            "apa itu pbb": {
                answer: `**PBB (Pajak Bumi dan Bangunan) **

PBB adalah pajak atas tanah dan bangunan yang dikelola oleh pemerintah daerah.

💰 **Tarif:** 0.1% - 0.5% x NJOP

**Cara Bayar:**
- Dapat SPPT dari kelurahan
- Bayar di bank/kantor pos/online

**Waktu Bayar:** Setiap tahun sebelum jatuh tempo (September)

**Sanksi:** Denda 2% per bulan jika telat

Selalu lunasi ya!`
            },
            "cara hitung bphtb": {
                answer: `**Cara Hitung BPHTB :**

BPHTB = 5% x (NJOP - NJOPTKP)

- NJOP: Nilai Jual Objek Pajak
- NJOPTKP: Nilai Tidak Kena Pajak (berbeda per daerah, misal Rp 60jt untuk rumah tinggal)

**Contoh:**
NJOP: Rp 500jt
NJOPTKP: Rp 80jt
BPHTB = 5% x (500jt - 80jt) = Rp 21jt

Bayar sebelum balik nama!`
            },
            "sengketa tanah gimana": {
                answer: `**Cara Atasi Sengketa Tanah :**

1. **Mediasi:** Bahas dengan pihak terkait, saksi dari RT/RW/kelurahan

2. **Lapor BPN:** Ajukan pengecekan data yuridis

3. **Gugatan Pengadilan:** Jika ga selesai, bawa ke pengadilan negeri

**Tips:** Kumpulkan bukti (sertifikat, saksi, foto)

Gunakan pengacara pertanahan kalau rumit!`
            },
            "cara bagi waris tanah": {
                answer: `**Cara Bagi Waris Tanah :**

1. **Tentukan Ahli Waris:** Berdasarkan hukum waris (Islam/Adat/BW)

2. **Dokumen:** Sertifikat tanah, surat kematian, akta kelahiran ahli waris

3. **Buat Akta Waris:** Di notaris/PPAT

4. **Balik Nama:** Ajukan ke BPN dengan akta waris

Waktu: 1-3 bulan, biaya tergantung luas

Hindari sengketa dengan musyawarah!`
            },
            "tanah negara bisa dibeli ga": {
                answer: `**Tanah Negara Bisa Dibeli ?**

Tidak langsung dibeli, tapi bisa dikuasai dengan hak seperti HGB/HP/HGU.

**Cara:**
- Ajukan izin pemanfaatan tanah negara ke BPN
- Syarat: Rencana penggunaan, KTP, dll
- Biaya: Uang pemasukan berdasarkan NJOP

Bukan hak milik, tapi bisa diperpanjang. Konsultasi BPN!`
            },
            "apa itu rtrw": {
                answer: `**RTRW (Rencana Tata Ruang Wilayah) **

RTRW adalah rencana penggunaan lahan di suatu daerah untuk 20 tahun ke depan.

**Fungsi:**
- Tentukan peruntukan tanah (hunian, pertanian, industri)
- Hindari penggunaan tanah sembarangan
- Dasar penerbitan IMB

Cek di Dinas Tata Kota daerahmu sebelum beli tanah!`
            },
            "cara cek rtrw": {
                answer: `**Cara Cek RTRW :**

1. **Online:** Akses website Dinas Tata Kota/Pemda (misal jakarta.go.id untuk Jakarta)

2. **Datang Langsung:** Ke kantor Dinas Tata Kota kabupaten/kota

3. **Dokumen:** Minta peta RTRW atau surat keterangan peruntukan tanah

Penting untuk pastikan tanahmu sesuai peruntukan!`
            },
            "biaya perpanjang shgb": {
                answer: `**Biaya Perpanjang SHGB :**

Biaya: 2-3% dari NJOP tanah (Nilai Jual Objek Pajak)

**Contoh:**
NJOP Rp 1 Miliar
Biaya ≈ Rp 20-30 Juta

Bayar di Kantor Pertanahan saat ajukan perpanjangan (minimal 2 tahun sebelum habis).`
            },
            "apa itu hmsrs": {
                answer: `**HMSRS (Hak Milik Atas Satuan Rumah Susun) **

HMSRS adalah sertifikat kepemilikan apartemen/kondominium.

**Karacteristik:**
- Berlaku selamanya seperti SHM
- Termasuk hak atas tanah bersama & fasum
- Bisa dijual, diwariskan, digadaikan

Wajib bayar service charge bulanan!`
            },
            "cara urus hmsrs": {
                answer: `**Cara Urus HMSRS :**

1. **Dokumen:** Sertifikat induk, AJB dari developer, KTP, NPWP

2. **Ajukan ke BPN:** Serahkan dokumen, bayar biaya

3. **Verifikasi:** Pengukuran unit & bagian bersama

4. **Terbit:** Dapat sertifikat HMSRS

Waktu: 1-2 bulan, biaya tergantung luas unit.`
            },
            "apa itu girik": {
                answer: `**Girik **

Girik adalah bukti penguasaan tanah lama (pra-sertifikat) dari desa/kelurahan.

**Fungsi:**
- Bukti sementara sebelum sertifikat
- Dasar untuk urus sertifikat tanah

**Catatan:** Bukan hak milik, mudah disengketakan. Segera konversi ke sertifikat SHM!`
            },
            "cara konversi girik ke shm": {
                answer: `**Cara Konversi Girik ke SHM :**

1. **Dokumen:** Girik asli, KTP, SPPT PBB 5 tahun, surat riwayat tanah

2. **Ajukan ke BPN:** Isi form, bayar biaya pendaftaran

3. **Pengukuran:** Petugas ukur tanah

4. **Pengumuman & Terbit:** Tunggu 3-6 bulan

Biaya: Rp 650rb - 1.8jt tergantung luas.`
            },
            "apa itu letter c": {
                answer: `**Letter C **

Letter C adalah buku catatan tanah di desa (mirip girik), bukti penguasaan tanah zaman kolonial.

**Fungsi:**
- Bukti sementara kepemilikan
- Dasar urus sertifikat

**Catatan:** Bukan sertifikat resmi, segera konversi ke SHM untuk keamanan!`
            },
            "cara urus tanah warisan": {
                answer: `**Cara Urus Tanah Warisan :**

1. **Buat Surat Keterangan Waris:** Dari kelurahan/notaris, daftar ahli waris

2. **Dokumen:** Sertifikat tanah, surat kematian pewaris, KTP ahli waris

3. **Balik Nama:** Ajukan ke BPN dengan akta pembagian waris

4. **Bayar BPHTB:** Jika ada peralihan hak

Waktu: 1-3 bulan, hindari sengketa dengan musyawarah!`
            },
            "biaya balik nama waris": {
                answer: `**Biaya Balik Nama Tanah Waris :**

- Biaya administrasi BPN: Rp 50rb - 100rb
- BPHTB: 5% x (NJOP - NJOPTKP) jika melebihi batas waris bebas pajak
- Biaya notaris: Rp 500rb - 2jt tergantung kompleksitas

Untuk waris keluarga inti biasanya bebas BPHTB sampai batas tertentu. Cek ke BPN!`
            },
            "apa itu hak pakai": {
                answer: `**Hak Pakai (HP) **

HP adalah hak menggunakan tanah negara untuk jangka waktu tertentu.

🕒 Masa Berlaku: 25 tahun + perpanjangan 20 tahun

**Syarat:** Untuk perorangan/badan hukum, tidak bisa dijual bebas

**Contoh:** Tanah untuk sekolah, rumah ibadah

Bukan hak milik, kembali ke negara setelah masa berakhir.`
            },
            "apa itu hak pengelolaan": {
                answer: `**Hak Pengelolaan (HPL) **

HPL adalah hak mengelola tanah negara oleh badan hukum pemerintah (BUMN, Pemda).

**Fungsi:**
- Kelola & berikan hak lain (HGB/HP) atas tanah negara

**Contoh:** PT KAI kelola tanah rel kereta

Tidak untuk perorangan, masa berlaku tak terbatas.`
            },
            "cara urus pemecahan sertifikat": {
                answer: `**Cara Urus Pemecahan Sertifikat Tanah :**

1. **Dokumen:** Sertifikat asli, KTP, SPPT PBB, gambar pemecahan

2. **Ajukan ke BPN:** Isi form pemecahan bidang tanah

3. **Pengukuran:** Petugas ukur ulang batas

4. **Terbit Sertifikat Baru:** Untuk setiap bidang hasil pemecahan

Waktu: 1-2 bulan, biaya Rp 200rb - 500rb per bidang.`
            },
            "cara gabung sertifikat tanah": {
                answer: `**Cara Gabung Sertifikat Tanah :**

1. **Dokumen:** Sertifikat-sertifikat asli, KTP, SPPT PBB, gambar penggabungan

2. **Ajukan ke BPN:** Isi form penggabungan bidang tanah

3. **Verifikasi:** Cek batas & riwayat

4. **Terbit Sertifikat Baru:** Satu sertifikat untuk tanah gabungan

Waktu: 1-3 bulan, biaya Rp 300rb - 600rb.`
            },
            "apa itu tanah adat": {
                answer: `**Tanah Adat **

Tanah adat adalah tanah yang dikuasai masyarakat adat berdasarkan hukum adat setempat.

**Status:** Diakui negara (UUPA Pasal 3), tapi harus didaftarkan ke BPN

**Contoh:** Tanah ulayat di Minangkabau atau Bali

**Catatan:** Bisa dikonversi ke hak individu, tapi hormati norma adat!`
            },
            "cara urus tanah adat": {
                answer: `**Cara Urus Tanah Adat :**

1. **Dapatkan Surat Keterangan:** Dari pemimpin adat & kelurahan

2. **Dokumen:** Bukti penguasaan adat, KTP, saksi

3. **Ajukan Sertifikat:** Ke BPN sebagai tanah hak milik/adat

4. **Verifikasi:** BPN periksa dengan masyarakat adat

Waktu: 3-6 bulan, biaya mirip sertifikat baru.`
            },
            "apa itu sengketa batas tanah": {
                answer: `**Sengketa Batas Tanah **

Sengketa batas adalah perselisihan antar pemilik tanah tentang garis batas bidang tanah.

**Penyebab:** Pengukuran salah, dokumen lama kabur, atau konflik waris

**Solusi:**
- Mediasi kelurahan
- Pengukuran ulang BPN
- Gugat pengadilan kalau ga selesai

Cegah dengan patok batas jelas & dokumen lengkap!`
            },
            "cara cegah sengketa tanah": {
                answer: `**Cara Cegah Sengketa Tanah :**

1. **Dokumen Lengkap:** Sertifikat asli, update balik nama

2. **Patok Batas Jelas:** Pasang patok & tanda batas permanen

3. **Cek Riwayat:** Sebelum beli, verifikasi ke BPN & tetangga

4. **Transaksi Legal:** Selalu pakai PPAT & bayar pajak

5. **Asuransi Tanah:** Pertimbangkan asuransi properti

Aman itu lebih baik dari obat!`
            },
            "apa itu lelang tanah": {
                answer: `**Lelang Tanah **

Lelang tanah adalah penjualan tanah melalui proses lelang resmi, biasanya karena sita hutang atau tanah negara.

**Proses:**
1. Pengumuman lelang
2. Daftar & bayar jaminan
3. Ikut lelang (tawar tertinggi menang)
4. Bayar & balik nama

**Sumber:** KPKNL atau lelang swasta

Hati-hati cek status tanah sebelum ikut!`
            },
            "tips ikut lelang tanah": {
                answer: `**Tips Ikut Lelang Tanah :**

1. **Cek Dokumen:** Pastikan tanah bebas sengketa, cek NJOP

2. **Survey Lokasi:** Kunjungi tanah, cek kondisi & akses

3. **Hitung Biaya Total:** Harga lelang + pajak + biaya lain

4. **Siapkan Dana:** Bayar jaminan & siap bayar penuh jika menang

5. **Ikuti Aturan:** Daftar tepat waktu, jangan emosi saat tawaran

Sabar & teliti kunci sukses!`
            },
            // ... (Tambah entry baru seperti ini kalau mau lebih banyak lagi, misal "apa itu imb", "cara urus ppjb", dll)
        };

        function addMessage(text, isUser = false) {
            const chatContainer = document.getElementById('chatContainer');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'bot'}`;
            
            const avatar = document.createElement('div');
            avatar.className = `avatar ${isUser ? 'user' : 'bot'}`;
            avatar.textContent = isUser ? '👤' : '🤖';
            
            const content = document.createElement('div');
            content.className = 'message-content';
            content.innerHTML = text.replace(/\n/g, '<br>');
            
            if (isUser) {
                messageDiv.appendChild(content);
                messageDiv.appendChild(avatar);
            } else {
                messageDiv.appendChild(avatar);
                messageDiv.appendChild(content);
            }
            
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function showTypingIndicator() {
            const chatContainer = document.getElementById('chatContainer');
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message bot';
            typingDiv.id = 'typingIndicator';
            
            const avatar = document.createElement('div');
            avatar.className = 'avatar bot';
            avatar.textContent = '🤖';
            
            const indicator = document.createElement('div');
            indicator.className = 'message-content';
            indicator.innerHTML = '<div class="typing-indicator"><span></span><span></span><span></span></div>';
            
            typingDiv.appendChild(avatar);
            typingDiv.appendChild(indicator);
            chatContainer.appendChild(typingDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function findAnswer(question) {
            const normalizedQuestion = question.toLowerCase().trim();
            
            // Direct match
            for (const [key, value] of Object.entries(knowledgeBase)) {
                if (normalizedQuestion === key || normalizedQuestion.includes(key)) {
                    return value;
                }
            }
            
            // Greeting detection
            if (normalizedQuestion.match(/^(hai|halo|hi|hello|hey)$/i)) {
                return knowledgeBase["halo"];
            }
            
            // Time-based greetings
            if (normalizedQuestion.includes('pagi')) return knowledgeBase["selamat pagi"];
            if (normalizedQuestion.includes('siang')) return knowledgeBase["selamat siang"];
            if (normalizedQuestion.includes('sore')) return knowledgeBase["selamat sore"];
            if (normalizedQuestion.includes('malam')) return knowledgeBase["selamat malam"];
            if (normalizedQuestion.includes('kabar')) return knowledgeBase["apa kabar"];
            
            // About system
            if ((normalizedQuestion.includes('cara') || normalizedQuestion.includes('bagaimana')) && 
                (normalizedQuestion.includes('pakai') || normalizedQuestion.includes('gunakan') || normalizedQuestion.includes('menggunakan'))) {
                return knowledgeBase["cara pakai"];
            }
            if (normalizedQuestion.includes('fitur')) return knowledgeBase["fitur apa saja"];
            if (normalizedQuestion.includes('geoai') || (normalizedQuestion.includes('apa') && normalizedQuestion.includes('ini'))) {
                return knowledgeBase["apa itu geoai"];
            }
            
            // About creator
            if (normalizedQuestion.includes('pembuat') || normalizedQuestion.includes('developer') || 
                normalizedQuestion.includes('made you') || normalizedQuestion.includes('creator') ||
                normalizedQuestion.includes('menciptakan')) {
                return knowledgeBase["siapa pembuatmu"];
            }
            if (normalizedQuestion.includes('rizkeygnawantftdot18')) return knowledgeBase["rizkeygnawantftdot18"];
            if ((normalizedQuestion.includes('siapa') || normalizedQuestion.includes('who')) && normalizedQuestion.includes('kamu')) {
                return knowledgeBase["siapa kamu"];
            }
            
            // Help & Support
            if (normalizedQuestion.includes('help') || normalizedQuestion.includes('bantuan') || normalizedQuestion.includes('tolong')) {
                return knowledgeBase["help"];
            }
            if (normalizedQuestion.includes('terima kasih') || normalizedQuestion.includes('thanks') || normalizedQuestion.includes('thank you')) {
                return knowledgeBase["terima kasih"];
            }
            if (normalizedQuestion.includes('tidak mengerti') || normalizedQuestion.includes('bingung') || normalizedQuestion.includes('confused')) {
                return knowledgeBase["tidak mengerti"];
            }
            
            // Land-related keywords
            if (normalizedQuestion.includes('sertifikat') && (normalizedQuestion.includes('urus') || normalizedQuestion.includes('buat') || normalizedQuestion.includes('proses'))) {
                return knowledgeBase["cara mengurus sertifikat tanah"];
            }
            if (normalizedQuestion.includes('balik nama')) return knowledgeBase["syarat balik nama tanah"];
            if (normalizedQuestion.includes('biaya') || normalizedQuestion.includes('harga') || normalizedQuestion.includes('tarif') || normalizedQuestion.includes('cost')) {
                return knowledgeBase["biaya pengurusan sertifikat"];
            }
            if (normalizedQuestion.includes('cek') || normalizedQuestion.includes('periksa') || normalizedQuestion.includes('verifikasi') || normalizedQuestion.includes('check')) {
                return knowledgeBase["cara cek sertifikat tanah"];
            }
            if (normalizedQuestion.includes('perbedaan') && (normalizedQuestion.includes('shm') || normalizedQuestion.includes('hgb'))) {
                return knowledgeBase["perbedaan shm dan shgb"];
            }
            if (normalizedQuestion.includes('perpanjang') || normalizedQuestion.includes('extend') || normalizedQuestion.includes('renewal')) {
                return knowledgeBase["cara perpanjang hgb"];
            }
            if (normalizedQuestion.includes('jenis') && normalizedQuestion.includes('sertifikat')) {
                return knowledgeBase["jenis sertifikat tanah"];
            }
            if (normalizedQuestion.includes('ppat')) return knowledgeBase["apa itu ppat"];
            if (normalizedQuestion.includes('njop')) return knowledgeBase["apa itu njop"];
            if (normalizedQuestion.includes('bphtb')) return knowledgeBase["apa itu bphtb"];
            if (normalizedQuestion.includes('dokumen') && normalizedQuestion.includes('apa')) {
                return knowledgeBase["dokumen apa saja"];
            }
            if (normalizedQuestion.includes('masalah') || normalizedQuestion.includes('sengketa') || normalizedQuestion.includes('problem')) {
                return knowledgeBase["masalah tanah"];
            }
            if (normalizedQuestion.includes('tips') && normalizedQuestion.includes('beli')) {
                return knowledgeBase["tips membeli tanah"];
            }
            if (normalizedQuestion.includes('pengertian') || normalizedQuestion.includes('definisi')) {
                return knowledgeBase["pengertian tanah"];
            }
            
            return null;
        }

        function askQuestion(question) {
            addMessage(question, true);
            
            showTypingIndicator();
            
            setTimeout(() => {
                removeTypingIndicator();
                
                const answer = findAnswer(question);
                
                if (answer) {
                    addMessage(answer.answer);
                } else {
                    addMessage(`Maaf , saya ga bisa jawab spesifik tentang "${question}" karena saya hanya AI Agent untuk pertanyaan seputar sistem informasi pertanahan. 😅

Ini bantuan pertanyaan yang lengkap banget buat kamu coba:
- "Cara mengurus sertifikat tanah" (panduan lengkap urus sertifikat)
- "Syarat balik nama tanah" (dokumen & proses balik nama)
- "Biaya pengurusan sertifikat" (rincian biaya sertifikat & balik nama)
- "Cara cek sertifikat tanah" (cek keaslian online/offline)
- "Perbedaan SHM dan SHGB" (perbedaan hak milik & guna bangunan)
- "Cara perpanjang HGB" (langkah perpanjang hak guna bangunan)
- "Jenis sertifikat tanah" (daftar jenis seperti SHM, HGB, HGU, dll)
- "Apa itu PPAT" (penjelasan Pejabat Pembuat Akta Tanah)
- "Apa itu NJOP" (nilai jual objek pajak & cara cek)
- "Apa itu BPHTB" (bea perolehan hak atas tanah & contoh hitung)
- "Dokumen apa saja" (daftar dokumen pengurusan tanah)
- "Masalah tanah" (masalah umum & solusi seperti sengketa)
- "Tips membeli tanah" (tips aman beli tanah tanpa kena tipu)
- "Pengertian tanah" (definisi tanah menurut UUPA)

Contoh: Ketik "cara mengurus sertifikat tanah" buat panduan lengkap!

Saya selalu belajar untuk melayani Anda lebih baik! 💚`);
                }
            }, 1500);
        }

        function sendMessage() {
            const input = document.getElementById('userInput');
            const message = input.value.trim();
            
            if (message) {
                askQuestion(message);
                input.value = '';
            }
        }

        function handleKeyPress(event) {
            if (event.key === 'Enter') {
                sendMessage();
            }
        }
    </script>
</body>
</html>
