<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SIP - Dashboard Pertanahan Nasional</title>
    <!-- Fontawesome & Google Font -->
    <link href="{{ asset('sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">
  
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        /* Loading Screen Styles - SUPER COOL */
        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 50%, #1d4ed8 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        /* Animated background particles */
        .loading-bg {
            position: absolute;
            inset: 0;
            overflow: hidden;
        }

        .loading-bg::before,
        .loading-bg::after {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            top: -50%;
            left: -50%;
            background: radial-gradient(circle at center, rgba(255,255,255,0.1) 0%, transparent 50%);
            animation: rotateBg 20s linear infinite;
        }

        .loading-bg::after {
            background: radial-gradient(circle at center, rgba(147, 197, 253, 0.1) 0%, transparent 40%);
            animation: rotateBg 25s linear infinite reverse;
        }

        @keyframes rotateBg {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 8px;
            height: 8px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            animation: float 3s ease-in-out infinite;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .particle:nth-child(1) { top: 20%; left: 20%; animation-delay: 0s; }
        .particle:nth-child(2) { top: 60%; left: 80%; animation-delay: 0.5s; }
        .particle:nth-child(3) { top: 80%; left: 30%; animation-delay: 1s; }
        .particle:nth-child(4) { top: 30%; left: 70%; animation-delay: 1.5s; }
        .particle:nth-child(5) { top: 50%; left: 15%; animation-delay: 2s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.8; }
            50% { transform: translateY(-30px) scale(1.5); opacity: 1; }
        }

        .loading-text {
            font-family: 'Nunito', sans-serif;
            font-size: 3.5rem;
            font-weight: 900;
            color: white;
            margin-bottom: 0.5rem;
            opacity: 0;
            animation: fadeInUp 0.8s ease forwards 0.3s;
            text-align: center;
            text-shadow: 0 4px 30px rgba(59, 130, 246, 0.8);
        }

        .loading-text span {
            background: linear-gradient(90deg, #60a5fa, #a78bfa, #60a5fa);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Welcome Text Animation - Letter by Letter */
        .welcome-text {
            font-family: 'Nunito', sans-serif;
            font-size: 4rem;
            font-weight: 900;
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .welcome-letter {
            display: inline-block;
            background: linear-gradient(180deg, #ffffff 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 30px rgba(96, 165, 250, 0.5);
            opacity: 0;
            transform: translateY(50px) scale(0.5);
            animation: letterPop 0.5s ease forwards;
        }

        .welcome-letter:nth-child(1) { animation-delay: 0.1s; }
        .welcome-letter:nth-child(2) { animation-delay: 0.15s; }
        .welcome-letter:nth-child(3) { animation-delay: 0.2s; }
        .welcome-letter:nth-child(4) { animation-delay: 0.25s; }
        .welcome-letter:nth-child(5) { animation-delay: 0.3s; }
        .welcome-letter:nth-child(6) { animation-delay: 0.35s; }
        .welcome-letter:nth-child(7) { animation-delay: 0.4s; }
        .welcome-letter:nth-child(8) { animation-delay: 0.45s; }
        .welcome-letter:nth-child(9) { animation-delay: 0.5s; }
        .welcome-letter:nth-child(10) { animation-delay: 0.55s; }
        .welcome-letter:nth-child(11) { animation-delay: 0.6s; }
        .welcome-letter:nth-child(12) { animation-delay: 0.65s; }
        .welcome-letter:nth-child(13) { animation-delay: 0.7s; }
        .welcome-letter:nth-child(14) { animation-delay: 0.75s; }

        @keyframes letterPop {
            0% { opacity: 0; transform: translateY(50px) scale(0.5); }
            70% { transform: translateY(-10px) scale(1.1); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Subtitle Animation - Word by Word */
        .subtitle-text {
            font-family: 'Nunito', sans-serif;
            font-size: 1.3rem;
            font-weight: 600;
            color: rgba(255,255,255,0.8);
            letter-spacing: 2px;
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }

        .subtitle-word {
            opacity: 0;
            transform: translateX(-30px);
            animation: wordSlide 0.6s ease forwards;
            background: linear-gradient(90deg, #60a5fa, #93c5fd);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .subtitle-word:nth-child(1) { animation-delay: 1s; }
        .subtitle-word:nth-child(2) { animation-delay: 1.2s; }
        .subtitle-word:nth-child(3) { animation-delay: 1.4s; }
        .subtitle-word:nth-child(4) { animation-delay: 1.6s; }
        .subtitle-word:nth-child(5) { animation-delay: 1.8s; }

        @keyframes wordSlide {
            0% { opacity: 0; transform: translateX(-30px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        .loading-subtitle {
            font-family: 'Nunito', sans-serif;
            font-size: 1rem;
            font-weight: 400;
            color: rgba(255,255,255,0.7);
            letter-spacing: 1px;
            margin-bottom: 1.5rem;
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 0.6s;
            text-align: center;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .loading-bar-container {
            width: 200px;
            height: 4px;
            background: rgba(255,255,255,0.2);
            border-radius: 4px;
            overflow: hidden;
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 2s;
        }

        .loading-bar {
            height: 100%;
            background: linear-gradient(90deg, #ffffff, #93c5fd, #ffffff);
            background-size: 200% 100%;
            animation: shimmer 1s ease-in-out infinite;
            border-radius: 4px;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .loading-dots {
            display: flex;
            gap: 8px;
            margin-top: 1rem;
            opacity: 0;
            animation: fadeIn 0.8s ease forwards 1.2s;
        }

        .loading-dots span {
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.5);
            border-radius: 50%;
            animation: bounce 1s infinite ease-in-out;
        }

        .loading-dots span:nth-child(1) { animation-delay: 0s; }
        .loading-dots span:nth-child(2) { animation-delay: 0.15s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.3s; }

        @keyframes bounce {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* Main Content - Hidden initially */
        .main-content {
            opacity: 0;
            transition: opacity 0.6s ease;
        }

        .main-content.visible {
            opacity: 1;
        }

        .page-section { display: none; }
        
        /* Marquee Animation Styles */
        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            padding: 10px 0;
        }
        .marquee-text {
            display: inline-block;
            animation: marquee 25s linear infinite;
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            letter-spacing: 2px;
        }
        .marquee-text i {
            margin: 0 15px;
            color: #fbbf24;
        }
        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }
        
        /* DARK THEME - Apply to ALL elements */
        body.dark-theme {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
        }
        
        /* Topbar - Dark Theme */
        body.dark-theme .topbar {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-bottom: 1px solid #334155 !important;
        }
        body.dark-theme .topbar .nav-item .nav-link {
            color: #e2e8f0 !important;
        }
        body.dark-theme .topbar .form-control {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        
        /* Sidebar - Dark Theme */
        body.dark-theme .sidebar {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme .sidebar .nav-item .nav-link {
            color: #cbd5e1 !important;
        }
        body.dark-theme .sidebar .nav-item .nav-link:hover {
            background-color: #334155 !important;
            color: #fff !important;
        }
        body.dark-theme .sidebar .nav-item.active .nav-link {
            background-color: #3b82f6 !important;
            color: #fff !important;
        }
        body.dark-theme .sidebar-brand {
            background-color: #1e293b !important;
            color: #fff !important;
        }
        body.dark-theme .sb-sidenav {
            background-color: #1e293b !important;
        }
        body.dark-theme .sb-sidenav-menu {
            background-color: #1e293b !important;
        }
        body.dark-theme .sb-sidenav-menu-heading {
            color: #94a3b8 !important;
        }
        
        /* Cards - Dark Theme */
        body.dark-theme .card {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }
        body.dark-theme .card-header {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
            border-bottom: 1px solid #475569 !important;
        }
        body.dark-theme .card-body {
            color: #e2e8f0 !important;
        }
        
        /* Tables - Dark Theme */
        body.dark-theme .table {
            color: #e2e8f0 !important;
            background-color: transparent !important;
        }
        body.dark-theme .table thead th {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
            border-color: #475569 !important;
        }
        body.dark-theme .table tbody td {
            border-color: #334155 !important;
            background-color: transparent !important;
        }
        body.dark-theme .table-hover tbody tr:hover {
            background-color: #334155 !important;
        }
        
        /* Forms - Dark Theme */
        body.dark-theme .form-control {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme .form-control:focus {
            background-color: #475569 !important;
            border-color: #3b82f6 !important;
            color: #fff !important;
        }
        body.dark-theme .form-group label {
            color: #cbd5e1 !important;
        }
        body.dark-theme select.form-control {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme textarea.form-control {
            background-color: #334155 !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme input.form-control::placeholder {
            color: #94a3b8 !important;
        }
        
        /* Buttons - Dark Theme */
        body.dark-theme .btn {
            color: #fff !important;
        }
        body.dark-theme .btn-primary {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
        body.dark-theme .btn-success {
            background-color: #10b981 !important;
            border-color: #10b981 !important;
        }
        body.dark-theme .btn-danger {
            background-color: #ef4444 !important;
            border-color: #ef4444 !important;
        }
        body.dark-theme .btn-warning {
            background-color: #f59e0b !important;
            border-color: #f59e0b !important;
            color: #000 !important;
        }
        body.dark-theme .btn-secondary {
            background-color: #64748b !important;
            border-color: #64748b !important;
        }
        body.dark-theme .btn-outline-primary {
            color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
        body.dark-theme .btn-outline-secondary {
            color: #94a3b8 !important;
            border-color: #64748b !important;
        }
        
        /* Modal - Dark Theme */
        body.dark-theme .modal-content {
            background-color: #1e293b !important;
            color: #e2e8f0 !important;
            border-color: #334155 !important;
        }
        body.dark-theme .modal-header {
            border-bottom-color: #334155 !important;
        }
        body.dark-theme .modal-footer {
            border-top-color: #334155 !important;
        }
        body.dark-theme .modal-title {
            color: #e2e8f0 !important;
        }
        body.dark-theme .close {
            color: #e2e8f0 !important;
        }
        
        /* Footer - Dark Theme */
        body.dark-theme footer {
            background-color: #1e293b !important;
            color: #cbd5e1 !important;
            border-top: 1px solid #334155 !important;
        }
        
        /* Badges - Dark Theme */
        body.dark-theme .badge {
            color: #fff !important;
        }
        
        /* Progress - Dark Theme */
        body.dark-theme .progress {
            background-color: #334155 !important;
        }
        
        /* Dropdown - Dark Theme */
        body.dark-theme .dropdown-menu {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        body.dark-theme .dropdown-item {
            color: #e2e8f0 !important;
        }
        body.dark-theme .dropdown-item:hover {
            background-color: #334155 !important;
            color: #fff !important;
        }
        
        /* Text colors - Dark Theme */
        body.dark-theme .text-muted {
            color: #94a3b8 !important;
        }
        body.dark-theme .text-primary {
            color: #3b82f6 !important;
        }
        body.dark-theme .text-success {
            color: #10b981 !important;
        }
        body.dark-theme .text-danger {
            color: #ef4444 !important;
        }
        body.dark-theme .text-warning {
            color: #f59e0b !important;
        }
        body.dark-theme .text-info {
            color: #06b6d4 !important;
        }
        
        /* Custom switch - Dark Theme */
        body.dark-theme .custom-control-label::before {
            background-color: #475569 !important;
        }
        
        /* Pagination - Dark Theme */
        body.dark-theme .page-link {
            background-color: #334155 !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        body.dark-theme .page-item.active .page-link {
            background-color: #3b82f6 !important;
            border-color: #3b82f6 !important;
        }
        
        /* Alert - Dark Theme */
        body.dark-theme .alert {
            border-color: transparent;
        }
        
        /* Large font size */
        body.large-font {
            font-size: 18px;
        }
        body.large-font .card {
            font-size: 16px;
        }
        
        /* Chart containers - Dark Theme */
        body.dark-theme .chart-container {
            background-color: #1e293b !important;
            border-radius: 8px;
            padding: 15px;
        }
        .page-section.active { display: block; }
        .sidebar .nav-item .nav-link.active-menu {
            background-color: rgba(255,255,255,0.1);
            font-weight: 600;
        }
        .modal-backdrop.show { opacity: 0.5; }
        .badge-role, .badge-status {
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 600;
        }
        .table-actions .btn {
            padding: 4px 8px;
            font-size: 12px;
        }
        .form-label-required::after {
            content: '*';
            color: #dc3545;
            margin-left: 4px;
        }
        .data-master-header {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
        }
        .table-penduduk th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }
        .table-penduduk td {
            vertical-align: middle;
        }
        /* Custom checkbox styling for bulk delete */
        .penduduk-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4e73df;
        }
        #selectAllPenduduk {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4e73df;
        }
        /* Bulk delete button styling */
        #deleteSelectedBtn {
            transition: all 0.3s ease;
            animation: slideIn 0.3s ease;
            font-weight: 600;
        }
        #deleteSelectedBtn:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        /* Table row hover effect */
        #pendudukTableBody tr:hover {
            background-color: #f8f9fa;
        }
        /* Selected row styling */
        .penduduk-checkbox:checked + td {
            background-color: #e8f4fd;
        }
        .report-card {
            border-left: 4px solid #6f42c1;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.15);
        }
        .report-icon {
            font-size: 3rem;
            opacity: 0.8;
        }
        .report-header {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        .filter-section {
            background-color: #f8f9fc;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .stat-box {
            background: white;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-box h4 {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stat-box p {
            margin: 0;
            color: #6c757d;
            font-size: 0.9rem;
        }
        #page-pengaturan .card {
            border: 1px solid #dee2e6 !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        #page-pengaturan .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        #page-pengaturan .card-body {
            padding: 20px !important;
        }
        #page-pengaturan .form-group {
            margin-bottom: 15px !important;
        }
        #page-pengaturan .form-control {
            width: 100% !important;
            padding: 8px 12px !important;
            border: 1px solid #ced4da !important;
            border-radius: 4px !important;
        }
        #page-pengaturan .btn {
            padding: 8px 16px !important;
            border-radius: 4px !important;
            border: 1px solid transparent !important;
        }
        .profile-photo-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #dee2e6;
        }
    </style>
</head>
<body id="page-top">

    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-bg">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
        
        <div class="welcome-text">
            <span class="welcome-letter">S</span>
            <span class="welcome-letter">E</span>
            <span class="welcome-letter">L</span>
            <span class="welcome-letter">A</span>
            <span class="welcome-letter">M</span>
            <span class="welcome-letter">A</span>
            <span class="welcome-letter">T</span>
            <span class="welcome-letter"> </span>
            <span class="welcome-letter">D</span>
            <span class="welcome-letter">A</span>
            <span class="welcome-letter">T</span>
            <span class="welcome-letter">A</span>
            <span class="welcome-letter">N</span>
            <span class="welcome-letter">G</span>
        </div>
        
        <div class="subtitle-text">
            <span class="subtitle-word">DI</span>
            <span class="subtitle-word">DASHBOARD</span>
            <span class="subtitle-word">SISTEM</span>
            <span class="subtitle-word">INFORMASI</span>
            <span class="subtitle-word">PERTANAHAN</span>
        </div>
        
        <div class="loading-bar-container">
            <div class="loading-bar"></div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="javascript:void(0)" onclick="showPage('dashboard')">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div class="sidebar-brand-text mx-3">{{ __('messages.dashboard') }}</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="showPage('dashboard')" id="menu-dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Fitur Utama</div>
            <!-- MENU PETA PERTANAHAN -->
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/petnah') }}" target="_blank">
                    <i class="fas fa-map-marked-alt text-warning"></i>
                    <span>Peta Pertanahan</span>
                </a>
            </li>

            <!-- MENU DATA MASTER (Admin/Staff Only) -->
            @role('admin|staff')
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="showPage('datamaster')" id="menu-datamaster">
                    <i class="fas fa-database text-success"></i>
                    <span>Data Master</span>
                </a>
            </li>
            @endrole
            <!-- MENU MANAJEMEN PENGGUNA (Admin Only) -->
            @role('admin')
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="showPage('users')" id="menu-users">
                    <i class="fas fa-users-cog text-info"></i>
                    <span>Manajemen Pengguna</span>
                </a>
            </li>
            @endrole
            <!-- MENU LAPORAN (Admin/Staff Only) -->
            @role('admin|staff')
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="showPage('laporan')" id="menu-laporan">
                    <i class="fas fa-file-alt text-purple"></i>
                    <span>Laporan</span>
                </a>
            </li>
            <!-- MENU SERTIFIKAT (Admin/Staff Only) -->
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/admin/sertifikat') }}" id="menu-sertifikat">
                    <i class="fas fa-certificate text-success"></i>
                    <span>Kelola Sertifikat</span>
                </a>
            </li>
            @endrole
            <!-- MENU PENGATURAN (All Users) -->
            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0)" onclick="showPage('pengaturan')" id="menu-pengaturan">
                    <i class="fas fa-cogs text-secondary"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
            <!-- MENU KRITIK & SARAN (User Only) -->
            @role('user')
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/notifikasi_realtime') }}" id="menu-kritiksaran">
                    <i class="fas fa-comments text-info"></i>
                    <span>Kritik & Saran</span>
                </a>
            </li>
            @endrole
            <hr class="sidebar-divider">
            <!-- LOGOUT -->
            <li class="nav-item">
                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt text-danger"></i>
                    <span>Logout</span>
                </a>
            </li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name ?? 'Admin' }}</span>
                                <img class="img-profile rounded-circle" id="topbarProfilePhoto" src="{{ auth()->user()->profile_photo_url ?? asset('sbadmin/img/undraw_profile.svg') }}">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @if(session('logged_via_magic_link'))
                    <!-- MAGIC LINK DASHBOARD -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard - Login via Magic Link</h1>
                        <span class="text-muted">Selamat datang, {{ Auth::user()->name ?? 'User' }}</span>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-book fa-3x text-primary mb-3"></i>
                                    <h5 class="card-title">Kelola Buku</h5>
                                    <button class="btn btn-primary btn-block" onclick="alert('Fitur Kelola Buku')">Kelola Buku</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                                    <h5 class="card-title">Kelola Anggota</h5>
                                    <button class="btn btn-success btn-block" onclick="alert('Fitur Kelola Anggota')">Kelola Anggota</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-exchange-alt fa-3x text-info mb-3"></i>
                                    <h5 class="card-title">Transaksi</h5>
                                    <button class="btn btn-info btn-block" onclick="alert('Fitur Transaksi')">Transaksi</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x text-warning mb-3"></i>
                                    <h5 class="card-title">Laporan</h5>
                                    <button class="btn btn-warning btn-block" onclick="alert('Fitur Laporan')">Laporan</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('logout') }}" class="btn btn-danger btn-lg"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt mr-2"></i>Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                    </div>

                    @else
                    <!-- DASHBOARD PAGE -->
                    <div id="page-dashboard" class="page-section active">
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Dashboard Pertanahan Nasional</h1>
                            <span class="text-muted">Update: {{ now()->format('d M Y') }}</span>
                        </div>
                        
                        <!-- USER ROLE: Regular User gets simple dashboard -->
                        @role('user')
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-map-marked-alt fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">Peta Pertanahan</h5>
                                        <p class="card-text text-muted">Lihat dan jelajahi peta tanah di Indonesia</p>
                                        <a href="{{ url('/petnah') }}" target="_blank" class="btn btn-primary btn-block">Buka Peta</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-comments fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">Kritik & Saran</h5>
                                        <p class="card-text text-muted">Sampaikan kritik, saran, atau pengaduan Anda</p>
                                        <a href="{{ url('/notifikasi_realtime') }}" class="btn btn-info btn-block">Kirim Pesan</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-3x text-success mb-3"></i>
                                        <h5 class="card-title">Profil Saya</h5>
                                        <p class="card-text text-muted">Kelola informasi profil akun Anda</p>
                                        <button class="btn btn-success btn-block" onclick="showPage('pengaturan')">Ubah Profil</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-search fa-3x text-warning mb-3"></i>
                                        <h5 class="card-title">Cek Sertifikat</h5>
                                        <p class="card-text text-muted">Periksa status sertipikat tanah Anda</p>
                                        <a href="{{ url('/cek-sertifikat') }}" class="btn btn-warning btn-block">Cek Sertifikat</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-purple shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-file-signature fa-3x text-purple mb-3" style="color: #6f42c1;"></i>
                                        <h5 class="card-title">Pengajuan Sertifikat</h5>
                                        <p class="card-text text-muted">Ajukan pembuatan sertifikat tanah baru</p>
                                        <a href="{{ url('/pengajuan-sertifikat') }}" class="btn btn-block" style="background: #6f42c1; color: white;">Ajukan Sekarang</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100">
                                    <div class="card-body text-center">
                                        <i class="fas fa-map-marker-alt fa-3x text-info mb-3"></i>
                                        <h5 class="card-title">Lokasi Kantor ATR/BPN</h5>
                                        <p class="card-text text-muted">Cari lokasi kantor ATR/BPN terdekat</p>
                                        <a href="{{ url('/kantor-atr') }}" class="btn btn-info btn-block">Cari Kantor</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- ADMIN/STAFF ROLE: Full dashboard with stats and charts -->
                        <!-- Statistik Cards -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Penduduk</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">278,6 Juta Jiwa</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Luas Wilayah Daratan</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">1,905 Juta km²</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-mountain fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Bidang Tanah</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">126,4 Juta Bidang</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-vector-square fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Bidang Tersertipikat</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">82,3 Juta (65%)</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-certificate fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Chart + Mini Map Row -->
                        <div class="row">
                            <!-- Chart Progress Sertipikasi -->
                            <div class="col-xl-8 col-lg-7">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Progress Sertipikasi Tanah Nasional (2020-2025)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="chart-area">
                                            <canvas id="myAreaChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Mini Peta Indonesia -->
                            <div class="col-xl-4 col-lg-5">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Peta Indonesia</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9f/Flag_of_Indonesia.svg/1280px-Flag_of_Indonesia.svg.png"
                                             alt="Peta Indonesia" style="width:100%; max-width:300px; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.1);">
                                        <p class="mt-3 text-muted small">Klik menu <strong>Peta Pertanahan</strong> untuk melihat peta interaktif</p>
                                        <a href="{{ url('/petnah') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-map-marked-alt"></i> Buka Peta Lengkap
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Footer Info -->
                        <div class="text-center py-4 text-muted">
                            <small>© 2025 Sistem Informasi Pertanahan Nasional • Kementerian ATR/BPN</small>
                        </div>
                        @endif
                    </div>

                    <!-- DATA MASTER PAGE (PENDUDUK) -->
                    <div id="page-datamaster" class="page-section">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">
                                <i class="fas fa-database text-success"></i> Data Master - Penduduk
                            </h1>
                            <div class="d-flex align-items-center">
                                <input type="text" id="searchPenduduk" class="form-control mr-2" style="max-width: 200px;" placeholder="Cari NIK/Nama...">
                                <button id="searchBtn" class="btn btn-primary btn-sm mr-2" onclick="loadPenduduk($('#searchPenduduk').val())">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                <button id="refreshBtn" class="btn btn-secondary btn-sm mr-2" onclick="$('#searchPenduduk').val(''); loadPenduduk()">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>
                                <button class="btn btn-success btn-sm" onclick="openPendudukModal('add')">
                                    <i class="fas fa-plus"></i> Tambah Penduduk
                                </button>
                                <button id="deleteSelectedBtn" class="btn btn-danger btn-sm ml-2" onclick="bulkDeletePenduduk()" style="display:none;">
                                    <i class="fas fa-trash"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                                </button>
                            </div>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 data-master-header">
                                <h6 class="m-0 font-weight-bold">Daftar Penduduk Terdaftar</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-penduduk" id="pendudukTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th width="5%"><input type="checkbox" id="selectAllPenduduk" onchange="toggleAllPenduduk(this)"></th>
                                                <th width="5%">No</th>
                                                <th width="15%">NIK</th>
                                                <th width="20%">Nama Lengkap</th>
                                                <th width="25%">Alamat</th>
                                                <th width="10%">RT/RW</th>
                                                <th width="10%">Provinsi</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pendudukTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- USER MANAGEMENT PAGE -->
                    <div id="page-users" class="page-section">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-users-cog text-info"></i> Manajemen Pengguna</h1>
                            <button class="btn btn-primary btn-sm" onclick="openUserModal('add')">
                                <i class="fas fa-plus"></i> Tambah Pengguna
                            </button>
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Daftar Pengguna Sistem</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="usersTable" width="100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Nama</th>
                                                <th width="20%">Email</th>
                                                <th width="10%">Role</th>
                                                <th width="12%">Status</th>
                                                <th width="10%">Bahasa</th>
                                                <th width="13%">Terdaftar</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="userTableBody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Registration Requests Table -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-success">Permintaan Registrasi Pengguna</h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Filter Status:</div>
                                        <a class="dropdown-item" href="#" onclick="filterRegisterRequests('all')">Semua</a>
                                        <a class="dropdown-item" href="#" onclick="filterRegisterRequests('pending')">Menunggu</a>
                                        <a class="dropdown-item" href="#" onclick="filterRegisterRequests('disetujui')">Disetujui</a>
                                        <a class="dropdown-item" href="#" onclick="filterRegisterRequests('ditolak')">Ditolak</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Search Form -->
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" id="searchRegisterRequest" class="form-control" placeholder="Cari berdasarkan Nama, Email, NIK, atau No. Registrasi..." onkeyup="searchRegisterRequests()">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="searchRegisterRequests()">
                                                <i class="fas fa-search"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" type="button" onclick="clearRegisterRequestSearch()">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="registerRequestsTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No. Registrasi</th>
                                                <th>Tanggal</th>
                                                <th>Nama Lengkap</th>
                                                <th>NIK</th>
                                                <th>Email</th>
                                                <th>Instansi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="registerRequestsTableBody">
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <i class="fas fa-spinner fa-spin"></i> Memuat data...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="requestDetailModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Permintaan Registrasi</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body" id="requestDetailBody"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Tolak Permintaan</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Alasan Penolakan (Opsional)</label>
                                            <textarea class="form-control" id="rejectReason" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-danger" id="confirmRejectBtn">Tolak</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- LAPORAN PAGE (BARU) -->
                    <div id="page-laporan" class="page-section">
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">
                                <i class="fas fa-file-alt text-purple"></i> Pusat Laporan Pertanahan
                            </h1>
                            <span class="text-muted">Sistem Pelaporan Terintegrasi</span>
                        </div>
                        <!-- Statistics Overview -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-box border-left-primary">
                                    <p class="text-primary mb-1">Total Laporan</p>
                                    <h4 class="text-primary">2,847</h4>
                                    <small class="text-success"><i class="fas fa-arrow-up"></i> +12% dari bulan lalu</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-success">
                                    <p class="text-success mb-1">Laporan Selesai</p>
                                    <h4 class="text-success">2,103</h4>
                                    <small class="text-muted">74% completion rate</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-warning">
                                    <p class="text-warning mb-1">Dalam Proses</p>
                                    <h4 class="text-warning">581</h4>
                                    <small class="text-muted">20% dari total</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-danger">
                                    <p class="text-danger mb-1">Perlu Tindakan</p>
                                    <h4 class="text-danger">163</h4>
                                    <small class="text-danger"><i class="fas fa-exclamation-circle"></i> Prioritas tinggi</small>
                                </div>
                            </div>
                        </div>
                        <!-- Report Types Cards -->
                        <div class="row">
                            <!-- Laporan Penduduk -->
                            <div class="col-lg-4 mb-4">
                                <div class="card report-card shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="font-weight-bold text-primary mb-0">Laporan Penduduk</h5>
                                            <i class="fas fa-users report-icon text-primary"></i>
                                        </div>
                                        <p class="text-muted mb-3">Laporan data demografi dan statistik kependudukan berdasarkan wilayah dan periode tertentu</p>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Total Data: <strong>278.6 Juta</strong></small>
                                            <small class="text-muted d-block">Update Terakhir: <strong>04 Des 2025</strong></small>
                                        </div>
                                        <button class="btn btn-primary btn-block" onclick="generateLaporanPenduduk()">
                                            <i class="fas fa-print"></i> Generate Laporan
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Laporan Lahan -->
                            <div class="col-lg-4 mb-4">
                                <div class="card report-card shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="font-weight-bold text-success mb-0">Laporan Lahan</h5>
                                            <i class="fas fa-map-marked-alt report-icon text-success"></i>
                                        </div>
                                        <p class="text-muted mb-3">Laporan kepemilikan, sertifikasi, dan status penggunaan lahan pertanahan nasional</p>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Total Bidang: <strong>126.4 Juta</strong></small>
                                            <small class="text-muted d-block">Tersertifikasi: <strong>65%</strong></small>
                                        </div>
                                        <a class="btn btn-success btn-block" href="{{ url('/laporan/print') }}?source=lahan" target="_blank" onclick="generateLaporanLahan(); return true;">
                                            <i class="fas fa-file-download"></i> Generate Laporan
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!-- Laporan Sertifikasi -->
                            <div class="col-lg-4 mb-4">
                                <div class="card report-card shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="font-weight-bold text-warning mb-0">Laporan Sertifikasi</h5>
                                            <i class="fas fa-certificate report-icon text-warning"></i>
                                        </div>
                                        <p class="text-muted mb-3">Laporan progress dan statistik proses sertifikasi tanah berdasarkan wilayah dan tahun</p>
                                        <div class="mb-3">
                                            <small class="text-muted d-block">Target 2025: <strong>10 Juta</strong></small>
                                            <small class="text-muted d-block">Tercapai: <strong>7.2 Juta (72%)</strong></small>
                                        </div>
                                        <button class="btn btn-warning btn-block" onclick="generateLaporanSertifikasi()">
                                            <i class="fas fa-file-download"></i> Buka Form Cetak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fitur Pengaduan dan Notifikasi Pengguna -->
                        <div class="row">
                            <div class="col-lg-12 mb-4">
                                <div class="card report-card shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="font-weight-bold text-primary mb-0">Pengaduan dan Notifikasi Pengguna</h5>
                                            <div>
                                                <i class="fas fa-exclamation-triangle report-icon text-danger mr-2"></i>
                                                <i class="fas fa-bell report-icon text-info"></i>
                                            </div>
                                        </div>
                                        <p class="text-muted mb-3">Kelola pengaduan dan notifikasi pengguna sistem terkait layanan pertanahan</p>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Total Pengaduan: <strong>47</strong></small>
                                                <small class="text-muted d-block">Belum Ditanggapi: <strong>12</strong></small>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block">Notifikasi Hari Ini: <strong>23</strong></small>
                                                <small class="text-muted d-block">Belum Dibaca: <strong>8</strong></small>
                                            </div>
                                        </div>
                                        <a href="{{ url('/formpengaduanataunotif') }}" class="btn btn-primary btn-block btn-lg">
                                            <i class="fas fa-comments mr-2"></i>
                                            <i class="fas fa-bell mr-2"></i>
                                            Kelola Pengaduan & Notifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PENGATURAN PAGE -->
                    <div id="page-pengaturan" class="page-section">
                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-cogs text-secondary"></i> Pengaturan & Profil Akun</h1>
                            <span class="text-muted">Kelola akun Anda - {{ auth()->user()->name }}</span>
                        </div>
                        <!-- Profile Info Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-user-circle"></i> Informasi Profil - {{ auth()->user()->name }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" width="100%">
                                        <thead class="thead-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="20%">Nama Lengkap</th>
                                                <th width="25%">Email</th>
                                                <th width="10%">Role</th>
                                                <th width="12%">Status</th>
                                                <th width="10%">Bahasa</th>
                                                <th width="13%">Bergabung</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">1</td>
                                                <td><strong>{{ auth()->user()->name }}</strong></td>
                                                <td>{{ auth()->user()->email }}</td>
                                                <td>
                                                    <span class="badge badge-role {{ auth()->user()->getRoleNames()->first() === 'admin' ? 'badge-danger' : (auth()->user()->getRoleNames()->first() === 'staff' ? 'badge-warning' : 'badge-info') }}">
                                                        {{ auth()->user()->getRoleNames()->first() ?? 'user' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-status {{ auth()->user()->is_approved ? 'badge-success' : 'badge-warning' }}">
                                                        {{ auth()->user()->is_approved ? 'Disetujui' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td>{{ auth()->user()->language_preference === 'id' ? 'Indonesia' : 'English' }}</td>
                                                <td>{{ auth()->user()->created_at->format('d M Y') }}</td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-warning" onclick="editProfile()">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Profile Edit Form Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-success">
                                    <i class="fas fa-edit"></i> Edit Profil & Pengaturan
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="profileForm" enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <div class="row">
                                        <div class="col-md-3 text-center">
                                            <img id="profilePhotoPreview" class="profile-photo-preview" src="{{ auth()->user()->profile_photo_url ?? asset('sbadmin/img/undraw_profile.svg') }}" alt="Foto Profil">
                                            <div class="form-group">
                                                <label for="profile_photo">Ubah Foto Profil</label>
                                                <input type="file" class="form-control-file" id="profile_photo" name="profile_photo" accept="image/*">
                                            </div>
                                        </div>
                                        <div class="col-md-9">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label form-label-required">Nama Lengkap</label>
                                                        <input type="text" class="form-control" id="name" name="name"
                                                               value="{{ auth()->user()->name }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="email" class="form-label form-label-required">Email</label>
                                                        <input type="email" class="form-control" id="email" name="email"
                                                               value="{{ auth()->user()->email }}" required>
                                                    </div>
                                                 </div>

                                             </div>
                                            <div class="form-group">
                                                <label for="current_password_profile">Password Saat Ini (untuk konfirmasi)</label>
                                                <input type="password" class="form-control" id="current_password_profile"
                                                       name="current_password" placeholder="Masukkan password saat ini">
                                                <small class="form-text text-muted">Diperlukan untuk mengubah data profil</small>
                                            </div>
                                            <button type="submit" class="btn btn-primary" id="saveProfileBtn">
                                                Simpan Perubahan Profil
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- Password Change Card -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-warning">
                                    Ubah Password Akun
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="passwordForm">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="current_password" class="form-label form-label-required">Password Saat Ini</label>
                                                <input type="password" class="form-control" id="current_password"
                                                       name="current_password" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="password" class="form-label form-label-required">Password Baru</label>
                                                <input type="password" class="form-control" id="password"
                                                       name="password" minlength="6" required>
                                                <small class="form-text text-muted">Minimal 6 karakter</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="password_confirmation" class="form-label form-label-required">Konfirmasi Password Baru</label>
                                                <input type="password" class="form-control" id="password_confirmation"
                                                       name="password_confirmation" required>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-warning" id="changePasswordBtn">
                                        Ubah Password
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Account Statistics Overview -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="stat-box border-left-primary">
                                    <p class="text-primary mb-1">Total Login</p>
                                    <h4 class="text-primary">{{ auth()->user()->login_count ?? 0 }}</h4>
                                    <small class="text-success">+12% dari bulan lalu</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-success">
                                    <p class="text-success mb-1">Aktivitas</p>
                                    <h4 class="text-success">89%</h4>
                                    <small class="text-muted">Tingkat aktif</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-warning">
                                    <p class="text-warning mb-1">Pengaturan</p>
                                    <h4 class="text-warning">12</h4>
                                    <small class="text-muted">Diubah bulan ini</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-box border-left-danger">
                                    <p class="text-danger mb-1">Notifikasi</p>
                                    <h4 class="text-danger">5</h4>
                                    <small class="text-danger">Belum dibaca</small>
                                </div>
                            </div>
                        </div>
                        <!-- Card Pengaturan Tema & Tampilan -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    Pengaturan Tema & Tampilan
                                </h6>
                            </div>
                            <div class="card-body">
                                <form id="themeForm">
                                    <div class="form-group">
                                        <label for="themeMode">Mode Tema</label>
                                        <select class="form-control" id="themeMode" onchange="applyTheme()">
                                            <option value="light">Terang</option>
                                            <option value="dark">Gelap</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fontSize">Ukuran Font</label>
                                        <select class="form-control" id="fontSize" onchange="applyFontSize()">
                                            <option value="14px">Kecil</option>
                                            <option value="16px" selected>Sedang</option>
                                            <option value="18px">Besar</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        Simpan Pengaturan Tema
                                    </button>
                                </form>
                            </div>
                        </div>
                        <!-- Card Marquee - KEMENTRIAN AGRARIA DAN TATA RUANG BADAN PERTANAHAN NASIONAL -->
                        <div class="card shadow mb-4" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); border: none;">
                            <div class="card-body py-3">
                                <div class="marquee-container">
                                    <div class="marquee-text">
                                        <i class="fas fa-landmark"></i> KEMENTRIAN AGRARIA DAN TATA RUANG BADAN PERTANAHAN NASIONAL <i class="fas fa-map-marked-alt"></i> SISTEM INFORMASI PERTANAHAN <i class="fas fa-home"></i> LAYANAN PERTANAHAN TERPADU <i class="fas fa-file-contract"></i> SERTIFIKASI TANAH <i class="fas fa-clipboard-check"></i> VERIFIKASI DATA <i class="fas fa-search"></i> PENCARIAN SERTIFIKAT <i class="fas fa-building"></i> KANTOR Pertanahan <i class="fas fa-user-tie"></i> LAYANAN ADMINISTRASI
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Lokasi Kantor ATR/BPN -->
                        <div id="kantor-atr" class="card shadow mb-4">
                            <div class="card-header py-3" style="background: linear-gradient(135deg, #059669, #10b981);">
                                <h6 class="m-0 font-weight-bold text-white">
                                    <i class="fas fa-map-marker-alt"></i> Lokasi Kantor ATR/BPN Seluruh Indonesia
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchKantor" placeholder="Cari kota/kantor... (contoh: Bandung, Jakarta, Surabaya)" onkeyup="searchKantor()">
                                        <div class="input-group-append">
                                            <button class="btn btn-success" type="button" onclick="searchKantor()">
                                                <i class="fas fa-search"></i> Cari
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="kantorList" class="kantor-list" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Kantor list will be populated by JavaScript -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PENDUDUK MODAL -->
    <div class="modal fade" id="pendudukModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pendudukModalTitle">Tambah Penduduk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="pendudukForm">
                    <div class="modal-body">
                        <input type="hidden" id="pendudukId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendudukNik" class="form-label form-label-required">NIK</label>
                                    <input type="text" class="form-control" id="pendudukNik" name="nik" required maxlength="16" placeholder="16 digit NIK">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendudukNama" class="form-label form-label-required">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="pendudukNama" name="nama" required placeholder="Nama lengkap">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="pendudukAlamat" class="form-label form-label-required">Alamat</label>
                            <textarea class="form-control" id="pendudukAlamat" name="alamat" rows="3" required placeholder="Alamat lengkap"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendudukRt" class="form-label form-label-required">RT</label>
                                    <input type="text" class="form-control" id="pendudukRt" name="rt" required maxlength="3" placeholder="001">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendudukRw" class="form-label form-label-required">RW</label>
                                    <input type="text" class="form-control" id="pendudukRw" name="rw" required maxlength="3" placeholder="001">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pendudukProvinsi" class="form-label form-label-required">Provinsi</label>
                                    <select class="form-control" id="pendudukProvinsi" name="provinsi" required>
                                        <option value="">Pilih Provinsi</option>
                                        <option value="Aceh">Aceh</option>
                                        <option value="Sumatera Utara">Sumatera Utara</option>
                                        <option value="Sumatera Utara">Sumatera Utara</option>
                                        <option value="Sumatera Barat">Sumatera Barat</option>
                                        <option value="Riau">Riau</option>
                                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                                        <option value="Jambi">Jambi</option>
                                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                                        <option value="Bangka Belitung">Bangka Belitung</option>
                                        <option value="Bengkulu">Bengkulu</option>
                                        <option value="Lampung">Lampung</option>
                                        <option value="DKI Jakarta">DKI Jakarta</option>
                                        <option value="Jawa Barat">Jawa Barat</option>
                                        <option value="Banten">Banten</option>
                                        <option value="Jawa Tengah">Jawa Tengah</option>
                                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                                        <option value="Jawa Timur">Jawa Timur</option>
                                        <option value="Bali">Bali</option>
                                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>
                                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                                        <option value="Kalimantan Utara">Kalimantan Utara</option>
                                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                                        <option value="Gorontalo">Gorontalo</option>
                                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>
                                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                                        <option value="Maluku">Maluku</option>
                                        <option value="Maluku Utara">Maluku Utara</option>
                                        <option value="Papua Barat">Papua Barat</option>
                                        <option value="Papua">Papua</option>
                                        <option value="Papua Tengah">Papua Tengah</option>
                                        <option value="Papua Pegunungan">Papua Pegunungan</option>
                                        <option value="Papua Selatan">Papua Selatan</option>
                                        <option value="Papua Barat Daya">Papua Barat Daya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="pendudukLatitude" class="form-label">Latitude</label>
                                    <input type="text" class="form-control" id="pendudukLatitude" name="latitude" placeholder="-6.123456">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="pendudukLongitude" class="form-label">Longitude</label>
                                    <input type="text" class="form-control" id="pendudukLongitude" name="longitude" placeholder="106.123456">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" class="btn btn-info btn-block" id="getLocationBtn">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <small id="locationStatus" class="text-muted"></small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitPendudukBtn">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- USER MODAL -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Tambah Pengguna Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="userForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" id="formAction" value="add">
                        <input type="hidden" id="userId" name="id">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userName" class="form-label form-label-required">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="userName" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userEmail" class="form-label form-label-required">Email</label>
                                    <input type="email" class="form-control" id="userEmail" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userPassword" class="form-label" id="passwordRequired">Password</label>
                                    <input type="password" class="form-control" id="userPassword" name="password">
                                    <small id="passwordHint" class="form-text text-muted">Min. 6 karakter</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userRole" class="form-label form-label-required">Role</label>
                                    <select class="form-control" id="userRole" name="role" required>
                                        <option value="user">User</option>
                                        <option value="staff">Staff</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userLanguage" class="form-label">Bahasa</label>
                                    <select class="form-control" id="userLanguage" name="language_preference">
                                        <option value="id">Indonesia</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="userApproved" class="form-label">Status Persetujuan</label>
                                    <select class="form-control" id="userApproved" name="is_approved">
                                        <option value="1">Disetujui</option>
                                        <option value="0">Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="userReason" class="form-label">Alasan Registrasi</label>
                            <textarea class="form-control" id="userReason" name="registration_reason" rows="3" placeholder="Opsional"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="submitUserBtn">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin keluar?</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">Pilih "Logout" untuk mengakhiri sesi.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </div>
    </div>



    <!-- Audio Element for Music Player -->
    <audio id="musicPlayer" preload="metadata">
        <source src="{{ asset('sbadmin/music/DJ SUDAH TERBIASA TERJADI TANTE TEMAN DATANG KETIKA BUTUH SAJA VIRAL TIKTOK TERBARU 2025.mp3') }}" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>

    <!-- Scripts -->
    <script src="{{ asset('sbadmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('sbadmin/js/sb-admin-2.min.js') }}"></script>
    <script src="{{ asset('sbadmin/vendor/chart.js/Chart.min.js') }}"></script>
    
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.0/dist/sweetalert2.all.min.js"></script>

   <script>
    // Setup CSRF Token untuk semua AJAX
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    let dataTable, pendudukTable;
    let currentSearch = '';

    // Page Navigation (tetap seperti asli)
    function showPage(page) {
        $('.page-section').removeClass('active');
        $('#page-' + page).addClass('active');
        $('.sidebar .nav-link').removeClass('active-menu');
        $('#menu-' + page).addClass('active-menu');

        if (page === 'pengaturan') {
            $('#page-pengaturan').show();
        }

        if (page === 'users') {
            loadUsers();
            loadRegisterRequests();
        }
        if (page === 'datamaster') loadPenduduk();
    }

    $(document).ready(function() {
        // Setup CSRF token untuk semua AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Dashboard aktif pertama
        $('#menu-dashboard').addClass('active-menu');

        // Load data awal
        loadPenduduk();
        loadUsers();
        loadRegisterRequests();

        // Event untuk search input dengan debounce
        let searchTimeout;
        $('#searchPenduduk').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadPenduduk($(this).val());
            }, 300); // Debounce 300ms
        });

        // Event untuk tombol search
        $('#searchBtn').on('click', function() {
            loadPenduduk($('#searchPenduduk').val());
        });

        // Event untuk tombol refresh
        $('#refreshBtn').on('click', function() {
            $('#searchPenduduk').val('');
            loadPenduduk();
        });

        // Validasi input penduduk (tetap ada)
        $('#pendudukNik').on('input', function() { this.value = this.value.replace(/[^0-9]/g, '').slice(0, 16); });
        $('#pendudukRt, #pendudukRw').on('input', function() { this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3); });
        $('#pendudukLatitude, #pendudukLongitude').on('input', function() { this.value = this.value.replace(/[^0-9.\-]/g, ''); });

        // Geolocation (tetap ada)
        $('#getLocationBtn').on('click', function() {
            const status = $('#locationStatus');
            if (navigator.geolocation) {
                status.text('Mendapatkan lokasi...').removeClass('text-danger text-success').addClass('text-warning');
                navigator.geolocation.getCurrentPosition(
                    pos => {
                        $('#pendudukLatitude').val(pos.coords.latitude.toFixed(6));
                        $('#pendudukLongitude').val(pos.coords.longitude.toFixed(6));
                        status.text('Lokasi berhasil!').addClass('text-success').removeClass('text-warning');
                    },
                    err => {
                        status.text('Gagal mendapatkan lokasi').addClass('text-danger');
                    }
                );
            } else {
                status.text('Browser tidak mendukung geolokasi').addClass('text-danger');
            }
        });
    });

    // Undangan pengguna (tetap seperti asli)
    function submitInviteForm(e) {
        e.preventDefault();
        sendInvite();
    }

    // Bind invite form submission
    $('#inviteForm').on('submit', submitInviteForm);

    function sendInvite() {
        const data = {
            name: $('#inviteName').val().trim(),
            email: $('#inviteEmail').val().trim(),
            role: $('#inviteRole').val()
        };
        if (!data.name || !data.email || !data.role) return Swal.fire('Error!', 'Semua field wajib!', 'error');
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email)) return Swal.fire('Error!', 'Email salah!', 'error');

        $('#sendInviteBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Membuat...');
        $.ajax({
            url: '{{ url("/users/invite") }}',
            method: 'POST',
            data: data,
            success: function(response) {
                $('#inviteForm')[0].reset();
                if (response.gmail_url) {
                    window.open(response.gmail_url, '_blank');
                }
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000, showConfirmButton: false })
                    .then(() => loadUsers());
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal membuat user', 'error');
            },
            complete: function() {
                $('#sendInviteBtn').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim Undangan');
            }
        });
    }

    // === USER MANAGEMENT (CRUD full AJAX + auto refresh) ===
    let usersDataTable = null;
    
    function loadUsers() {
        if (!$('#usersTable').length) return; // Skip if table doesn't exist yet
        $.get('{{ url("/users/list") }}').done(function(response) {
            const users = Array.isArray(response) ? response : (response.users || []);
            
            // Build table rows
            let html = '';
            if (users.length > 0) {
                users.forEach((user, index) => {
                    const roleName = user.roles?.[0] || 'user';
                    const roleClass = roleName === 'admin' ? 'danger' : roleName === 'staff' ? 'warning' : 'info';
                    const statusClass = user.is_approved ? 'success' : 'warning';
                    html += `<tr data-user-id="${user.id}">
                            <td class="text-center">${index + 1}</td>
                            <td><strong>${user.name}</strong></td>
                            <td>${user.email}</td>
                            <td><span class="badge badge-role badge-${roleClass}">${roleName.toUpperCase()}</span></td>
                            <td><span class="badge badge-status badge-${statusClass}">${user.is_approved ? 'Disetujui' : 'Pending'}</span></td>
                            <td>${user.language_preference === 'id' ? 'Indonesia' : 'English'}</td>
                            <td>${new Date(user.created_at).toLocaleDateString('id-ID')}</td>
                            <td class="text-center table-actions">
                                <button class="btn btn-sm btn-warning" onclick="editUser(${user.id})"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser(${user.id})"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;
                });
            }
            $('#userTableBody').html(html || '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>');

            // Initialize or update DataTable without destroying
            if ($.fn.DataTable.isDataTable('#usersTable')) {
                usersDataTable.clear();
                if (users.length > 0) {
                    usersDataTable.rows.add($('#userTableBody tr'));
                }
                usersDataTable.draw();
            } else {
                usersDataTable = $('#usersTable').DataTable({
                    language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" },
                    pageLength: 10
                });
            }
        }).fail(function(xhr) {
            console.error('Failed to load users:', xhr.responseText);
        });
    }

    function openUserModal(action, userId = null) {
        $('#userForm')[0].reset();
        $('#formAction').val(action);
        if (action === 'add') {
            $('#userModalTitle').text('Tambah Pengguna Baru');
            $('#userId').val('');
            $('#userPassword').prop('required', true);
            $('#passwordRequired').addClass('form-label-required');
            $('#passwordHint').text('Min. 6 karakter');
            $('#submitUserBtn').html('<i class="fas fa-save"></i> Simpan');
        } else {
            $('#userModalTitle').text('Edit Pengguna');
            $('#userPassword').prop('required', false);
            $('#passwordRequired').removeClass('form-label-required');
            $('#passwordHint').text('Kosongkan jika tidak ingin ubah password');
            $('#submitUserBtn').html('<i class="fas fa-save"></i> Update');
        }
        $('#userModal').modal('show');
    }

    function editUser(id) {
        $.get('{{ url("/users") }}/' + id).done(function(response) {
            openUserModal('edit');
            $('#userId').val(response.id);
            $('#userName').val(response.name);
            $('#userEmail').val(response.email);
            $('#userRole').val(response.roles?.[0]?.name || 'user');

            $('#userApproved').val(response.is_approved ? '1' : '0');
            $('#userReason').val(response.registration_reason);
        });
    }

    $('#userForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const action = $('#formAction').val();
        const userId = $('#userId').val();
        let url = '{{ url("/users") }}';
        if (action === 'edit') {
            url += '/' + userId;
            formData.append('_method', 'PUT');
        }

        $('#submitUserBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#userModal').modal('hide');
                Swal.fire({icon: 'success', title: 'Berhasil!', text: response.message || 'Data tersimpan', timer: 2000});
                loadUsers();
            },
            error: function(xhr) {
                Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal simpan', 'error');
            },
            complete: function() {
                $('#submitUserBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    function deleteUser(id) {
        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data pengguna akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url("/users") }}/${id}`,
                    method: 'DELETE',
                    success: function() {
                        Swal.fire('Terhapus!', '', 'success');
                        loadUsers();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal menghapus user', 'error');
                    }
                });
            }
        });
    }

    // === DATA MASTER PENDUDUK (CRUD full AJAX + auto refresh tanpa flicker) ===
    let pendudukXHR = null; // Store current AJAX request
    function loadPenduduk(search = '') {
        if (!$('#pendudukTable').length) return; // Skip if table doesn't exist yet
        
        // Reset bulk delete UI
        $('#selectAllPenduduk').prop('checked', false);
        $('#deleteSelectedBtn').hide();
        $('#selectedCount').text('0');

        // Cancel previous request if still running
        if (pendudukXHR) {
            pendudukXHR.abort();
        }

        // Destroy existing table if it exists
        if (pendudukTable) {
            pendudukTable.destroy();
            pendudukTable = null;
        }

        // Clear table body first
        $('#pendudukTableBody').html('<tr><td colspan="8" class="text-center"><i class="fas fa-spinner fa-spin"></i> Memuat data...</td></tr>');

        // Load data via AJAX and initialize DataTable
        pendudukXHR = $.get('{{ url("/penduduk/penduduk") }}', { search: search }, function(response) {
            let html = '';
            if (!response || !Array.isArray(response)) {
                html = '<tr><td colspan="8" class="text-center text-danger">Error: Invalid response format</td></tr>';
            } else if (response.length === 0) {
                html = '<tr><td colspan="8" class="text-center">Tidak ada data</td></tr>';
                $('#pendudukTableBody').html(html);
                return; // Don't initialize DataTable for empty data
            } else {
                response.forEach((item, i) => {
                    html += `<tr>
                        <td><input type="checkbox" class="penduduk-checkbox" value="${item.id}" onchange="updateSelectedCount()"></td>
                        <td>${i+1}</td>
                        <td><strong>${item.nik || '-'}</strong></td>
                        <td>${item.nama || '-'}</td>
                        <td>${item.alamat ? item.alamat.substring(0,50) + (item.alamat.length > 50 ? '...' : '') : '-'}</td>
                        <td>${item.rt || '-'}/${item.rw || '-'}</td>
                        <td>${item.provinsi || '-'}</td>
                        <td>
                            <button class="btn btn-sm btn-warning" onclick="editPenduduk(${item.id})"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deletePenduduk(${item.id})"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>`;
                });
            }
            $('#pendudukTableBody').html(html);

            // Only initialize DataTable if there's data
            if (response && Array.isArray(response) && response.length > 0) {
                pendudukTable = $('#pendudukTable').DataTable({
                    language: { url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json" },
                    pageLength: 10,
                    ordering: false,
                    searching: false,
                    paging: true,
                    info: true
                });
            }
        }).fail(function(xhr) {
            if (xhr.status !== 0) { // Ignore aborted requests
                console.error('Failed to load penduduk:', xhr.responseText);
                $('#pendudukTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
            }
        });
    }

    function openPendudukModal(action, data = null) {
        $('#pendudukForm')[0].reset();
        $('#pendudukId').val('');
        $('#pendudukModalTitle').text(action === 'add' ? 'Tambah Penduduk' : 'Edit Penduduk');
        if (data) {
            $('#pendudukId').val(data.id);
            $('#pendudukNik').val(data.nik);
            $('#pendudukNama').val(data.nama);
            $('#pendudukAlamat').val(data.alamat);
            $('#pendudukRt').val(data.rt);
            $('#pendudukRw').val(data.rw);
            $('#pendudukProvinsi').val(data.provinsi);
            $('#pendudukLatitude').val(data.latitude || '');
            $('#pendudukLongitude').val(data.longitude || '');
        }
        $('#pendudukModal').modal('show');
    }

    function editPenduduk(id) {
        $.get(`{{ url("/penduduk/penduduk") }}/${id}`).done(data => openPendudukModal('edit', data));
    }

    $('#pendudukForm').on('submit', function(e) {
        e.preventDefault();

        const nik = $('#pendudukNik').val().trim();
        if (!/^\d{16}$/.test(nik)) return Swal.fire('Error!', 'NIK harus 16 digit angka!', 'error');

        const rt = $('#pendudukRt').val().trim();
        const rw = $('#pendudukRw').val().trim();
        if (!rt || !rw || !/^\d{1,3}$/.test(rt) || !/^\d{1,3}$/.test(rw)) return Swal.fire('Error!', 'RT/RW harus angka 1-3 digit!', 'error');

        const lat = $('#pendudukLatitude').val().trim();
        const lng = $('#pendudukLongitude').val().trim();
        if (lat && (isNaN(lat) || lat < -90 || lat > 90)) return Swal.fire('Error!', 'Latitude salah!', 'error');
        if (lng && (isNaN(lng) || lng < -180 || lng > 180)) return Swal.fire('Error!', 'Longitude salah!', 'error');

        // Dummy fields tetap ditambahkan
        $(this).append('<input type="hidden" name="tanggal_lahir" value="1990-01-01">');
        $(this).append('<input type="hidden" name="jenis_kelamin" value="Laki-laki">');
        $(this).append('<input type="hidden" name="status_perkawinan" value="Belum Kawin">');
        $(this).append('<input type="hidden" name="pekerjaan" value="Tidak diketahui">');

        const id = $('#pendudukId').val();
        const url = id ? `{{ url("/penduduk/penduduk") }}/${id}` : '{{ url("/penduduk/penduduk") }}';
        const method = id ? 'PUT' : 'POST';

        const formData = new FormData(this);
        formData.append('_token', '{{ csrf_token() }}');
        if (id) {
            formData.append('_method', 'PUT');
        }

        $('#submitPendudukBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#pendudukModal').modal('hide');
                Swal.fire('Sukses!', 'Data penduduk tersimpan', 'success');
                loadPenduduk($('#searchPenduduk').val());
            },
            error: function() {
                Swal.fire('Error!', 'Gagal menyimpan penduduk', 'error');
            },
            complete: function() {
                $('#submitPendudukBtn').prop('disabled', false).html('Simpan');
            }
        });
    });

    function deletePenduduk(id) {
        Swal.fire({
            title: 'Yakin hapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus!'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url("/penduduk/penduduk") }}/${id}`,
                    method: 'POST',
                    data: {'_method': 'DELETE'},
            success: function() {
                Swal.fire('Terhapus!', '', 'success');
                loadPenduduk($('#searchPenduduk').val());
            }
                });
            }
        });
    }

    // === BULK DELETE FUNCTIONS ===
    function toggleAllPenduduk(source) {
        const checkboxes = document.querySelectorAll('.penduduk-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = source.checked;
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.penduduk-checkbox:checked');
        const count = checkboxes.length;
        const deleteBtn = $('#deleteSelectedBtn');
        $('#selectedCount').text(count);
        
        if (count > 0) {
            deleteBtn.show();
        } else {
            deleteBtn.hide();
        }
    }

    function bulkDeletePenduduk() {
        const checkboxes = document.querySelectorAll('.penduduk-checkbox:checked');
        const ids = Array.from(checkboxes).map(cb => cb.value);
        
        if (ids.length === 0) {
            Swal.fire('Peringatan!', 'Pilih setidaknya satu data untuk dihapus', 'warning');
            return;
        }

        Swal.fire({
            title: `Yakin hapus ${ids.length} data?`,
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url("/penduduk/penduduk/bulk-delete") }}`,
                    method: 'POST',
                    data: {
                        ids: ids,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', response.message, 'success');
                            // Reset checkboxes
                            $('#selectAllPenduduk').prop('checked', false);
                            loadPenduduk($('#searchPenduduk').val());
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal menghapus data', 'error');
                    }
                });
            }
        });
    }

    // === REGISTRATION REQUESTS MANAGEMENT ===
    let registerRequestsData = [];
    let currentRejectId = null;

    function loadRegisterRequests() {
        if (!$('#registerRequestsTable').length) return;
        
        $.get('{{ url("/admin/register-requests/data") }}').done(function(response) {
            registerRequestsData = response;
            renderRegisterRequests(response);
        }).fail(function(xhr) {
            console.error('Failed to load register requests:', xhr.responseText);
            $('#registerRequestsTableBody').html('<tr><td colspan="8" class="text-center text-danger">Gagal memuat data</td></tr>');
        });
    }

    function renderRegisterRequests(data) {
        const tbody = $('#registerRequestsTableBody');
        if (!data || data.length === 0) {
            tbody.html('<tr><td colspan="8" class="text-center">Belum ada permintaan registrasi</td></tr>');
            return;
        }
        
        let html = '';
        data.forEach(item => {
            const statusBadge = item.status === 'pending' ? 
                '<span class="badge badge-warning">Pending</span>' : 
                item.status === 'disetujui' ? 
                    '<span class="badge badge-success">Disetujui</span>' : 
                    '<span class="badge badge-danger">Ditolak</span>';
            
            let actionButtons = '';
            if (item.status === 'pending') {
                actionButtons = `
                    <button class="btn btn-sm btn-info" onclick="viewRegisterRequestDetail(${item.id})" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-success" onclick="approveRegisterRequest(${item.id})" title="Terima"><i class="fas fa-check"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="openRejectModal(${item.id})" title="Tolak"><i class="fas fa-times"></i></button>
                    <button class="btn btn-sm btn-secondary" onclick="deleteRegisterRequest(${item.id})" title="Hapus"><i class="fas fa-trash"></i></button>
                `;
            } else {
                actionButtons = `
                    <button class="btn btn-sm btn-info" onclick="viewRegisterRequestDetail(${item.id})" title="Lihat Detail"><i class="fas fa-eye"></i></button>
                    <button class="btn btn-sm btn-secondary" onclick="deleteRegisterRequest(${item.id})" title="Hapus"><i class="fas fa-trash"></i></button>
                `;
            }
            
            html += `<tr>
                <td><strong>${String(item.id).padStart(6, '0')}</strong></td>
                <td>${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
                <td><strong>${item.nama_lengkap}</strong></td>
                <td>${item.nik}</td>
                <td>${item.email}</td>
                <td>${item.instansi}</td>
                <td>${statusBadge}</td>
                <td class="table-actions">${actionButtons}</td>
            </tr>`;
        });
        
        tbody.html(html);
    }

    // Search and Filter Functions for Registration Requests
    let currentRegisterRequestFilter = 'all';
    
    function searchRegisterRequests() {
        const searchTerm = $('#searchRegisterRequest').val().toLowerCase();
        
        if (!searchTerm) {
            renderFilteredRegisterRequests();
            return;
        }
        
        const filtered = registerRequestsData.filter(item => {
            const nama = (item.nama_lengkap || '').toLowerCase();
            const email = (item.email || '').toLowerCase();
            const nik = (item.nik || '').toLowerCase();
            const id = String(item.id).toLowerCase();
            const noReg = String(item.id).padStart(6, '0');
            
            return nama.includes(searchTerm) || 
                   email.includes(searchTerm) || 
                   nik.includes(searchTerm) || 
                   id.includes(searchTerm) ||
                   noReg.includes(searchTerm);
        });
        
        renderRegisterRequests(filtered);
    }
    
    function clearRegisterRequestSearch() {
        $('#searchRegisterRequest').val('');
        renderFilteredRegisterRequests();
    }
    
    function filterRegisterRequests(status) {
        currentRegisterRequestFilter = status;
        renderFilteredRegisterRequests();
    }
    
    function renderFilteredRegisterRequests() {
        let filtered = registerRequestsData;
        
        // Apply status filter
        if (currentRegisterRequestFilter !== 'all') {
            filtered = filtered.filter(item => item.status === currentRegisterRequestFilter);
        }
        
        // Apply search filter
        const searchTerm = $('#searchRegisterRequest').val().toLowerCase();
        if (searchTerm) {
            filtered = filtered.filter(item => {
                const nama = (item.nama_lengkap || '').toLowerCase();
                const email = (item.email || '').toLowerCase();
                const nik = (item.nik || '').toLowerCase();
                const id = String(item.id).toLowerCase();
                
                return nama.includes(searchTerm) || 
                       email.includes(searchTerm) || 
                       nik.includes(searchTerm) || 
                       id.includes(searchTerm);
            });
        }
        
        renderRegisterRequests(filtered);
    }

    function viewRegisterRequestDetail(id) {
        const item = registerRequestsData.find(x => x.id === id);
        if (!item) return;
        
        const profilePhoto = item.foto_profil ? `/storage/${item.foto_profil}` : '';
        const ktpPhoto = item.foto_ktp ? `/storage/${item.foto_ktp}` : '';
        
        let photosHtml = '';
        if (profilePhoto || ktpPhoto) {
            photosHtml = '<div class="col-12 mt-3">';
            if (profilePhoto) {
                photosHtml += `
                    <p><strong>Foto Profil:</strong></p>
                    <div class="text-center mb-3">
                        <img src="${profilePhoto}" alt="Foto Profil" style="max-width:150px;max-height:150px;border-radius:50%;border:3px solid #0d6efd;object-fit:cover;">
                    </div>
                `;
            }
            if (ktpPhoto) {
                photosHtml += `
                    <p><strong>Foto KTP:</strong></p>
                    <div class="text-center mb-3">
                        <img src="${ktpPhoto}" alt="Foto KTP" style="max-width:100%;max-height:300px;border-radius:8px;border:2px solid #dee2e6;cursor:pointer;" onclick="window.open('${ktpPhoto}', '_blank')" title="Klik untuk memperbesar">
                    </div>
                `;
            }
            photosHtml += '</div>';
        }
        
        const detailHtml = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Lengkap:</strong> ${item.nama_lengkap}</p>
                    <p><strong>NIK:</strong> ${item.nik}</p>
                    <p><strong>NIP:</strong> ${item.nip || '-'}</p>
                    <p><strong>Tanggal Lahir:</strong> ${new Date(item.tanggal_lahir).toLocaleDateString('id-ID')}</p>
                    <p><strong>Jenis Kelamin:</strong> ${item.jenis_kelamin}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Alamat:</strong> ${item.alamat}, RT ${item.rt}/RW ${item.rw}, ${item.kelurahan}, ${item.kecamatan}, ${item.kota}, ${item.provinsi}</p>
                    <p><strong>No. Telepon:</strong> ${item.no_telepon}</p>
                    <p><strong>Email:</strong> ${item.email}</p>
                    <p><strong>Jabatan:</strong> ${item.jabatan}</p>
                    <p><strong>Instansi:</strong> ${item.instansi}</p>
                    <p><strong>Unit Kerja:</strong> ${item.unit_kerja}</p>
                </div>
                <div class="col-12 mt-3">
                    <p><strong>Alasan Pendaftaran:</strong></p>
                    <div class="alert alert-info">${item.alasan_pendaftaran}</div>
                </div>
                ${photosHtml}
                ${item.status !== 'pending' ? `
                <div class="col-12 mt-2">
                    <p><strong>Catatan Admin:</strong></p>
                    <div class="alert alert-secondary">${item.catatan_admin || '-'}</div>
                </div>
                ` : ''}
            </div>
        `;
        
        $('#requestDetailBody').html(detailHtml);
        $('#requestDetailModal').modal('show');
    }

    function approveRegisterRequest(id) {
        const item = registerRequestsData.find(x => x.id === id);
        if (!item) return;
        
        Swal.fire({
            title: 'Setuju Permintaan?',
            text: 'User akan dibuat dengan password acak. Anda perlu mengirim email secara manual.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Setuju!',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/admin/register-requests') }}/${id}/approve`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            const password = response.temp_password;
                            const email = item.email;
                            const nama = item.nama_lengkap;
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                html: `Permintaan disetujui!<br><strong>Password: ${password}</strong>`,
                                showDenyButton: true,
                                confirmButtonText: 'Buka Gmail',
                                denyButtonText: 'Tutup'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Open Gmail with auto-composed email
                                    const subject = encodeURIComponent('Selamat! Pendaftaran Anda Diterima - Sistem Informasi Pertanahan');
                                    const body = encodeURIComponent(
                                        `Halo ${nama}!\n\n` +
                                        `Selamat! Anda telah LULUS seleksi dan diterima sebagai pengguna Sistem Informasi Pertanahan.\n\n` +
                                        `Berikut adalah informasi akun Anda:\n` +
                                        `━━━━━━━━━━━━━━━━━━━━\n` +
                                        `Email: ${email}\n` +
                                        `Password: ${password}\n` +
                                        `━━━━━━━━━━━━━━━━━━━━\n\n` +
                                        `Silakan login di: http://127.0.0.1:8000/login\n\n` +
                                        `Setelah login, segera ubah password Anda untuk keamanan.\n\n` +
                                        'Hormat kami,\\nKementerian ATR/BPN'
                                    );
                                    window.open(`https://mail.google.com/mail/?view=cm&to=${email}&su=${subject}&body=${body}`, '_blank');
                                }
                            });
                            
                            loadRegisterRequests();
                            loadUsers();
                        } else {
                            Swal.fire('Error!', response.message || 'Gagal menyetujui', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Gagal menyetujui: ' + (xhr.responseJSON?.message || xhr.statusText), 'error');
                    }
                });
            }
        });
    }

    function openRejectModal(id) {
        currentRejectId = id;
        $('#rejectReason').val('');
        $('#rejectModal').modal('show');
    }

    $('#confirmRejectBtn').on('click', function() {
        const reason = $('#rejectReason').val();
        
        if (!currentRejectId) return;
        
        $.ajax({
            url: `{{ url('/admin/register-requests') }}/${currentRejectId}/reject`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: { 
                reason: reason 
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Berhasil!', 'Permintaan ditolak', 'success');
                    $('#rejectModal').modal('hide');
                    loadRegisterRequests();
                } else {
                    Swal.fire('Error!', response.message || 'Gagal menolak', 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Gagal menolak: ' + (xhr.responseJSON?.message || xhr.statusText), 'error');
            }
        });
    });

    function deleteRegisterRequest(id) {
        Swal.fire({
            title: 'Hapus Permintaan?',
            text: 'Data akan dihapus dari database dan user bisa mendaftar ulang.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('/admin/register-requests') }}/${id}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil!', 'Permintaan berhasil dihapus', 'success');
                            loadRegisterRequests();
                        } else {
                            Swal.fire('Error!', response.message || 'Gagal menghapus', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal menghapus permintaan', 'error');
                    }
                });
            }
        });
    }

    // === SETTINGS PAGE (semua fungsi tetap utuh) ===
    // Profile photo preview
    $('#profile_photo').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePhotoPreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        const btn = $('#saveProfileBtn');
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        var formData = new FormData(this);

        $.ajax({
            url: '{{ url("/profile") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': 'PATCH'
            },
            success: function(response) {
                Swal.fire({icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000});
                if ($('#name').val() !== '{{ auth()->user()->name }}') $('.sidebar .text-gray-600').text($('#name').val());
                // Update profile photo in preview and topbar
                if (response.profile_photo_url) {
                    $('#profilePhotoPreview').attr('src', response.profile_photo_url);
                    $('#topbarProfilePhoto').attr('src', response.profile_photo_url);
                }
            },
            error: function(xhr) { Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal update profil', 'error'); },
            complete: () => btn.prop('disabled', false).html(original)
        });
    });

    $('#passwordForm').on('submit', function(e) {
        e.preventDefault();
        if ($('#password').val() !== $('#password_confirmation').val()) return Swal.fire('Error!', 'Konfirmasi password beda!', 'error');

        const btn = $('#changePasswordBtn');
        const original = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengubah...');

        $.ajax({
            url: '{{ url("/password/update") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                Swal.fire({icon: 'success', title: 'Berhasil!', text: response.message, timer: 2000});
                $('#passwordForm')[0].reset();
            },
            error: function(xhr) { Swal.fire('Error!', xhr.responseJSON?.message || 'Gagal ubah password', 'error'); },
            complete: () => btn.prop('disabled', false).html(original)
        });
    });



    function generateLaporanSertifikasi() {
        window.open('{{ url("/laporan/sertifikasi") }}', '_blank');
    }

    function generateLaporanPenduduk() {
        // Opens the print-ready penduduk page (controlled by route /laporan/print)
        window.open('{{ url("/laporan/print") }}?source=penduduk', '_blank');
    }

    function generateLaporanLahan() {
        // Opens the print-ready lahan page (controlled by route /laporan/print)
        window.open('{{ url("/laporan/print") }}?source=lahan', '_blank');
    }

    // Chart (tetap ada)
    if (document.getElementById('myAreaChart')) {
        var ctx = document.getElementById('myAreaChart').getContext('2d');
        var myAreaChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['2020', '2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Bidang Tersertipikat (Juta)',
                    data: [45, 58, 68, 75, 80, 82.3],
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: false } }
            }
        });
    }
</script>
<script>
  window.addEventListener('message', function(e) {
    if (e.origin !== window.location.origin) return;
    var data = e.data;
    if (data && data.type === 'navigate' && data.url) {
      window.location.href = data.url;
      try { window.focus(); } catch (err) {}
    }
  });
</script>

<!-- Kantor ATR/BPN Detail Modal -->
<div class="modal fade" id="kantorDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #059669, #10b981);">
                <h5 class="modal-title text-white"><i class="fas fa-building"></i> Detail Kantor ATR/BPN</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="kantorDetailContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="#" target="_blank" id="kantorMapsLink" class="btn btn-success"><i class="fas fa-map"></i> Buka di Google Maps</a>
            </div>
        </div>
    </div>
</div>

<!-- Theme & Settings JavaScript -->
<script>
// Load saved settings on page load
document.addEventListener('DOMContentLoaded', function() {
    // Load theme preference
    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-theme');
        document.getElementById('themeMode').value = 'dark';
    }
    
    // Load font size preference
    const savedFontSize = localStorage.getItem('fontSize') || '16px';
    document.getElementById('fontSize').value = savedFontSize;
    if (savedFontSize === '18px') {
        document.body.classList.add('large-font');
    }
});

// Apply theme function
function applyTheme() {
    const theme = document.getElementById('themeMode').value;
    if (theme === 'dark') {
        document.body.classList.add('dark-theme');
        localStorage.setItem('theme', 'dark');
    } else {
        document.body.classList.remove('dark-theme');
        localStorage.setItem('theme', 'light');
    }
    showToast('Tema berhasil disimpan!', 'success');
}

// Apply font size function
function applyFontSize() {
    const fontSize = document.getElementById('fontSize').value;
    document.body.classList.remove('large-font');
    if (fontSize === '18px') {
        document.body.classList.add('large-font');
    }
    localStorage.setItem('fontSize', fontSize);
    showToast('Ukuran font berhasil disimpan!', 'success');
}

// Theme form submit handler
document.getElementById('themeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    applyTheme();
    applyFontSize();
});

// === KANTOR ATR/BPN DATA ===
const kantorATR = [
    { nama: 'Kantor Wilayah BPN Provinsi DKI Jakarta', kota: 'Jakarta', alamat: 'Jl. Letjen MT Haryono Kav. 52, Jakarta Selatan', phone: '(021) 798-4501', fax: '(021) 798-4505', email: 'kanwil.jakarta@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Jakarta Selatan', kota: 'Jakarta', alamat: 'Jl. Kemang Raya No. 17, Jakarta Selatan', phone: '(021) 719-1234', fax: '(021) 719-1235', email: 'kantah.jakartaselatan@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Jakarta Utara', kota: 'Jakarta', alamat: 'Jl. Pluit Raya No. 1, Jakarta Utara', phone: '(021) 660-1234', fax: '(021) 660-1235', email: 'kantah.jakartautara@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Jakarta Barat', kota: 'Jakarta', alamat: 'Jl. Palmerah Raya No. 21, Jakarta Barat', phone: '(021) 548-1234', fax: '(021) 548-1235', email: 'kantah.jakartabarat@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Jakarta Timur', kota: 'Jakarta', alamat: 'Jl. Radin Inten II No. 1, Jakarta Timur', phone: '(021) 489-1234', fax: '(021) 489-1235', email: 'kantah.jakartatimur@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Jakarta Pusat', kota: 'Jakarta', alamat: 'Jl. Abdul Muis No. 2, Jakarta Pusat', phone: '(021) 690-1234', fax: '(021) 690-1235', email: 'kantah.jakartapusat@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Jawa Barat', kota: 'Bandung', alamat: 'Jl. Asia Afrika No. 1, Bandung', phone: '(022) 423-1234', fax: '(022) 423-1235', email: 'kanwil.jabar@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Bandung', kota: 'Bandung', alamat: 'Jl. Braga No. 91, Bandung', phone: '(022) 421-1234', fax: '(022) 421-1235', email: 'kantah.bandung@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Bekasi', kota: 'Bekasi', alamat: 'Jl. Ahmad Yani No. 1, Bekasi', phone: '(021) 889-1234', fax: '(021) 889-1235', email: 'kantah.bekasi@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Depok', kota: 'Depok', alamat: 'Jl. Margonda Raya No. 1, Depok', phone: '(021) 779-1234', fax: '(021) 779-1235', email: 'kantah.depok@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Bogor', kota: 'Bogor', alamat: 'Jl. Jemekesari No. 1, Bogor', phone: '(0251) 832-1234', fax: '(0251) 832-1235', email: 'kantah.bogor@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Jawa Tengah', kota: 'Semarang', alamat: 'Jl. Pemandian No. 1, Semarang', phone: '(024) 831-1234', fax: '(024) 831-1235', email: 'kanwil.jateng@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Semarang', kota: 'Semarang', alamat: 'Jl. Pahlawan No. 1, Semarang', phone: '(024) 841-1234', fax: '(024) 841-1235', email: 'kantah.semarang@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Surakarta', kota: 'Surakarta', alamat: 'Jl. Sudirman No. 1, Surakarta', phone: '(0271) 123-456', fax: '(0271) 123-457', email: 'kantah.surakarta@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Jawa Timur', kota: 'Surabaya', alamat: 'Jl. Raya Surabaya Malang Km. 6, Sukun, Malang', phone: '(031) 574-1234', fax: '(031) 574-1235', email: 'kanwil.jatim@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Surabaya', kota: 'Surabaya', alamat: 'Jl. Basuki Rahmat No. 1, Surabaya', phone: '(031) 531-1234', fax: '(031) 531-1235', email: 'kantah.surabaya@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Malang', kota: 'Malang', alamat: 'Jl. Ijen No. 1, Malang', phone: '(0341) 551-1234', fax: '(0341) 551-1235', email: 'kantah.malang@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Banten', kota: 'Serang', alamat: 'Jl. Syech Quro No. 1, Serang', phone: '(0254) 123-456', fax: '(0254) 123-457', email: 'kanwil.banten@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Tangerang', kota: 'Tangerang', alamat: 'Jl. Suparno No. 1, Tangerang', phone: '(021) 557-1234', fax: '(021) 557-1235', email: 'kantah.tangerang@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Serang', kota: 'Serang', alamat: 'Jl. Kh. Wahabsyah No. 1, Serang', phone: '(0254) 201-1234', fax: '(0254) 201-1235', email: 'kantah.serang@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Sumatera Utara', kota: 'Medan', alamat: 'Jl. Letjen D.I. Panjaitan No. 1, Medan', phone: '(061) 456-1234', fax: '(061) 456-1235', email: 'kanwil.sumut@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Medan', kota: 'Medan', alamat: 'Jl. Sisingamangaraja No. 1, Medan', phone: '(061) 789-1234', fax: '(061) 789-1235', email: 'kantah.medan@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Pertanahan Kota Pematang Siantar', kota: 'Pematang Siantar', alamat: 'Jl. SM Raja No. 1, Pematang Siantar', phone: '(0622) 123-456', fax: '(0622) 123-457', email: 'kantah.pematangsiantar@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Sumatera Selatan', kota: 'Palembang', alamat: 'Jl. Jend. Sudirman No. 1, Palembang', phone: '(0711) 123-456', fax: '(0711) 123-457', email: 'kanwil.sumsel@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Palembang', kota: 'Palembang', alamat: 'Jl. POM IX No. 1, Palembang', phone: '(0711) 567-1234', fax: '(0711) 567-1235', email: 'kantah.palembang@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Lampung', kota: 'Bandar Lampung', alamat: 'Jl. Z.A. Pagar Alam No. 1, Bandar Lampung', phone: '(0721) 123-456', fax: '(0721) 123-457', email: 'kanwil.lampung@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Bandar Lampung', kota: 'Bandar Lampung', alamat: 'Jl. Pangeran Emir M. No. 1, Bandar Lampung', phone: '(0721) 789-1234', fax: '(0721) 789-1235', email: 'kantah.bandarlampung@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Kalimantan Barat', kota: 'Pontianak', alamat: 'Jl. Ahmad Yani No. 1, Pontianak', phone: '(0561) 123-456', fax: '(0561) 123-457', email: 'kanwil.kalbar@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Pontianak', kota: 'Pontianak', alamat: 'Jl. Parit Mayor No. 1, Pontianak', phone: '(0561) 734-1234', fax: '(0561) 734-1235', email: 'kantah.pontianak@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Kalimantan Timur', kota: 'Samarinda', alamat: 'Jl. MT Haryono No. 1, Samarinda', phone: '(0541) 123-456', fax: '(0541) 123-457', email: 'kanwil.kaltim@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Samarinda', kota: 'Samarinda', alamat: 'Jl. Pangeran高铁 No. 1, Samarinda', phone: '(0541) 734-1234', fax: '(0541) 734-1235', email: 'kantah.samarinda@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Sulawesi Selatan', kota: 'Makassar', alamat: 'Jl. Bontang Kuala No. 1, Makassar', phone: '(0411) 123-456', fax: '(0411) 123-457', email: 'kanwil.sulsel@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Makassar', kota: 'Makassar', alamat: 'Jl. AP Pettarani No. 1, Makassar', phone: '(0411) 456-1234', fax: '(0411) 456-1235', email: 'kantah.makassar@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Bali', kota: 'Denpasar', alamat: 'Jl.WR Supratman No. 1, Denpasar', phone: '(0361) 123-456', fax: '(0361) 123-457', email: 'kanwil.bali@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Denpasar', kota: 'Denpasar', alamat: 'Jl. Sunset Road No. 1, Denpasar', phone: '(0361) 789-1234', fax: '(0361) 789-1235', email: 'kantah.denpasar@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Nusa Tenggara Barat', kota: 'Mataram', alamat: 'Jl. Majapahit No. 1, Mataram', phone: '(0370) 123-456', fax: '(0370) 123-457', email: 'kanwil.ntb@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Mataram', kota: 'Mataram', alamat: 'Jl. Selaparang No. 1, Mataram', phone: '(0370) 634-1234', fax: '(0370) 634-1235', email: 'kantah.mataram@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Papua', kota: 'Jayapura', alamat: 'Jl. Sam Ratulangi No. 1, Jayapura', phone: '(0967) 123-456', fax: '(0967) 123-457', email: 'kanwil.papua@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Jayapura', kota: 'Jayapura', alamat: 'Jl. Papua Raya No. 1, Jayapura', phone: '(0967) 534-1234', fax: '(0967) 534-1235', email: 'kantah.jayapura@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'Kantor Wilayah BPN Provinsi Maluku', kota: 'Ambon', alamat: 'Jl. Sultan Hairun No. 1, Ambon', phone: '(0911) 123-456', fax: '(0911) 123-457', email: 'kanwil.maluku@bpn.go.id', type: 'Kantor Wilayah' },
    { nama: 'Kantor Pertanahan Kota Ambon', kota: 'Ambon', alamat: 'Jl. Jend. Sudirman No. 1, Ambon', phone: '(0911) 361-1234', fax: '(0911) 361-1235', email: 'kantah.ambon@bpn.go.id', type: 'Kantor Pertanahan' },
    { nama: 'KANTOR PUSAT ATR/BPN', kota: 'Jakarta', alamat: 'Jl. HM. Rasmin No. 1, Menteng, Jakarta Pusat', phone: '(021) 392-1234', fax: '(021) 392-1235', email: 'info@bpn.go.id', type: 'Kantor Pusat' }
];

// Initialize kantor list on page load
document.addEventListener('DOMContentLoaded', function() {
    renderKantorList(kantorATR);
});

// Render kantor list
function renderKantorList(data) {
    const container = document.getElementById('kantorList');
    if (!container) return;
    
    if (data.length === 0) {
        container.innerHTML = '<p class="text-center text-muted p-3">Tidak ada kantor yang ditemukan</p>';
        return;
    }
    
    let html = '';
    data.forEach(kantor => {
        const typeColor = kantor.type === 'Kantor Pusat' ? 'danger' : kantor.type === 'Kantor Wilayah' ? 'warning' : 'success';
        html += `
            <div class="kantor-item p-3 mb-2 border rounded" style="cursor: pointer; transition: all 0.3s;" onclick="showKantorDetail(${kantorATR.indexOf(kantor)})" onmouseover="this.style.backgroundColor='#f8f9fa'; this.style.transform='translateX(5px)'" onmouseout="this.style.backgroundColor=''; this.style.transform=''">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1 font-weight-bold"><i class="fas fa-building text-${typeColor}"></i> ${kantor.nama}</h6>
                        <p class="mb-1 text-muted small"><i class="fas fa-map-marker-alt"></i> ${kantor.alamat}</p>
                        <p class="mb-0 small"><i class="fas fa-city"></i> ${kantor.kota}</p>
                    </div>
                    <span class="badge badge-${typeColor}">${kantor.type}</span>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

// Search kantor
function searchKantor() {
    const query = document.getElementById('searchKantor').value.toLowerCase();
    const filtered = kantorATR.filter(kantor => 
        kantor.nama.toLowerCase().includes(query) ||
        kantor.kota.toLowerCase().includes(query) ||
        kantor.alamat.toLowerCase().includes(query) ||
        kantor.type.toLowerCase().includes(query)
    );
    renderKantorList(filtered);
}

// Show kantor detail in modal
function showKantorDetail(index) {
    const kantor = kantorATR[index];
    if (!kantor) return;
    
    const typeColor = kantor.type === 'Kantor Pusat' ? 'danger' : kantor.type === 'Kantor Wilayah' ? 'warning' : 'success';
    
    const content = `
        <div class="text-center mb-4">
            <i class="fas fa-building text-${typeColor}" style="font-size: 3rem;"></i>
            <h5 class="mt-3 font-weight-bold">${kantor.nama}</h5>
            <span class="badge badge-${typeColor} badge-lg">${kantor.type}</span>
        </div>
        <table class="table table-borderless">
            <tr>
                <td width="40"><i class="fas fa-map-marker-alt text-danger"></i></td>
                <td><strong>Alamat</strong></td>
                <td>${kantor.alamat}</td>
            </tr>
            <tr>
                <td><i class="fas fa-city text-primary"></i></td>
                <td><strong>Kota</strong></td>
                <td>${kantor.kota}</td>
            </tr>
            <tr>
                <td><i class="fas fa-phone text-success"></i></td>
                <td><strong>Telepon</strong></td>
                <td>${kantor.phone}</td>
            </tr>
            <tr>
                <td><i class="fas fa-fax text-warning"></i></td>
                <td><strong>Fax</strong></td>
                <td>${kantor.fax}</td>
            </tr>
            <tr>
                <td><i class="fas fa-envelope text-info"></i></td>
                <td><strong>Email</strong></td>
                <td><a href="mailto:${kantor.email}">${kantor.email}</a></td>
            </tr>
        </table>
    `;
    
    document.getElementById('kantorDetailContent').innerHTML = content;
    
    // Set Google Maps link
    const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(kantor.alamat + ' ' + kantor.kota)}`;
    document.getElementById('kantorMapsLink').href = mapsUrl;
    
    $('#kantorDetailModal').modal('show');
}

// === NOTIFIKASI PENDAFTARAN BARU (UMUM) ===
document.addEventListener('DOMContentLoaded', function() {
    // Check if user just registered
    @if(session('user_just_registered'))
        const tempPassword = '{{ session('temp_password', '') }}';
        
        if (tempPassword) {
            Swal.fire({
                icon: 'success',
                title: '🎉 Pendaftaran Berhasil!',
                html: `
                    <div style="text-align: left;">
                        <p><strong>Selamat datang di Sistem Informasi Pertanahan!</strong></p>
                        <p style="margin-top: 15px;">Akun Anda telah dibuat dengan password sementara:</p>
                        <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 10px 0; text-align: center; font-family: monospace; font-size: 18px; letter-spacing: 2px;">
                            <strong>${tempPassword}</strong>
                        </div>
                        <p style="color: #dc3545; font-weight: 600;">⚠️ Segera ubah password Anda untuk keamanan akun!</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-key"></i> Ubah Password',
                cancelButtonText: 'Nanti Saja',
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Scroll to password change section
                    const passwordSection = document.querySelector('.card:has(#passwordForm)');
                    if (passwordSection) {
                        passwordSection.scrollIntoView({ behavior: 'smooth' });
                    }
                }
            });
            
            // Clear the session after showing notification
            fetch('{{ url("/clear-registration-session") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }
    @endif
});
</script>
</div>

<!-- Loading Screen JavaScript -->
<script>
    setTimeout(function() {
        var loadingScreen = document.getElementById('loadingScreen');
        var mainContent = document.getElementById('mainContent');
        
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');
        }
        if (mainContent) {
            mainContent.classList.add('visible');
        }
        
        setTimeout(function() {
            if (loadingScreen) {
                loadingScreen.style.display = 'none';
            }
        }, 700);
    }, 3000); // 3 seconds - faster!
</script>

@endif
</body>
</html>
