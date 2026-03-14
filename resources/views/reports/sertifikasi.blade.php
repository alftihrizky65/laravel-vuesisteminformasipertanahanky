<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sertifikasi Pertanahan - Form Cetak</title>
  <style>
    @page { size: A4; margin: 12mm; }
    body { font-family: 'Times New Roman', serif; color: #000; margin: 0; }
    .no-print { margin-bottom: 8px; }
    .container { max-width: 760px; margin: 0 auto; box-sizing: border-box; padding: 6mm; }
    header { text-align: center; margin-bottom: 6px; }
    header h1 { font-size: 18px; margin: 0; letter-spacing: 0.5px; }
    header p { margin: 2px 0 8px; font-size: 11px; }
    .title { text-align: center; margin: 8px 0 12px; }
    .title h2 { font-size: 16px; margin: 0; font-weight: 700; text-transform: uppercase; }

    .section { margin-bottom: 10px; }
    .field { margin-bottom: 6px; }
    .field .label { display:block; font-weight:700; margin-bottom:6px; font-size:12px; }
    .line { border-bottom: 1px solid #000; height: 20px; }
    .small-line { border-bottom: 1px solid #000; height: 16px; display:inline-block; width: 48%; }

    .checkbox { display:inline-block; width:14px; height:14px; border:1px solid #000; vertical-align:middle; margin-right:8px; }
    .checkbox-label { margin-right:16px; display:inline-block; vertical-align:middle; font-size:12px; }

    .signature { margin-top:18px; display:flex; justify-content:space-between; gap: 12px; }
    .sig-box { width:48%; text-align:left; }
    .sig-line { margin-top:40px; border-top:1px solid #000; width:100%; }
    .note { font-size:11px; color:#333; margin-top:6px; }

    /* Ensure single page: prevent page breaks inside main container */
    .container { page-break-inside: avoid; }
    .section, .field { page-break-inside: avoid; }

    @media print {
      .no-print { display:none; }
      body { margin: 0; }
      /* make font slightly smaller for print compactness */
      header h1 { font-size: 17px; }
      .title h2 { font-size: 15px; }
      .field .label { font-size:11px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="no-print">
      <button onclick="window.print()" style="padding:8px 12px; font-size:14px;">Cetak / Simpan PDF</button>
      <span style="margin-left:12px; color:#555;">Cetak halaman ini lalu isi manual dengan pensil atau pulpen sesuai kebutuhan.</span>
    </div>

    <header>
      <h1>Kementerian Agraria dan Tata Ruang / Badan Pertanahan Nasional</h1>
      <p>Direktorat Jenderal Pertanahan - Unit Pelayanan Pertanahan</p>
    </header>

    <div class="title">
      <h2>SERTIFIKASI PERTANAHAN</h2>
      <p style="font-size:12px; margin-top:6px;">Formulir resmi untuk keperluan pencatatan dan verifikasi data pertanahan</p>
    </div>

    <div class="section">
      <div class="field">
        <span class="label">1. Identitas Pemohon / Pemilik</span>
        <div style="margin-bottom:6px;">Nama: <span class="line" style="width:70%; display:inline-block;"></span></div>
        <div style="margin-bottom:6px;">No. Identitas (NIK/KTP): <span class="line" style="width:50%; display:inline-block;"></span></div>
        <div>Alamat: <div class="line" style="width:100%;"></div></div>
      </div>
    </div>

    <div class="section">
      <div class="field">
        <span class="label">2. Keterangan Perolehan Hak</span>
        <div style="margin-bottom:6px;">
          <span class="checkbox"></span><span class="checkbox-label">Jual Beli</span>
          <span class="checkbox"></span><span class="checkbox-label">Hibah</span>
          <span class="checkbox"></span><span class="checkbox-label">Warisan</span>
          <span class="checkbox"></span><span class="checkbox-label">Tukar Menukar</span>
          <span class="checkbox"></span><span class="checkbox-label">Lainnya: ____________________</span>
        </div>
        <div class="note">Silakan beri tanda centang (✓) sesuai kondisi sebenarnya.</div>
      </div>
    </div>

    <div class="section">
      <div class="field">
        <span class="label">3. Data Objek Tanah</span>
        <div style="margin-bottom:6px;">Lokasi (Desa/Kelurahan / Kecamatan / Kabupaten): <span class="line" style="width:65%; display:inline-block;"></span></div>
        <div style="margin-bottom:6px;">Luas: <span class="small-line"></span> m²  &nbsp;&nbsp; Batas Utara: <span class="line" style="width:35%; display:inline-block;"></span></div>
        <div style="margin-bottom:6px;">Batas Timur: <span class="line" style="width:35%; display:inline-block;"></span> &nbsp;&nbsp; Batas Selatan: <span class="line" style="width:35%; display:inline-block;"></span></div>
        <div style="margin-bottom:6px;">Batas Barat: <span class="line" style="width:35%; display:inline-block;"></span></div>
      </div>
    </div>

    <div class="section">
      <div class="field">
        <span class="label">4. Pernyataan</span>
        <p style="text-align:justify;">Yang bertanda tangan di bawah ini menyatakan bahwa informasi yang tercantum pada formulir ini adalah benar dan dapat dipertanggungjawabkan. Formulir ini digunakan sebagai bukti awal untuk proses verifikasi dan sertifikasi pertanahan. Untuk keperluan administrasi, data dapat dilengkapi oleh petugas pemerintah sesuai ketentuan yang berlaku.</p>
      </div>
    </div>

    <div class="signature">
      <div class="sig-box">
        <div style="font-weight:700;">Pemilik / Penerima Hak</div>
        <div class="sig-line"></div>
        <div style="margin-top:6px; font-size:12px;">Nama: ______________________  Tanggal: ________</div>
      </div>

      <div class="sig-box">
        <div style="font-weight:700;">Petugas Verifikasi</div>
        <div class="sig-line"></div>
        <div style="margin-top:6px; font-size:12px;">Nama Petugas: ______________________  NIP: __________</div>
      </div>
    </div>

    <div style="margin-top:18px; font-size:12px;">Catatan: Cetak halaman ini pada kertas A4 dengan orientasi potrait. Isi formulir secara rapi dan tanda centang menggunakan pulpen atau pensil sesuai instruksi.</div>
  </div>

  <!-- Lembar kedua: Bukti area (foto) -->
  <div class="container page" id="bukti-area-page">
    <h2 style="font-size:16px; margin:0 0 8px 0; text-align:center;">LEMBAR 2 — BUKTI AREA</h2>

    <div class="no-print" style="margin:8px 0 12px;">
      <label><strong>Pilih foto bukti area:</strong> <input type="file" id="bukti-photo-input" accept="image/*"></label>
      <button id="bukti-save">Simpan Foto</button>
      <button id="bukti-clear">Hapus Foto</button>
      <button id="bukti-regenerate-qr">Regenerate QR</button>
    </div>

    <div class="image-frame" id="bukti-image-frame">
      <img id="bukti-photo-preview" src="" alt="Preview Foto" style="display:none">
      <div id="bukti-placeholder">Klik "Pilih foto bukti area" untuk menaruh gambar di sini (16:9)</div>
    </div>

    <div class="caption" style="margin-top:10px;">
      <strong>Keterangan =</strong>
      <p id="bukti-caption-text" style="margin-top:8px; font-size:13px; line-height:1.4;">Data area ini memuat informasi lokasi dan bukti visual yang digunakan untuk keperluan sertifikasi pertanahan. "Pertanahan" merujuk pada pengelolaan dan verifikasi hak atas tanah, termasuk identifikasi batas, pemilik, dan status hukum. Data area digunakan untuk verifikasi lapangan, dokumentasi bukti, serta mendukung proses administrasi dan pelaporan.</p>
    </div>

    <!-- QR di pojok kanan bawah -->
    <img id="bukti-qr" class="qr" src="" alt="QR" />

    <div class="no-print" style="margin-top:10px; font-size:12px; color:#666">Catatan: Kontrol ini tidak akan dicetak. Foto dan keterangan disimpan di browser (localStorage) untuk preview; beri tahu saya agar saya tambahkan endpoint upload untuk menyimpan ke server.</div>
  </div>

  <style>
    /* Bukti area styles */
    .page { page-break-before: always; position:relative; padding-bottom:36mm; }
    .image-frame { width:100%; background:#f9f9f9; display:flex; align-items:center; justify-content:center; overflow:hidden; border:1px dashed #ccc; min-height:160px; }
    .image-frame { aspect-ratio: 16/9; }
    .image-frame img { width:100%; height:100%; object-fit:cover; display:block; }
    .image-frame #bukti-placeholder { color:#888; font-size:14px; padding:12px; }
    .caption { margin-top:8px; font-size:13px; margin-bottom:36mm; }
    /* QR positioned slightly inset from paper edge so it remains visible */
    .qr { position:absolute; right:4mm; bottom:6mm; width:64px; height:64px; z-index:9999; background:#fff; padding:3px; box-shadow:0 1px 2px rgba(0,0,0,0.08); border-radius:2px; }
    @media print { .no-print { display:none; } }
  </style>

  <script>
    (function(){
      const input = document.getElementById('bukti-photo-input');
      const preview = document.getElementById('bukti-photo-preview');
      const placeholder = document.getElementById('bukti-placeholder');
      const saveBtn = document.getElementById('bukti-save');
      const clearBtn = document.getElementById('bukti-clear');
      const captionText = document.getElementById('bukti-caption-text');
      const qrImage = document.getElementById('bukti-qr');
      const regenBtn = document.getElementById('bukti-regenerate-qr');

      const IMG_KEY = 'sertifikasi_bukti_photo';
      const QR_KEY = 'sertifikasi_bukti_qr';

      function loadSaved(){
        const img = localStorage.getItem(IMG_KEY);
        if(img){ preview.src = img; preview.style.display='block'; placeholder.style.display='none'; }
        // Caption is fixed static text on the page; no load/save behavior
        const qr = localStorage.getItem(QR_KEY);
        if(qr){ qrImage.src = qr; }
        else { generateAndSaveQR(); }
      }

      function generateRandomData(){
        const rand = Math.random().toString(36).slice(2,12);
        return `sertifikasi-${Date.now()}-${rand}`;
      }

      function generateAndSaveQR(){
        const data = generateRandomData();
        const src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(data)}`;
        qrImage.src = src;
        localStorage.setItem(QR_KEY, src);
      }

      function handleFile(file, autoSave=false){
        if(!file) return;
        const reader = new FileReader();
        reader.onload = function(ev){
          preview.src = ev.target.result; preview.style.display='block'; placeholder.style.display='none';
          if(autoSave){ localStorage.setItem(IMG_KEY, preview.src); }
        };
        reader.readAsDataURL(file);
      }

      if(input){
        input.addEventListener('change', function(e){
          const file = e.target.files && e.target.files[0];
          handleFile(file, false);
        });
      }

      if(saveBtn){
        saveBtn.addEventListener('click', function(){
          if(preview.src){ localStorage.setItem(IMG_KEY, preview.src); alert('Foto disimpan di browser (localStorage).'); }
        });
      }

      if(clearBtn){
        clearBtn.addEventListener('click', function(){
          localStorage.removeItem(IMG_KEY); preview.src=''; preview.style.display='none'; placeholder.style.display='block'; input.value='';
        });
      }


      regenBtn && regenBtn.addEventListener('click', function(){ generateAndSaveQR(); alert('QR diganti.'); });

      // Init
      loadSaved();

    })();
  </script>

</body>
</html>