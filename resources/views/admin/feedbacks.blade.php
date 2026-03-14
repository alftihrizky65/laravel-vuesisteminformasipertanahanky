<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Feedbacks - Admin</title>
  <style>body{font-family:Arial,Helvetica,sans-serif;padding:20px;} table{width:100%;border-collapse:collapse;} th,td{padding:10px;border:1px solid #eee;text-align:left} .status-sent{color:green} .status-failed{color:#c0392b} .status-pending{color:#f39c12}</style>
</head>
<body>
  <h1>Feedbacks (Terakhir)</h1>
  <p><a href="/dashboard">Kembali</a> | <a href="/mail/test">Uji Mail</a> | <a href="javascript:location.reload()">Refresh</a></p>
  <table>
    <thead>
      <tr><th>ID</th><th>Nama</th><th>Email</th><th>Pesan</th><th>Status</th><th>Attempt</th><th>Terakhir</th></tr>
    </thead>
    <tbody>
      @foreach($items as $it)
        <tr>
          <td>{{ $it->id }}</td>
          <td>{{ $it->name ?? '-' }}</td>
          <td>{{ $it->email ?? '-' }}</td>
          <td style="max-width:600px">{{ Str::limit($it->message, 200) }}</td>
          <td class="status-{{ $it->status }}">{{ $it->status }}</td>
          <td>{{ $it->attempts }}</td>
          <td>{{ $it->updated_at }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>
</html>