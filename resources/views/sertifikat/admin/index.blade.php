<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Sertifikat - SIP Pertanahan</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
        }
        
        /* Animated Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .toast {
            padding: 16px 24px;
            border-radius: 12px;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            animation: slideIn 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            transform: translateX(120%);
            opacity: 0;
        }
        .toast.show {
            transform: translateX(0);
            opacity: 1;
        }
        .toast.hide {
            animation: slideOut 0.3s ease-in forwards;
        }
        @keyframes slideIn {
            0% { transform: translateX(120%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            0% { transform: translateX(0); opacity: 1; }
            100% { transform: translateX(120%); opacity: 0; }
        }
        .toast-success { background: linear-gradient(135deg, #10b981, #059669); }
        .toast-error { background: linear-gradient(135deg, #ef4444, #dc2626); }
        .toast-info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
        .toast-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .toast i { font-size: 1.25rem; }
        
        /* Custom Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 10000;
            animation: fadeIn 0.2s ease;
        }
        .modal-overlay.active { display: flex; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-icon {
            font-size: 3rem;
            color: #f59e0b;
            margin-bottom: 1rem;
        }
        .modal-icon.danger { color: #ef4444; }
        .modal-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: #1f2937; }
        .modal-message { color: #6b7280; margin-bottom: 1.5rem; }
        .modal-actions { display: flex; gap: 1rem; justify-content: center; }
        .btn-modal {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }
        .btn-cancel { background: #e5e7eb; color: #374151; }
        .btn-cancel:hover { background: #d1d5db; }
        .btn-confirm { background: linear-gradient(135deg, #ef4444, #dc2626); color: white; }
        .btn-confirm:hover { background: linear-gradient(135deg, #dc2626, #b91c1c); }
        
        header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        header h1 { font-size: 1.5rem; margin-bottom: 0.5rem; }
        header p { opacity: 0.9; font-size: 0.9rem; }
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .stat-card.blue { border-left: 4px solid #3b82f6; }
        .stat-card.green { border-left: 4px solid #10b981; }
        .stat-card.orange { border-left: 4px solid #f59e0b; }
        .stat-card.purple { border-left: 4px solid #8b5cf6; }
        .stat-number { font-size: 2rem; font-weight: 700; color: #1e293b; }
        .stat-label { color: #64748b; font-size: 0.875rem; }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
        }
        .card-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            color: #1e293b;
        }
        .card-body { padding: 1.5rem; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: #3b82f6; color: white; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); }
        .btn-success { background: #10b981; color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3); }
        .btn-danger { background: #ef4444; color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
        .btn-sm { padding: 0.375rem 0.75rem; font-size: 0.8125rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 0.875rem; }
        tr:hover { background: #f8fafc; }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #dbeafe; color: #1e40af; }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-secondary { background: #e5e7eb; color: #374151; }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #3b82f6;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <header>
        <div style="max-width: 1400px; margin: 0 auto;">
            <a href="{{ url('/dashboard') }}" class="back-link" style="color: white;">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
            <h1><i class="fas fa-certificate"></i> Kelola Pengajuan Sertifikat</h1>
            <p>Kelola pengajuan sertifikat tanah warga</p>
        </div>
    </header>
    
    <div class="container">
        <!-- Stats -->
        <div class="stats-row">
            <div class="stat-card blue">
                <div class="stat-number">{{ $certificates->total() }}</div>
                <div class="stat-label">Total Pengajuan</div>
            </div>
            <div class="stat-card orange">
                <div class="stat-number">{{ $certificates->where('status', 'menunggu_pembayaran')->count() + $certificates->where('status', 'menunggu_verifikasi_pembayaran')->count() }}</div>
                <div class="stat-label">Menunggu Pembayaran</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-number">{{ $certificates->where('status', 'diproses')->count() }}</div>
                <div class="stat-label">Sedang Diproses</div>
            </div>
            <div class="stat-card green">
                <div class="stat-number">{{ $certificates->where('status', 'selesai')->count() }}</div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
        
        <!-- Table -->
        <div class="card">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Daftar Pengajuan Sertifikat</span>
                </div>
            </div>
            <div class="card-body" style="overflow-x: auto;">
                <!-- Custom Modal for Confirmation -->
                <div class="modal-overlay" id="confirmModal">
                    <div class="modal-content">
                        <div class="modal-icon" id="confirmIcon"><i class="fas fa-question-circle"></i></div>
                        <h3 class="modal-title" id="confirmTitle">Konfirmasi</h3>
                        <p class="modal-message" id="confirmMessage">Apakah Anda yakin?</p>
                        <div class="modal-actions">
                            <button class="btn-modal btn-cancel" id="confirmCancel">Batal</button>
                            <button class="btn-modal btn-confirm" id="confirmOk">Ya, Lanjutkan</button>
                        </div>
                    </div>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div>
                        <button type="button" class="btn btn-danger" id="bulkDeleteBtn" style="display: none;" onclick="bulkDelete()">
                            <i class="fas fa-trash"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll" onchange="toggleSelectAll()"></th>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Jenis Sertifikat</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Pengiriman</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($certificates as $index => $cert)
                        <tr>
                            <td><input type="checkbox" class="row-checkbox" value="{{ $cert->id }}" onchange="updateSelectedCount()"></td>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $cert->nik }}</td>
                            <td>{{ $cert->nama_lengkap }}</td>
                            <td>{{ $cert->jenis_sertifikat }}</td>
                            <td>
                                @php
                                    $statusColors = [
                                        'menunggu_pembayaran' => 'warning',
                                        'menunggu_verifikasi_pembayaran' => 'info',
                                        'diproses' => 'info',
                                        'selesai' => 'success',
                                        'ditolak' => 'danger'
                                    ];
                                @endphp
                                <span class="badge badge-{{ $statusColors[$cert->status] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $cert->status)) }}
                                </span>
                            </td>
                            <td>
                                @if($cert->payment)
                                    @if($cert->payment->status == 'verified')
                                        <span class="badge badge-success">Lunas</span>
                                    @elseif($cert->payment->status == 'pending')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @else
                                        <span class="badge badge-danger">Ditolak</span>
                                    @endif
                                @else
                                    <span class="badge badge-secondary">Belum Bayar</span>
                                @endif
                            </td>
                            <td>
                                @if($cert->status_pengiriman == 'terkirim')
                                    <span class="badge badge-success">Terkirim</span>
                                @elseif($cert->status_pengiriman == 'sedang_dikirim')
                                    <span class="badge badge-info">Dikirim</span>
                                @else
                                    <span class="badge badge-secondary">Belum</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('sertifikat.admin.show', $cert->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-danger btn-sm" onclick="deleteCert({{ $cert->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align: center; color: #64748b;">Belum ada pengajuan sertifikat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                
                {{ $certificates->links() }}
            </div>
        </div>
    </div>
    
    <script>
        // Toast Notification Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const icons = {
                success: 'fas fa-check-circle',
                error: 'fas fa-times-circle',
                info: 'fas fa-info-circle',
                warning: 'fas fa-exclamation-triangle'
            };
            
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `<i class="${icons[type]}"></i><span>${message}</span>`;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Remove after 4 seconds
            setTimeout(() => {
                toast.classList.add('hide');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        document.getElementById('tambahForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('{{ route("sertifikat.admin.pengajuan.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        });
        
        // Show welcome toast
        window.onload = () => {
            showToast('Selamat datang di Kelola Sertifikat!', 'info');
        };
        
        // Toggle select all checkboxes
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateSelectedCount();
        }
        
        // Update selected count and show/hide bulk delete button
        function updateSelectedCount() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const count = checkboxes.length;
            const bulkBtn = document.getElementById('bulkDeleteBtn');
            const countSpan = document.getElementById('selectedCount');
            
            countSpan.textContent = count;
            bulkBtn.style.display = count > 0 ? 'inline-block' : 'none';
        }
        
        // Custom Confirmation Modal
        function showConfirmModal(message) {
            return new Promise((resolve) => {
                const modal = document.getElementById('confirmModal');
                document.getElementById('confirmMessage').textContent = message;
                document.getElementById('confirmIcon').className = 'modal-icon danger';
                document.getElementById('confirmIcon').innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
                modal.classList.add('active');
                
                const confirmBtn = document.getElementById('confirmOk');
                const cancelBtn = document.getElementById('confirmCancel');
                
                const handleConfirm = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(true);
                };
                
                const handleCancel = () => {
                    modal.classList.remove('active');
                    cleanup();
                    resolve(false);
                };
                
                const cleanup = () => {
                    confirmBtn.removeEventListener('click', handleConfirm);
                    cancelBtn.removeEventListener('click', handleCancel);
                };
                
                confirmBtn.addEventListener('click', handleConfirm);
                cancelBtn.addEventListener('click', handleCancel);
            });
        }
        
        // Delete single certificate
        async function deleteCert(id) {
            const confirmed = await showConfirmModal('Apakah Anda yakin ingin menghapus data sertifikat ini?');
            if (!confirmed) return;
            
            try {
                const response = await fetch(`/admin/sertifikat/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        }
        
        // Bulk delete certificates
        async function bulkDelete() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
            
            if (ids.length === 0) {
                showToast('Pilih setidaknya satu data untuk dihapus', 'warning');
                return;
            }
            
            const confirmed = await showConfirmModal(`Apakah Anda yakin ingin menghapus ${ids.length} data sertifikat?`);
            if (!confirmed) return;
            
            try {
                const response = await fetch('{{ route("sertifikat.admin.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ ids })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showToast(result.message, 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast(result.message, 'error');
                }
            } catch (error) {
                showToast('Terjadi kesalahan!', 'error');
            }
        }
    </script>
</body>
</html>
