<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magic Link Login - GEOTERRAID</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; padding: 20px 0; background-color: #2563eb; color: white; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .button { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: white; text-decoration: none; border-radius: 4px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        .warning { background-color: #fef3c7; border: 1px solid #f59e0b; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>GEOTERRAID</h1>
            <p>Sistem Informasi Pertanahan Terintegrasi</p>
        </div>

        <div class="content">
            <h2>Magic Link untuk Masuk</h2>
            <p>Halo {{ $user->name }},</p>
            <p>Anda menerima email ini karena kami menerima permintaan magic link untuk masuk ke akun Anda.</p>

            <p>Klik tombol di bawah ini untuk langsung masuk ke sistem tanpa perlu password:</p>

            <a href="{{ $magicUrl }}" class="button">Masuk Sekarang</a>

            <p>Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:</p>
            <p>{{ $magicUrl }}</p>

            <p>Link ini akan kedaluwarsa dalam 60 menit.</p>

            <p>Jika Anda tidak meminta magic link ini, abaikan email ini.</p>

            <div class="warning">
                <strong>PERINGATAN:</strong> Sistem ini hanya diperuntukkan bagi petugas resmi Kementerian ATR/BPN dan pihak yang telah mendapat izin akses. Penggunaan tanpa izin dapat dikenai sanksi sesuai peraturan perundang-undangan yang berlaku.
            </div>
        </div>

        <div class="footer">
            <p>&copy; 2024 Kementerian Agraria dan Tata Ruang / Badan Pertanahan Nasional</p>
            <p>Developed by GEOTERRAID Team</p>
        </div>
    </div>
</body>
</html>
