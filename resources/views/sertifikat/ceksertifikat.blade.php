<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cek Sertifikat - SIP Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            min-height: 100vh;
            padding: 2rem 1rem;
        }
        .container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            max-width: 700px;
            margin: 0 auto;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .header p { opacity: 0.9; }
        .body { padding: 2rem; }
        .form-group { margin-bottom: 1.5rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
            width: 100%;
        }
        .btn-primary:hover { background: #2563eb; }
        .btn-secondary {
            background: #6b7280;
            color: white;
        }
        .result-section {
            display: none;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }
        .result-section.active { display: block; }
        .result-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
        }
        .result-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .status-warning { background: #fef3c7; color: #92400e; }
        .status-info { background: #dbeafe; color: #1e40af; }
        .status-primary { background: #d1d5db; color: #1f2937; }
        .status-success { background: #d1fae5; color: #065f46; }
        .status-danger { background: #fee2e2; color: #991b1b; }
        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 1rem;
        }
        .result-item {
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }
        .result-item label {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .result-item p {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-top: 0.25rem;
        }
        .payment-status {
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        .payment-paid { background: #d1fae5; color: #065f46; }
        .payment-unpaid { background: #fee2e2; color: #991b1b; }
        .shipping-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: white;
            border-radius: 8px;
            margin-top: 1rem;
            border: 1px solid #e2e8f0;
        }
        .expired-warning {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .back-btn:hover { text-decoration: underline; }
        .loading {
            display: none;
            text-align: center;
            padding: 2rem;
        }
        .loading.active { display: block; }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e2e8f0;
            border-top-color: #3b82f6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .error-message {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
            display: none;
        }
        .error-message.active { display: block; }
        
        /* Toast Animation */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
        }
        .toast {
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            margin-bottom: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: slideIn 0.4s ease;
        }
        @keyframes slideIn {
            from { transform: translateX(120%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-success { background: linear-gradient(135deg, #10b981, #059669); }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <div class="container">
        <div class="header">
            <i class="fas fa-certificate" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h1>Cek Status Sertifikat</h1>
            <p>Cek status pengajuan sertifikat tanah Anda</p>
        </div>
        
        <div class="body">
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            
            <form id="searchForm">
                <div class="form-group">
                    <label for="nik">Masukkan NIK Anda</label>
                    <input type="text" id="nik" name="nik" placeholder="Contoh: 3275012345678901" maxlength="16" required>
                </div>
                <button type="submit" class="btn btn-primary" id="searchBtn">
                    <i class="fas fa-search"></i> Cek Status
                </button>
            </form>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Mencari data...</p>
            </div>
            
            <div class="error-message" id="errorMessage"></div>
            
            <div class="result-section" id="resultSection">
                <div class="result-card">
                    <div class="result-header">
                        <h3 id="namaLengkap"></h3>
                        <span class="status-badge" id="statusBadge"></span>
                    </div>
                    
                    <div class="result-grid">
                        <div class="result-item">
                            <label>NIK</label>
                            <p id="nikResult"></p>
                        </div>
                        <div class="result-item">
                            <label>Jenis Sertifikat</label>
                            <p id="jenisSertifikat"></p>
                        </div>
                        <div class="result-item">
                            <label>Alamat</label>
                            <p id="alamat"></p>
                        </div>
                        <div class="result-item">
                            <label>Status Saat Ini</label>
                            <p id="statusText"></p>
                        </div>
                    </div>
                    
                    <div id="paymentStatus"></div>
                    
                    <div id="expiredWarning" class="expired-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="expiredText"></span>
                    </div>
                    
                    <div class="shipping-info" id="shippingInfo" style="display: none;">
                        <i class="fas fa-shipping-fast"></i>
                        <div>
                            <strong>Status Pengiriman:</strong> <span id="shippingStatus"></span>
                            <br><small id="resiInfo"></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // Toast Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = 'toast toast-' + type;
            toast.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'times-circle') + '"></i> ' + message;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
        
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const nik = document.getElementById('nik').value;
            const searchBtn = document.getElementById('searchBtn');
            const loading = document.getElementById('loading');
            const errorMessage = document.getElementById('errorMessage');
            const resultSection = document.getElementById('resultSection');
            
            // Reset
            errorMessage.classList.remove('active');
            resultSection.classList.remove('active');
            loading.classList.add('active');
            searchBtn.disabled = true;
            
            try {
                const response = await fetch('{{ route("sertifikat.search") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ nik })
                });
                
                const result = await response.json();
                loading.classList.remove('active');
                searchBtn.disabled = false;
                
                if (!result.success) {
                    errorMessage.textContent = result.message;
                    errorMessage.classList.add('active');
                    return;
                }
                
                const data = result.data;
                
                // Fill data
                document.getElementById('namaLengkap').textContent = data.nama_lengkap;
                document.getElementById('nikResult').textContent = data.nik;
                document.getElementById('jenisSertifikat').textContent = data.jenis_sertifikat;
                document.getElementById('alamat').textContent = data.alamat_rumah;
                document.getElementById('statusText').textContent = data.status_text;
                
                // Status badge
                const badge = document.getElementById('statusBadge');
                badge.textContent = data.status_text;
                badge.className = 'status-badge status-' + data.status_badge;
                
                // Payment status
                const paymentStatus = document.getElementById('paymentStatus');
                if (data.payment) {
                    const isPaid = data.is_paid;
                    paymentStatus.innerHTML = `
                        <div class="payment-status ${isPaid ? 'payment-paid' : 'payment-unpaid'}">
                            <i class="fas fa-${isPaid ? 'check-circle' : 'clock'}"></i>
                            <strong>Pembayaran: ${data.payment.status_text}</strong>
                            ${data.payment.jumlah_pembayaran ? '<br><small>Rp ' + new Intl.NumberFormat('id-ID').format(data.payment.jumlah_pembayaran) + '</small>' : ''}
                        </div>
                    `;
                } else {
                    paymentStatus.innerHTML = `
                        <div class="payment-status payment-unpaid">
                            <i class="fas fa-clock"></i>
                            <strong>Belum melakukan pembayaran</strong>
                        </div>
                    `;
                }
                
                // Expired warning
                const expiredWarning = document.getElementById('expiredWarning');
                if (data.is_expired) {
                    expiredWarning.style.display = 'flex';
                    document.getElementById('expiredText').textContent = 'Pajak Tanah Anda sudah expired pada ' + new Date(data.tanggal_expired_pajak).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                } else if (data.tanggal_expired_pajak) {
                    expiredWarning.style.display = 'flex';
                    expiredWarning.style.background = '#dbeafe';
                    expiredWarning.style.color = '#1e40af';
                    document.getElementById('expiredText').innerHTML = '<i class="fas fa-info-circle"></i> Pajak Tanah berlaku hasta ' + new Date(data.tanggal_expired_pajak).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                }
                
                // Shipping info
                const shippingInfo = document.getElementById('shippingInfo');
                if (data.status_pengiriman && data.status_pengiriman !== 'belum_dikirim') {
                    shippingInfo.style.display = 'flex';
                    const shippingLabels = {
                        'sedang_dikirim': 'Sedang Dikirim',
                        'terkirim': 'Telah Dikirim'
                    };
                    document.getElementById('shippingStatus').textContent = shippingLabels[data.status_pengiriman] || data.status_pengiriman;
                    document.getElementById('resiInfo').textContent = data.nomor_resi ? 'No. Resi: ' + data.nomor_resi : '';
                }
                
                resultSection.classList.add('active');
                
            } catch (error) {
                loading.classList.remove('active');
                searchBtn.disabled = false;
                errorMessage.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                errorMessage.classList.add('active');
            }
        });
    </script>
</body>
</html>
