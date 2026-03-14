<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran - SIP Pertanahan</title>
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
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 { font-size: 1.8rem; margin-bottom: 0.5rem; }
        .header p { opacity: 0.9; }
        .body { padding: 2rem; }
        .summary-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .summary-item:last-child { border-bottom: none; }
        .summary-item .label { color: #64748b; }
        .summary-item .value { font-weight: 600; color: #1e293b; }
        .summary-total {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            background: #ecfdf5;
            border-radius: 8px;
            margin-top: 1rem;
            font-size: 1.2rem;
        }
        .summary-total .value {
            color: #059669;
            font-weight: 700;
        }
        .section { margin-bottom: 1.5rem; }
        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        .form-group label .required { color: #dc2626; }
        .form-group select,
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .bank-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        .bank-option {
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .bank-option:hover { border-color: #3b82f6; }
        .bank-option.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }
        .bank-option input { display: none; }
        .bank-option i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        .file-upload {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload:hover { border-color: #3b82f6; }
        .file-upload input { display: none; }
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
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
            width: 100%;
        }
        .btn-success {
            background: #10b981;
            color: white;
        }
        .btn-success:hover { background: #059669; }
        .btn-secondary {
            background: #6b7280;
            color: white;
            margin-top: 0.75rem;
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
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
        }
        .alert.active { display: block; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
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
            border-top-color: #10b981;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-credit-card" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h1>Pembayaran Biaya Admin</h1>
            <p>Silakan lakukan pembayaran untuk memproses sertifikat Anda</p>
        </div>
        
        <div class="body">
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <div class="summary-card">
                <div class="summary-item">
                    <span class="label">Nama Pemohon</span>
                    <span class="value">{{ $certificate->nama_lengkap }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">NIK</span>
                    <span class="value">{{ $certificate->nik }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Jenis Sertifikat</span>
                    <span class="value">{{ $certificate->jenis_sertifikat }}</span>
                </div>
                <div class="summary-total">
                    <span>Total Pembayaran</span>
                    <span class="value" id="totalBiaya">Rp 350.000</span>
                </div>
            </div>
            
            <div class="alert alert-success" id="successAlert"></div>
            <div class="alert alert-error" id="errorAlert"></div>
            
            <form id="pembayaranForm">
                <div class="section">
                    <div class="section-title">Metode Pembayaran</div>
                    <div class="bank-options">
                        <label class="bank-option" onclick="selectBank(this, 'Bank BRI')">
                            <input type="radio" name="bank" value="Bank BRI" required>
                            <i class="fas fa-university text-primary"></i>
                            <span>Bank BRI</span>
                        </label>
                        <label class="bank-option" onclick="selectBank(this, 'Bank BCA')">
                            <input type="radio" name="bank" value="Bank BCA" required>
                            <i class="fas fa-university text-primary"></i>
                            <span>Bank BCA</span>
                        </label>
                        <label class="bank-option" onclick="selectBank(this, 'Bank Mandiri')">
                            <input type="radio" name="bank" value="Bank Mandiri" required>
                            <i class="fas fa-university text-primary"></i>
                            <span>Bank Mandiri</span>
                        </label>
                        <label class="bank-option" onclick="selectBank(this, 'Bank BTN')">
                            <input type="radio" name="bank" value="Bank BTN" required>
                            <i class="fas fa-university text-primary"></i>
                            <span>Bank BTN</span>
                        </label>
                        <label class="bank-option" onclick="selectBank(this, 'Bank BNI')">
                            <input type="radio" name="bank" value="Bank BNI" required>
                            <i class="fas fa-university text-primary"></i>
                            <span>Bank BNI</span>
                        </label>
                        <label class="bank-option" onclick="selectBank(this, 'E-Wallet')">
                            <input type="radio" name="bank" value="E-Wallet" required>
                            <i class="fas fa-mobile-alt text-primary"></i>
                            <span>E-Wallet</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Nomor Rekening Pengirim</label>
                    <input type="text" name="nomor_rekening" placeholder="Masukkan nomor rekening Anda" required>
                </div>
                
                <div class="form-group">
                    <label>Nama Pemilik Rekening <span class="required">*</span></label>
                    <input type="text" name="atas_nama" placeholder="Nama sesuai rekening bank" required>
                </div>
                
                <div class="section">
                    <div class="section-title">Bukti Pembayaran</div>
                    <div class="form-group">
                        <label>Upload Bukti Transfer</label>
                        <div class="file-upload" onclick="document.getElementById('bukti_transfer').click()">
                            <input type="file" name="bukti_transfer" id="bukti_transfer" accept="image/*" required>
                            <div class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem;"></i>
                                <span id="buktiLabel">Klik untuk upload bukti transfer</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Screenshot Pembayaran</label>
                        <div class="file-upload" onclick="document.getElementById('screenshot').click()">
                            <input type="file" name="screenshot_pembayaran" id="screenshot" accept="image/*" required>
                            <div class="file-upload-label">
                                <i class="fas fa-camera" style="font-size: 2rem;"></i>
                                <span id="screenshotLabel">Klik untuk upload screenshot</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <input type="hidden" name="metode_pembayaran" id="metodePembayaran" value="transfer_bank">
                
                <button type="submit" class="btn btn-success" id="submitBtn">
                    <i class="fas fa-check"></i> Konfirmasi Pembayaran
                </button>
            </form>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Memproses pembayaran...</p>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        function selectBank(element, bankName) {
            document.querySelectorAll('.bank-option').forEach(opt => opt.classList.remove('selected'));
            element.classList.add('selected');
            element.querySelector('input').checked = true;
            
            if (bankName === 'E-Wallet') {
                document.getElementById('metodePembayaran').value = 'e_wallet';
            } else {
                document.getElementById('metodePembayaran').value = 'transfer_bank';
            }
        }
        
        document.getElementById('bukti_transfer').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('buktiLabel').textContent = this.files[0].name;
            }
        });
        
        document.getElementById('screenshot').addEventListener('change', function() {
            if (this.files.length > 0) {
                document.getElementById('screenshotLabel').textContent = this.files[0].name;
            }
        });
        
        document.getElementById('pembayaranForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const loading = document.getElementById('loading');
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            const form = e.target;
            
            successAlert.classList.remove('active');
            errorAlert.classList.remove('active');
            submitBtn.disabled = true;
            loading.classList.add('active');
            
            try {
                const formData = new FormData(form);
                
                const response = await fetch('{{ route("sertifikat.pembayaran.proses", $certificate->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                
                const result = await response.json();
                loading.classList.remove('active');
                
                if (!result.success) {
                    submitBtn.disabled = false;
                    errorAlert.textContent = result.message;
                    errorAlert.classList.add('active');
                    return;
                }
                
                successAlert.innerHTML = '<i class="fas fa-check-circle"></i> Pembayaran berhasil! Mengarahkan ke struk...';
                successAlert.classList.add('active');
                
                // Redirect to receipt
                setTimeout(function() {
                    window.location.href = '{{ url("/sertifikat/") }}/{{ $certificate->id }}/struk';
                }, 2000);
                
            } catch (error) {
                loading.classList.remove('active');
                submitBtn.disabled = false;
                errorAlert.textContent = 'Terjadi kesalahan. Silakan coba lagi.';
                errorAlert.classList.add('active');
            }
        });
    </script>
</body>
</html>
