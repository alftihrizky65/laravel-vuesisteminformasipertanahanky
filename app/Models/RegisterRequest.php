<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'nip',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'kecamatan',
        'kota',
        'provinsi',
        'no_telepon',
        'email',
        'jabatan',
        'instansi',
        'unit_kerja',
        'foto_ktp',
        'foto_profil',
        'foto_formal',
        'alasan_pendaftaran',
        'jenis_registrasi',
        'status',
        'catatan_admin',
        'diproses_oleh',
        'diproses_pada',
        'temp_password',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'diproses_pada' => 'datetime',
    ];

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}
