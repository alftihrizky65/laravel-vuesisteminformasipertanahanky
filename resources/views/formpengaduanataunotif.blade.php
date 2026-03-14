<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kritik & Saran - Admin Dashboard</title>
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
            --info: #0ea5e9;
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
            position: relative;
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
            transition: all 0.2s;
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

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

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
            letter-spacing: 0.5px;
        }

        td {
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        tr:hover {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-kritik { background: #fef3c7; color: #92400e; }
        .badge-saran { background: #dbeafe; color: #1e40af; }
        .badge-pengaduan { background: #fee2e2; color: #991b1b; }

        .badge-status {
            background: #dcfce7;
            color: #166534;
        }

        .badge-status.diproses {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-status.selesai {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-action {
            padding: 0.4rem 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 0.85rem;
            transition: all 0.2s;
            margin-right: 0.3rem;
        }

        .btn-view {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn-view:hover { background: #c7dbff; }

        .btn-reply {
            background: #dcfce7;
            color: #166534;
        }

        .btn-reply:hover { background: #bbf7d0; }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover { background: #fecaca; }

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

        /* Modal Styles */
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
            max-width: 700px;
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
            line-height: 1;
        }

        .modal-body { padding: 1.5rem; }

        .detail-row {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 0.5rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }

        .detail-label {
            font-weight: 600;
            color: var(--text-light);
        }

        .message-content {
            background: #f8fafc;
            padding: 1.25rem;
            border-radius: 10px;
            border-left: 4px solid var(--primary);
            white-space: pre-wrap;
            line-height: 1.7;
        }

        .reply-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }

        .reply-section h4 {
            margin-bottom: 1rem;
            color: var(--text);
        }

        .reply-textarea {
            width: 100%;
            min-height: 120px;
            padding: 1rem;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 0.95rem;
            resize: vertical;
            margin-bottom: 1rem;
        }

        .reply-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(13,110,253,0.15);
        }

        .reply-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
        }

        .btn {
            padding: 0.6rem 1.25rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover { background: var(--primary-dark); }

        .btn-secondary {
            background: #e2e8f0;
            color: var(--text);
        }

        .btn-secondary:hover { background: #cbd5e1; }

        .loading-spinner {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
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
            .search-box { max-width: 100%; }
            .filter-group { justify-content: center; }
            .detail-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<header>
    <h1><i class="fas fa-comments"></i> Kritik & Saran</h1>
    <p>Admin Dashboard - Kelola Masukan Pengguna</p>
</header>

<div class="container">
    <div class="card">
        <div class="card-header">
            <span><i class="fas fa-inbox"></i> Daftar Kritik & Saran</span>
            <a href="{{ url('/dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <div class="toolbar">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari nama, email, atau subjek...">
            </div>
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">Semua</button>
                <button class="filter-btn" data-filter="kritik">Kritik</button>
                <button class="filter-btn" data-filter="saran">Saran</button>
                <button class="filter-btn" data-filter="pengaduan">Pengaduan</button>
                <button class="filter-btn" data-filter="baru">Baru</button>
            </div>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Subjek</th>
                        <th>Isi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <tr>
                        <td colspan="7">
                            <div class="loading-spinner"><div class="spinner"></div></div>
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
            <span>Detail Pesan</span>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <div class="modal-header" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
            <span><i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus</span>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <p style="font-size: 1.1rem; margin-bottom: 1.5rem;">Yakin ingin menghapus data ini?</p>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <button onclick="confirmDelete()" style="background: #ef4444; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    <i class="fas fa-trash"></i> Hapus
                </button>
                <button onclick="closeDeleteModal()" style="background: #6b7280; color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600;">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div id="replyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span>Balas Pesan</span>
            <button class="modal-close" onclick="closeReplyModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-row">
                <span class="detail-label">Kepada:</span>
                <span id="replyToName"></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span id="replyToEmail"></span>
            </div>
            <div class="reply-section">
                <h4>Balasan:</h4>
                <textarea id="replyText" class="reply-textarea" placeholder="Tulis balasan Anda di sini..."></textarea>
                <div class="reply-actions">
                    <button class="btn btn-secondary" onclick="closeReplyModal()">Batal</button>
                    <select id="replyStatus" style="padding: 0.6rem; border-radius: 8px; border: 1px solid var(--border); margin-right: auto;">
                        <option value="diproses">Tandai Diproses</option>
                        <option value="selesai">Tandai Selesai</option>
                    </select>
                    <button class="btn btn-primary" onclick="sendReply()">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
let currentFilter = 'all';
let currentData = [];
let currentReplyId = null;

document.addEventListener('DOMContentLoaded', function() {
    loadData();
    
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', () => handleFilter(btn.dataset.filter));
    });
});

async function loadData() {
    try {
        const response = await fetch('{{ url("/admin/kritik-saran/data") }}');
        if (!response.ok) throw new Error('Gagal memuat data');
        
        currentData = await response.json();
        renderTable(currentData);
    } catch (error) {
        console.error('Error:', error);
        document.getElementById('tableBody').innerHTML = `
            <tr>
                <td colspan="7" class="empty-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Gagal memuat data. Silakan refresh halaman.</p>
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
                    <p>Belum ada kritik atau saran</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = data.map(item => `
        <tr>
            <td>${formatDate(item.created_at)}</td>
            <td>
                <strong>${item.nama || '-'}</strong><br>
                <small style="color: var(--text-light)">${item.email || '-'}</small>
            </td>
            <td><span class="badge badge-${item.jenis}">${item.jenis}</span></td>
            <td>${item.subjek}</td>
            <td>${item.isi.substring(0, 50)}${item.isi.length > 50 ? '...' : ''}</td>
            <td><span class="badge badge-status ${item.status}">${item.status}</span></td>
            <td>
                <button class="btn-action btn-view" onclick="showDetail(${item.id})" title="Lihat Detail">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn-action btn-reply" onclick="openReplyModal(${item.id})" title="Balas">
                    <i class="fas fa-reply"></i>
                </button>
                <button class="btn-action btn-delete" onclick="showDeleteModal(${item.id})" title="Hapus">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function handleSearch(e) {
    const query = e.target.value.toLowerCase();
    const filtered = currentData.filter(item => 
        item.nama?.toLowerCase().includes(query) ||
        item.email?.toLowerCase().includes(query) ||
        item.subjek?.toLowerCase().includes(query) ||
        item.isi?.toLowerCase().includes(query)
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
    } else if (['kritik', 'saran', 'pengaduan'].includes(filter)) {
        renderTable(currentData.filter(item => item.jenis === filter));
    } else {
        renderTable(currentData.filter(item => item.status === filter));
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function showDetail(id) {
    const item = currentData.find(d => d.id === id);
    if (!item) return;

    // Mark as read
    fetch(`/admin/kritik-saran/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json'
        }
    });

    const modalBody = document.getElementById('modalBody');
    modalBody.innerHTML = `
        <div class="detail-row">
            <span class="detail-label">Nama:</span>
            <span>${item.nama || '-'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span>${item.email || '-'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">No. HP:</span>
            <span>${item.no_hp || '-'}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Jenis:</span>
            <span class="badge badge-${item.jenis}">${item.jenis}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Subjek:</span>
            <span>${item.subjek}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Waktu:</span>
            <span>${formatDate(item.created_at)}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status:</span>
            <span class="badge badge-status ${item.status}">${item.status}</span>
        </div>
        <div class="detail-row" style="display: block;">
            <span class="detail-label">Isi Pesan:</span>
            <div class="message-content">${item.isi}</div>
        </div>
        ${item.balasan ? `
        <div class="detail-row" style="display: block; margin-top: 1rem; padding-top: 1rem; border-top: 2px solid var(--success);">
            <span class="detail-label" style="color: var(--success);"><i class="fas fa-check-circle"></i> Balasan:</span>
            <div class="message-content" style="border-left-color: var(--success); background: #f0fdf4;">${item.balasan}</div>
            <small style="color: var(--text-light); margin-top: 0.5rem; display: block;">
                Dibalas pada: ${formatDate(item.dibalas_pada)}
            </small>
        </div>
        ` : ''}
    `;
    
    document.getElementById('detailModal').classList.add('show');
}

function openReplyModal(id) {
    const item = currentData.find(d => d.id === id);
    if (!item) return;

    currentReplyId = id;
    document.getElementById('replyToName').textContent = item.nama || '-';
    document.getElementById('replyToEmail').textContent = item.email || '-';
    document.getElementById('replyText').value = item.balasan || '';
    document.getElementById('replyStatus').value = item.status === 'baru' ? 'diproses' : item.status;
    
    document.getElementById('replyModal').classList.add('show');
}

function closeReplyModal() {
    document.getElementById('replyModal').classList.remove('show');
    currentReplyId = null;
}

async function sendReply() {
    if (!currentReplyId) return;

    const balasan = document.getElementById('replyText').value.trim();
    const status = document.getElementById('replyStatus').value;

    if (!balasan) {
        showToast('Balasan tidak boleh kosong!', 'error');
        return;
    }

    try {
        const response = await fetch(`/admin/kritik-saran/${currentReplyId}/reply`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ balasan, status })
        });

        const result = await response.json();

        if (result.success) {
            showToast('Balasan berhasil dikirim!', 'success');
            closeReplyModal();
            loadData();
        } else {
            showToast(result.message || 'Gagal mengirim balasan', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

let deleteTargetId = null;

function showDeleteModal(id) {
    console.log('showDeleteModal called with id:', id);
    deleteTargetId = id;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    deleteTargetId = null;
}

async function confirmDelete() {
    if (!deleteTargetId) {
        showToast('ID tidak valid!', 'error');
        return;
    }
    
    const id = deleteTargetId;
    closeDeleteModal();

    try {
        const response = await fetch(`/admin/kritik-saran/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN
            }
        });

        const result = await response.json();

        if (result.success) {
            showToast('Data berhasil dihapus!', 'success');
            loadData();
        } else {
            showToast(result.message || 'Gagal menghapus', 'error');
        }
    } catch (error) {
        showToast('Terjadi kesalahan!', 'error');
    }
}

function closeModal() {
    document.getElementById('detailModal').classList.remove('show');
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.remove(), 3000);
}

// Close modals on outside click
document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.getElementById('replyModal').addEventListener('click', function(e) {
    if (e.target === this) closeReplyModal();
});
</script>

</body>
</html>
