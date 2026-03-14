<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Formulir Pendaftaran - Sistem Informasi Pertanahan</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --primary-light: #e7f1ff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: var(--text);
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
            position: relative;
        }

        .header i {
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
        }

        .header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            opacity: 0.9;
            font-size: 1rem;
        }

        .body {
            padding: 2rem;
        }
        
        .mode-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }
        
        .mode-btn {
            flex: 1;
            max-width: 300px;
            padding: 1.5rem;
            border: 2px solid var(--border);
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }
        
        .mode-btn:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }
        
        .mode-btn.active {
            border-color: var(--primary);
            background: var(--primary-light);
        }
        
        .mode-btn i {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        .mode-btn h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .mode-btn p {
            font-size: 0.9rem;
            color: var(--text-light);
        }

        .mode-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }
        
        .mode-badge.umum {
            background: #dcfce7;
            color: #166534;
        }
        
        .mode-badge.staff {
            background: #fee2e2;
            color: #991b1b;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary);
            margin: 2rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary-light);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-title:first-child { margin-top: 0; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text);
            font-size: 0.95rem;
        }

        .form-group label .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }
        
        .form-control.error {
            border-color: var(--danger) !important;
            background-color: #fef2f2;
        }

        select.form-control {
            cursor: pointer;
            background: white;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .file-upload {
            border: 2px dashed var(--border);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .file-upload:hover {
            border-color: var(--primary);
            background: var(--primary-light);
        }

        .file-upload i {
            font-size: 2.5rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .file-upload p {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .file-upload input {
            display: none;
        }

        .file-preview {
            display: none;
            margin-top: 1rem;
        }

        .file-preview img {
            max-width: 200px;
            max-height: 150px;
            border-radius: 10px;
            border: 2px solid var(--border);
        }

        .btn-submit {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(13,110,253,0.3);
        }

        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }

        .footer {
            text-align: center;
            padding: 1.5rem 2rem;
            background: #f8fafc;
            border-top: 1px solid var(--border);
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 1rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            color: white;
            font-weight: 500;
            z-index: 2000;
            animation: slideIn 0.3s ease;
        }

        .toast.success { background: var(--success); }
        .toast.error { background: var(--danger); }

        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 768px) {
            .form-grid { grid-template-columns: 1fr; }
            .form-group.full-width { grid-column: span 1; }
            .header h1 { font-size: 1.5rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <i class="fas fa-file-signature"></i>
            <h1>Formulir Pendaftaran</h1>
            <p>Sistem Informasi Pertanahan Nasional</p>
        </div>
        
        <div class="body">
            <form id="registerForm" enctype="multipart/form-data">
                <input type="hidden" name="jenis_registrasi" value="umum">
                
                <!-- Link to Staff Registration -->
                <div style="text-align: center; margin-bottom: 2rem; padding: 1rem; background: #f8fafc; border-radius: 12px;">
                    <p style="margin-bottom: 0.75rem; color: #64748b;">Apakah Anda Staff/ASN?</p>
                    <a href="{{ url('/register-staff') }}" style="display: inline-block; padding: 0.75rem 1.5rem; background: #10b981; color: white; border-radius: 8px; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-user-tie"></i> Daftar sebagai Staff/ASN
                    </a>
                    <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">Persetujuan admin diperlukan</p>
                </div>

                <!-- Data Pribadi -->
                <div class="section-title">
                    <i class="fas fa-user"></i> Data Pribadi
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="required">*</span></label>
                        <input type="text" name="nama_lengkap" class="form-control" placeholder="Sesuai dengan KTP" required>
                    </div>

                    <div class="form-group">
                        <label>NIK (Nomor Induk Kependudukan) <span class="required">*</span></label>
                        <input type="text" name="nik" class="form-control" placeholder="16 digit NIK" maxlength="16" required>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="alamat" class="form-control" placeholder="Alamat lengkap sesuai KTP" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Nomor Telepon <span class="required">*</span></label>
                        <input type="text" name="no_telepon" class="form-control" placeholder="0812xxxxxxx" maxlength="15" required>
                    </div>

                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                    </div>
                </div>

                <!-- Data Kepegawaian (Staff Only) - Hidden for Umum page -->
                <div class="section-title" id="staff-section-title" style="display:none;">
                    <i class="fas fa-briefcase"></i> Data Kepegawaian (Wajib Diisi untuk Staff/ASN)
                </div>

                <div class="form-grid" id="staff-fields" style="display:none;">
                    <div class="form-group">
                        <label>NIP (Nomor Induk Pegawai)</label>
                        <input type="text" name="nip" class="form-control" placeholder="18 digit NIP (jika ada)" maxlength="18">
                    </div>

                    <div class="form-group">
                        <label>Jabatan <span class="required">*</span></label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Staff, Kepala Bagian, dll">
                    </div>

                    <div class="form-group">
                        <label>Instansi <span class="required">*</span></label>
                        <input type="text" name="instansi" class="form-control" placeholder="Kementerian ATR/BPN">
                    </div>

                    <div class="form-group">
                        <label>Unit Kerja <span class="required">*</span></label>
                        <input type="text" name="unit_kerja" class="form-control" placeholder="Direktorat/Seksi/Subdirektorat">
                    </div>

                    <div class="form-group">
                        <label>RT <span class="required">*</span></label>
                        <input type="text" name="rt" class="form-control" placeholder="001" maxlength="3">
                    </div>

                    <div class="form-group">
                        <label>RW <span class="required">*</span></label>
                        <input type="text" name="rw" class="form-control" placeholder="001" maxlength="3">
                    </div>

                    <div class="form-group">
                        <label>Kelurahan/Desa <span class="required">*</span></label>
                        <input type="text" name="kelurahan" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Provinsi <span class="required">*</span></label>
                        <select name="provinsi" id="provinsi_reg" class="form-control" required>
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
                        <label>Kabupaten/Kota <span class="required">*</span></label>
                        <select name="kota" id="kota_reg" class="form-control" required>
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kecamatan <span class="required">*</span></label>
                        <select name="kecamatan" id="kecamatan_reg" class="form-control" required>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kelurahan/Desa <span class="required">*</span></label>
                        <input type="text" name="kelurahan" id="kelurahan_reg" class="form-control">
                    </div>

                    <script>
                    const kabupatenKotaDataReg = {
                        'Aceh': ['Banda Aceh', 'Langsa', 'Lhokseumawe', 'Meulaboh', 'Sabang', 'Subulussalam', 'Aceh Barat', 'Aceh Barat Daya', 'Aceh Besar', 'Aceh Jaya', 'Aceh Selatan', 'Aceh Singkil', 'Aceh Tamiang', 'Aceh Tengah', 'Aceh Tenggara', 'Aceh Timur', 'Aceh Utara', 'Bener Meriah', 'Bireuen', 'Gayo Lues', 'Nagan Raya', 'Pidie', 'Pidie Jaya', 'Simeulue'],
                        'Sumatera Utara': ['Medan', 'Pematang Siantar', 'Tebing Tinggi', 'Binjai', 'Tanjungbalai', 'Sibolga', 'Padang Sidempuan', 'Gunungsitoli', 'Deli Serdang', 'Langkat', 'Karo', 'Simalungun', 'Dairi', 'Toba', 'Mandailing Natal', 'Labuhanbatu', 'Asahan', 'Samosir', 'Labuhanbatu Utara', 'Labuhanbatu Selatan', 'Nias', 'Nias Utara', 'Nias Barat', 'Humbang Hasundutan', 'Pakpak Bharat', 'Padang Lawas', 'Padang Lawas Utara', 'Serdang Bedagai', 'Tapanuli Selatan', 'Tapanuli Tengah', 'Tapanuli Utara'],
                        'Sumatera Barat': ['Padang', 'Bukittinggi', 'Padang Panjang', 'Solok', 'Payakumbuh', 'Pariaman', 'Sawahlunto', 'Padang Pariaman', 'Agam', 'Dharmasraya', 'Limapuluh Kota', 'Mentawai', 'Pasaman', 'Pasaman Barat', 'Pesisir Selatan', 'Solok Selatan', 'Tanah Datar'],
                        'Riau': ['Pekanbaru', 'Dumai', 'Bengkalis', 'Indragiri Hilir', 'Indragiri Hulu', 'Kampar', 'Kuantan Singingi', 'Meranti', 'Pelalawan', 'Siak', 'Rokan Hilir', 'Rokan Hulu', 'Simpang Hilir', 'Tebing Tinggi'],
                        'Jambi': ['Jambi', 'Sungai Penuh', 'Batang Hari', 'Bungo', 'Kerinci', 'Merangin', 'Muaro Jambi', 'Sarolangun', 'Tebo', 'Tanjung Jabung Barat', 'Tanjung Jabung Timur'],
                        'Sumatera Selatan': ['Palembang', 'Prabumulih', 'Pagar Alam', 'Lubuklinggau', 'Banyuasin', 'Empat Lawang', 'Lahat', 'Muara Enim', 'Musi Banyuasin', 'Musi Rawas', 'Musi Rawas Utara', 'Ogan Ilir', 'Ogan Komering Ilir', 'Ogan Komering Ulu', 'Ogan Komering Ulu Selatan', 'Ogan Komering Ulu Timur', 'Penukal Abab Lematang Ilir'],
                        'Bengkulu': ['Bengkulu', 'Curup', 'Kepahiang', 'Bengkulu Selatan', 'Bengkulu Tengah', 'Bengkulu Utara', 'Kaur', 'Lebong', 'Muko Muko', 'Rejang Lebong', 'Seluma'],
                        'Lampung': ['Bandar Lampung', 'Metro', 'Lampung Barat', 'Lampung Selatan', 'Lampung Tengah', 'Lampung Timur', 'Lampung Utara', 'Mesuji', 'Pesawaran', 'Pringsewu', 'Tanggamus', 'Tulang Bawang', 'Tulang Bawang Barat', 'Way Kanan'],
                        'Kepulauan Bangka Belitung': ['Pangkal Pinang', 'Bangka', 'Bangka Barat', 'Bangka Selatan', 'Bangka Tengah', 'Belitung', 'Belitung Timur'],
                        'Kepulauan Riau': ['Tanjung Pinang', 'Batam', 'Anambas', 'Bintan', 'Karimun', 'Kepulauan Anambas', 'Lingga', 'Natuna'],
                        'DKI Jakarta': ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur', 'Kepulauan Seribu'],
                        'Jawa Barat': ['Bandung', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya', 'Bengkulu', 'Bandung Barat', 'Banjir', 'Bekasi', 'Bogor', 'Ciamis', 'Cianjur', 'Cirebon', 'Garut', 'Indramayu', 'Karawang', 'Kuningan', 'Majalengka', 'Pangandaran', 'Purwakarta', 'Subang', 'Sukabumi', 'Sumedang', 'Tasikmalaya'],
                        'Jawa Tengah': ['Semarang', 'Surakarta', 'Salatiga', 'Pekalongan', 'Tegal', 'Banjir', 'Banyumas', 'Batang', 'Blora', 'Boyolali', 'Brebes', 'Cilacap', 'Demak', 'Grobogan', 'Jepara', 'Karanganyar', 'Kebumen', 'Kendal', 'Klaten', 'Kudus', 'Magelang', 'Pati', 'Pekalongan', 'Pemalang', 'Purbalingga', 'Purworejo', 'Rembang', 'Semarang', 'Sragen', 'Sukoharjo', 'Temanggung', 'Wonogiri', 'Wonosobo'],
                        'DI Yogyakarta': ['Yogyakarta', 'Sleman', 'Bantul', 'Gunung Kidul', 'Kulon Progo'],
                        'Jawa Timur': ['Surabaya', 'Madiun', 'Malang', 'Probolinggo', 'Pasuruan', 'Mojokerto', 'Kediri', 'Blitar', 'Lamongan', 'Jember', 'Banyuwangi', 'Batu', 'Bojonegoro', 'Bondowoso', 'Gresik', 'Jombang', 'Kediri', 'Lamongan', 'Lumajang', 'Magetan', 'Mojokerto', 'Nganjuk', 'Ngawi', 'Pacitan', 'Pamekasan', 'Pasuruan', 'Ponorogo', 'Probolinggo', 'Sidoarjo', 'Situbondo', 'Sumenep', 'Trenggalek', 'Tulungagung'],
                        'Banten': ['Serang', 'Tangerang', 'Cilegon', 'Pandeglang', 'Lebak', 'Tangerang Selatan'],
                        'Bali': ['Denpasar', 'Badung', 'Bangli', 'Buleleng', 'Gianyar', 'Jembrana', 'Karangasem', 'Klungkung', 'Tabanan'],
                        'Nusa Tenggara Barat': ['Mataram', 'Bima', 'Dompu', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Lombok Utara', 'Sumbawa', 'Sumbawa Barat'],
                        'Nusa Tenggara Timur': ['Kupang', 'Alor', 'Belu', 'Ende', 'Flores Timur', 'Lembata', 'Manggarai', 'Manggarai Barat', 'Manggarai Timur', 'Nagekeo', 'Ngada', 'Rote Ndao', 'Sabu Raijua', 'Sikka', 'Sumba Barat', 'Sumba Barat Daya', 'Sumba Tengah', 'Sumba Timur', 'Timor Tengah Selatan', 'Timor Tengah Utara'],
                        'Kalimantan Barat': ['Pontianak', 'Singkawang', 'Bengkayang', 'Kapuas Hulu', 'Kayong Utara', 'Ketapang', 'Kubu Raya', 'Landak', 'Melawi', 'Mempawah', 'Sambas', 'Sanggau', 'Sekadau', 'Sintang'],
                        'Kalimantan Tengah': ['Palangka Raya', 'Barito Selatan', 'Barito Timur', 'Barito Utara', 'Gunung Mas', 'Katingan', 'Kapuas', 'Kotawaringin Barat', 'Kotawaringin Timur', 'Lamandau', 'Murung Raya', 'Pulang Pisau', 'Sukamara', 'Seruyan'],
                        'Kalimantan Selatan': ['Banjarmasin', 'Banjarmasin', 'Banyu Mas', 'Hulu Sungai Selatan', 'Hulu Sungai Tengah', 'Hulu Sungai Utara', 'Kotabaru', 'Tabalong', 'Tanah Bumbu', 'Tanah Laut', 'Tapin', 'Barito Kuala', 'OKU', 'OKU Selatan', 'OKU Timur'],
                        'Kalimantan Timur': ['Samarinda', 'Balikpapan', 'Bontang', 'Kutai Kartanegara', 'Kutai Barat', 'Kutai Timur', 'Paser', 'Penajam Paser Utara', 'Mahakam Ulu'],
                        'Kalimantan Utara': ['Tarakan', 'Bulungan', 'Malinau', 'Nunukan', 'Tana Tidung'],
                        'Sulawesi Barat': ['Mamuju', 'Mamasa', 'Mamuju Tengah', 'Mamuju Utara', 'Polewali Mandar'],
                        'Sulawesi Tengah': ['Palu', 'Banggai', 'Banggai Kepulauan', 'Banggai Laut', 'Buol', 'Donggala', 'Morowali', 'Morowali Utara', 'Parigi Moutong', 'Poso', 'Sigi', 'Tojo Una-Una', 'Tolitoli'],
                        'Sulawesi Utara': ['Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Bolaang Mongondow', 'Bolaang Mongondow Selatan', 'Bolaang Mongondow Timur', 'Bolaang Mongondow Utara', 'Kepulauan Sangihe', 'Kepulauan Talaud', 'Minahasa', 'Minahasa Selatan', 'Minahasa Tenggara', 'Minahasa Utara', 'Siau Tagulandang Biaro'],
                        'Sulawesi Selatan': ['Makassar', 'Parepare', 'Palopo', 'Bantaeng', 'Barru', 'Bone', 'Bulukumba', 'Enrekang', 'Gowa', 'Jeneponto', 'Luwu', 'Luwu Utara', 'Luwu Timur', 'Maros', 'Pangkajene', 'Pinrang', 'Sidenreng Rappang', 'Sinjai', 'Soppeng', 'Takalar', 'Tana Toraja', 'Toraja Utara', 'Wajo'],
                        'Sulawesi Tenggara': ['Kendari', 'Baubau', 'Bombana', 'Buton', 'Buton Utara', 'Kolaka', 'Kolaka Utara', 'Konawe', 'Konawe Selatan', 'Konawe Utara', 'Muna', 'Muna Barat', 'Wakatobi'],
                        'Gorontalo': ['Gorontalo', 'Boalemo', 'Bone Bolango', 'Gorontalo Utara', 'Pohuwato'],
                        'Maluku': ['Ambon', 'Tual', 'Buru', 'Buru Selatan', 'Kepulauan Aru', 'Maluku Barat Daya', 'Maluku Tengah', 'Maluku Tenggara', 'Maluku Tenggara Barat', 'Seram Bagian Barat', 'Seram Bagian Timur'],
                        'Maluku Utara': ['Ternate', 'Tidore Kepulauan', 'Halmahera Barat', 'Halmahera Selatan', 'Halmahera Tengah', 'Halmahera Timur', 'Halmahera Utara', 'Kepulauan Sula', 'Pulau Morotai'],
                        'Papua': ['Jayapura', 'Sorong', 'Biak Numfor', 'Boven Digoel', 'Deiyai', 'Dogiyai', 'Intan Jaya', 'Jayapura', 'Keerom', 'Kepulauan Yapen', 'Lanny Jaya', 'Mamberamo Raya', 'Mamberamo Tengah', 'Merauke', 'Mimika', 'Nabire', 'Nduga', 'Paniai', 'Pegunungan Arfak', 'Pegunungan Bintang', 'Puncak', 'Puncak Jaya', 'Sarmi', 'Supiori', 'Tolikara', 'Waropen', 'Yahukimo', 'Yalimo'],
                        'Papua Barat': ['Manokwari', 'Fakfak', 'Kaimana', 'Manokwari', 'Manokwari Selatan', 'Maybrat', 'Pegunungan Arfak', 'Raja Ampat', 'Sorong', 'Sorong Selatan', 'Tambrauw', 'Teluk Bintuni', 'Teluk Wondama'],
                        'Papua Selatan': ['Merauke', 'Mappi', 'Asmat', 'Boven Digoel'],
                        'Papua Tengah': ['Nabire', 'Intan Jaya', 'Paniai', 'Dogiyai', 'Migoid'],
                        'Papua Pegunungan': ['Jayawijaya', 'Lanny Jaya', 'Nduga', 'Pegunungan Bintang', 'Tolikara', 'Yahukimo']
                    };

                    const kecamatanDataReg = {
                        'Bandung': ['Andir', 'Antapani', 'Arcamanik', 'Astana Anyar', 'Babakan Ciparay', 'Bandung Kidul', 'Bandung Kulon', 'Bandung Wetan', 'Batununggal', 'Cibeunying Kaler', 'Cibeunying Kidul', 'Cibiru', 'Cicadas', 'Cicendo', 'Cidadap', 'Coblong', 'Mandalajati', 'Panyileukan', 'Rancasari', 'Regol', 'Sukajadi', 'Sukasari', 'Sumur Bandung', 'Ujung Berung'],
                        'Jakarta Selatan': ['Cilandak', 'Jagakarsa', 'Kebayoran Baru', 'Kebayoran Lama', 'Mampang Prapatan', 'Pancoran', 'Pasar Minggu', 'Pesanggrahan', 'Setiabudi', 'Tebet'],
                        'Jakarta Pusat': ['Cempaka Putih', 'Gambir', 'Johar Baru', 'Kemayoran', 'Menteng', 'Sawah Besar', 'Senen', 'Tanah Abang'],
                        'Jakarta Utara': ['Cilincing', 'Kelapa Gading', 'Koja', 'Pademangan', 'Penjaringan', 'Tanjung Priok'],
                        'Jakarta Barat': ['Cengkareng', 'Grogol Petamburan', 'Kalideres', 'Kebon Jeruk', 'Kembangan', 'Palmerah', 'Taman Sari', 'Tambora'],
                        'Jakarta Timur': ['Cipayung', 'Ciracas', 'Duren Sawit', 'Jatinegara', 'Kramat Jati', 'Makasar', 'Matraman', 'Pasar Rebo', 'Pulo Gadung'],
                        'Surabaya': ['Asemrowo', 'Benowo', 'Bubutan', 'Bulak', 'Dukuh Pakis', 'Gayungan', 'Genteng', 'Gubeng', 'Gunung Anyar', 'Kenjeran', 'Mulyorejo', 'Pabean Cantikan', 'Pakal', 'Rungkut', 'Sambikerep', 'Sawahan', 'Simo', 'Sukolilo', 'Sukomanunggal', 'Tandes', 'Tegalsari', 'Tenggilis Mejoyo', 'Wiyung', 'Wonocolo', 'Wonokromo'],
                        'Medan': ['Amplas', 'Banding', 'Belawan', 'Bintang', 'Deli Tua', 'Denai', 'Hamber', 'Kota Matsum', 'Labuhan Deli', 'Medan Amplas', 'Medan Barat', 'Medan Baru', 'Medan Belawan', 'Medan Deli', 'Medan Hora', 'Medan Johor', 'Medan Kota', 'Medan Maimun', 'Medan Marelan', 'Medan Polonia', 'Medan Selayang', 'Medan Sunggal', 'Medan Timur', 'Medan Utara'],
                        'Semarang': ['Banyumanik', 'Candisari', 'Gajahmungkur', 'Gayamsari', 'Genuk', 'Gunungpati', 'Mijen', 'Ngaliyan', 'Pedurungan', 'Semarang Barat', 'Semarang Selatan', 'Semarang Tengah', 'Semarang Timur', 'Semarang Utara', 'Tembalang', 'Tugu'],
                        'Yogyakarta': ['Danurejan', 'Gedong Tengen', 'Gondokusuman', 'Gondomanan', 'Jetis', 'Kotagede', 'Kraton', 'Mantrijeron', 'Mergangsan', 'Ngampilan', 'Pakualaman', 'Panjatan', 'Prambanan', 'Sleman', 'Tamansari', 'Tegalrejo', 'Umbulharjo', 'Wirobrajan'],
                        'Malang': ['Blimbing', 'Kedungkandang', 'Klojen', 'Lowokwaru', 'Sukun'],
                        'Tangerang': ['Batuceper', 'Benda', 'Cibodas', 'Cipondoh', 'Jatiuwung', 'Karang Tengah', 'Karawaci', 'Larangan', 'Neglasari', 'Periuk', 'Pinang', 'Tangerang'],
                        'Bekasi': ['Bantar Gebang', 'Bekasi Barat', 'Bekasi Selatan', 'Bekasi Timur', 'Bekasi Utara', 'Bongas', 'Cibitung', 'Cikarang', 'Cikarang Barat', 'Cikarang Selatan', 'Cikarang Timur', 'Cikarang Utara'],
                        'Bogor': ['Bogor Barat', 'Bogor Selatan', 'Bogor Tengah', 'Bogor Timur', 'Bogor Utara', 'Tanah Sereal'],
                        'Denpasar': ['Denpasar Barat', 'Denpasar Selatan', 'Denpasar Timur', 'Denpasar Utara'],
                        'Makassar': ['Biringkanaya', 'Bontoala', 'Makassar', 'Mamajang', 'Manggala', 'Mariso', 'Panakkukang', 'Rappocini', 'Sangur', 'Tallo', 'Tamalanrea', 'Tamalate', 'Ujung Pandang', 'Ujung Tanah', 'Wajo']
                    };

                    // Province change handler
                    document.getElementById('provinsi_reg').addEventListener('change', function() {
                        const selectedProvinsi = this.value;
                        const kotaSelect = document.getElementById('kota_reg');
                        const kecamatanSelect = document.getElementById('kecamatan_reg');
                        
                        kotaSelect.innerHTML = '<option value="">Pilih Kabupaten/Kota</option>';
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        document.getElementById('kelurahan_reg').value = '';
                        
                        if (kabupatenKotaDataReg[selectedProvinsi]) {
                            kabupatenKotaDataReg[selectedProvinsi].forEach(function(kota) {
                                const option = document.createElement('option');
                                option.value = kota;
                                option.textContent = kota;
                                kotaSelect.appendChild(option);
                            });
                        }
                    });

                    // Kota/Kabupaten change handler
                    document.getElementById('kota_reg').addEventListener('change', function() {
                        const selectedKota = this.value;
                        const kecamatanSelect = document.getElementById('kecamatan_reg');
                        
                        kecamatanSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        document.getElementById('kelurahan_reg').value = '';
                        
                        if (kecamatanDataReg[selectedKota]) {
                            kecamatanDataReg[selectedKota].forEach(function(kecamatan) {
                                const option = document.createElement('option');
                                option.value = kecamatan;
                                option.textContent = kecamatan;
                                kecamatanSelect.appendChild(option);
                            });
                        } else {
                            // Leave dropdown empty - user can type manually
                        }
                    });

                    // Kecamatan change handler
                    document.getElementById('kecamatan_reg').addEventListener('change', function() {
                        document.getElementById('kelurahan_reg').value = '';
                    });
                    </script>
                </div>

                <!-- Upload Dokumen (Staff Only) -->
                <div class="section-title" id="upload-section-title" style="display:none;">
                    <i class="fas fa-camera"></i> Upload Dokumen
                </div>

                <div class="form-grid" id="upload-fields" style="display:none;">
                    <div class="form-group">
                        <label>Foto KTP <span class="required">*</span></label>
                        <div class="file-upload" onclick="document.getElementById('foto_ktp').click()">
                            <i class="fas fa-id-card"></i>
                            <p>Klik untuk upload foto KTP</p>
                            <p style="font-size: 0.8rem; margin-top: 0.5rem;">Format: JPG, PNG (Max 2MB)</p>
                            <input type="file" id="foto_ktp" name="foto_ktp" accept="image/*" onchange="previewFile(this, 'preview_ktp')">
                        </div>
                        <div class="file-preview" id="preview_ktp"></div>
                    </div>

                    <div class="form-group">
                        <label>Foto Profil</label>
                        <div class="file-upload" onclick="document.getElementById('foto_profil').click()">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Klik untuk upload foto profil</p>
                            <p style="font-size: 0.8rem; margin-top: 0.5rem;">Format: JPG, PNG (Max 2MB)</p>
                            <input type="file" id="foto_profil" name="foto_profil" accept="image/*" onchange="previewFile(this, 'preview_profil')">
                        </div>
                        <div class="file-preview" id="preview_profil"></div>
                    </div>
                </div>

                <!-- Alasan Pendaftaran -->
                <div class="section-title">
                    <i class="fas fa-pen"></i> Alasan Pendaftaran
                </div>

                <div class="form-group full-width">
                    <label>Alasan Mendaftarkan Diri <span class="required">*</span></label>
                    <textarea name="alasan_pendaftaran" class="form-control" placeholder="Jelaskan mengapa Anda ingin bergabung dengan sistem ini..." required></textarea>
                </div>

                <button type="submit" class="btn-submit" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> <span id="submitText">Daftar Sekarang (Auto-Approve)</span>
                </button>
            </form>

            <div id="alertMessage"></div>
        </div>

        <div class="footer">
            <p>Sudah punya akun? <a href="{{ url('/login') }}">Login di sini</a></p>
            <p style="margin-top: 0.5rem;"><a href="{{ url('/') }}">← Kembali ke Halaman Utama</a></p>
        </div>
    </div>

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
        let currentMode = 'umum';

        function previewFile(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        }

        function selectMode(mode) {
            currentMode = mode;
            document.getElementById('jenis_registrasi').value = mode;
            
            document.getElementById('mode-umum').classList.toggle('active', mode === 'umum');
            document.getElementById('mode-staff').classList.toggle('active', mode === 'staff');
            
            const staffFields = document.getElementById('staff-fields');
            const staffSection = document.getElementById('staff-section-title');
            const uploadFields = document.getElementById('upload-fields');
            const uploadSection = document.getElementById('upload-section-title');
            const submitText = document.getElementById('submitText');
            
            if (mode === 'staff') {
                staffFields.style.display = 'grid';
                staffSection.style.display = 'flex';
                uploadFields.style.display = 'grid';
                uploadSection.style.display = 'flex';
                submitText.textContent = 'Kirim Permintaan (Butuh Persetujuan Admin)';
                
                // Add required attributes
                addRequiredAttributes();
            } else {
                staffFields.style.display = 'none';
                staffSection.style.display = 'none';
                uploadFields.style.display = 'none';
                uploadSection.style.display = 'none';
                submitText.textContent = 'Daftar Sekarang (Auto-Approve)';
                
                // Remove required attributes
                removeRequiredAttributes();
            }
        }

        function addRequiredAttributes() {
            const fields = ['nip', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'jabatan', 'instansi', 'unit_kerja'];
            fields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (el) el.setAttribute('required', 'required');
            });
            const fotoKtp = document.getElementById('foto_ktp');
            if (fotoKtp) fotoKtp.setAttribute('required', 'required');
        }

        function removeRequiredAttributes() {
            const fields = ['nip', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'jabatan', 'instansi', 'unit_kerja'];
            fields.forEach(name => {
                const el = document.querySelector(`[name="${name}"]`);
                if (el) el.removeAttribute('required');
            });
            const fotoKtp = document.getElementById('foto_ktp');
            if (fotoKtp) fotoKtp.removeAttribute('required');
        }

        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('submitBtn');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

            try {
                const response = await fetch('{{ url("/register-request") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CSRF_TOKEN
                    },
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    if (result.auto_login && result.redirect) {
                        // Auto-login mode (umum) - redirect to dashboard
                        showToast(result.message, 'success');
                        setTimeout(() => {
                            window.location.href = result.redirect;
                        }, 1500);
                    } else {
                        // Staff mode - show finalization message, no redirect
                        showToast(result.message, 'success');
                        // Reset form
                        document.getElementById('registerForm').reset();
                        // Reset mode to umum
                        selectMode('umum');
                    }
                } else {
                    showToast(result.message || 'Terjadi kesalahan', 'error');
                    
                    // Display field-specific errors
                    if (result.errors) {
                        displayValidationErrors(result.errors);
                    }
                    
                    // If validation fails, try to switch to umum mode automatically
                    // This helps users who fail validation in staff mode
                    if (currentMode === 'staff' && result.errors) {
                        showToast('Validasi gagal di mode Staff. Silakan coba di mode Umum.', 'warning');
                        setTimeout(() => {
                            selectMode('umum');
                        }, 1500);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                // Jangan tampilkan error popup jika success sudah ditampilkan
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> ' + 
                    (currentMode === 'staff' ? 'Kirim Permintaan (Butuh Persetujuan Admin)' : 'Daftar Sekarang (Auto-Approve)');
            }
        });

        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'exclamation-circle'}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);
        }
        
        // Function to display validation errors on form fields
        function displayValidationErrors(errors) {
            // First, clear all previous error messages
            document.querySelectorAll('.form-control.error').forEach(el => {
                el.classList.remove('error');
            });
            document.querySelectorAll('.error-message').forEach(el => {
                el.remove();
            });
            
            // Add error class and message to fields with errors
            for (const [field, messages] of Object.entries(errors)) {
                const fieldEl = document.querySelector(`[name="${field}"]`);
                if (fieldEl) {
                    fieldEl.classList.add('error');
                    // Add red border
                    fieldEl.style.borderColor = '#ef4444';
                    
                    // Create error message element
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message';
                    errorDiv.style.color = '#ef4444';
                    errorDiv.style.fontSize = '0.85rem';
                    errorDiv.style.marginTop = '0.25rem';
                    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${Array.isArray(messages) ? messages[0] : messages}`;
                    
                    // Insert after the field
                    fieldEl.parentElement.appendChild(errorDiv);
                }
            }
            
            // Scroll to first error
            const firstError = document.querySelector('.form-control.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
        
        // Clear error styling when user starts typing
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('form-control')) {
                e.target.classList.remove('error');
                e.target.style.borderColor = '';
                const errorMsg = e.target.parentElement.querySelector('.error-message');
                if (errorMsg) errorMsg.remove();
            }
        });
    </script>
</body>
</html>
