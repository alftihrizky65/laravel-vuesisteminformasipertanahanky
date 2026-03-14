<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Berhasil Dibuat - SIPertanahan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.3.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.8);
            --card-border: rgba(16, 185, 129, 0.2);
            --text: #f1f5f9;
            --text-light: #cbd5e1;
            --accent: #10b981;
            --accent-hover: #059669;
        }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--card-border);
            border-radius: 24px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.4);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
            text-decoration: none;
            display: inline-block;
        }
        .btn-secondary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(6, 182, 212, 0.4);
        }
        .brand-title {
            font-family: 'Orbitron', sans-serif;
            background: linear-gradient(135deg, var(--accent), #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 700;
        }
    </style>
</head>
<body class="min-h-screen p-6">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-8">
            <h1 class="brand-title mb-2">Undangan Berhasil Dibuat</h1>
            <p class="text-gray-400">Sistem Informasi Pertanahan Nasional</p>
        </div>

        <div class="glass-card p-8">
            <div class="text-center mb-6">
                <i class="fas fa-check-circle text-6xl text-emerald-400 mb-4"></i>
                <h2 class="text-2xl font-bold mb-2">Pengguna Berhasil Dibuat!</h2>
                <p class="text-gray-400">Undangan telah dibuat dan siap dikirim via email.</p>
            </div>

            <div class="bg-gray-800/50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-emerald-400"></i>
                    Detail Pengguna
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Nama:</span>
                        <span class="font-medium">{{ $user->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Email:</span>
                        <span class="font-medium">{{ $user->email }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Undangan:</span>
                        <span class="font-mono bg-gray-700 px-2 py-1 rounded text-xs">{{ $inviteCode }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">PIN Verifikasi:</span>
                        <span class="font-mono bg-gray-700 px-2 py-1 rounded text-xs">{{ $randomPin }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-900/20 border border-blue-500/30 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fas fa-envelope text-blue-400"></i>
                    Kirim Undangan via Gmail
                </h3>
                <p class="text-sm text-gray-400 mb-4">
                    Klik tombol di bawah ini untuk membuka Gmail dengan email undangan yang sudah diisi otomatis.
                </p>
                <a href="{{ $gmailUrl }}" target="_blank" class="btn-primary w-full text-center">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    Buka Gmail & Kirim Undangan
                </a>
            </div>

            <div class="flex gap-4">
                <a href="/dashboard" class="btn-secondary flex-1 text-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Dashboard
                </a>
                <a href="/users" class="btn-primary flex-1 text-center">
                    <i class="fas fa-users mr-2"></i>
                    Kelola Pengguna
                </a>
            </div>
        </div>
    </div>
</body>
</html>
