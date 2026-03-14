<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - GEOTERRAID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js"></script>
    <style>
        body, html { margin:0; padding:0; height:100%; font-family:'Inter',sans-serif; }
        input[type="email"]::-webkit-outer-spin-button,
        input[type="email"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="h-full bg-gray-50">

<div class="h-full flex flex-col lg:flex-row">

    <!-- ================== KIRI: BACKGROUND GEDUNG SUPER LUAS ================== -->
    <!-- GANTI LINK INI: Foto gedung kantor ATR/BPN atau gedung pertanahan resmi -->
    <div class="relative hidden lg:block flex-[2.4] bg-cover bg-center"
         style="background-image: url('{{ asset('sbadmin/img/mount-agung-volcano-rice-fields-bali-indonesia-crescent-3000x3974-1107.jpg') }}');">

        <div class="absolute inset-0 flex flex-col justify-between p-16 text-white">
<br>
<br>

            <div class="max-w-5xl">
                <h1 class="text-6xl font-black">Lupa Password</h1>
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
        <!-- Back to Login Button -->
        <a href="/login" class="absolute top-4 left-4 bg-white/90 hover:bg-white text-blue-600 hover:text-blue-700 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center space-x-2 z-10">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Login</span>
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

            <form id="forgotPasswordForm" method="POST" action="{{ route('magic.link') }}" class="space-y-7">
                @csrf
                @if(session('status'))
                    <div class="bg-green-50 border border-green-300 text-green-700 p-4 rounded-xl text-center font-medium">
                        {{ session('status') }}
                        @if(session('verification_code'))
                            <div class="mt-4 p-4 bg-blue-50 border border-blue-300 rounded-lg">
                                <p class="text-blue-800 font-semibold">Kode Verifikasi Anda:</p>
                                <p class="text-2xl font-bold text-blue-600">{{ session('verification_code') }}</p>
                                <p class="text-sm text-blue-700 mt-2">Masukkan kode ini untuk mendapatkan Magic Link</p>
                            </div>
                        @endif
                        @if(session('magic_url'))
                            <div class="mt-4">
                                <a href="{{ session('magic_url') }}"
                                   class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 shadow-lg">
                                    🔗 Klik Magic Link untuk Masuk Langsung
                                </a>
                                <p class="text-xs text-gray-600 mt-2">
                                    Link berlaku selama 1 jam
                                </p>
                            </div>
                        @endif
                    </div>
                @endif

                @error('email')
                    <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl text-center font-medium">
                        {{ $message }}
                    </div>
                @enderror

                @if(session('show_verification'))
                    <input type="text" name="verification_code" required
                           class="w-full px-6 py-5 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg text-center text-2xl font-bold"
                           placeholder="Masukkan Kode Verifikasi 6 Digit"
                           maxlength="6">
                @else
                    <input type="email" name="email" required
                           class="w-full px-6 py-5 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg"
                           placeholder="Masukkan Email Anda">
                @endif

                <button type="button"
                        onclick="showConfirmPopup()"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-black text-2xl py-6 rounded-2xl shadow-xl transform hover:scale-105 transition">
                    @if(session('show_verification'))
                        Verifikasi Kode
                    @else
                        Kirim Kode Verifikasi
                    @endif
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    @if(session('show_verification'))
                        Masukkan kode verifikasi 6 digit yang ditampilkan di atas
                    @else
                        Kode verifikasi akan dibuat dan ditampilkan untuk keamanan
                    @endif
                </p>
            </div>

            <!-- PERINGATAN RESMI (SEPERTI YANG KAMU MAU) -->
            <div class="mt-10 text-center text-gray-600 space-y-4 text-sm lg:text-base">
                <p class="text-xs lg:text-sm text-gray-500">
                    Link reset password akan dikirim ke email Anda.
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



<script>
    function toggleFeatureList() {
        const list = document.getElementById('featureList');
        list.classList.toggle('hidden');
    }

    function showConfirmPopup() {
        document.getElementById('confirmPopup').classList.remove('hidden');
    }

    function closeConfirmPopup() {
        document.getElementById('confirmPopup').classList.add('hidden');
    }

    function proceedToDashboard() {
        // Submit the form to direct login
        document.getElementById('forgotPasswordForm').submit();
    }
</script>

<!-- Confirmation Popup -->
<div id="confirmPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-6">
            <div class="text-blue-500 text-5xl mb-4">
                <i class="fas fa-question-circle"></i>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Konfirmasi Verifikasi</h2>
        </div>

        <div class="text-center text-gray-700 mb-8">
            <p>Apakah Anda yakin ingin melanjutkan proses verifikasi?</p>
            <p class="mt-2 text-sm text-gray-500">Anda akan langsung masuk ke dashboard setelah konfirmasi.</p>
        </div>

        <div class="flex space-x-4">
            <button onclick="closeConfirmPopup()"
                    class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                Batal
            </button>
            <button onclick="proceedToDashboard()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl transition duration-200">
                Ya, Lanjutkan
            </button>
        </div>
    </div>
</div>

@include('auth.magic-link-verification-popup')

</body>
</html>
