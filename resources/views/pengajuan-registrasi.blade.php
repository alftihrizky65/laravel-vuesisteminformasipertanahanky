<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Registrasi - Sistem Informasi Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        
        .card {
            background: white;
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
        
        .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .card-header i {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .card-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .card-header p {
            opacity: 0.9;
        }
        
        .card-body {
            padding: 2rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .status-pending {
            background: #fef3c7;
            color: #92400e;
            border: 2px solid #fcd34d;
        }
        
        .status-approved {
            background: #d1fae5;
            color: #065f46;
            border: 2px solid #34d399;
        }
        
        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
            border: 2px solid #f87171;
        }
        
        .info-box {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #64748b;
        }
        
        .info-value {
            font-weight: 500;
            color: #1e293b;
        }
        
        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
        
        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }
        
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #34d399;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #f87171;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
        }
        
        .btn-primary {
            background: #0d6efd;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0a58ca;
            transform: translateY(-2px);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .text-center {
            text-align: center;
        }
        
        .mt-3 {
            margin-top: 1rem;
        }
        
        .loading {
            text-align: center;
            padding: 2rem;
        }
        
        .loading i {
            font-size: 2rem;
            color: #0d6efd;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-clipboard-check"></i>
            <h1>Pengajuan Registrasi</h1>
            <p>Sistem Informasi Pertanahan Nasional</p>
        </div>
        
        <div class="card-body">
            <div id="statusContent">
                <div class="loading">
                    <i class="fas fa-spinner"></i>
                    <p style="margin-top: 1rem;">Memuat status pengajuan...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get email from sessionStorage (set during registration)
        const userEmail = sessionStorage.getItem('pending_registration_email');
        
        async function loadStatus() {
            const content = document.getElementById('statusContent');
            
            if (!userEmail) {
                content.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Data pengajuan tidak ditemukan. Silakan lakukan registrasi terlebih dahulu.
                    </div>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                `;
                return;
            }
            
            try {
                const response = await fetch('{{ url("/api/registration-status") }}?email=' + encodeURIComponent(userEmail));
                const data = await response.json();
                
                if (data.success && data.request) {
                    const req = data.request;
                    let statusClass = 'status-pending';
                    let statusIcon = 'fa-clock';
                    let statusText = 'Menunggu Persetujuan';
                    
                    if (req.status === 'disetujui') {
                        statusClass = 'status-approved';
                        statusIcon = 'fa-check-circle';
                        statusText = 'Disetujui';
                    } else if (req.status === 'ditolak') {
                        statusClass = 'status-rejected';
                        statusIcon = 'fa-times-circle';
                        statusText = 'Ditolak';
                    }
                    
                    content.innerHTML = `
                        <div class="text-center">
                            <span class="status-badge ${statusClass}">
                                <i class="fas ${statusIcon}"></i> ${statusText}
                            </span>
                        </div>
                        
                        <div class="info-box">
                            <div class="info-row">
                                <span class="info-label">Nama Lengkap</span>
                                <span class="info-value">${req.nama_lengkap}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Email</span>
                                <span class="info-value">${req.email}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">NIK</span>
                                <span class="info-value">${req.nik}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Jenis Registrasi</span>
                                <span class="info-value">${req.jenis_registrasi === 'staff' ? 'Staff/ASN' : 'Umum'}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tanggal Pengajuan</span>
                                <span class="info-value">${new Date(req.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                            </div>
                        </div>
                        
                        ${req.status === 'pending' ? `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> 
                                Permintaan registrasi Anda sedang menunggu persetujuan dari administrator. 
                                Anda akan menerima notifikasi melalui email setelah pengajuan disetujui.
                            </div>
                        ` : ''}
                        
                        ${req.status === 'disetujui' ? `
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                Selamat! Akun Anda telah disetujui. Silakan login dengan email dan password yang telah dikirimkan.
                            </div>
                            <a href="{{ url('/login') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-sign-in-alt"></i> Login Sekarang
                            </a>
                        ` : ''}
                        
                        ${req.status === 'ditolak' ? `
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i> 
                                Maaf, permintaan registrasi Anda ditolak. ${req.catatan_admin ? 'Catatan: ' + req.catatan_admin : ''}
                            </div>
                            <a href="{{ url('/register') }}" class="btn btn-primary btn-block">
                                <i class="fas fa-user-plus"></i> Daftar Kembali
                            </a>
                        ` : ''}
                        
                        <div class="text-center mt-3">
                            <a href="{{ url('/') }}" style="color: #64748b;">
                                <i class="fas fa-home"></i> Kembali ke Halaman Utama
                            </a>
                        </div>
                    `;
                } else {
                    content.innerHTML = `
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Data pengajuan tidak ditemukan.
                        </div>
                        <a href="{{ url('/register') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </a>
                    `;
                }
            } catch (error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Gagal memuat status: ${error.message}
                    </div>
                    <a href="{{ url('/register') }}" class="btn btn-primary btn-block">
                        <i class="fas fa-user-plus"></i> Daftar Sekarang
                    </a>
                `;
            }
        }
        
        loadStatus();
    </script>
</body>
</html>
