<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Laporan Pengguna - Cetak</title>
  <style>
    @page { size: A4; margin: 12mm; }
    body { font-family: Arial, sans-serif; color:#000; margin:0; }
    .wrap { max-width: 760px; margin: 0 auto; padding: 6mm; box-sizing:border-box; }
    header { text-align:center; margin-bottom:8px; }
    h1 { font-size:16px; margin:0; }
    .meta { font-size:12px; color:#333; margin-top:6px; }
    table { width:100%; border-collapse:collapse; font-size:12px; margin-top:10px; }
    th, td { border:1px solid #333; padding:6px; text-align:left; }
    th { background:#f3f3f3; font-weight:700; }
    .no-print { margin-bottom:10px; }
    @media print { .no-print { display:none; } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="no-print"><button onclick="window.print()">Cetak / Simpan PDF</button></div>
    <header>
      <h1>Laporan Pengguna</h1>
      <div class="meta">Tanggal: {{ now()->format('d M Y H:i') }} &nbsp; • &nbsp; Jumlah pengguna: {{ $users->count() }}</div>
    </header>

    <table>
      <thead>
        <tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Disetujui</th><th>Dibuat</th></tr>
      </thead>
      <tbody>
        @foreach($users as $i => $u)
        <tr>
          <td>{{ $i+1 }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->roles->pluck('name')->join(', ') }}</td>
          <td>{{ $u->is_approved ? 'Ya' : 'Tidak' }}</td>
          <td>{{ optional($u->created_at)->format('Y-m-d') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>