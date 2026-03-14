<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEOTERRAID - Login Dashboard SIP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <style>
        body, html { margin:0; padding:0; height:100%; font-family:'Inter',sans-serif; }
        input[type="password"]::-webkit-reveal-button { display: none; }
        input[type="password"]::-ms-reveal { display: none; }
        
        /* Loading Screen Styles */
        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #3b82f6 100%);
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

        .loading-logo-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin-bottom: 2rem;
        }

        .loading-logo {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 4px solid rgba(255,255,255,0.3);
            box-shadow: 0 0 60px rgba(255,255,255,0.4);
            animation: pulseGlow 2s infinite ease-in-out;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
        }

        .loading-logo img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 40px rgba(255,255,255,0.4); transform: scale(1); }
            50% { box-shadow: 0 0 100px rgba(255,255,255,0.7); transform: scale(1.08); }
        }

        .loading-ring {
            position: absolute;
            inset: -10px;
            border: 3px solid transparent;
            border-top-color: rgba(255,255,255,0.8);
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        .loading-ring:nth-child(2) {
            inset: -20px;
            border-top-color: rgba(255,255,255,0.5);
            animation-duration: 2s;
            animation-direction: reverse;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-text {
            font-family: 'Inter', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            opacity: 0;
            animation: fadeInUp 1s ease forwards 0.5s;
            text-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }

        .loading-text span {
            color: #60a5fa;
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .loading-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 1.2rem;
            font-weight: 300;
            color: rgba(255,255,255,0.8);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 2rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards 0.8s;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .loading-developer {
            font-family: 'Inter', sans-serif;
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
            letter-spacing: 2px;
            text-transform: uppercase;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.2s;
        }

        .loading-developer span {
            color: #93c5fd;
            font-weight: 700;
        }

        .loading-bar-container {
            width: 200px;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 2px;
            margin-top: 2rem;
            overflow: hidden;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.5s;
        }

        .loading-bar {
            height: 100%;
            background: linear-gradient(90deg, #60a5fa, #93c5fd, #60a5fa);
            background-size: 200% 100%;
            animation: shimmer 1.5s ease-in-out infinite;
            border-radius: 2px;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .loading-dots {
            display: flex;
            gap: 10px;
            margin-top: 1.5rem;
            opacity: 0;
            animation: fadeIn 1s ease forwards 1.8s;
        }

        .loading-dots span {
            width: 12px;
            height: 12px;
            background: rgba(255,255,255,0.6);
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

        @keyframes countdownPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .countdown-pulse {
            animation: countdownPulse 0.5s ease-in-out;
        }

        /* Main Content - Hidden initially */
        .main-content {
            opacity: 0;
            transition: opacity 0.8s ease;
            position: relative;
            z-index: 1;
        }

        .main-content.visible {
            opacity: 1;
        }
    </style>
</head>
<body class="h-full bg-gray-50">

    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo-container">
            <div class="loading-ring"></div>
            <div class="loading-ring"></div>
            <div class="loading-logo">
                <img src="{{ asset('sbadmin/img/20251208_184230.png') }}" alt="Logo">
            </div>
        </div>
        <div class="loading-text">GEOTER<span>RAID</span></div>
        <div class="loading-subtitle">Sistem Informasi Pertanahan</div>
        <div class="loading-developer">KEMENTRIAN AGRARIA DAN TATA RUANG</div>
        <div class="loading-bar-container">
            <div class="loading-bar"></div>
        </div>
        <div class="loading-dots">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">

<div class="h-full flex flex-col lg:flex-row">

    <!-- ================== KIRI: BACKGROUND GEDUNG SUPER LUAS ================== -->
    <!-- GANTI LINK INI: Foto gedung kantor ATR/BPN atau gedung pertanahan resmi -->
    <div class="relative hidden lg:block flex-[2.4] bg-cover bg-center"
         style="background-image: url('{{ asset('sbadmin/img/mount-agung-volcano-rice-fields-bali-indonesia-crescent-3000x3974-1107.jpg') }}');">
        
        <div class="absolute inset-0 flex flex-col justify-between p-16 text-white">
<br>
<br>

            <div class="max-w-5xl">
                <h1 class="text-6xl font-black">Selamat Datang di</h1>
                <h2 class="text-9xl font-black text-blue-400 mt-2 leading-none">GEOTERRAID</h2>
                <p class="text-3xl font-light mt-6">Sistem Informasi Pertanahan Terintegrasi</p>
                <p class="text-lg mt-2 opacity-90">Kementerian Agraria dan Tata Ruang / Badan Pertanahan Nasional</p>
                <div class="mt-12">
                    <p class="text-sm opacity-80">Developed by</p>
                    <p class="text-4xl font-bold">Kementrian ATR/BPN</p>
                </div>
            </div>

            <!-- GANTI LINK INI: Logo resmi Kementerian ATR/BPN (PNG transparan) -->
            <div class="text-center">
                <img src="{{ asset('sbadmin/img/Logo_BPN-KemenATR_(2017).png') }}" alt="ATR/BPN" class="h-24" height="96">
            </div>
        </div>
    </div>

    <!-- ================== KANAN: FORM LEBIH RAMMING + BISA SCROLL ================== -->
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12 relative">
        <!-- Back to Frontend Button -->
        <a href="/" class="absolute top-4 left-4 bg-white/90 hover:bg-white text-blue-600 hover:text-blue-700 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center space-x-2 z-10">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Menu Utama</span>
        </a>

        <!-- Responsive Feature List - Mobile -->
        <div class="absolute top-4 right-4 lg:hidden z-10">
            <button onclick="toggleFeatureList()" class="bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg shadow-lg transition duration-200">
                <i class="fas fa-list-ul"></i>
            </button>

            <div id="featureList" class="hidden absolute top-16 right-0 bg-white rounded-xl shadow-2xl p-4 w-80 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-3 text-center">Fitur Sistem</h4>
                <div class="space-y-2">
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-map-marked-alt text-blue-600 w-5"></i>
                        <span class="text-sm text-gray-700">Peta Pertanahan Interaktif</span>
                    </div>
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-database text-green-600 w-5"></i>
                        <span class="text-sm text-gray-700">Data Master Penduduk</span>
                    </div>
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-users-cog text-purple-600 w-5"></i>
                        <span class="text-sm text-gray-700">Manajemen Pengguna</span>
                    </div>
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-file-alt text-orange-600 w-5"></i>
                        <span class="text-sm text-gray-700">Laporan Terintegrasi</span>
                    </div>
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-certificate text-yellow-600 w-5"></i>
                        <span class="text-sm text-gray-700">Sertifikasi Tanah</span>
                    </div>
                    <div class="flex items-center space-x-3 p-2 hover:bg-blue-50 rounded-lg transition">
                        <i class="fas fa-cogs text-gray-600 w-5"></i>
                        <span class="text-sm text-gray-700">Pengaturan Sistem</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-10 lg:p-12 max-h-screen overflow-y-auto">

            <!-- GANTI LINK INI: Logo instansi / universitas / proyek (logo di atas form) -->
            <div class="flex justify-center mb-10">
                <img src="{{ asset('sbadmin/img/20251208_184230.png') }}" alt="Logo Instansi 1" class="h-40" height="160">
            </div>

            <p class="text-center text-gray-600 text-lg mb-10">
                Sistem Informasi Pertanahan
            </p>

            <form method="POST" action="/login" class="space-y-7" id="loginForm">
                @csrf

                <!-- Error Modal -->
                <div id="errorModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-100 transition-transform">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2" id="errorTitle">Login Gagal</h3>
                            <p class="text-gray-600 mb-6" id="errorMessage">Email atau password salah.</p>
                            <button type="button" onclick="closeErrorModal()" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition">
                                Coba Lagi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Success Modal -->
                <div id="successModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Login Berhasil</h3>
                            <p class="text-gray-600 mb-6">Sedang mengalihkan ke halaman dashboard...</p>
                            <div class="flex justify-center">
                                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-500"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lockout Modal -->
                <div id="lockoutModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
                        <div class="text-center">
                            <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-clock text-orange-500 text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-800 mb-2">Terlalu Banyak Percobaan</h3>
                            <p class="text-gray-600 mb-6">Anda telah melebihi batas percobaan login.</p>
                            <div class="text-6xl font-bold text-orange-500 mb-4 countdown-number" id="countdownTimer">30</div>
                            <p class="text-gray-500 mb-6">detik lagi</p>
                            <button type="button" id="lockoutOkBtn" onclick="closeLockoutModal()" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-6 rounded-xl transition hidden">
                                OK
                            </button>
                        </div>
                    </div>
                </div>

                <input type="text" name="email" id="email" required
                       class="w-full px-6 py-5 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg"
                       placeholder="Email atau Username">

                <div class="relative">
                    <input type="password" name="password" required id="password" maxlength="10"
                           class="w-full px-6 py-5 pr-16 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg"
                           placeholder="Password">
                    <span onclick="togglePass()" class="absolute right-5 top-6 cursor-pointer text-gray-500 hover:text-blue-600 text-xl">
                        <i class="fas fa-eye" id="eye"></i>
                    </span>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-black text-2xl py-6 rounded-2xl shadow-xl transform hover:scale-105 transition">
                    Login
                </button>
            </form>

            <!-- PERINGATAN RESMI (SEPERTI YANG KAMU MAU) -->
            <div class="mt-10 text-center text-gray-600 space-y-4 text-sm lg:text-base">
                <p>
                    <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline font-medium">Lupa Password?</a>
                </p>
                <p class="text-xs lg:text-sm text-gray-500">
                    Belum memiliki akun?
                    <a href="/register" class="font-bold text-blue-600 hover:underline">Daftar Disini!</a>
                </p>

                <div class="mt-8 p-5 bg-amber-50 border border-amber-300 rounded-xl text-amber-800">
                    <p class="font-bold text-sm lg:text-base">PERINGATAN</p>
                    <p class="text-xs lg:text-sm mt-2 leading-relaxed">
                        Sistem ini hanya diperuntukkan bagi <strong>petugas resmi Kementerian ATR/BPN</strong> dan pihak yang telah mendapat izin akses.<br>
                        Penggunaan tanpa izin dapat dikenai sanksi sesuai peraturan perundang-undangan yang berlaku.
                    </p>
                </div>
            </div>

            <!-- Logo ATR/BPN di HP -->
            <div class="mt-10 text-center lg:hidden">
                <img src="https://via.placeholder.com/500x200?text=LOGO+ATR+BPN" alt="ATR/BPN" class="h-20 mx-auto">
            </div>
        </div>
    </div>
</div>
    </div>



<script>
    function togglePass() {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
    }

    function toggleFeatureList() {
        const list = document.getElementById('featureList');
        list.classList.toggle('hidden');
    }

    function openContactModal() {
        $('#contactModal').modal('show');
    }

    // Error Modal Functions
    function showErrorModal(message, focusField = 'email', errorType = 'general') {
        const modal = document.getElementById('errorModal');
        const errorMessage = document.getElementById('errorMessage');
        const errorTitle = document.getElementById('errorTitle');
        
        // Set title based on error type
        if (errorType === 'email') {
            errorTitle.textContent = 'Email Salah';
        } else if (errorType === 'password') {
            errorTitle.textContent = 'Password Salah';
        } else if (errorType === 'email_format') {
            errorTitle.textContent = 'Format Email Salah';
            message = 'Email harus ada @';
        } else if (errorType === 'both') {
            errorTitle.textContent = 'Login Gagal';
            message = 'Email dan password salah';
        } else {
            errorTitle.textContent = 'Login Gagal';
        }
        
        errorMessage.textContent = message || 'Email atau password salah.';
        modal.classList.remove('hidden');
        
        // Store the field to focus after modal closes and error type
        modal.dataset.focusField = focusField;
        modal.dataset.errorType = errorType;
    }

    function closeErrorModal() {
        const modal = document.getElementById('errorModal');
        const focusField = modal.dataset.focusField || 'email';
        const errorType = modal.dataset.errorType || 'general';
        modal.classList.add('hidden');
        
        // Clear fields based on error type
        if (errorType === 'email') {
            // Email wrong - clear email only
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.value = '';
            }
        } else if (errorType === 'password') {
            // Password wrong - clear password only
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.value = '';
            }
        } else if (errorType === 'email_format') {
            // Email format wrong - clear email only
            const emailField = document.getElementById('email');
            if (emailField) {
                emailField.value = '';
            }
        } else {
            // Both wrong or general error - clear both
            const emailField = document.getElementById('email');
            const passwordField = document.getElementById('password');
            if (emailField) emailField.value = '';
            if (passwordField) passwordField.value = '';
        }
        
        // Auto-focus on the specified field
        setTimeout(() => {
            let focusTarget = focusField;
            // Override focus for email_format error
            if (errorType === 'email_format') {
                focusTarget = 'email';
            }
            const field = document.getElementById(focusTarget);
            if (field) {
                field.focus();
                // Add visual indicator that this field was the error
                field.classList.add('border-red-500', 'ring-4', 'ring-red-100');
                setTimeout(() => {
                    field.classList.remove('border-red-500', 'ring-4', 'ring-red-100');
                }, 2000);
            }
        }, 100);
    }

    // Success Modal Functions
    function showSuccessModal() {
        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden');
    }

    // Lockout Modal Functions
    let lockoutInterval = null;
    
    function showLockoutModal(seconds) {
        const modal = document.getElementById('lockoutModal');
        const countdownElement = document.getElementById('countdownTimer');
        const okButton = document.getElementById('lockoutOkBtn');
        modal.classList.remove('hidden');
        
        let remaining = seconds;
        countdownElement.textContent = remaining;
        
        // Disable the submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Terlocked...';
        submitBtn.classList.add('opacity-50');
        
        // Hide OK button initially
        okButton.classList.add('hidden');
        
        // Clear any existing interval
        if (lockoutInterval) {
            clearInterval(lockoutInterval);
        }
        
        // Animate countdown
        lockoutInterval = setInterval(() => {
            remaining--;
            countdownElement.textContent = remaining;
            
            // Add bounce animation
            countdownElement.classList.add('animate-bounce');
            setTimeout(() => countdownElement.classList.remove('animate-bounce'), 200);
            
            if (remaining <= 0) {
                clearInterval(lockoutInterval);
                lockoutInterval = null;
                
                // Show "Coba Lagi" button
                countdownElement.textContent = '✓';
                countdownElement.classList.add('text-green-500');
                okButton.classList.remove('hidden');
            }
        }, 1000);
    }
    
    function closeLockoutModal() {
        const modal = document.getElementById('lockoutModal');
        modal.classList.add('hidden');
        
        // Re-enable submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        submitBtn.disabled = false;
        submitBtn.textContent = 'Login';
        submitBtn.classList.remove('opacity-50');
        
        // Clear fields
        document.getElementById('email').value = '';
        document.getElementById('password').value = '';
        
        // Focus on email
        document.getElementById('email').focus();
    }

    // AJAX Form Submission
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        // Disable button during submission
        submitBtn.disabled = true;
        submitBtn.textContent = 'Memuat...';
        submitBtn.classList.add('opacity-75');

        fetch('/login', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-AJAX-Login': 'true'
            },
            credentials: 'same-origin',
            redirect: 'manual'
        })
        .then(response => {
            // Check response status
            if (response.status === 401 || response.status === 422) {
                // Authentication failed
                return response.json().then(data => {
                    // Check if locked out
                    if (data.error_field === 'locked' && data.lockout_seconds) {
                        showLockoutModal(data.lockout_seconds);
                        return;
                    }
                    
                    // Get the specific error field from response
                    const focusField = data.error_field || 'email';
                    let errorType = data.error_field || 'general';
                    let message = data.message;
                    
                    // Update title based on error
                    const errorTitle = document.getElementById('errorTitle');
                    
                    if (data.error_field === 'both') {
                        errorTitle.textContent = 'Login Gagal';
                        message = 'Email dan password salah';
                        // Clear both fields
                        document.getElementById('email').value = '';
                        document.getElementById('password').value = '';
                    } else if (data.error_field === 'email_format') {
                        errorTitle.textContent = 'Format Email Salah';
                        message = 'Email harus ada @';
                    } else if (data.failed_attempts) {
                        // Show remaining attempts
                        const remaining = 5 - data.failed_attempts;
                        if (remaining > 0 && remaining <= 3) {
                            message = data.message + ' (' + remaining + ' percobaan lagi)';
                        }
                    }
                    
                    showErrorModal(message, focusField, errorType);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Login';
                    submitBtn.classList.remove('opacity-75');
                }).catch(() => {
                    showErrorModal('Email atau password salah.', 'email', 'general');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Login';
                    submitBtn.classList.remove('opacity-75');
                });
            } else if (response.type === 'opaqueredirect') {
                // This is a redirect response - check if login was successful by trying to get user
                // If we got here, it's likely successful
                showSuccessModal();
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            } else if (response.ok) {
                // Success - check if JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json().then(data => {
                        // Check if locked out
                        if (data.error_field === 'locked' && data.lockout_seconds) {
                            showLockoutModal(data.lockout_seconds);
                            return;
                        }
                        
                        if (data.success) {
                            showSuccessModal();
                            setTimeout(() => {
                                window.location.href = data.redirect || '/dashboard';
                            }, 1500);
                        } else {
                            const focusField = data.error_field || 'email';
                            let errorType = data.error_field || 'general';
                            
                            // Update title based on error
                            const errorTitle = document.getElementById('errorTitle');
                            let message = data.message;
                            
                            if (data.error_field === 'both') {
                                errorTitle.textContent = 'Login Gagal';
                                message = 'Email dan password salah';
                                // Clear both fields
                                document.getElementById('email').value = '';
                                document.getElementById('password').value = '';
                            } else if (data.error_field === 'email_format') {
                                errorTitle.textContent = 'Format Email Salah';
                                message = 'Email harus ada @';
                            } else if (data.failed_attempts) {
                                // Show remaining attempts
                                const remaining = 5 - data.failed_attempts;
                                if (remaining > 0 && remaining <= 3) {
                                    message = data.message + ' (' + remaining + ' percobaan lagi)';
                                }
                            }
                            
                            showErrorModal(message, focusField, errorType);
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Login';
                            submitBtn.classList.remove('opacity-75');
                        }
                    });
                } else {
                    // Not JSON but OK - likely redirect happened
                    showSuccessModal();
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1500);
                }
            } else {
                showErrorModal('Terjadi kesalahan. Silakan coba lagi.', 'email', 'general');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Login';
                submitBtn.classList.remove('opacity-75');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorModal('Terjadi kesalahan jaringan. Silakan coba lagi.', 'email', 'general');
            submitBtn.disabled = false;
            submitBtn.textContent = 'Login';
            submitBtn.classList.remove('opacity-75');
        });
    });
</script>

@if(session('kicked_out'))
<script>
    let countdown = 10;
    const countdownElement = document.createElement('div');
    countdownElement.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
    countdownElement.innerHTML = `Anda akan dikembalikan ke halaman lupa password dalam ${countdown} detik...`;
    document.body.appendChild(countdownElement);

    const interval = setInterval(() => {
        countdown--;
        countdownElement.innerHTML = `Anda akan dikembalikan ke halaman lupa password dalam ${countdown} detik...`;
        if (countdown <= 0) {
            clearInterval(interval);
            window.location.href = '/forgot-password';
        }
    }, 1000);
</script>
@endif

<!-- Loading Screen JavaScript -->
<script>
    // Hide loading screen and show main content
    setTimeout(function() {
        var loadingScreen = document.getElementById('loadingScreen');
        var mainContent = document.getElementById('mainContent');
        
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');
        }
        if (mainContent) {
            mainContent.classList.add('visible');
        }
        
        // Remove loading screen from DOM after animation
        setTimeout(function() {
            if (loadingScreen) {
                loadingScreen.style.display = 'none';
            }
        }, 1000);
    }, 5000); // 5 seconds loading time
</script>

</body>
</html>
