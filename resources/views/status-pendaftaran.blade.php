<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Status Pendaftaran - Sistem Informasi Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        .card-body {
            padding: 2rem;
        }
        
        /* Search Form Styles */
        .search-section {
            margin-bottom: 1.5rem;
        }
        
        .search-form {
            display: flex;
            gap: 0.5rem;
        }
        
        .search-form input {
            flex: 1;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: border-color 0.3s;
        }
        
        .search-form input:focus {
            outline: none;
            border-color: #0d6efd;
        }
        
        .search-form button {
            padding: 0.875rem 1.5rem;
            background: #0d6efd;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .search-form button:hover {
            background: #0a58ca;
        }
        
        .search-form button:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }
        
        .search-hint {
            font-size: 0.875rem;
            color: #64748b;
            margin-top: 0.75rem;
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .status-pending {
            background: linear-gradient(135deg, #fef3c7 0%, #fcd34d 100%);
            color: #92400e;
            box-shadow: 0 4px 15px rgba(252, 211, 77, 0.4);
        }
        
        .status-approved {
            background: linear-gradient(135deg, #d1fae5 0%, #34d399 100%);
            color: #065f46;
            box-shadow: 0 4px 15px rgba(52, 211, 153, 0.4);
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #fee2e2 0%, #f87171 100%);
            color: #991b1b;
            box-shadow: 0 4px 15px rgba(248, 113, 113, 0.4);
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
        
        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn-group .btn {
            flex: 1;
            min-width: 150px;
        }
        
        .text-center {
            text-align: center;
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
        
        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fcd34d;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            z-index: 9999;
            transform: translateX(400px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            max-width: 400px;
        }
        
        .toast-notification.show {
            transform: translateX(0);
            opacity: 1;
        }
        
        .toast-notification.error {
            border-left: 4px solid #ef4444;
        }
        
        .toast-notification.error .toast-icon {
            color: #ef4444;
            font-size: 1.5rem;
        }
        
        .toast-notification .toast-content {
            flex: 1;
        }
        
        .toast-notification .toast-title {
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }
        
        .toast-notification .toast-message {
            font-size: 0.875rem;
            color: #64748b;
        }
        
        .toast-notification .toast-close {
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1.25rem;
            padding: 0;
            line-height: 1;
        }
        
        .toast-notification .toast-close:hover {
            color: #64748b;
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
        
        .footer-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #e2e8f0;
        }
        
        .footer-links a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .footer-links a:hover {
            color: #0d6efd;
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <i class="fas fa-clipboard-check"></i>
            <h1>Cek Status Pendaftaran</h1>
            <p>Sistem Informasi Pertanahan Nasional</p>
        </div>
        
        <div class="card-body">
            <!-- Search Section -->
            <div class="search-section" id="searchSection">
                <form class="search-form" onsubmit="searchRegistration(event)">
                    <input type="text" id="searchInput" placeholder="Masukkan NIK (Nomor Induk Kependudukan)" required>
                    <button type="submit" id="searchBtn">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </form>
                <p class="search-hint">
                    Masukkan NIK yang Anda gunakan saat mendaftar
                </p>
            </div>
            
            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="loading hidden">
                <i class="fas fa-spinner"></i>
                <p style="margin-top: 1rem;">Mencari data...</p>
            </div>
            
            <!-- Error Message -->
            <div id="errorMessage" class="hidden"></div>
            
            <!-- Status Content -->
            <div id="statusContent" class="hidden">
                <!-- Content will be populated by JavaScript -->
            </div>
            
            <div class="footer-links">
                <a href="http://127.0.0.1:8000/">
                    <i class="fas fa-home"></i> Kembali ke Homepage
                </a>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastNotification" class="toast-notification">
        <i class="fas fa-exclamation-circle toast-icon"></i>
        <div class="toast-content">
            <div class="toast-title">Pemberitahuan</div>
            <div class="toast-message" id="toastMessage"></div>
        </div>
        <button class="toast-close" onclick="closeToast()">&times;</button>
    </div>

    <script>
        // Toast Notification Functions
        function showToast(message) {
            const toast = document.getElementById('toastNotification');
            const toastMessage = document.getElementById('toastMessage');
            toastMessage.textContent = message;
            toast.classList.add('show');
            
            // Auto close after 4 seconds
            setTimeout(() => {
                closeToast();
            }, 4000);
        }
        
        function closeToast() {
            const toast = document.getElementById('toastNotification');
            toast.classList.remove('show');
        }
        
        // Copy to clipboard function
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showToast('Password berhasil disalin ke clipboard!');
            }).catch(err => {
                showToast('Gagal menyalin password: ' + err.message);
            });
        }
        
        // Check if there's an ID in the URL
        const pathParts = window.location.pathname.split('/');
        const urlId = pathParts[pathParts.length - 1];
        
        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            // If ID is in URL, auto-search
            if (urlId && !isNaN(urlId) && urlId > 0) {
                document.getElementById('searchInput').value = urlId;
                searchRegistrationById(urlId);
            }
        });
        
        async function searchRegistration(event) {
            event.preventDefault();
            
            const nomor = document.getElementById('searchInput').value.trim();
            if (!nomor) return;
            
            await searchRegistrationByNomor(nomor);
        }
        
        async function searchRegistrationByNomor(nomor) {
            const searchBtn = document.getElementById('searchBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const errorMessage = document.getElementById('errorMessage');
            const statusContent = document.getElementById('statusContent');
            const searchSection = document.getElementById('searchSection');
            
            // Show loading, hide others
            searchBtn.disabled = true;
            loadingIndicator.classList.remove('hidden');
            errorMessage.classList.add('hidden');
            statusContent.classList.add('hidden');
            
            try {
                const response = await fetch('{{ url("/api/registration-search") }}?nomor=' + encodeURIComponent(nomor));
                const data = await response.json();
                
                loadingIndicator.classList.add('hidden');
                
                if (data.success && data.request) {
                    // Update URL without reload
                    window.history.pushState({}, '', '{{ url("/status-pendaftaran") }}/' + data.request.id);
                    
                    // Show status
                    displayStatus(data.request);
                    statusContent.classList.remove('hidden');
                } else {
                    errorMessage.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            ${data.message || 'Data tidak ditemukan'}
                        </div>
                    `;
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                errorMessage.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Gagal mencari: ${error.message}
                    </div>
                `;
                errorMessage.classList.remove('hidden');
            } finally {
                searchBtn.disabled = false;
            }
        }
        
        async function searchRegistrationById(id) {
            const searchBtn = document.getElementById('searchBtn');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const errorMessage = document.getElementById('errorMessage');
            const statusContent = document.getElementById('statusContent');
            const searchSection = document.getElementById('searchSection');
            
            // Show loading, hide others
            searchBtn.disabled = true;
            loadingIndicator.classList.remove('hidden');
            errorMessage.classList.add('hidden');
            statusContent.classList.add('hidden');
            
            try {
                const response = await fetch('{{ url("/api/registration-status") }}/' + id);
                const data = await response.json();
                
                loadingIndicator.classList.add('hidden');
                
                if (data.success && data.request) {
                    displayStatus(data.request);
                    statusContent.classList.remove('hidden');
                } else {
                    errorMessage.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            ${data.message || 'Data tidak ditemukan'}
                        </div>
                    `;
                    errorMessage.classList.remove('hidden');
                }
            } catch (error) {
                loadingIndicator.classList.add('hidden');
                errorMessage.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i> 
                        Gagal mencari: ${error.message}
                    </div>
                `;
                errorMessage.classList.remove('hidden');
            } finally {
                searchBtn.disabled = false;
            }
        }
        
        function displayStatus(req) {
            const statusContent = document.getElementById('statusContent');
            
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
            
            let alertHtml = '';
            if (req.status === 'pending') {
                alertHtml = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Permintaan registrasi Anda sedang menunggu persetujuan dari administrator. 
                        Silakan menunggu atau cek berkala halaman ini.
                    </div>
                `;
            } else if (req.status === 'disetujui') {
                alertHtml = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> 
                        Selamat! Akun Anda telah disetujui. Silakan cek email untuk informasi login.
                    </div>
                `;
            } else if (req.status === 'ditolak') {
                alertHtml = `
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle"></i> 
                        Maaf, permintaan registrasi Anda ditolak. ${req.catatan_admin ? '<br><strong>Alasan:</strong> ' + req.catatan_admin : ''}
                    </div>
                `;
            }
            
            statusContent.innerHTML = `
                <div class="text-center">
                    <span class="status-badge ${statusClass}">
                        <i class="fas ${statusIcon}"></i> ${statusText}
                    </span>
                </div>
                
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">No. Registrasi</span>
                        <span class="info-value">${String(req.id).padStart(6, '0')}</span>
                    </div>
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
                        <span class="info-label">Instansi</span>
                        <span class="info-value">${req.instansi || '-'}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Pengajuan</span>
                        <span class="info-value">${new Date(req.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                    </div>
                </div>
                
                ${alertHtml}
                
                ${req.status === 'disetujui' && req.temp_password ? 
                    `<div class="info-box" style="background: #d1fae5; border: 2px solid #34d399;">
                        <div class="info-row">
                            <span class="info-label"><i class="fas fa-key"></i> Password Login Anda</span>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span class="info-value" style="font-family: monospace; font-size: 1.1rem;">${req.temp_password}</span>
                                <button onclick="copyToClipboard('${req.temp_password}')" style="background: #065f46; color: white; border: none; border-radius: 4px; padding: 0.25rem 0.5rem; cursor: pointer; font-size: 0.875rem;">
                                    <i class="fas fa-copy"></i> Salin
                                </button>
                            </div>
                        </div>
                        <p style="font-size: 0.75rem; color: #065f46; margin-top: 0.5rem;">
                            <i class="fas fa-info-circle"></i> Simpan password ini dengan aman. Anda akan membutuhkan email dan password ini untuk login.
                        </p>
                    </div>` : ''
                }
                
                <div class="btn-group">
                    ${req.status === 'disetujui' ? 
                        `<a href="http://127.0.0.1:8000/kartu-pendaftaran/${req.id}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-id-card"></i> Lihat/Download Kartu
                        </a>` :
                        `<button class="btn btn-primary" onclick="showToast('Anda tidak dapat mengunduh kartu sebelum pendaftaran disetujui. Silakan tunggu persetujuan dari administrator.')">
                            <i class="fas fa-id-card"></i> Lihat/Download Kartu
                        </button>`
                    }
                    <a href="http://127.0.0.1:8000/" class="btn" style="background: #6c757d; color: white;">
                        <i class="fas fa-home"></i> Homepage
                    </a>
                </div>
            `;
        }
    </script>
</body>
</html>
