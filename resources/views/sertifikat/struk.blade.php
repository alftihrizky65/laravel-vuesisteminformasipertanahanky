<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - SIP Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #059669, #10b981);
            min-height: 100vh;
            padding: 2rem 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .struk-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .success-header {
            background: linear-gradient(135deg, #059669, #10b981);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pop 0.5s ease 0.3s both;
        }
        @keyframes pop {
            0% { transform: scale(0); }
            70% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .success-icon i {
            font-size: 2.5rem;
            color: #059669;
        }
        .success-header h1 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .struk-body {
            padding: 2rem;
        }
        .struk-title {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px dashed #e2e8f0;
        }
        .transaksi-info {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        .transaksi-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 0.9rem;
        }
        .transaksi-row .label { color: #64748b; }
        .transaksi-row .value { font-weight: 600; color: #1e293b; }
        .transaksi-row.total {
            border-top: 2px solid #e2e8f0;
            margin-top: 0.5rem;
            padding-top: 1rem;
            font-size: 1.1rem;
        }
        .transaksi-row.total .value {
            color: #059669;
            font-size: 1.3rem;
        }
        .pembayaran-section {
            margin: 1.5rem 0;
        }
        .pembayaran-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .pembayaran-detail {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 1rem;
        }
        .pembayaran-row {
            display: flex;
            justify-content: space-between;
            padding: 0.25rem 0;
            font-size: 0.9rem;
        }
        .screenshot-section {
            margin: 1.5rem 0;
        }
        .screenshot-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }
        .screenshot-box {
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
        }
        .screenshot-box img {
            max-width: 100%;
            border-radius: 4px;
            margin-top: 0.5rem;
        }
        .catatan {
            background: #fef3c7;
            border-radius: 8px;
            padding: 1rem;
            margin: 1.5rem 0;
            font-size: 0.9rem;
            color: #92400e;
        }
        .catatan i { margin-right: 0.5rem; }
        .btn-group {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }
        .btn {
            flex: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border: none;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #3b82f6;
            color: white;
        }
        .btn-primary:hover { background: #2563eb; }
        .btn-outline {
            background: white;
            color: #3b82f6;
            border: 2px solid #3b82f6;
        }
        .btn-outline:hover { background: #eff6ff; }
        .print-btn {
            display: block;
            width: 100%;
            margin-top: 1rem;
            background: #6b7280;
            color: white;
            text-align: center;
            padding: 0.75rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .print-btn:hover { background: #4b5563; }
    </style>
</head>
<body>
    <div class="struk-container">
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check"></i>
            </div>
            <h1>Pembayaran Berhasil!</h1>
            <p>Terima kasih, pembayaran Anda telah kami terima</p>
        </div>
        
        <div class="struk-body">
            <div class="struk-title">
                <i class="fas fa-receipt"></i> STRUK PEMBAYARAN
            </div>
            
            <div class="transaksi-info">
                <div class="transaksi-row">
                    <span class="label">Nomor Transaksi</span>
                    <span class="value">{{ $payment->nomor_transaksi }}</span>
                </div>
                <div class="transaksi-row">
                    <span class="label">Tanggal Pembayaran</span>
                    <span class="value">{{ $payment->tanggal_pembayaran->format('d/m/Y H:i') }}</span>
                </div>
                <div class="transaksi-row">
                    <span class="label">Status</span>
                    <span class="value" style="color: #059669;">
                        <i class="fas fa-check-circle"></i> Menunggu Verifikasi
                    </span>
                </div>
            </div>
            
            <div class="pembayaran-section">
                <div class="pembayaran-title">Data Pemohon</div>
                <div class="pembayaran-detail">
                    <div class="pembayaran-row">
                        <span>Nama</span>
                        <span>{{ $certificate->nama_lengkap }}</span>
                    </div>
                    <div class="pembayaran-row">
                        <span>NIK</span>
                        <span>{{ $certificate->nik }}</span>
                    </div>
                    <div class="pembayaran-row">
                        <span>Jenis Sertifikat</span>
                        <span>{{ $certificate->jenis_sertifikat }}</span>
                    </div>
                    <div class="pembayaran-row">
                        <span>Alamat</span>
                        <span>{{ $certificate->alamat_rumah }}</span>
                    </div>
                </div>
            </div>
            
            <div class="transaksi-row total">
                <span class="label">Total Pembayaran</span>
                <span class="value">Rp {{ number_format($payment->jumlah_pembayaran, 0, ',', '.') }}</span>
            </div>
            
            <div class="screenshot-section">
                <div class="screenshot-title"><i class="fas fa-camera"></i> Screenshot Detail Pembayaran</div>
                <div class="screenshot-box">
                    @if($payment->screenshot_pembayaran)
                        <img src="{{ asset('storage/' . $payment->screenshot_pembayaran) }}" alt="Screenshot Pembayaran">
                    @else
                        <p style="color: #64748b;">Screenshot tidak tersedia</p>
                    @endif
                </div>
            </div>
            
            <div class="catatan">
                <i class="fas fa-info-circle"></i>
                <strong>Catatan:</strong> Pembayaran Anda akan diverifikasi oleh admin dalam 1x24 jam. 
                Anda dapat melihat status terbaru di menu Cek Sertifikat dengan memasukkan NIK Anda.
            </div>
            
            <div class="btn-group">
                <a href="{{ url('/cek-sertifikat') }}" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cek Status
                </a>
                <a href="{{ url('/dashboard') }}" class="btn btn-outline">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
            
            <button onclick="window.print()" class="print-btn">
                <i class="fas fa-print"></i> Cetak Struk
            </button>
        </div>
    </div>
</body>
</html>
