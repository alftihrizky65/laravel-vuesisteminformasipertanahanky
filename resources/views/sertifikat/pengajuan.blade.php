<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pengajuan Sertifikat - SIP Pertanahan</title>
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
            max-width: 800px;
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
        .section { margin-bottom: 2rem; }
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .form-group { margin-bottom: 1rem; }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #1e293b;
        }
        .form-group label .required { color: #dc2626; }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: inherit;
            transition: border-color 0.3s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        .file-upload {
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        .file-upload:hover { border-color: #3b82f6; background: #f8fafc; }
        .file-upload input { display: none; }
        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
        }
        .file-upload.has-file { border-color: #10b981; background: #ecfdf5; }
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
        .btn-primary:disabled { background: #9ca3af; cursor: not-allowed; }
        .btn-secondary {
            background: #6b7280;
            color: white;
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
        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: none;
        }
        .alert.active { display: block; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .info-box {
            background: #dbeafe;
            color: #1e40af;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .info-box i { margin-top: 0.25rem; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-file-signature" style="font-size: 3rem; margin-bottom: 1rem;"></i>
            <h1>Pengajuan Sertifikat Tanah</h1>
            <p>Ajukan pembuatan sertifikat tanah Anda</p>
        </div>
        
        <div class="body">
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            
            <div class="info-box">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Informasi:</strong> Silakan lengkapi formulir di bawah dengan data yang benar. 
                    Setelah提交, Anda akan diarahkan ke halaman pembayaran. Biaya administrasi berbeda-beda 
                    tergantung jenis sertifikat.
                </div>
            </div>
            
            <div class="alert alert-success" id="successAlert"></div>
            <div class="alert alert-error" id="errorAlert"></div>
            
            <form id="pengajuanForm">
                <div class="section">
                    <div class="section-title">Data Pribadi</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>NIK <span class="required">*</span></label>
                            <input type="text" name="nik" id="nik" maxlength="16" placeholder="Masukkan 16 digit NIK" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap <span class="required">*</span></label>
                            <input type="text" name="nama_lengkap" placeholder="Sesuai KTP" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nomor HP</label>
                            <input type="tel" name="nomor_hp" placeholder="0812xxxxxxx">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="email@anda.com">
                        </div>
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Alamat Rumah</div>
                    <div class="form-group">
                        <label>Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="alamat_rumah" placeholder="Jl. Nama Jalan, No. Rumah, RT/RW" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Provinsi</label>
                            <select name="provinsi" id="provinsi_sertifikat" class="form-control">
                                <option value="">Pilih Provinsi</option>
                                <option value="Aceh">Aceh</option>
                                <option value="Sumatera Utara">Sumatera Utara</option>
                                <option value="Sumatera Barat">Sumatera Barat</option>
                                <option value="Riau">Riau</option>
                                <option value="Jambi">Jambi</option>
                                <option value="Sumatera Selatan">Sumatera Selatan</option>
                                <option value="Bengkulu">Bengkulu</option>
                                <option value="Lampung">Lampung</option>
                                <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                                <option value="Kepulauan Riau">Kepulauan Riau</option>
                                <option value="DKI Jakarta">DKI Jakarta</option>
                                <option value="Jawa Barat">Jawa Barat</option>
                                <option value="Jawa Tengah">Jawa Tengah</option>
                                <option value="DI Yogyakarta">DI Yogyakarta</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                                <option value="Banten">Banten</option>
                                <option value="Bali">Bali</option>
                                <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                <option value="Kalimantan Barat">Kalimantan Barat</option>
                                <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                <option value="Kalimantan Timur">Kalimantan Timur</option>
                                <option value="Kalimantan Utara">Kalimantan Utara</option>
                                <option value="Sulawesi Barat">Sulawesi Barat</option>
                                <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                <option value="Sulawesi Utara">Sulawesi Utara</option>
                                <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                <option value="Gorontalo">Gorontalo</option>
                                <option value="Maluku">Maluku</option>
                                <option value="Maluku Utara">Maluku Utara</option>
                                <option value="Papua">Papua</option>
                                <option value="Papua Barat">Papua Barat</option>
                                <option value="Papua Selatan">Papua Selatan</option>
                                <option value="Papua Tengah">Papua Tengah</option>
                                <option value="Papua Pegunungan">Papua Pegunungan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Kabupaten/Kota</label>
                            <select name="kabupaten_kota" id="kabupaten_kota" class="form-control">
                                <option value="">Pilih Kabupaten/Kota</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Kecamatan</label>
                            <input type="text" id="kecamatan_sertifikat" name="kecamatan" class="form-control" placeholder="Masukkan Kecamatan" value="{{ old('kecamatan') }}">
                        </div>
                        <div class="form-group">
                            <label>Kelurahan</label>
                            <input type="text" name="kelurahan" id="kelurahan_sertifikat" placeholder="Kelurahan">
                        </div>
                    </div>

                    <script>
                    const kabupatenKotaDataSertifikat = {
                        'Aceh': ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Meulaboh', 'Sabang', 'Subulussalam', 'Aceh Barat', 'Aceh Barat Daya', 'Aceh Besar', 'Aceh Jaya', 'Aceh Selatan', 'Aceh Singkil', 'Aceh Tamiang', 'Aceh Tengah', 'Aceh Tenggara', 'Aceh Timur', 'Aceh Utara', 'Bener Meriah', 'Bireuen', 'Gayo Lues', 'Nagan Raya', 'Pidie', 'Pidie Jaya', 'Simeulue', 'Aceh Singkil', 'Aceh Tenggara'],
                        'Sumatera Utara': ['Medan', 'Pematang Siantar', 'Tebing Tinggi', 'Binjai', 'Tanjungbalai', 'Sibolga', 'Padang Sidempuan', 'Gunungsitoli', 'Deli Serdang', 'Langkat', 'Karo', 'Simalungun', 'Dairi', 'Toba', 'Mandailing Natal', 'Labuhanbatu', 'Asahan', 'Samosir', 'Labuhanbatu Utara', 'Labuhanbatu Selatan', 'Nias', 'Nias Utara', 'Nias Barat', 'Humbang Hasundutan', 'Pakpak Bharat', 'Padang Lawas', 'Padang Lawas Utara', 'Serdang Bedagai', 'Tapanuli Selatan', 'Tapanuli Tengah', 'Tapanuli Utara', 'Batubara', 'Padang Lawas'],
                        'Sumatera Barat': ['Padang', 'Bukittinggi', 'Payakumbuh', 'Pariaman', 'Solok', 'Sawahlunto', 'Padang Panjang', 'Batusangkar', 'Limapuluh Kota', 'Agam', 'Dharmasraya', 'Kepulauan Mentawai', 'Lima Puluh Kota', 'Padang Pariaman', 'Pasaman', 'Pasaman Barat', 'Pesisir Selatan', 'Solok Selatan', 'Tanah Datar'],
                        'Riau': ['Pekanbaru', 'Dumai', 'Ujung Tanjung', 'Siak', 'Pelalawan', 'Indragiri Hilir', 'Indragiri Hulu', 'Kampar', 'Kuantan Singingi', 'Rokan Hilir', 'Rokan Hulu', 'Meranti', 'Siak'],
                        'Jambi': ['Jambi', 'Sungaipenuh', 'Batang Hari', 'Bungo', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Tebo', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur'],
                        'Sumatera Selatan': ['Palembang', 'Prabumulih', 'Pagar Alam', 'Lubuklinggau', 'Banyuasin', 'Empat Lawang', 'Lahat', 'Muara Enim', 'Musi Banyuasin', 'Musi Rawas', 'Ogan Ilir', 'Ogan Komering Ilir', 'Ogan Komering Ulu', 'Ogan Komering Ulu Selatan', 'Ogan Komering Ulu Timur', 'Penukal Abab Lematang Ilir'],
                        'Bengkulu': ['Bengkulu', 'Curup', 'Mukomuko', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Kepahiang', 'Lebong', 'Muko Muko', 'Rejang Lebong', 'Seluma'],
                        'Lampung': ['Bandar Lampung', 'Metro', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Utara', 'Lampung Barat', 'Lampung Timur', 'Tulang Bawang', 'Tulang Bawang Barat', 'Pesawaran', 'Pesisir Barat', 'Pringsewu', 'Mesuji', 'Way Kanan', 'Ogan Komering Ulu', 'Ogan Ilir'],
                        'Kepulauan Bangka Belitung': ['Pangkal Pinang', 'Belitung', 'Bangka', 'Bangka Barat', 'Bangka Selatan', 'Bangka Tengah', 'Belitung Timur'],
                        'Kepulauan Riau': ['Batam', 'Tanjung Pinang', ' Bintan', 'Anambas', 'Karimun', 'Kepulauan Anambas', 'Lingga', 'Natuna'],
                        'DKI Jakarta': ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur', 'Kepulauan Seribu'],
                        'Jawa Barat': ['Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Bandung Barat', 'Karawang', 'Cianjur', 'Garut', 'Subang', 'Purwakarta', 'Sumedang', 'Ciamis', 'Kuningan', 'Majalengka', 'Indramayu', 'Pangandaran', 'Banjars', 'Cikitaman', 'Ciparay'],
                        'Jawa Tengah': ['Semarang', 'Surakarta', 'Salatiga', 'Pekalongan', 'Tegal', 'Kendal', 'Banyumas', 'Purbalingga', 'Batang', 'Klaten', 'Sukoharjo', 'Boyolali', 'Sragen', 'Wonogiri', 'Kudus', 'Jepara', 'Demak', 'Grobogan', 'Blora', 'Rembang', 'Pati', 'Kudus', 'Wonosobo', 'Magelang', 'Temanggung', 'Banjarnegara', 'Cilacap'],
                        'DI Yogyakarta': ['Yogyakarta', 'Sleman', 'Bantul', 'Gunung Kidul', 'Kulon Progo'],
                        'Jawa Timur': ['Surabaya', 'Madiun', 'Malang', 'Probolinggo', 'Pasuruan', 'Mojokerto', 'Kediri', 'Blitar', 'Lamongan', 'Jember', 'Banyuwangi', 'Batu', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jombang', 'Nganjuk', 'Ngawi', 'Pacitan', 'Ponorogo', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tulungagung', 'Lumajang', 'Magetan', 'Pamekasan', 'Sampang', 'Sidoarjo', 'Tulungag'],
                        'Banten': ['Serang', 'Tangerang', 'Cilegon', 'Pandeglang', 'Lebak', 'Tangerang Selatan', 'Serang'],
                        'Bali': ['Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
                        'Nusa Tenggara Barat': ['Mataram', 'Bima', 'Dompu', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Lombok Utara', 'Sumbawa', 'Sumbawa Barat'],
                        'Nusa Tenggara Timur': ['Kupang', 'Alor', 'Belu', 'Ende', 'Flores Timur', 'Kepulauan Alor', 'Komodo', 'Lembata', 'Mabar', 'Malaka', 'Manggarai', 'Manggarai Barat', 'Manggarai Timur', 'Nagekeo', 'Ngada', 'Ngada', 'Rote Ndao', 'Sabu Raijua', 'Sikka', 'Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur', 'Timor Tengah Selatan', 'Timor Tengah Utara'],
                        'Kalimantan Barat': ['Pontianak', 'Singkawang', 'Bengkayang', 'Kapuas Hulu', 'Kayong Utara', 'Ketapang', 'Kubu Raya', 'Landak', 'Melawi', 'Mempawah', 'Sambas', 'Sanggau', 'Sekadau', 'Sintang'],
                        'Kalimantan Tengah': ['Palangka Raya', 'Barito Selatan', 'Barito Timur', 'Barito Utara', 'Gunung Mas', 'Kapuas', 'Katingan', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Lamandau', 'Murung Raya', 'Pulang Pisau', 'Seruyan', 'Sukamara'],
                        'Kalimantan Selatan': ['Banjarmasin', 'Banjarbaru', 'Balangan', 'Banjar', 'Barito Kuala', 'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru', 'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin'],
                        'Kalimantan Timur': ['Samarinda', 'Balikpapan', 'Bontang', 'Berau', 'Kutai Barat', 'Kutai Kartanegara', 'Kutai Timur', 'Mahakam Ulu', 'Paser', 'Penajam Paser Utara'],
                        'Kalimantan Utara': ['Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung'],
                        'Sulawesi Utara': ['Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Bolaang Mongondow', 'Bolaang Mongondow Selatan', 'Bolaang Mongondow Timur', 'Bolaang Mongondow Utara', 'Kepulauan Sangihe', 'Kepulauan Talaud', 'Minahasa', 'Minahasa Selatan', 'Minahasa Tenggara', 'Minahasa Utara', 'Siau Tagulandang Biaro'],
                        'Sulawesi Tengah': ['Palu', 'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Buol', 'Donggala', 'Morowali', 'Morowali Utara', 'Parigi Moutong', 'Poso', 'Sigi', 'Tojo Una-Una', 'Toli-Toli'],
                        'Sulawesi Selatan': ['Makassar', 'Parepare', 'Palopo', 'Bantaeng', 'Barru', 'Bone', 'Bulukumba', 'Enrekang', 'Gowa', 'Jeneponto', 'Luwu', 'Luwu Utara', 'Luwu Timur', 'Maros', 'Pangkajene dan Kepulauan', 'Pinrang', 'Sidenreng Rappang', 'Sinjai', 'Soppeng', 'Takalar', 'Tana Toraja', 'Toraja Utara', 'Wajo'],
                        'Sulawesi Tenggara': ['Kendari', 'Baubau', 'Bombana', 'Buton', 'Buton Selatan', 'Buton Tengah', 'Buton Utara', 'Kolaka', 'Kolaka Utara', 'Konawe', 'Konawe Kepulauan', 'Konawe Selatan', 'Konawe Utara', 'Muna', 'Muna Barat', 'Wakatobi'],
                        'Gorontalo': ['Gorontalo', 'Boalemo', 'Bone Bolango', 'Gorontalo Utara', 'Pohuwato'],
                        'Sulawesi Barat': ['Mamuju', 'Mamasa', 'Mamuju Utara', 'Mamuju Tengah', 'Polewali Mandar'],
                        'Maluku': ['Ambon', 'Tual', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Barat Daya', 'Maluku Tengah', 'Maluku Tenggara', 'Maluku Tenggara Barat', 'Seram Bagian Barat', 'Seram Bagian Timur'],
                        'Maluku Utara': ['Ternate', 'Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Selatan', 'Halmahera Tengah', 'Halmahera Timur', 'Halmahera Utara', 'Kepulauan Sula', 'Pulau Morotai'],
                        'Papua': ['Jayapura', 'Sorong', 'Biak Numfor', 'Boven Digoel', 'Deiyai', 'Dogiyai', 'Intan Jaya', 'Jayawiaya', 'Keerom', 'Kepulauan Yapen', 'Lanny Jaya', 'Mamberamo Raya', 'Mamberamo Tengah', 'Manokwari', 'Manokwari Selatan', 'Mappi', 'Merauke', 'Mimika', 'Nabire', 'Nduga', 'Paniai', 'Pegunungan Arfak', 'Pegunungan Bintang', 'Puncak', 'Puncak Jaya', 'Sarmi', 'Supiori', 'Tolikara', 'Waropen', 'Yahukimo', 'Yalimo'],
                        'Papua Barat': ['Manokwari', 'Fakfak', 'Kaimana', 'Manokwari Selatan', 'Pegunungan Arfak', 'Raja Ampat', 'Sorong', 'Sorong Selatan', 'Tambrauw', 'Teluk Bintuni', 'Teluk Wondama'],
                        'Papua Selatan': ['Merauke', 'Asmat', 'Boven Digoel', 'Mappi', 'Yahukimo'],
                        'Papua Pegunungan': ['Jayawijaya', 'Lanny Jaya', 'Nduga', 'Pegunungan Bintang', 'Tolikara', 'Yahukimo'],
                        'Papua Barat Daya': ['Sorong', 'Raja Ampat', 'Sorong Selatan', 'Tambrauw', 'Maybrat']
                    };

                    // Province change handler
                    document.getElementById('provinsi_sertifikat').addEventListener('change', function() {
                        const selectedProvinsi = this.value;
                        const kotaSelect = document.getElementById('kabupaten_kota');
                        
                        kotaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        document.getElementById('kelurahan_sertifikat').value = '';
                        
                        if (kabupatenKotaDataSertifikat[selectedProvinsi]) {
                            kabupatenKotaDataSertifikat[selectedProvinsi].forEach(function(kota) {
                                const option = document.createElement('option');
                                option.value = kota;
                                option.textContent = kota;
                                kotaSelect.appendChild(option);
                            });
                        }
                    });
                    </script>
                </div>
                
                <div class="section">
                    <div class="section-title">Data Sertifikat</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Jenis Sertifikat <span class="required">*</span></label>
                            <select name="jenis_sertifikat" required>
                                <option value="">Pilih Jenis Sertifikat</option>
                                <option value="SHM">SHM - Sertifikat Hak Milik</option>
                                <option value="SHGB">SHGB - Sertifikat Hak Guna Bangunan</option>
                                <option value="HGB">HGB - Hak Guna Bangunan</option>
                                <option value="SHP">SHP - Sertifikat Hak Pakai</option>
                                <option value="GIRIK">Girik</option>
                                <option value="APBT">APBT - Akta Perolehan Hak Tanah</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nomor Sertifikat (Jika Ada)</label>
                            <input type="text" name="nomor_sertifikat" placeholder="Nomor sertifikat lama (jika ada)">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Luas Tanah (m²)</label>
                            <input type="number" name="luas_tanah" step="0.01" placeholder="Contoh: 500">
                        </div>
                        <div class="form-group">
                            <label>Luas Bangunan (m²)</label>
                            <input type="number" name="luas_bangunan" step="0.01" placeholder="Contoh: 150">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi Lokasi Tanah</label>
                        <textarea name="deskripsi_lokasi" placeholder="Jelaskan letak atau patokan tanah Anda..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Expired Pajak Terakhir</label>
                        <input type="date" name="tanggal_expired_pajak">
                    </div>
                </div>
                
                <div class="section">
                    <div class="section-title">Upload Dokumen</div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Foto KTP</label>
                            <div class="file-upload" onclick="document.getElementById('foto_ktp').click()">
                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*">
                                <div class="file-upload-label">
                                    <i class="fas fa-camera" style="font-size: 2rem; color: #64748b;"></i>
                                    <span>Klik untuk upload foto KTP</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Foto Rumah/Tanah</label>
                            <div class="file-upload" onclick="document.getElementById('foto_rumah').click()">
                                <input type="file" name="foto_rumah" id="foto_rumah" accept="image/*">
                                <div class="file-upload-label">
                                    <i class="fas fa-home" style="font-size: 2rem; color: #64748b;"></i>
                                    <span>Klik untuk upload foto rumah/tanah</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Ajukan Sertifikat
                </button>
            </form>
            
            <div class="loading" id="loading">
                <div class="spinner"></div>
                <p>Mengirim pengajuan...</p>
            </div>
        </div>
    </div>
    
    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        // File upload preview
        document.querySelectorAll('.file-upload input').forEach(input => {
            input.addEventListener('change', function() {
                const container = this.closest('.file-upload');
                if (this.files.length > 0) {
                    container.classList.add('has-file');
                    container.querySelector('.file-upload-label span').textContent = this.files[0].name;
                }
            });
        });
        
        document.getElementById('pengajuanForm').addEventListener('submit', async (e) => {
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
                
                const response = await fetch('{{ route("sertifikat.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                
                const result = await response.json();
                loading.classList.add('active');
                submitBtn.disabled = false;
                
                if (!result.success) {
                    errorAlert.textContent = result.message;
                    errorAlert.classList.add('active');
                    return;
                }
                
                successAlert.innerHTML = '<i class="fas fa-check-circle"></i> ' + result.message + ' Mengarahkan ke halaman pembayaran...';
                successAlert.classList.add('active');
                
                // Redirect to payment page
                setTimeout(() => {
                    window.location.href = '/sertifikat/' + result.data.id + '/pembayaran';
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
