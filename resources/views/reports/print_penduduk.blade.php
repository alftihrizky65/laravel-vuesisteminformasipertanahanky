<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Laporan Penduduk - Dashboard</title>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background: linear-gradient(135deg, #f0f8ff 0%, #e3f2fd 100%);
      color: #333; 
      min-height: 100vh;
    }
    .container { 
      max-width: 1200px; 
      margin: 40px auto; 
      background: white; 
      border-radius: 20px;
      box-shadow: 0 15px 50px rgba(0,123,255,0.2);
      overflow: hidden;
    }
    .header {
      background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
      color: white;
      padding: 40px;
      text-align: center;
      position: relative;
    }
    .header h1 { 
      font-size: 36px; 
      margin-bottom: 10px; 
      font-weight: 600;
    }
    .header .meta { 
      font-size: 18px; 
      opacity: 0.9;
      display: flex; 
      justify-content: center; 
      gap: 30px; 
      flex-wrap: wrap;
      margin-top: 10px;
    }
    .back-btn {
      position: absolute;
      top: 30px;
      left: 40px;
      background: rgba(255,255,255,0.2);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 500;
    }
    .back-btn:hover {
      background: rgba(255,255,255,0.3);
      transform: translateX(-8px);
    }
    .content { 
      padding: 50px 60px; 
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 50px;
      align-items: start;
    }

    /* Left: Action Cards */
    .actions {
      display: flex;
      flex-direction: column;
      gap: 30px;
    }
    .card {
      background: linear-gradient(145deg, #ffffff 0%, #f8fbff 100%);
      border-radius: 18px;
      padding: 35px;
      box-shadow: 0 10px 30px rgba(0,123,255,0.12);
      border: 1px solid rgba(0,123,255,0.15);
      transition: all 0.4s ease;
      text-decoration: none;
      color: inherit;
      text-align: center;
      min-height: 250px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    .card::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 5px;
      background: linear-gradient(90deg, #007bff, #00c6ff);
      border-radius: 18px 18px 0 0;
      inset: 0 auto;
    }
    .card:hover {
      transform: translateY(-12px);
      box-shadow: 0 25px 50px rgba(0,123,255,0.25);
      border-color: #007bff;
    }
    .card-icon {
      font-size: 60px;
      margin-bottom: 20px;
      background: linear-gradient(135deg, #007bff, #00c6ff);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .card h2 { 
      font-size: 26px; 
      margin-bottom: 12px; 
      color: #1a1a1a;
      font-weight: 600;
    }
    .card p { 
      color: #555; 
      font-size: 15px;
      line-height: 1.6;
    }

    /* Right: Stats + Table */
    .main-data {
      display: flex;
      flex-direction: column;
      gap: 40px;
    }
    .print-btn {
      align-self: flex-start;
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
      border: none;
      padding: 14px 30px;
      border-radius: 12px;
      font-size: 17px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 6px 20px rgba(0,123,255,0.3);
    }
    .print-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 30px rgba(0,123,255,0.4);
    }
    .stats {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }
    .stat-item {
      background: linear-gradient(135deg, rgba(0,123,255,0.08), rgba(0,198,255,0.08));
      padding: 25px;
      border-radius: 16px;
      text-align: center;
      border: 1px solid rgba(0,123,255,0.1);
    }
    .stat-number {
      font-size: 36px;
      font-weight: 700;
      color: #007bff;
      margin-bottom: 8px;
    }
    .stat-label {
      color: #666;
      font-size: 15px;
      font-weight: 500;
    }

    /* Table */
    .data-section {
      background: #f8f9ff;
      border-radius: 18px;
      padding: 30px;
      border: 1px solid rgba(0,123,255,0.1);
      overflow-x: auto;
    }
    .data-section h3 {
      color: #007bff;
      font-size: 26px;
      margin-bottom: 25px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    table { 
      width: 100%; 
      min-width: 700px;
      border-collapse: collapse; 
      font-size: 15px; 
      background: white;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    }
    th, td { 
      padding: 16px 20px; 
      text-align: left; 
      border-bottom: 1px solid #e8f0fe;
    }
    th { 
      background: linear-gradient(135deg, #007bff, #0056b3); 
      color: white; 
      font-weight: 600;
      font-size: 14px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
    }
    tr:hover { background: #f0f8ff; }
    tr:last-child td { border-bottom: none; }

    /* Print Styles */
    @page { size: A4; margin: 12mm; }
    @media print { 
      body { background: white !important; padding: 0 !important; }
      .container { box-shadow: none !important; border-radius: 0; margin: 0; max-width: none; }
      .header { padding: 20px; }
      .content { display: block; padding: 20px; gap: 0; }
      .actions, .print-btn, .stats, .back-btn { display: none !important; }
      .main-data { gap: 20px; }
      table { font-size: 12px !important; }
      th, td { padding: 10px !important; }
    }

    /* Responsive Mobile */
    @media (max-width: 992px) {
      .container { margin: 20px; border-radius: 16px; }
      .header { padding: 30px 20px; }
      .header h1 { font-size: 30px; }
      .back-btn { position: static; margin-bottom: 20px; justify-self: start; }
      .content { 
        grid-template-columns: 1fr; 
        padding: 30px 40px; 
        gap: 40px; 
      }
      .stats { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 576px) {
      .header .meta { flex-direction: column; gap: 10px; font-size: 16px; }
      .content { padding: 20px; }
      .card { padding: 25px; min-height: 200px; }
      .card-icon { font-size: 50px; }
      .card h2 { font-size: 22px; }
      .stats { grid-template-columns: 1fr; }
      .data-section { padding: 20px; }
      table { font-size: 13px; }
      th, td { padding: 12px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <a href="/dashboard" class="back-btn">⬅ Kembali ke Dashboard</a>
      <h1>📊 Laporan Penduduk Indonesia</h1>
      <div class="meta">
        <span>Tanggal: {{ now()->format('d M Y H:i') }}</span>
        <span>📈 Total Data: {{ $penduduk->count() }} Penduduk</span>
        <span>🇮🇩 Data Master Terupdate</span>
      </div>
    </div>

    <div class="content">
      <!-- Kiri: 2 Tombol Aksi -->
      <div class="actions">
        <a href="https://www.worldometers.info/world-population/indonesia-population/" class="card" target="_blank" style="position: relative;">
          <div class="card-icon">🌍</div>
          <h2>Statistik Penduduk Indonesia</h2>
          <p>Data populasi real-time Indonesia tahun 2025 dari Worldometer. Termasuk proyeksi, pertumbuhan, dan demografi lengkap.</p>
        </a>
        
        <a href="https://mandata.bappenas.go.id/direktorat.kjs/dokumen_pengetahuan/khportal/file/112214_Buku_Penduduk_Berkualitas_Menuju_Indonesia_Emas.pdf" class="card" target="_blank" style="position: relative;">
          <div class="card-icon">📖</div>
          <h2>Buku Elektronik Demografi</h2>
          <p>Buku resmi Bappenas: "Penduduk Berkualitas Menuju Indonesia Emas". Analisis & proyeksi demografi Indonesia 2020–2050 (PDF).</p>
        </a>
      </div>

      <!-- Kanan: Print + Stats + Tabel -->
      <div class="main-data">
        <button class="print-btn" onclick="window.print()">
          🖨️ Cetak / Simpan sebagai PDF
        </button>

        <div class="stats">
          <div class="stat-item">
            <div class="stat-number">{{ $penduduk->count() }}</div>
            <div class="stat-label">Total Penduduk</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ $penduduk->unique('provinsi')->count() }}</div>
            <div class="stat-label">Provinsi Terdaftar</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ now()->format('Y') }}</div>
            <div class="stat-label">Tahun Data</div>
          </div>
        </div>

        <div class="data-section">
          <h3>📋 Daftar Penduduk Lengkap</h3>
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>RT/RW</th>
                <th>Provinsi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($penduduk as $i => $p)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->nik }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ ($p->rt ?? '-') . '/' . ($p->rw ?? '-') }}</td>
                <td>{{ $p->provinsi }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Hover effect halus pada card
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-12px)');
      card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });

    // Optimasi print
    window.addEventListener('beforeprint', () => {
      document.querySelectorAll('.actions, .print-btn, .stats, .back-btn').forEach(el => el.style.display = 'none');
    });
    window.addEventListener('afterprint', () => {
      document.querySelectorAll('.actions, .print-btn, .stats, .back-btn').forEach(el => el.style.display = '');
    });
  </script>
</body>
</html>