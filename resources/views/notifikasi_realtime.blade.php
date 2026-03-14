<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kritik & Saran - Sistem Informasi Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #f5f7fa;
            --card: #ffffff;
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --primary-light: #e7f1ff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem 1rem;
        }

        .container {
            background: var(--card);
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
            position: relative;
        }

        .header i {
            font-size: 3rem;
            margin-bottom: 0.5rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .body {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
        }

        .radio-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .radio-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            flex: 1;
            min-width: 140px;
            justify-content: center;
        }

        .radio-option:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .radio-option.selected {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .radio-option input {
            display: none;
        }

        .radio-option i {
            font-size: 1.1rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13,110,253,0.3);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: #f8fafc;
            border-top: 1px solid var(--border);
        }

        .footer p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 0.75rem;
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        }

        .toast.success { background: var(--success); }
        .toast.error { background: var(--danger); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 480px) {
            .header h1 { font-size: 1.5rem; }
            .body { padding: 1.5rem; }
            .radio-group { flex-direction: column; }
            .radio-option { min-width: 100%; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <i class="fas fa-comments"></i>
        <h1>Kritik & Saran</h1>
        <p>Sampaikan pendapat Anda untuk perbaikan layanan kami</p>
    </div>

    <div class="body">
        <div id="alertMessage"></div>

        <form id="kritikSaranForm">
            <div class="form-group">
                <label>Nama Lengkap <span class="required">*</span></label>
                <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control" placeholder="email@anda.com" required>
            </div>

            <div class="form-group">
                <label>No. HP (Opsional)</label>
                <input type="tel" name="no_hp" class="form-control" placeholder="0812xxxxxxx">
            </div>

            <div class="form-group">
                <label>Jenis Pesan <span class="required">*</span></label>
                <div class="radio-group">
                    <label class="radio-option selected" data-value="kritik">
                        <input type="radio" name="jenis" value="kritik" checked>
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Kritik</span>
                    </label>
                    <label class="radio-option" data-value="saran">
                        <input type="radio" name="jenis" value="saran">
                        <i class="fas fa-lightbulb"></i>
                        <span>Saran</span>
                    </label>
                    <label class="radio-option" data-value="pengaduan">
                        <input type="radio" name="jenis" value="pengaduan">
                        <i class="fas fa-bullhorn"></i>
                        <span>Pengaduan</span>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label>Subjek <span class="required">*</span></label>
                <input type="text" name="subjek" class="form-control" placeholder="Ringkasan topik yang ingin Anda sampaikan" required>
            </div>

            <div class="form-group">
                <label>Isi Pesan <span class="required">*</span></label>
                <textarea name="isi" class="form-control" placeholder="Tuliskan kritik, saran, atau pengaduan Anda secara detail..." required></textarea>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-paper-plane"></i>
                Kirim Pesan
            </button>
        </form>
    </div>

    <div class="footer">
        <p>Terima kasih atas partisipasi Anda</p>
        <a href="{{ url('/dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke Halaman Utama</a>
    </div>
</div>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

// Radio button handling
document.querySelectorAll('.radio-option').forEach(option => {
    option.addEventListener('click', function() {
        document.querySelectorAll('.radio-option').forEach(o => o.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input').checked = true;
    });
});

// Form submission
document.getElementById('kritikSaranForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

    try {
        const response = await fetch('{{ url("/kritik-saran") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            this.reset();
            document.querySelector('.radio-option[data-value="kritik"]').click();
        } else {
            showToast(result.message || 'Terjadi kesalahan', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan jaringan!', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Kirim Pesan';
    }
});

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 4000);
}
</script>

</body>
</html>
