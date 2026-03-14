<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Kartu Pendaftaran - {{ $request->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .card-container {
            background: white;
            width: 450px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 50%, #084298 100%);
            color: white;
            padding: 25px 20px;
            text-align: center;
            position: relative;
        }
        
        .card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48ZyBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDBoNDB2NDBIMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iLjEiLz48L2c+PC9zdmc+');
            opacity: 0.1;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            margin: 0 auto 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            overflow: hidden;
        }
        
        .card-header h1 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 3px;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .card-header p {
            font-size: 11px;
            opacity: 0.95;
            position: relative;
        }
        
        .card-body {
            padding: 25px;
        }
        
        .photo-section {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .photo-frame {
            width: 130px;
            height: 160px;
            border: 4px solid #0d6efd;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
            background: #f8f9fa;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .photo-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width:100%;
            height:100%;
            display:flex;
            align-items:center;
            justify-content:center;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            color: #adb5bd;
        }
        
        .photo-placeholder i {
            font-size: 50px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .info-table tr {
            border-bottom: 1px solid #e9ecef;
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table td {
            padding: 12px 10px;
            font-size: 12px;
        }
        
        .info-table td:first-child {
            font-weight: 600;
            color: #495057;
            width: 35%;
            background: #f1f3f5;
        }
        
        .info-table td:last-child {
            color: #212529;
            font-weight: 500;
        }
        
        .card-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 3px solid #0d6efd;
        }
        
        .status {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #212529;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 3px 10px rgba(255,193,7,0.3);
        }
        
        .registration-number {
            margin-top: 12px;
            font-size: 11px;
            color: #6c757d;
        }
        
        .registration-number strong {
            color: #495057;
            font-size: 13px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .card-container {
                box-shadow: none;
                border: 2px solid #0d6efd;
            }
        }
        
        .no-print {
            text-align: center;
            margin-top: 20px;
        }
        
        .btn-back {
            display: inline-block;
            padding: 12px 30px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-back:hover {
            background: #5a6268;
        }
        
        .btn-download {
            display: inline-block;
            padding: 12px 30px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin-left: 10px;
            transition: all 0.3s;
        }
        
        .btn-download:hover {
            background: #0a58ca;
        }
    </style>
</head>
<body>
    <div class="card-container">
        <div class="card-header">
            <div class="logo">
                <img src="{{ asset('sbadmin/img/Logo_BPN-KemenATR_(2017).png') }}" alt="Logo BPN" style="width: 100%; height: 100%; object-fit: contain;">
            </div>
            <h1>Kartu Pendaftaran</h1>
            <p>Sistem Informasi Pertanahan Nasional</p>
            <p>Kementerian Agraria dan Tata Ruang / BPN</p>
        </div>
        
        <div class="card-body">
            <div class="photo-section">
                <div class="photo-frame">
                    @if($request->foto_formal && file_exists(public_path('storage/' . $request->foto_formal)))
                        <img src="{{ asset('storage/' . $request->foto_formal) }}" alt="Foto Formal">
                    @else
                        <div class="photo-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
            </div>
            
            <table class="info-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $request->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>{{ $request->nik }}</td>
                </tr>
                <tr>
                    <td>Jenis Pendaftaran</td>
                    <td>Staff/ASN</td>
                </tr>
                <tr>
                    <td>Instansi</td>
                    <td>{{ $request->instansi }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>{{ $request->jabatan }}</td>
                </tr>
                <tr>
                    <td>Tanggal Daftar</td>
                    <td>{{ \Carbon\Carbon::parse($request->created_at)->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>
        
        <div class="card-footer">
            <span class="status">{{ $request->status === 'disetujui' ? 'DISETUJUI' : ($request->status === 'ditolak' ? 'DITOLAK' : 'MENUNGGU PERSETUJUAN') }}</span>
            <p class="registration-number">No. Registrasi: <strong>{{ str_pad($request->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
        </div>
    </div>
    
    <div class="no-print">
        <a href="http://127.0.0.1:8000/" class="btn-back">
            <i class="fas fa-home"></i> Kembali ke Homepage
        </a>
        <a href="http://127.0.0.1:8000/kartu-pendaftaran/{{ $request->id }}?download=1" class="btn-download" onclick="window.print(); return false;">
            <i class="fas fa-download"></i> Download PDF
        </a>
    </div>
    
    <script>
        // Check if download parameter is set
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('download') === '1') {
            // Auto-trigger print after a short delay
            setTimeout(function() {
                window.print();
            }, 500);
        }
    </script>
</body>
</html>
