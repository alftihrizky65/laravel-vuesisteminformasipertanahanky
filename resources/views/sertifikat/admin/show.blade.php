<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Sertifikat - SIP Pertanahan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
        }
        
        /* Animated Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform: translateX(120%);
            opacity: 0;
        }
        .toast.show { transform: translateX(0); opacity: 1; }
        .toast.hide { animation: slideOut 0.3s ease-in forwards; }
        @keyframes slideIn {
            0% { transform: translateX(120%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(120%); opacity: 0; }
        }
        .toast-success { background: linear-gradient(135deg, #10b981, #059669); }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .toast-info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .toast-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .toast i { font-size: 1.25rem; }
        
        /* Custom Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.2s ease;
        }
        .modal-overlay.active { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }
        .modal-icon.info { color: #3b82f6; }
        .modal-icon.success { color: #10b981; }
        .modal-icon.danger { color: #ef4444; }
        .modal-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: #1f2937; }
        .modal-message { color: #6b7280; margin-bottom: 1.5rem; }
        .modal-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            transition: border-color 0.2s;
        }
        .modal-input:focus { outline: none; border-color: #3b82f6; }
        .modal-actions { display: flex; gap: 1rem; justify-content: center; }
        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-cancel { background: #e5e7eb; color: #374151; }
        .btn-cancel:hover { background: #d1d5db; }
        .btn-confirm { background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; }
        .btn-confirm:hover { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
        header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        header h1 { font-size: 1.5rem; }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .back-link:hover { text-decoration: underline; }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header {
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 600;
        }
        .card-header.blue { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .card-header.green { background: linear-gradient(135deg, #10b981, #059669); }
        .card-header.orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .card-header.red { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .card-body { padding: 1.5rem; }
        .info-row {
            display: flex;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label {
            width: 200px;
            font-weight: 600;
            color: #64748b;
        }
        .info-value { flex: 1; color: #1e293b; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary { background: #3b82f6; color: white; }
        .btn-success { background: #10b981; color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-warning { background: #f59e0b; color: white; }
        .btn-group { display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap; }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        @media (max-width: 768px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Custom Modal for Confirmation -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-content">
            <div class="modal-icon" id="confirmIcon"><i class="fas fa-question-circle"></i></div>
            <h3 class="modal-title" id="confirmTitle">Konfirmasi</h3>
            <p class="modal-message" id="confirmMessage">Apakah Anda yakin?</p>
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" id="confirmCancel">Batal</button>
                <button class="btn-modal btn-confirm" id="confirmOk">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>
    
    <!-- Custom Modal for Input -->
    <div class="modal-overlay" id="inputModal">
        <div class="modal-content">
            <div class="modal-icon info"><i class="fas fa-edit"></i></div>
            <h3 class="modal-title" id="inputTitle">Masukkan Data</h3>
            <p class="modal-message" id="inputMessage">Silakan masukkan data:</p>
            <input type="text" class="modal-input" id="modalInput" placeholder="Masukkan nomor resi (opsional)">
            <div class="modal-actions">
                <button class="btn-modal btn-cancel" id="inputCancel">Batal</button>
                <button class="btn-modal btn-confirm" id="inputOk">Simpan</button>
            </div>
        </div>
    </div>
    
    <header>
        <a href="{{ route('sertifikat.admin.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1><i class="fas fa-certificate"></i> Detail Pengajuan Sertifikat</h1>
    </header>
    
    <div class="container">
        <div class="grid-2">
            <!-- Data Pemohon -->
            <div class="card">
                <div class="card-header blue">
                    <i class="fas fa-user"></i> Data Pemohon
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">NIK</span>
                        <span class="info-value">{{ $certificate->nik }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ $certificate->nama_lengkap }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">{{ $certificate->alamat_rumah }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kelurahan</span>
                        <span class="info-value">{{ $certificate->kelurahan ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kecamatan</span>
                        <span class="info-value">{{ $certificate->kecamatan ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. HP</span>
                        <span class="info-value">{{ $certificate->nomor_hp ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $certificate->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Data Sertifikat -->
            <div class="card">
                <div class="card-header blue">
                    <i class="fas fa-file-alt"></i> Data Sertifikat
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label">Jenis Sertifikat</span>
                        <span class="info-value">{{ $certificate->jenis_sertifikat }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nomor Sertifikat</span>
                        <span class="info-value">{{ $certificate->nomor_sertifikat ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Luas Tanah</span>
                        <span class="info-value">{{ $certificate->luas_tanah ? $certificate->luas_tanah . ' m²' : '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="badge badge-{{ $certificate->status_badge }}">
                                {{ $certificate->status_text }}
                            </span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Pajak Expired</span>
                        <span class="info-value">
                            @if($certificate->tanggal_expired_pajak)
                                {{ $certificate->tanggal_expired_pajak->format('d/m/Y') }}
                                @if($certificate->isExpired())
                                    <span class="badge badge-danger">Expired</span>
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pembayaran -->
        @if($certificate->payment)
        <div class="card">
            <div class="card-header green">
                <i class="fas fa-credit-card"></i> Data Pembayaran
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="info-row">
                        <span class="info-label">Nomor Transaksi</span>
                        <span class="info-value">{{ $certificate->payment->nomor_transaksi }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Jumlah</span>
                        <span class="info-value">Rp {{ number_format($certificate->payment->jumlah_pembayaran, 0, ',', '.') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <span class="badge badge-{{ $certificate->payment->status_badge }}">
                                {{ $certificate->payment->status_text }}
                            </span>
                        </span>
                    </div>
                </div>
                @if($certificate->payment->status == 'pending')
                <div class="btn-group">
                    <button class="btn btn-success" onclick="verifyPayment({{ $certificate->payment->id }}, 'verified')">
                        <i class="fas fa-check"></i> Verifikasi Pembayaran
                    </button>
                    <button class="btn btn-danger" onclick="verifyPayment({{ $certificate->payment->id }}, 'rejected')">
                        <i class="fas fa-times"></i> Tolak Pembayaran
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Pengiriman -->
        <div class="card">
            <div class="card-header orange">
                <i class="fas fa-shipping-fast"></i> Pengiriman
            </div>
            <div class="card-body">
                <div class="grid-2">
                    <div class="info-row">
                        <span class="info-label">Status Pengiriman</span>
                        <span class="info-value">
                            @if($certificate->status_pengiriman == 'terkirim')
                                <span class="badge badge-success">Telah Dikirim</span>
                            @elseif($certificate->status_pengiriman == 'sedang_dikirim')
                                <span class="badge badge-info">Sedang Dikirim</span>
                            @else
                                <span class="badge badge-secondary">Belum Dikirim</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nomor Resi</span>
                        <span class="info-value">{{ $certificate->nomor_resi ?? '-' }}</span>
                    </div>
                </div>
                <div class="btn-group">
                    <button class="btn btn-warning" onclick="updateShipping({{ $certificate->id }}, 'sedang_dikirim')">
                        <i class="fas fa-truck"></i> Kirim Sekarang
                    </button>
                    <button class="btn btn-success" onclick="updateShipping({{ $certificate->id }}, 'terkirim')">
                        <i class="fas fa-check"></i> Tandai Terkirim
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Update Status -->
        <div class="card">
            <div class="card-header red">
                <i class="fas fa-edit"></i> Update Status
            </div>
            <div class="card-body">
                <form id="statusForm">
                    <div class="grid-2">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Status Baru</label>
                            <select name="status" required style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px;">
                                <option value="menunggu_pembayaran" {{ $certificate->status == 'menunggu_pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                <option value="menunggu_verifikasi_pembayaran" {{ $certificate->status == 'menunggu_verifikasi_pembayaran' ? 'selected' : '' }}>Menunggu Verifikasi Pembayaran</option>
                                <option value="diproses" {{ $certificate->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $certificate->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $certificate->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nomor Resi</label>
                            <input type="text" name="nomor_resi" value="{{ $certificate->nomor_resi }}" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px;">
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Catatan</label>
                        <textarea name="catatan" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #e2e8f0; border-radius: 6px;">{{ $certificate->catatan }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-times-circle',
                info: 'fas fa-info-circle',
                warning: 'fas fa-exclamation-triangle'
            };
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="${icons[type]}"></i><span>${message}</span>`;
            container.appendChild(toast);
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        document.getElementById('statusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                status: formData.get('status'),
                catatan: formData.get('catatan'),
                nomor_resi: formData.get('nomor_resi')
            };
            
            try {
                const response = await fetch('/admin/sertifikat/{{ $certificate->id }}/status', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        });
        
        // Custom Confirmation Modal
        function showConfirmModal(message) {
            return new Promise((resolve) => {
                const modal = document.getElementById('confirmModal');
                document.getElementById('confirmMessage').textContent = message;
                modal.classList.add('active');
                
                const confirmBtn = document.getElementById('confirmOk');
                const cancelBtn = document.getElementById('confirmCancel');
                
                const handleConfirm = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(true);
                };
                
                const handleCancel = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(false);
                };
                
                const cleanup = () => {
                    confirmBtn.removeEventListener('click', handleConfirm);
                    cancelBtn.removeEventListener('click', handleCancel);
                };
                
                confirmBtn.addEventListener('click', handleConfirm);
                cancelBtn.addEventListener('click', handleCancel);
            });
        }
        
        // Custom Input Modal
        function showInputModal(message, defaultValue = '') {
            return new Promise((resolve) => {
                const modal = document.getElementById('inputModal');
                document.getElementById('inputMessage').textContent = message;
                const input = document.getElementById('modalInput');
                input.value = defaultValue;
                input.placeholder = 'Masukkan nomor resi (opsional)';
                modal.classList.add('active');
                input.focus();
                
                const confirmBtn = document.getElementById('inputOk');
                const cancelBtn = document.getElementById('inputCancel');
                
                const handleConfirm = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(input.value);
                };
                
                const handleCancel = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(null);
                };
                
                const cleanup = () => {
                    confirmBtn.removeEventListener('click', handleConfirm);
                    cancelBtn.removeEventListener('click', handleCancel);
                };
                
                confirmBtn.addEventListener('click', handleConfirm);
                cancelBtn.addEventListener('click', handleCancel);
                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') handleConfirm();
                });
            });
        }
        
        async function verifyPayment(paymentId, status) {
            const confirmed = await showConfirmModal('Apakah Anda yakin ingin ' + (status === 'verified' ? 'memverifikasi' : 'menolak') + ' pembayaran ini?');
            if (!confirmed) return;
            
            try {
                const response = await fetch('/admin/sertifikat/payment/' + paymentId + '/verify', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        }
        
        async function updateShipping(certId, status) {
            const resi = await showInputModal('Masukkan nomor resi (opsional):');
            if (resi === null) return;
            
            try {
                const response = await fetch('/admin/sertifikat/' + certId + '/pengiriman', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        status_pengiriman: status,
                        nomor_resi: resi || ''
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        }
        
        async function updateShipping(certId, status) {
            const resi = await showInputModal('Masukkan nomor resi (opsional):');
            if (resi === null) return;
            
            try {
                const response = await fetch('/admin/sertifikat/' + certId + '/pengiriman', {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ 
                        status_pengiriman: status,
                        nomor_resi: resi || ''
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        }
    </script>
</body>
</html>
