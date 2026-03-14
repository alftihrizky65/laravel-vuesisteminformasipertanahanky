<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GEOTERRAID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body, html { margin:0; padding:0; height:100%; font-family:'Inter',sans-serif; }
        input[type="password"]::-webkit-reveal-button { display: none; }
        input[type="password"]::-ms-reveal { display: none; }
    </style>
</head>
<body class="h-full bg-gray-50">

<div class="h-full flex flex-col lg:flex-row">

    <!-- ================== KIRI: BACKGROUND GEDUNG SUPER LUAS ================== -->
    <div class="relative hidden lg:block flex-[2.4] bg-cover bg-center"
         style="background-image: url('{{ asset('sbadmin/img/mount-agung-volcano-rice-fields-bali-indonesia-crescent-3000x3974-1107.jpg') }}');">

        <div class="absolute inset-0 flex flex-col justify-between p-16 text-white">
            <div class="max-w-5xl">
                <h1 class="text-6xl font-black">Reset Password</h1>
                <h2 class="text-3xl font-light mt-6">Masukkan password baru Anda</h2>
            </div>

            <!-- Logo ATR/BPN -->
            <div class="text-center">
                <img src="{{ asset('sbadmin/img/Logo_BPN-KemenATR_(2017).png') }}" alt="ATR/BPN" class="h-24">
            </div>
        </div>
    </div>

    <!-- ================== KANAN: FORM LEBIH RAMMING ================== -->
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <!-- Back to Login Button -->
        <a href="/login" class="absolute top-4 left-4 bg-white/90 hover:bg-white text-blue-600 hover:text-blue-700 font-semibold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center space-x-2 z-10">
            <i class="fas fa-arrow-left"></i>
            <span>Kembali ke Login</span>
        </a>

        <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-10 lg:p-12">

            <!-- Logo -->
            <div class="flex justify-center mb-10">
                <img src="{{ asset('sbadmin/img/20251208_184230.png') }}" alt="Logo Instansi" class="h-40">
            </div>

            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">Reset Password</h2>
            <p class="text-center text-gray-600 mb-10">Masukkan password baru Anda</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-7">
                @csrf

                @if(session('status'))
                    <div class="bg-green-50 border border-green-300 text-green-700 p-4 rounded-xl text-center font-medium">
                        {{ session('status') }}
                    </div>
                @endif

                @error('email')
                    <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl text-center font-medium">
                        {{ $message }}
                    </div>
                @enderror

                @error('password')
                    <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl text-center font-medium">
                        {{ $message }}
                    </div>
                @enderror

                <!-- Hidden fields -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <input type="hidden" name="email" value="{{ $request->email }}">

                <div class="relative">
                    <input type="password" name="password" required id="password"
                           class="w-full px-6 py-5 pr-16 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg"
                           placeholder="Password Baru">
                    <span onclick="togglePass()" class="absolute right-5 top-6 cursor-pointer text-gray-500 hover:text-blue-600 text-xl">
                        <i class="fas fa-eye" id="eye"></i>
                    </span>
                </div>

                <input type="password" name="password_confirmation" required
                       class="w-full px-6 py-5 border-2 border-gray-300 rounded-2xl focus:border-blue-600 focus:ring-4 focus:ring-blue-100 outline-none text-lg"
                       placeholder="Konfirmasi Password Baru">

                <button type="submit"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-black text-2xl py-6 rounded-2xl shadow-xl transform hover:scale-105 transition">
                    Reset Password
                </button>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-8 text-center">
                <p class="text-gray-600">
                    <a href="/login" class="text-blue-600 hover:underline font-medium">Kembali ke Login</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePass() {
        const p = document.getElementById('password');
        p.type = p.type === 'password' ? 'text' : 'password';
    }
</script>

</body>
</html>
