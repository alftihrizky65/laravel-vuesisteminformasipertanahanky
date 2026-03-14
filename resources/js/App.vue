<template>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
      <div class="floating-shape absolute top-20 left-10 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
      <div class="floating-shape absolute top-40 right-10 w-72 h-72 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
      <div class="floating-shape absolute -bottom-8 left-20 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>

    <!-- Navigation -->
    <nav class="relative z-50 bg-white/80 backdrop-blur-lg shadow-lg sticky top-0 border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center group cursor-pointer">
              <div class="relative">
                <img src="/sbadmin/img/Logo_BPN-KemenATR_(2017).png" alt="ATR/BPN" class="h-12 w-auto transition-transform duration-300 group-hover:scale-110">
                <div class="absolute inset-0 bg-blue-400 rounded-full blur-lg opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
              </div>
              <span class="ml-4 text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">GEOTERRAID</span>
            </div>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-8">
            <!-- Tentang Dropdown -->
            <div class="relative">
              <button
                @click="toggleTentangDropdown"
                class="flex items-center text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-300"
              >
                Tentang
                <i class="fas fa-chevron-down ml-2 text-sm transition-transform duration-300" :class="{ 'rotate-180': showTentangDropdown }"></i>
              </button>
              <transition name="dropdown">
                <div
                  v-if="showTentangDropdown"
                  class="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-100 py-4 z-50"
                  @click.stop
                >
                  <button @click="scrollToAbout(); showTentangDropdown = false" type="button" class="block w-full text-left px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300">
                    <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                    Tentang GEOTERRAID
                  </button>
                  <button @click="scrollToFeatures(); showTentangDropdown = false" type="button" class="block w-full text-left px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300">
                    <i class="fas fa-star mr-3 text-yellow-500"></i>
                    Fitur Utama
                  </button>
                  <button @click="scrollToFooter(); showTentangDropdown = false" type="button" class="block w-full text-left px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300">
                    <i class="fas fa-envelope mr-3 text-green-500"></i>
                    Kontak Kami
                  </button>
                </div>
              </transition>
            </div>

            <!-- Informasi Publik Dropdown -->
            <div class="relative">
              <button
                @click="toggleInformasiDropdown"
                class="flex items-center text-gray-700 hover:text-blue-600 font-semibold transition-colors duration-300"
              >
                Informasi Publik
                <i class="fas fa-chevron-down ml-2 text-sm transition-transform duration-300" :class="{ 'rotate-180': showInformasiDropdown }"></i>
              </button>
              <transition name="dropdown">
                <div
                  v-if="showInformasiDropdown"
                  class="absolute top-full left-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 py-4 z-50"
                  @click.stop
                >
                  <button @click="openInfoModal('ppid'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-file-alt mr-3 text-blue-500"></i>
                    PPID (Pejabat Pengelola Informasi dan Dokumentasi)
                  </button>
                  <button @click="openInfoModal('peraturan'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-gavel mr-3 text-red-500"></i>
                    Peraturan Perundang-undangan
                  </button>
                  <button @click="openInfoModal('layanan'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-info-circle mr-3 text-green-500"></i>
                    Layanan Informasi Publik
                  </button>
                  <button @click="openInfoModal('transparansi'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-eye mr-3 text-purple-500"></i>
                    Transparansi Anggaran
                  </button>
                  <button @click="openInfoModal('laporan'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-chart-line mr-3 text-orange-500"></i>
                    Laporan Tahunan
                  </button>
                  <button @click="openInfoModal('profil'); showInformasiDropdown = false" class="block px-6 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                    <i class="fas fa-building mr-3 text-indigo-500"></i>
                    Profil Kementerian ATR/BPN
                  </button>
                </div>
              </transition>
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button
              @click="toggleMobileMenu"
              class="text-gray-700 hover:text-blue-600 transition-colors duration-300 p-2"
            >
              <i class="fas fa-bars text-2xl"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile Menu Overlay -->
      <transition name="mobile-menu">
        <div
          v-if="showMobileMenu"
          class="md:hidden fixed inset-0 z-40 bg-black/50 backdrop-blur-sm"
          @click="showMobileMenu = false"
        >
          <div class="fixed top-20 left-0 right-0 bg-white shadow-2xl border-t border-gray-100">
            <div class="px-4 py-6 space-y-4" @click.stop>
              <!-- Tentang Section -->
              <div>
                <button
                  @click.stop="toggleTentangDropdown"
                  class="flex items-center justify-between w-full text-left text-gray-700 hover:text-blue-600 font-semibold py-3 transition-colors duration-300"
                >
                  Tentang
                  <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{ 'rotate-180': showTentangDropdown }"></i>
                </button>
                <transition name="mobile-dropdown">
                  <div v-if="showTentangDropdown" class="ml-4 mt-2 space-y-2">
                    <a href="#about" @click.prevent="scrollToAbout; showMobileMenu = false; showTentangDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300">
                      <i class="fas fa-info-circle mr-2"></i>Tentang Kami
                    </a>
                    <a href="#features" @click.prevent="scrollToFeatures; showMobileMenu = false; showTentangDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300">
                      <i class="fas fa-star mr-2"></i>Fitur Utama
                    </a>
                    <a href="#contact" @click.prevent="scrollToFooter; showMobileMenu = false; showTentangDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300">
                      <i class="fas fa-envelope mr-2"></i>Kontak Kami
                    </a>
                  </div>
                </transition>
              </div>

              <!-- Informasi Publik Section -->
              <div>
                <button
                  @click.stop="toggleInformasiDropdown"
                  class="flex items-center justify-between w-full text-left text-gray-700 hover:text-blue-600 font-semibold py-3 transition-colors duration-300"
                >
                  Informasi Publik
                  <i class="fas fa-chevron-down text-sm transition-transform duration-300" :class="{ 'rotate-180': showInformasiDropdown }"></i>
                </button>
                <transition name="mobile-dropdown">
                  <div v-if="showInformasiDropdown" class="ml-4 mt-2 space-y-2">
                    <button @click="openInfoModal('ppid'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-file-alt mr-2"></i>PPID
                    </button>
                    <button @click="openInfoModal('peraturan'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-gavel mr-2"></i>Peraturan
                    </button>
                    <button @click="openInfoModal('layanan'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-info-circle mr-2"></i>Layanan Informasi
                    </button>
                    <button @click="openInfoModal('transparansi'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-eye mr-2"></i>Transparansi
                    </button>
                    <button @click="openInfoModal('laporan'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-chart-line mr-2"></i>Laporan Tahunan
                    </button>
                    <button @click="openInfoModal('profil'); showMobileMenu = false; showInformasiDropdown = false" class="block py-2 text-gray-600 hover:text-blue-600 transition-colors duration-300 text-left w-full">
                      <i class="fas fa-building mr-2"></i>Profil Kementerian
                    </button>
                  </div>
                </transition>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </nav>

    <!-- Hero Section -->
    <div class="relative z-10 pt-20 pb-32 px-4 sm:px-6 lg:px-8">
      <video class="absolute inset-0 w-full h-full object-cover object-bottom z-0" autoplay loop muted>
        <source src="/sbadmin/video/vid pertanahan.mp4" type="video/mp4">
      </video>
      <!-- Dark overlay for better text readability -->
      <div class="absolute inset-0 bg-black/40 z-10"></div>
      <div class="relative z-20 max-w-7xl mx-auto">
        <div class="text-center">
          <!-- Badge -->
          <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 font-semibold text-sm mb-8 animate-fade-in-down border border-blue-200">
            <i class="fas fa-sparkles mr-2"></i>
            Sistem Informasi Pertanahan Terdepan
          </div>

          <!-- Main Heading -->
          <h1 class="text-5xl md:text-7xl font-black mb-6">
            <span class="text-white drop-shadow-lg">Selamat Datang di</span>
            <span class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 animate-gradient drop-shadow-lg">
              GEOTERRAID
            </span>
          </h1>

          <!-- Subtitle -->
          <p class="text-xl md:text-2xl text-white/90 mb-12 max-w-4xl mx-auto leading-relaxed animate-fade-in font-light drop-shadow-lg">
            Sistem Informasi Pertanahan Terintegrasi Kementerian Agraria dan Tata Ruang / Badan Pertanahan Nasional
          </p>
          
          <!-- CTA Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up animation-delay-300">
            <a href="http://127.0.0.1:8000/login" class="group relative px-8 py-4 font-bold text-white overflow-hidden rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
              <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
              <span class="relative flex items-center text-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Masuk ke Dashboard
                <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </span>
            </a>
            <a href="https://www.atrbpn.go.id/" target="_blank" class="group px-8 py-4 font-bold text-gray-700 bg-white border-2 border-gray-300 rounded-2xl text-lg transition-all duration-300 hover:scale-105 hover:shadow-xl hover:border-blue-400 hover:text-blue-600">
              <span class="flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Pelajari Lebih Lanjut
                <i class="fas fa-external-link-alt ml-2"></i>
              </span>
            </a>
          </div>
        </div>
        
        <!-- Hero Image/Illustration Area -->
        <div class="mt-16 relative">
          <div class="relative bg-white/50 backdrop-blur-sm rounded-3xl p-8 shadow-2xl border border-white/50">
            <div class="grid grid-cols-3 gap-6">
              <div class="text-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl hover:scale-105 transition-transform duration-300">
                <i class="fas fa-map-marked-alt text-5xl text-blue-600 mb-3"></i>
                <div class="text-3xl font-black text-gray-800">10K+</div>
                <div class="text-sm text-gray-600 font-medium">Data Terintegrasi</div>
              </div>
              <div class="text-center p-6 bg-gradient-to-br from-indigo-50 to-purple-50 rounded-2xl hover:scale-105 transition-transform duration-300">
                <i class="fas fa-users text-5xl text-indigo-600 mb-3"></i>
                <div class="text-3xl font-black text-gray-800">500+</div>
                <div class="text-sm text-gray-600 font-medium">Pengguna Aktif</div>
              </div>
              <div class="text-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl hover:scale-105 transition-transform duration-300">
                <i class="fas fa-shield-alt text-5xl text-purple-600 mb-3"></i>
                <div class="text-3xl font-black text-gray-800">99.9%</div>
                <div class="text-sm text-gray-600 font-medium">Keamanan Data</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="relative z-10 py-24 bg-white/50 backdrop-blur-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
          <div class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 font-semibold text-sm mb-4">
            <i class="fas fa-star mr-2"></i>Fitur Unggulan
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
            Fitur <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Utama Sistem</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            GEOTERRAID menyediakan berbagai fitur canggih untuk pengelolaan data pertanahan yang terintegrasi
          </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Feature Cards -->
          <div class="feature-card group relative bg-gradient-to-br from-white to-blue-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-blue-200 cursor-pointer overflow-hidden" @click="openPetaBandung()">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-map-marked-alt text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-blue-600 transition-colors duration-300">Peta Interaktif</h3>
              <p class="text-gray-600 leading-relaxed">Visualisasi data pertanahan dengan peta digital yang akurat dan mudah digunakan</p>
              <div class="mt-4 flex items-center text-blue-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Klik untuk masuk <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>

          <div class="feature-card group relative bg-gradient-to-br from-white to-green-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-green-200 cursor-pointer overflow-hidden" @click="redirectToLogin()">
            <div class="absolute inset-0 bg-gradient-to-br from-green-600 to-emerald-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-database text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-green-600 transition-colors duration-300">Manajemen Data</h3>
              <p class="text-gray-600 leading-relaxed">Pengelolaan data penduduk, polygon, polyline, dan marker dengan sistem yang terstruktur</p>
              <div class="mt-4 flex items-center text-green-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Klik untuk masuk <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>

          <div class="feature-card group relative bg-gradient-to-br from-white to-purple-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-purple-200 cursor-pointer overflow-hidden" @click="openLaporanAnalitik()">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-600 to-pink-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-chart-bar text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-purple-600 transition-colors duration-300">Laporan & Analitik</h3>
              <p class="text-gray-600 leading-relaxed">Generate laporan komprehensif dan analisis data untuk keperluan pengambilan keputusan</p>
              <div class="mt-4 flex items-center text-purple-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Klik untuk masuk <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>

          <div class="feature-card group relative bg-gradient-to-br from-white to-red-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-red-200 cursor-pointer overflow-hidden" @click="openManajemenPengguna()">
            <div class="absolute inset-0 bg-gradient-to-br from-red-600 to-orange-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-red-500 to-orange-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-users text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-red-600 transition-colors duration-300">Manajemen Pengguna</h3>
              <p class="text-gray-600 leading-relaxed">Sistem manajemen pengguna dengan role-based access control untuk keamanan data</p>
              <div class="mt-4 flex items-center text-red-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Pelajari lebih lanjut <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>

          <div class="feature-card group relative bg-gradient-to-br from-white to-yellow-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-yellow-200 cursor-pointer overflow-hidden" @click="openNotifikasiRealTime()">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-600 to-amber-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-yellow-500 to-amber-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-bell text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-yellow-600 transition-colors duration-300">Notifikasi Real-time</h3>
              <p class="text-gray-600 leading-relaxed">Sistem notifikasi real-time untuk update data dan informasi penting</p>
              <div class="mt-4 flex items-center text-yellow-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Pelajari lebih lanjut <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>

          <div class="feature-card group relative bg-gradient-to-br from-white to-indigo-50 p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-gray-100 hover:border-indigo-200 cursor-pointer overflow-hidden" @click="openChatbotInfo()">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-blue-600 opacity-0 group-hover:opacity-5 transition-opacity duration-500"></div>
            <div class="relative">
              <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-2xl flex items-center justify-center mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                <i class="fas fa-robot text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-black text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-300">Chatbot AI</h3>
              <p class="text-gray-600 leading-relaxed">Asisten virtual cerdas untuk membantu pengguna dalam mengakses informasi</p>
              <div class="mt-4 flex items-center text-indigo-600 font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                Pelajari lebih lanjut <i class="fas fa-arrow-right ml-2 transform group-hover:translate-x-2 transition-transform duration-300"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- About Section -->
    <div id="about" class="relative z-10 py-24 bg-gradient-to-br from-gray-50 to-blue-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-20">
          <div class="inline-block px-4 py-2 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-700 font-semibold text-sm mb-4">
            <i class="fas fa-info-circle mr-2"></i>Tentang Kami
          </div>
          <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">
            Tentang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">GEOTERRAID</span>
          </h2>
          <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
            Sistem informasi pertanahan modern yang dikembangkan untuk mendukung tugas-tugas Kementerian ATR/BPN
          </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div class="space-y-8">
            <div>
              <h3 class="text-3xl font-black text-gray-900 mb-6">Teknologi Modern untuk Pengelolaan Pertanahan</h3>
              <p class="text-lg text-gray-600 leading-relaxed">
                GEOTERRAID menggunakan teknologi terkini untuk memastikan pengelolaan data pertanahan yang akurat, efisien, dan dapat diakses oleh pihak yang berwenang.
              </p>
            </div>
            
            <div class="space-y-4">
              <div class="flex items-start group cursor-pointer">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                  <i class="fas fa-check text-white text-xl"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-green-600 transition-colors duration-300">Sistem Keamanan Berlapis</h4>
                  <p class="text-gray-600">Autentikasi ganda dan enkripsi data tingkat enterprise</p>
                </div>
              </div>
              
              <div class="flex items-start group cursor-pointer">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mr-4 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                  <i class="fas fa-check text-white text-xl"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-blue-600 transition-colors duration-300">Interface User-Friendly</h4>
                  <p class="text-gray-600">Desain responsif dan intuitif untuk semua perangkat</p>
                </div>
              </div>
              
              <div class="flex items-start group cursor-pointer">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                  <i class="fas fa-check text-white text-xl"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-purple-600 transition-colors duration-300">Integrasi Data Geografis</h4>
                  <p class="text-gray-600">Kompatibel dengan berbagai format data GIS</p>
                </div>
              </div>
              
              <div class="flex items-start group cursor-pointer">
                <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center mr-4 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                  <i class="fas fa-check text-white text-xl"></i>
                </div>
                <div>
                  <h4 class="font-bold text-gray-900 text-lg mb-1 group-hover:text-orange-600 transition-colors duration-300">Backup Otomatis</h4>
                  <p class="text-gray-600">Sistem backup dan recovery data yang handal</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/20 to-indigo-400/20 rounded-3xl blur-3xl"></div>
            <div class="relative bg-white/80 backdrop-blur-sm rounded-3xl p-12 shadow-2xl border border-white/50">
              <div class="text-center">
                <div class="relative inline-block">
                  <img src="/sbadmin/img/Logo_BPN-KemenATR_(2017).png" alt="ATR/BPN Logo" class="mx-auto h-64 w-auto mb-8 transform hover:scale-105 transition-transform duration-500">
                  <div class="absolute inset-0 bg-blue-400 rounded-full blur-3xl opacity-0 hover:opacity-20 transition-opacity duration-500"></div>
                </div>
                <div class="relative">
                  <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600 opacity-5 rounded-2xl"></div>
                  <p class="relative text-gray-700 italic text-xl font-medium leading-relaxed p-6 border-l-4 border-blue-600">
                    "Melayani dengan Integritas, Profesionalitas, dan Inovasi"
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- News Section -->
    <div id="news" class="relative z-10 py-24 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>
      <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
      </div>
      <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-block px-4 py-2 rounded-full bg-white/20 backdrop-blur-sm text-white font-semibold text-sm mb-6">
          <i class="fas fa-rocket mr-2"></i>Mulai Sekarang
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
          Siap Menggunakan GEOTERRAID?
        </h2>
        <p class="text-xl md:text-2xl text-white/90 mb-10 leading-relaxed max-w-3xl mx-auto">
          Masuk ke dashboard untuk mengakses semua fitur sistem informasi pertanahan
        </p>
        <a href="http://127.0.0.1:8000/login" class="group inline-flex items-center px-10 py-5 bg-white text-blue-600 font-black rounded-2xl text-lg transition-all duration-300 hover:scale-105 hover:shadow-2xl">
          <i class="fas fa-sign-in-alt mr-3 text-xl"></i>
          Masuk Sekarang
          <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform duration-300"></i>
        </a>
      </div>
    </div>

    <!-- Footer -->
    <footer id="contact" class="relative z-10 bg-gray-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
          <div>
            <div class="flex items-center mb-6">
              <img src="/sbadmin/img/Logo_BPN-KemenATR_(2017).png" alt="ATR/BPN" class="h-12 w-auto mr-4">
              <span class="text-2xl font-black">GEOTERRAID</span>
            </div>
            <p class="text-gray-400 leading-relaxed">
              Sistem Informasi Pertanahan Terintegrasi Kementerian ATR/BPN
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold mb-6">Kontak</h3>
            <p class="text-gray-400 leading-relaxed">
              Kementerian Agraria dan Tata Ruang<br>
              Badan Pertanahan Nasional<br>
              Republik Indonesia
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold mb-6">Pengembang</h3>
            <p class="text-gray-400 leading-relaxed">
              Developed by Kementerian ATR/BPN<br>
              © 2025 All Rights Reserved
            </p>
          </div>
        </div>
        <div class="border-t border-gray-800 pt-8 text-center">
          <p class="text-gray-500 leading-relaxed">
            Sistem ini hanya diperuntukkan bagi petugas resmi Kementerian ATR/BPN dan pihak yang telah mendapat izin akses.
          </p>
        </div>
      </div>
    </footer>

    <!-- Floating AI Assistant Button -->
    <button
      @click="showModal = true"
      class="fixed bottom-8 right-8 z-50 w-20 h-20 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-full shadow-2xl flex items-center justify-center text-white hover:scale-110 transition-all duration-300 group animate-pulse-slow"
    >
      <i class="fas fa-robot text-3xl group-hover:scale-110 transition-transform duration-300"></i>
      <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      <div class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-xs font-bold animate-bounce">
        AI
      </div>
    </button>

    <!-- AI Assistant Modal -->
    <transition name="modal">
      <div
        v-if="showModal"
        class="fixed inset-0 z-[60] flex items-center justify-center px-4"
      >
        <div
          class="absolute inset-0 bg-black/70 backdrop-blur-sm"
          @click="showModal = false"
        ></div>

        <div class="relative bg-gradient-to-br from-white to-blue-50 rounded-3xl shadow-2xl max-w-lg w-full p-10 transform transition-all duration-500 border-2 border-white/50">
          <button
            @click="showModal = false"
            class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-300"
          >
            <i class="fas fa-times text-2xl"></i>
          </button>

          <div class="text-center">
            <div class="relative inline-block mb-8">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full blur-2xl opacity-50 animate-pulse"></div>
              <div class="relative w-32 h-32 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-full flex items-center justify-center text-white shadow-2xl">
                <i class="fas fa-robot text-6xl animate-bounce-slow"></i>
              </div>
            </div>

            <h2 class="text-4xl font-black text-gray-900 mb-4">
              AI <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">CS/Assistant</span>
            </h2>
            <p class="text-lg text-gray-600 mb-10 leading-relaxed">
              Dapatkan bantuan instan untuk semua pertanyaan Anda tentang sistem pertanahan
            </p>

            <a
              href="/chatbot"
              class="group inline-flex items-center px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 text-lg"
            >
              <i class="fas fa-comments mr-3 text-xl"></i>
              Coba Sekarang
              <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform duration-300"></i>
            </a>

            <div class="mt-8 grid grid-cols-3 gap-4 text-sm">
              <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                <i class="fas fa-clock text-blue-600 text-2xl mb-2"></i>
                <div class="font-bold text-gray-900">24/7</div>
                <div class="text-gray-500 text-xs">Tersedia</div>
              </div>
              <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                <i class="fas fa-bolt text-yellow-600 text-2xl mb-2"></i>
                <div class="font-bold text-gray-900">Instan</div>
                <div class="text-gray-500 text-xs">Respons</div>
              </div>
              <div class="text-center p-3 bg-white rounded-xl shadow-sm">
                <i class="fas fa-brain text-purple-600 text-2xl mb-2"></i>
                <div class="font-bold text-gray-900">Cerdas</div>
                <div class="text-gray-500 text-xs">AI Powered</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Info Modal -->
    <transition name="modal">
      <div
        v-if="showInfoModal"
        class="fixed inset-0 z-[60] flex items-center justify-center px-4"
      >
        <div
          class="absolute inset-0 bg-black/70 backdrop-blur-sm"
          @click="showInfoModal = false"
        ></div>

        <div class="relative bg-gradient-to-br from-white to-blue-50 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto p-10 transform transition-all duration-500 border-2 border-white/50">
          <button
            @click="showInfoModal = false"
            class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-full transition-all duration-300"
          >
            <i class="fas fa-times text-2xl"></i>
          </button>

          <div v-if="currentInfoType && infoContent[currentInfoType]">
            <div class="text-center mb-8">
              <div class="relative inline-block mb-4">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full blur-2xl opacity-50 animate-pulse"></div>
                <div :class="`relative w-20 h-20 bg-gradient-to-r from-${infoContent[currentInfoType].color}-500 to-${infoContent[currentInfoType].color}-600 rounded-full flex items-center justify-center text-white shadow-2xl`">
                  <i :class="`${infoContent[currentInfoType].icon} text-3xl`"></i>
                </div>
              </div>
              <h2 class="text-3xl font-black text-gray-900 mb-2">
                {{ infoContent[currentInfoType].title }}
              </h2>
              <p class="text-sm text-gray-500">Informasi terbaru - {{ new Date().toLocaleDateString('id-ID') }}</p>
            </div>

            <div v-html="infoContent[currentInfoType].content" class="prose prose-lg max-w-none"></div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Terms and Conditions Modal -->
    <transition name="modal">
      <div
        v-if="showTermsModal"
        class="fixed inset-0 z-[70] flex items-center justify-center px-4"
      >
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm"></div>

        <div class="relative bg-gradient-to-br from-white to-blue-50 rounded-3xl shadow-2xl max-w-lg w-full p-10 transform transition-all duration-500 border-2 border-white/50">
          <div class="text-center">
            <div class="relative inline-block mb-8">
              <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-indigo-400 rounded-full blur-2xl opacity-50 animate-pulse"></div>
              <div class="relative w-32 h-32 bg-gradient-to-r from-blue-600 to-indigo-700 rounded-full flex items-center justify-center text-white shadow-2xl">
                <i class="fas fa-file-contract text-6xl animate-bounce-slow"></i>
              </div>
            </div>

            <h2 class="text-4xl font-black text-gray-900 mb-4">
              Syarat dan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Ketentuan</span>
            </h2>
            <p class="text-lg text-gray-600 mb-10 leading-relaxed">
              Website ini bukan untuk sembarang orang. Untuk mendapatkan akses, harap hubungi administrator terlebih dahulu.
            </p>

            <div class="mb-6">
              <label class="flex items-center justify-center space-x-3 cursor-pointer">
                <input
                  type="checkbox"
                  v-model="agreed"
                  class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                >
                <span class="text-gray-700 font-medium">Saya setuju dengan syarat dan ketentuan</span>
              </label>
            </div>

            <button
              @click="agreeToTerms"
              :disabled="!agreed"
              class="group inline-flex items-center px-10 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold rounded-full hover:shadow-2xl transform hover:scale-105 transition-all duration-300 text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
            >
              <i class="fas fa-check mr-3 text-xl"></i>
              Setuju
              <i class="fas fa-arrow-right ml-3 transform group-hover:translate-x-2 transition-transform duration-300"></i>
            </button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'LandingPage',
  data() {
    return {
      showModal: false,
      showTentangDropdown: false,
      showInformasiDropdown: false,
      showMobileMenu: false,
      showInfoModal: false,
      currentInfoType: '',
      showTermsModal: false,
      agreed: false,
      infoContent: {
        ppid: {
          title: 'PPID (Pejabat Pengelola Informasi dan Dokumentasi)',
          icon: 'fas fa-file-alt',
          color: 'blue',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-2xl border border-blue-200">
                <h3 class="text-xl font-bold text-blue-800 mb-3">Informasi Terbaru</h3>
                <div class="space-y-3">
                  <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div>
                      <p class="text-gray-700 font-medium">Update Sistem PPID 2025</p>
                      <p class="text-sm text-gray-600">Implementasi sistem informasi publik terbaru sesuai UU KIP</p>
                      <p class="text-xs text-gray-500 mt-1">2 jam yang lalu</p>
                    </div>
                  </div>
                  <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                    <div>
                      <p class="text-gray-700 font-medium">Laporan Tahunan 2024</p>
                      <p class="text-sm text-gray-600">Laporan kinerja PPID Kementerian ATR/BPN tahun 2024</p>
                      <p class="text-xs text-gray-500 mt-1">1 hari yang lalu</p>
                    </div>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                  <div class="flex items-center mb-2">
                    <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                    <span class="font-semibold text-gray-800">Total Permohonan</span>
                  </div>
                  <div class="text-2xl font-bold text-blue-600">1,247</div>
                  <div class="text-sm text-gray-600">Bulan ini</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                  <div class="flex items-center mb-2">
                    <i class="fas fa-clock text-green-600 mr-2"></i>
                    <span class="font-semibold text-gray-800">Rata-rata Response</span>
                  </div>
                  <div class="text-2xl font-bold text-green-600">2.3</div>
                  <div class="text-sm text-gray-600">Hari</div>
                </div>
              </div>
            </div>
          `
        },
        peraturan: {
          title: 'Peraturan Perundang-undangan',
          icon: 'fas fa-gavel',
          color: 'red',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-red-50 to-orange-50 p-6 rounded-2xl border border-red-200">
                <h3 class="text-xl font-bold text-red-800 mb-3">Peraturan Terbaru</h3>
                <div class="space-y-4">
                  <div class="border-l-4 border-red-500 pl-4">
                    <h4 class="font-bold text-gray-800">Peraturan Pemerintah No. 11 Tahun 2025</h4>
                    <p class="text-sm text-gray-600 mt-1">Tentang Pengelolaan Tanah untuk Pembangunan Infrastruktur</p>
                    <p class="text-xs text-gray-500 mt-2">Ditetapkan: 15 Januari 2025</p>
                  </div>
                  <div class="border-l-4 border-red-500 pl-4">
                    <h4 class="font-bold text-gray-800">Keputusan Menteri ATR/BPN No. 123/Kep-ATR/2025</h4>
                    <p class="text-sm text-gray-600 mt-1">Pedoman Teknis Sertifikasi Tanah Elektronik</p>
                    <p class="text-xs text-gray-500 mt-2">Ditetapkan: 10 Januari 2025</p>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                  <div class="text-3xl font-bold text-red-600 mb-1">156</div>
                  <div class="text-sm font-medium text-gray-800">Undang-Undang</div>
                  <div class="text-xs text-gray-600">Aktif</div>
                </div>
                <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                  <div class="text-3xl font-bold text-red-600 mb-1">89</div>
                  <div class="text-sm font-medium text-gray-800">Peraturan Pemerintah</div>
                  <div class="text-xs text-gray-600">Aktif</div>
                </div>
                <div class="text-center bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                  <div class="text-3xl font-bold text-red-600 mb-1">234</div>
                  <div class="text-sm font-medium text-gray-800">Keputusan Menteri</div>
                  <div class="text-xs text-gray-600">Aktif</div>
                </div>
              </div>
            </div>
          `
        },
        layanan: {
          title: 'Layanan Informasi Publik',
          icon: 'fas fa-info-circle',
          color: 'green',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl border border-green-200">
                <h3 class="text-xl font-bold text-green-800 mb-3">Layanan Aktif</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-white p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-2">
                      <i class="fas fa-search text-green-600 mr-2"></i>
                      <span class="font-semibold text-gray-800">Pencarian Informasi</span>
                    </div>
                    <p class="text-sm text-gray-600">Cari informasi publik dengan mudah</p>
                    <div class="mt-2">
                      <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Online</span>
                    </div>
                  </div>
                  <div class="bg-white p-4 rounded-lg border border-green-200">
                    <div class="flex items-center mb-2">
                      <i class="fas fa-file-download text-green-600 mr-2"></i>
                      <span class="font-semibold text-gray-800">Download Dokumen</span>
                    </div>
                    <p class="text-sm text-gray-600">Unduh dokumen resmi secara gratis</p>
                    <div class="mt-2">
                      <span class="inline-block bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Online</span>
                    </div>
                  </div>
                </div>
              </div>
              <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Statistik Layanan</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                  <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">45,231</div>
                    <div class="text-sm text-gray-600">Pengunjung Bulan Ini</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">12,456</div>
                    <div class="text-sm text-gray-600">Dokumen Diunduh</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">98.5%</div>
                    <div class="text-sm text-gray-600">Kepuasan Pengguna</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">24/7</div>
                    <div class="text-sm text-gray-600">Waktu Operasional</div>
                  </div>
                </div>
              </div>
            </div>
          `
        },
        transparansi: {
          title: 'Transparansi Anggaran',
          icon: 'fas fa-eye',
          color: 'purple',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-purple-50 to-pink-50 p-6 rounded-2xl border border-purple-200">
                <h3 class="text-xl font-bold text-purple-800 mb-3">Anggaran 2025</h3>
                <div class="space-y-4">
                  <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-purple-200">
                    <div>
                      <div class="font-semibold text-gray-800">Program Modernisasi Sistem</div>
                      <div class="text-sm text-gray-600">Digitalisasi layanan pertanahan</div>
                    </div>
                    <div class="text-right">
                      <div class="font-bold text-purple-600">Rp 250M</div>
                      <div class="text-xs text-gray-500">Realisasi: 85%</div>
                    </div>
                  </div>
                  <div class="flex justify-between items-center p-4 bg-white rounded-lg border border-purple-200">
                    <div>
                      <div class="font-semibold text-gray-800">Pengembangan SDM</div>
                      <div class="text-sm text-gray-600">Pelatihan dan pendidikan</div>
                    </div>
                    <div class="text-right">
                      <div class="font-bold text-purple-600">Rp 180M</div>
                      <div class="text-xs text-gray-500">Realisasi: 92%</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                  <h4 class="font-bold text-gray-800 mb-4">Realisasi Anggaran 2025</h4>
                  <div class="space-y-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Januari - Juni</span>
                      <span class="font-semibold text-purple-600">Rp 2.1T</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                      <div class="bg-purple-600 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                    <div class="text-xs text-gray-500">78% dari target semester I</div>
                  </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                  <h4 class="font-bold text-gray-800 mb-4">Distribusi Anggaran</h4>
                  <div class="space-y-3">
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Program & Kegiatan</span>
                      <span class="font-semibold text-purple-600">65%</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Operasional</span>
                      <span class="font-semibold text-purple-600">25%</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-sm text-gray-600">Investasi</span>
                      <span class="font-semibold text-purple-600">10%</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          `
        },
        laporan: {
          title: 'Laporan Tahunan',
          icon: 'fas fa-chart-line',
          color: 'orange',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-orange-50 to-yellow-50 p-6 rounded-2xl border border-orange-200">
                <h3 class="text-xl font-bold text-orange-800 mb-3">Laporan Terbaru</h3>
                <div class="space-y-4">
                  <div class="bg-white p-4 rounded-lg border border-orange-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h4 class="font-bold text-gray-800">Laporan Kinerja 2024</h4>
                        <p class="text-sm text-gray-600 mt-1">Pencapaian target dan indikator kinerja utama</p>
                        <div class="flex items-center mt-2 space-x-4">
                          <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">PDF</span>
                          <span class="text-xs text-gray-500">2.3 MB</span>
                        </div>
                      </div>
                      <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Unduh
                      </button>
                    </div>
                  </div>
                  <div class="bg-white p-4 rounded-lg border border-orange-200 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <h4 class="font-bold text-gray-800">Laporan Keuangan 2024</h4>
                        <p class="text-sm text-gray-600 mt-1">Laporan audit dan realisasi anggaran</p>
                        <div class="flex items-center mt-2 space-x-4">
                          <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full">PDF</span>
                          <span class="text-xs text-gray-500">1.8 MB</span>
                        </div>
                      </div>
                      <button class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition-colors text-sm font-medium">
                        <i class="fas fa-download mr-2"></i>Unduh
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-orange-600 mb-1">98.7%</div>
                  <div class="text-sm font-medium text-gray-800">Target Tercapai</div>
                  <div class="text-xs text-gray-600">Tahun 2024</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-orange-600 mb-1">1.2M</div>
                  <div class="text-sm font-medium text-gray-800">Sertifikat Diterbitkan</div>
                  <div class="text-xs text-gray-600">Tahun 2024</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-orange-600 mb-1">95.2%</div>
                  <div class="text-sm font-medium text-gray-800">Kepuasan Masyarakat</div>
                  <div class="text-xs text-gray-600">Survei 2024</div>
                </div>
              </div>
            </div>
          `
        },
        profil: {
          title: 'Profil Kementerian ATR/BPN',
          icon: 'fas fa-building',
          color: 'indigo',
          content: `
            <div class="space-y-6">
              <div class="bg-gradient-to-r from-indigo-50 to-blue-50 p-6 rounded-2xl border border-indigo-200">
                <h3 class="text-xl font-bold text-indigo-800 mb-3">Tentang Kementerian</h3>
                <p class="text-gray-700 leading-relaxed mb-4">
                  Kementerian Agraria dan Tata Ruang/Badan Pertanahan Nasional (ATR/BPN) adalah kementerian yang bertugas melaksanakan urusan pemerintahan di bidang agraria dan tata ruang.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="bg-white p-4 rounded-lg border border-indigo-200">
                    <h4 class="font-semibold text-gray-800 mb-2">Visi</h4>
                    <p class="text-sm text-gray-600">"Terwujudnya penyelenggaraan agraria dan tata ruang yang profesional, transparan, dan akuntabel"</p>
                  </div>
                  <div class="bg-white p-4 rounded-lg border border-indigo-200">
                    <h4 class="font-semibold text-gray-800 mb-2">Misi</h4>
                    <p class="text-sm text-gray-600">Mewujudkan pengelolaan agraria dan tata ruang yang berkeadilan</p>
                  </div>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-indigo-600 mb-1">34</div>
                  <div class="text-sm font-medium text-gray-800">Provinsi</div>
                  <div class="text-xs text-gray-600">Kantor Wilayah</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-indigo-600 mb-1">514</div>
                  <div class="text-sm font-medium text-gray-800">Kabupaten/Kota</div>
                  <div class="text-xs text-gray-600">Kantor Pertanahan</div>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
                  <div class="text-3xl font-bold text-indigo-600 mb-1">45K+</div>
                  <div class="text-sm font-medium text-gray-800">Pegawai</div>
                  <div class="text-xs text-gray-600">Profesional</div>
                </div>
              </div>
              <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Struktur Organisasi</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                  <div>
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                      <i class="fas fa-user-tie text-indigo-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800 text-sm">Menteri</div>
                  </div>
                  <div>
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                      <i class="fas fa-users text-indigo-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800 text-sm">Sekretariat Jenderal</div>
                  </div>
                  <div>
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                      <i class="fas fa-map text-indigo-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800 text-sm">Ditjen Planologi</div>
                  </div>
                  <div>
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-2">
                      <i class="fas fa-home text-indigo-600 text-xl"></i>
                    </div>
                    <div class="font-semibold text-gray-800 text-sm">Ditjen Penetapan Hak</div>
                  </div>
                </div>
              </div>
            </div>
          `
        }
      }
    }
  },
  mounted() {
    // Check if user has already agreed to terms
    if (!localStorage.getItem('termsAgreed')) {
      this.showTermsModal = true;
    }
  },
  methods: {
    scrollToFeatures() {
      const element = document.getElementById('features');
      if (element) {
        const rect = element.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const elementTop = rect.top + scrollTop;
        window.scrollTo({ top: elementTop - 80, behavior: 'smooth' });
      }
    },
    scrollToAbout() {
      const element = document.getElementById('about');
      if (element) {
        const rect = element.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const elementTop = rect.top + scrollTop;
        window.scrollTo({ top: elementTop - 80, behavior: 'smooth' });
      }
    },
    scrollToFooter() {
      const element = document.getElementById('contact');
      if (element) {
        const rect = element.getBoundingClientRect();
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const elementTop = rect.top + scrollTop;
        window.scrollTo({ top: elementTop - 80, behavior: 'smooth' });
      }
    },
    toggleTentangDropdown() {
      this.showTentangDropdown = !this.showTentangDropdown;
      this.showInformasiDropdown = false;
    },
    toggleInformasiDropdown() {
      this.showInformasiDropdown = !this.showInformasiDropdown;
      this.showTentangDropdown = false;
    },
    toggleMobileMenu() {
      this.showMobileMenu = !this.showMobileMenu;
      if (!this.showMobileMenu) {
        this.showTentangDropdown = false;
        this.showInformasiDropdown = false;
      }
    },
    redirectToLogin() {
      window.location.href = 'http://127.0.0.1:8000/login';
    },
    openLaporanAnalitik() {
      const url = 'https://www.bps.go.id/id/publication/2025/01/31/29a40174e02f20a7a31b5bc3/statistik-demografi-indonesia--hasil-sensus-penduduk-2020-.html';
      window.open(url, '_blank');
    },
    openManajemenPengguna() {
      const url = 'https://blog.scalefusion.com/id/apa-itu-manajemen-pengguna/#:~:text=Manajemen%20pengguna%20adalah%20proses%20membuat%2C%20memelihara%2C%20dan%20menghapus,dapat%20mengakses%20perangkat%2C%20aplikasi%2C%20dan%20data%20yang%20tepat.';
      window.open(url, '_blank');
    },
    openNotifikasiRealTime() {
      // Navigate to the local notification view in the same tab
      window.location.href = '/notifikasi_realtime';
    },
    openChatbotInfo() {
      // Open the AWS Chatbot overview page in a new tab
      const url = 'https://aws.amazon.com/id/what-is/chatbot/';
      window.open(url, '_blank');
    },
    openPetaBandung() {
      // Open Google Maps centered at Bandung (lat, lng)
      const lat = -6.914744, lng = 107.609810, zoom = 13;
      const url = `https://www.google.com/maps/@${lat},${lng},${zoom}z`;
      window.open(url, '_blank');
    },
    openInfoModal(type) {
      // Map Informasi Publik keys to official pages and open in new tab
      const urls = {
        ppid: 'https://ppid.atrbpn.go.id/',
        peraturan: 'https://jdih.atrbpn.go.id/',
        layanan: 'https://www.atrbpn.go.id/cari-layanan',
        transparansi: 'https://ppid.atrbpn.go.id/bpn/page/details/informasi-berkala',
        laporan: 'https://www.atrbpn.go.id/berita',
        profil: 'https://www.atrbpn.go.id/profil-menteri'
      };
      const url = urls[type] || 'https://www.atrbpn.go.id/';
      window.open(url, '_blank');
    },
    agreeToTerms() {
      if (this.agreed) {
        localStorage.setItem('termsAgreed', 'true');
        this.showTermsModal = false;
      }
    }
  }
}
</script>

<style scoped>
/* Animations */
@keyframes blob {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  33% {
    transform: translate(30px, -50px) scale(1.1);
  }
  66% {
    transform: translate(-20px, 20px) scale(0.9);
  }
}

@keyframes gradient {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes fadeInDown {
  from {
    opacity: 0;
    transform: translateY(-30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.animate-blob {
  animation: blob 7s infinite;
}

.animation-delay-2000 {
  animation-delay: 2s;
}

.animation-delay-4000 {
  animation-delay: 4s;
}

.animation-delay-300 {
  animation-delay: 0.3s;
}

.animate-gradient {
  background-size: 200% 200%;
  animation: gradient 3s ease infinite;
}

.animate-fade-in-down {
  animation: fadeInDown 0.8s ease-out;
}

.animate-fade-in-up {
  animation: fadeInUp 0.8s ease-out;
}

.animate-fade-in {
  animation: fadeIn 1s ease-out;
}

.animate-pulse-slow {
  animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.animate-bounce-slow {
  animation: bounce 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.8;
  }
}

@keyframes bounce {
  0%, 100% {
    transform: translateY(-5%);
    animation-timing-function: cubic-bezier(0.8, 0, 1, 1);
  }
  50% {
    transform: translateY(0);
    animation-timing-function: cubic-bezier(0, 0, 0.2, 1);
  }
}

/* Dropdown Transitions */
.dropdown-enter-active, .dropdown-leave-active {
  transition: all 0.3s ease;
}

.dropdown-enter-from, .dropdown-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

/* Mobile Menu Transitions */
.mobile-menu-enter-active, .mobile-menu-leave-active {
  transition: all 0.3s ease;
}

.mobile-menu-enter-from, .mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Mobile Dropdown Transitions */
.mobile-dropdown-enter-active, .mobile-dropdown-leave-active {
  transition: all 0.3s ease;
}

.mobile-dropdown-enter-from, .mobile-dropdown-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-10px);
}

.mobile-dropdown-enter-to, .mobile-dropdown-leave-from {
  opacity: 1;
  max-height: 200px;
  transform: translateY(0);
}

/* Modal Transitions */
.modal-enter-active, .modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-active .relative,
.modal-leave-active .relative {
  transition: transform 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
}

.modal-enter-from .relative {
  transform: scale(0.9) translateY(20px);
}

.modal-leave-to .relative {
  transform: scale(0.9) translateY(20px);
}

/* Feature Card Hover Effects */
.feature-card {
  transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.feature-card:hover {
  transform: translateY(-8px);
}
</style>