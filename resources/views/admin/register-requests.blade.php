<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Permintaan Registrasi - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --bg: #f5f7fa;
            --card: #ffffff;
            --primary: #0d6efd;
            --primary-dark: #0a58ca;
            --primary-light: #e7f1ff;
            --text: #1e293b;
            --text-light: #64748b;
            --border: #e2e8f0;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            line-height: 1.6;
        }

        header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 2rem 1rem;
            text-align: center;
            box-shadow: 0 10px 30px rgba(13,110,253,0.2);
        }

        header h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; }
        header p { font-size: 1rem; opacity: 0.9; }

        .container {
            max-width: 1400px;
            margin: -3rem auto 2rem;
            padding: 0 1.5rem;
            position: relative;
            z-index: 2;
        }

        .card {
            background: var(--card);
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            overflow: hidden;
            border: 1px solid rgba(226,232,240,0.6);
        }

        .card-header {
            background: linear-gradient(90deg, var(--primary), #3b82f6);
            color: white;
            padding: 1.2rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .back-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.4);
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.22s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: rgba(255,255,255,0.35);
            transform: translateY(-2px);
        }

        .toolbar {
            padding: 1rem 1.5rem;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .search-box {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 2.5rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.95rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }

        .search-box i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
        }

        .filter-group {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border);
            background: white;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        .table-wrapper { overflow-x: auto; }

        table { width: 100%; border-collapse: collapse; }

        th, td {
            padding: 1rem 1.25rem;
            text-align: left;
        }

        th {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        td { border-bottom: 1px solid var(--border); font-size: 0.95rem; }

        tr:hover { background: #f8fafc; }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-disetujui { background: #dcfce7; color: #166534; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
            margin-right: 0.3rem;
        }

        .btn-view { background: var(--primary-light); color: var(--primary-dark); }
        .btn-view:hover { background: #c7dbff; }

        .btn-approve { background: #dcfce7; color: #166534; }
        .btn-approve:hover { background: #bbf7d0; }

        .btn-reject { background: #fee2e2; color: #991b1b; }
        .btn-reject:hover { background: #fecaca; }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-light);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15,23,42,0.6);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            backdrop-filter: blur(4px);
        }

        .modal.show { display: flex; }

        .modal-content {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 60px rgba(0,0,0,0.3);
            animation: modalFadeIn 0.3s ease;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            background: var(--primary);
            color: white;
            padding: 1.2rem 1.5rem;
            font-size: 1.2rem;
            font-weight: 600;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-close {
            background: none;
            border: none;
            color: white;
            font-size: 1.8rem;
            cursor: pointer;
        }

        .modal-body { padding: 1.5rem; }

        .detail-grid {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-light);
        }

        .detail-value { color: var(--text); }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .modal-actions button {
            flex: 1;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-approve-full {
            background: var(--success);
            color: white;
        }

        .btn-approve-full:hover { background: #059669; }

        .btn-reject-full {
            background: var(--danger);
            color: white;
        }

        .btn-reject-full:hover { background: #dc2626; }

        textarea {
            width: 100%;
            min-height: 100px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: var(--primary);
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
            .toolbar { flex-direction: column; align-items: stretch; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-user-plus"></i> Permintaan Registrasi</h1>
    <p>Kelola Pendaftaran Pengguna Baru</p>
</header>

<div class="container">
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-list"></i> Daftar Permintaan</span>
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama, NIK, atau email...">
            </div>
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="pending">Pending</button>
                <button class="filter-btn" data-filter="disetujui">Disetujui</button>
                <button class="filter-btn" data-filter="ditolak">Ditolak</button>
            </div>
        </div>

        <div class="table-wrapper">
            <div style="padding:1rem 1.5rem;background:#f8fafc;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                    <span>Pilih Semua</span>
                </label>
                <button id="bulkDeleteBtn" class="btn-action btn-reject" onclick="bulkDeleteSelected()" style="display:none;">
                    <i class="fas fa-trash"></i> Hapus Terpilih
                </button>
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th>Tanggal</th>
                        <th>Nama Lengkap</th>
                        <th>NIK</th>
                        <th>Email</th>
                        <th>Instansi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="7">
                            <div style="text-align:center;padding:3rem;">
                                <i class="fas fa-spinner fa-spin" style="font-size:2rem;"></i>
                                <p>Memuat data...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Detail Permintaan</span>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <span>Tolak Permintaan</span>
            <button class="modal-close" onclick="closeRejectModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom:1rem;">Masukkan alasan penolakan:</p>
            <textarea id="rejectReason" placeholder="Contoh: Data tidak lengkap, NIK sudah terdaftar, dll..."></textarea>
            <div class="modal-actions">
                <button class="btn-reject-full" onclick="confirmReject()">Tolak Permintaan</button>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
let currentFilter = 'all';
let currentData = [];
let currentDetailId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => handleFilter(btn.dataset.filter));
    });
});

async function loadData() {
    try {
        const response = await fetch('{{ url("/admin/register-requests/data") }}');
        if (!response.ok) throw new Error('Gagal memuat data');
        
        currentData = await response.json();
        renderTable(currentData);
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('tableBody').innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Gagal memuat data</p>
                </td>
            </tr>
        `;
    }
}

function renderTable(data) {
    const tbody = document.getElementById('tableBody');
    
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada permintaan registrasi</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = data.map(item => `
        <tr>
            <td><input type="checkbox" class="row-select" value="${item.id}" onchange="updateBulkDeleteButton()"></td>
            <td>${formatDate(item.created_at)}</td>
            <td><strong>${item.nama_lengkap}</strong></td>
            <td>${item.nik}</td>
            <td>${item.email}</td>
            <td>${item.instansi}</td>
            <td><span class="badge badge-${item.status}">${item.status}</span></td>
            <td>
                <button class="btn-action btn-view" onclick="showDetail(${item.id})" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </button>
                ${item.status === 'pending' ? `
                <button class="btn-action btn-approve" onclick="approveRequest(${item.id})" title="Setuju">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn-action btn-reject" onclick="openRejectModal(${item.id})" title="Tolak">
                    <i class="fas fa-times"></i>
                </button>
                ` : ''}
                <button class="btn-action btn-reject" onclick="deleteRequest(${item.id})" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function handleSearch(e) {
    const query = e.target.value.toLowerCase();
    const filtered = currentData.filter(item => 
        item.nama_lengkap?.toLowerCase().includes(query) ||
        item.nik?.toLowerCase().includes(query) ||
        item.email?.toLowerCase().includes(query)
    );
    renderTable(filtered);
}

function handleFilter(filter) {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.filter === filter);
    });
    currentFilter = filter;
    
    if (filter === 'all') {
        renderTable(currentData);
    } else {
        renderTable(currentData.filter(item => item.status === filter));
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
}

async function showDetail(id) {
    const item = currentData.find(d => d.id === id);
    if (!item) return;

    const profilePhotoUrl = item.foto_profil ? '/storage/' + item.foto_profil : null;
    const ktpPhotoUrl = item.foto_ktp ? '/storage/' + item.foto_ktp : null;

    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        ${profilePhotoUrl ? `<div style="text-align:center;margin-bottom:1.5rem;">
            <img src="${profilePhotoUrl}" class="profile-photo" alt="Foto Profil">
        </div>` : ''}
        
        ${ktpPhotoUrl ? `<div style="text-align:center;margin-bottom:1.5rem;">
            <p style="font-weight:600;color:var(--text-light);margin-bottom:0.5rem;">Foto KTP</p>
            <img src="${ktpPhotoUrl}" alt="Foto KTP" style="max-width:100%;max-height:300px;border-radius:8px;border:2px solid var(--border);cursor:pointer;" onclick="window.open('${ktpPhotoUrl}', '_blank')" title="Klik untuk memperbesar">
        </div>` : ''}
        
        <div class="detail-grid">
            <span class="detail-label">Nama Lengkap</span>
            <span class="detail-value">${item.nama_lengkap}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">NIK</span>
            <span class="detail-value">${item.nik}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">NIP</span>
            <span class="detail-value">${item.nip || '-'}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Tanggal Lahir</span>
            <span class="detail-value">${formatDate(item.tanggal_lahir)}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Jenis Kelamin</span>
            <span class="detail-value">${item.jenis_kelamin}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Alamat</span>
            <span class="detail-value">${item.alamat}, RT ${item.rt}/RW ${item.rw}, ${item.kelurahan}, ${item.kecamatan}, ${item.kota}, ${item.provinsi}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">No. Telepon</span>
            <span class="detail-value">${item.no_telepon}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Email</span>
            <span class="detail-value">${item.email}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Jabatan</span>
            <span class="detail-value">${item.jabatan}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Instansi</span>
            <span class="detail-value">${item.instansi}</span>
        </div>
        <div class="detail-grid">
            <span class="detail-label">Unit Kerja</span>
            <span class="detail-value">${item.unit_kerja}</span>
        </div>
        <div class="detail-grid" style="display:block;">
            <span class="detail-label">Alasan Pendaftaran</span>
            <div style="background:#f8fafc;padding:1rem;border-radius:8px;margin-top:0.5rem;">${item.alasan_pendaftaran}</div>
        </div>
        ${item.status !== 'pending' ? `
        <div class="detail-grid" style="display:block;">
            <span class="detail-label">Catatan Admin</span>
            <div style="background:#f8fafc;padding:1rem;border-radius:8px;margin-top:0.5rem;">${item.catatan_admin || '-'}</div>
        </div>
        ` : ''}
        
        ${item.status === 'pending' ? `
        <div class="modal-actions">
            <button class="btn-approve-full" onclick="approveRequest(${item.id});closeModal();">
                <i class="fas fa-check"></i> Setuju & Buat Akun
            </button>
            <button class="btn-reject-full" onclick="closeModal();openRejectModal(${item.id});">
                <i class="fas fa-times"></i> Tolak
            </button>
        </div>
        ` : ''}
    `;
    
    document.getElementById('detailModal').classList.add('show');
}

function closeModal() {
    document.getElementById('detailModal').classList.remove('show');
}

async function approveRequest(id) {
    if (!confirm('Setuju permintaan ini dan buat akun user?')) return;

    try {
        const response = await fetch(`/admin/register-requests/${id}/approve`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        });

        const result = await response.json();

        if (result.success) {
            showToast(`Permintaan disetujui! Password: ${result.data.password}`, 'success');
            loadData();
        } else {
            showToast(result.message || 'Gagal menyetujui', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

function openRejectModal(id) {
    currentDetailId = id;
    document.getElementById('rejectReason').value = '';
    document.getElementById('rejectModal').classList.add('show');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('show');
    currentDetailId = null;
}

async function confirmReject() {
    const reason = document.getElementById('rejectReason').value.trim();
    
    if (!reason) {
        showToast('Alasan penolakan wajib diisi!', 'error');
        return;
    }

    try {
        const response = await fetch(`/admin/register-requests/${currentDetailId}/reject`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ catatan_admin: reason })
        });

        const result = await response.json();

        if (result.success) {
            showToast('Permintaan ditolak', 'success');
            closeRejectModal();
            loadData();
        } else {
            showToast(result.message || 'Gagal menolak', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    document.querySelectorAll('.row-select').forEach(cb => {
        cb.checked = selectAll.checked;
    });
    updateBulkDeleteButton();
}

function updateBulkDeleteButton() {
    const selected = document.querySelectorAll('.row-select:checked');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    if (selected.length > 0) {
        bulkDeleteBtn.style.display = 'inline-flex';
    } else {
        bulkDeleteBtn.style.display = 'none';
    }
}

async function deleteRequest(id) {
    if (!confirm('Yakin ingin menghapus permintaan ini? User akan bisa mendaftar ulang.')) return;

    try {
        const response = await fetch(`/admin/register-requests/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });

        const result = await response.json();

        if (result.success) {
            showToast('Permintaan berhasil dihapus', 'success');
            loadData();
        } else {
            showToast(result.message || 'Gagal menghapus', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

async function bulkDeleteSelected() {
    const selected = document.querySelectorAll('.row-select:checked');
    const ids = Array.from(selected).map(cb => cb.value);

    if (ids.length === 0) {
        showToast('Pilih setidaknya satu permintaan untuk dihapus', 'error');
        return;
    }

    if (!confirm(`Yakin ingin menghapus ${ids.length} permintaan ini? User akan bisa mendaftar ulang.`)) return;

    try {
        const response = await fetch('/admin/register-requests/bulk-delete', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ ids: ids })
        });

        const result = await response.json();

        if (result.success) {
            showToast(result.message, 'success');
            loadData();
            document.getElementById('selectAll').checked = false;
            updateBulkDeleteButton();
        } else {
            showToast(result.message || 'Gagal menghapus', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 4000);
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) closeRejectModal();
});
</script>

</body>
</html>
