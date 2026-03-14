<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Laporan Lahan - Dashboard</title>
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background: linear-gradient(135deg, #f0f8ff 0%, #e3f2fd 100%);
      color: #333; 
      min-height: 100vh;
    }
    .container { 
      max-width: none; 
      width: 100%;
      background: white; 
      box-shadow: 0 15px 50px rgba(0,123,255,0.2);
      overflow: hidden;
      min-height: 100vh;
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

    /* Right: Print + Map + Stats + Table */
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
    #map {
      height: 300px;
      width: 100%;
      border-radius: 18px;
      box-shadow: 0 10px 30px rgba(0,123,255,0.12);
      border: 1px solid rgba(0,123,255,0.15);
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
      .actions, .print-btn, #map, .stats, .back-btn { display: none !important; }
      .main-data { gap: 20px; }
      table { font-size: 12px !important; }
      th, td { padding: 10px !important; }
    }

    /* Responsive Mobile */
    @media (max-width: 992px) {
      .container { margin: 0; border-radius: 0; }
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
      #map { height: 250px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <a href="/dashboard" class="back-btn">⬅ Kembali ke Dashboard</a>
      <h1>🌿 Laporan Lahan Indonesia</h1>
      <div class="meta">
        <span>Tanggal: {{ now()->format('d M Y H:i') }}</span>
        <span>📈 Total Data: {{ $lahan->count() }} Lahan</span>
        <span>🇮🇩 Data Master Terupdate</span>
      </div>
    </div>

    <div class="content">
      <!-- Kiri: 2 Tombol Aksi -->
      <div class="actions">
        <a href="https://satudata.pertanian.go.id/" class="card" target="_blank" style="position: relative;">
          <div class="card-icon">🌍</div>
          <h2>Statistik Lahan Indonesia</h2>
          <p>Data statistik lahan dan pertanian resmi dari Kementerian Pertanian. Termasuk luas lahan, produksi, dan data terkini.<grok-card data-id="610fe9" data-type="citation_card" ></grok-card></p>
        </a>
        
        <a href="https://lcdi-indonesia.id/wp-content/uploads/2024/01/Laporan-KLHS-RPJPN-Tahun-2025-2045.pdf" class="card" target="_blank" style="position: relative;">
          <div class="card-icon">📖</div>
          <h2>Buku Elektronik Lahan & Lingkungan</h2>
          <p>Laporan Kajian Lingkungan Hidup Strategis (KLHS) RPJPN 2025-2045 dari Bappenas. Analisis keberlanjutan lahan & lingkungan Indonesia (PDF).<grok-card data-id="b6cf91" data-type="citation_card" ></grok-card></p>
        </a>
      </div>

      <!-- Kanan: Print + Map + Stats + Tabel -->
      <div class="main-data">
        <button class="print-btn" onclick="window.print()">
          🖨️ Cetak / Simpan sebagai PDF
        </button>

        <!-- Peta Kecil -->
        <div id="map"></div>

        <div class="stats">
          <div class="stat-item">
            <div class="stat-number">{{ $lahan->count() }}</div>
            <div class="stat-label">Total Lahan</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ $lahan->unique('provinsi')->count() }}</div>
            <div class="stat-label">Provinsi Terdaftar</div>
          </div>
          <div class="stat-item">
            <div class="stat-number">{{ now()->format('Y') }}</div>
            <div class="stat-label">Tahun Data</div>
          </div>
        </div>

        <div class="data-section">
          <h3>📋 Daftar Lahan Lengkap</h3>
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>ID Lahan</th>
                <th>Luas (Ha)</th>
                <th>Lokasi</th>
                <th>RT/RW</th>
                <th>Provinsi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($lahan as $i => $l)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $l->id_lahan }}</td>
                <td>{{ $l->luas }}</td>
                <td>{{ $l->lokasi }}</td>
                <td>{{ ($l->rt ?? '-') . '/' . ($l->rw ?? '-') }}</td>
                <td>{{ $l->provinsi }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>
    // Inisialisasi Peta Kecil dengan OpenStreetMap
    var map = L.map('map').setView([-2.5489, 118.0149], 4); // Pusat Indonesia, zoom level 4 untuk tampilan seluruh negara
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    // Hover effect halus pada card
    document.querySelectorAll('.card').forEach(card => {
      card.addEventListener('mouseenter', () => card.style.transform = 'translateY(-12px)');
      card.addEventListener('mouseleave', () => card.style.transform = 'translateY(0)');
    });

    // Optimasi print
    window.addEventListener('beforeprint', () => {
      document.querySelectorAll('.actions, .print-btn, #map, .stats, .back-btn').forEach(el => el.style.display = 'none');
    });
    window.addEventListener('afterprint', () => {
      document.querySelectorAll('.actions, .print-btn, #map, .stats, .back-btn').forEach(el => el.style.display = '');
    });
  </script>
</body>
</html>