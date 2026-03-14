<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'alamat_rumah',
        'kelurahan',
        'kecamatan',
        'kabupaten_kota',
        'provinsi',
        'nomor_hp',
        'email',
        'jenis_sertifikat',
        'nomor_sertifikat',
        'luas_tanah',
        'luas_bangunan',
        'deskripsi_lokasi',
        'status',
        'status_pengiriman',
        'nomor_resi',
        'tanggal_pengiriman',
        'tanggal_terkirim',
        'processed_by',
        'processed_at',
        'catatan',
        'tanggal_expired_pajak',
        'foto_rumah',
        'foto_ktp',
        'bukti_pembayaran',
        'sertifikatjadi',
    ];

    protected $casts = [
        'luas_tanah' => 'decimal:2',
        'luas_bangunan' => 'decimal:2',
        'tanggal_pengiriman' => 'datetime',
        'tanggal_terkirim' => 'datetime',
        'processed_at' => 'datetime',
        'tanggal_expired_pajak' => 'date',
    ];

    // Status constants
    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi_pembayaran';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DITOLAK = 'ditolak';

    // Status pengiriman constants
    const PENGIRIMAN_BELUM = 'belum_dikirim';
    const PENGIRIMAN_SEDANG = 'sedang_dikirim';
    const PENGIRIMAN_TERKIRIM = 'terkirim';

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function isPaid(): bool
    {
        return $this->payment && $this->payment->status === 'verified';
    }

    public function isExpired(): bool
    {
        if (!$this->tanggal_expired_pajak) {
            return false;
        }
        return now()->greaterThan($this->tanggal_expired_pajak);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'menunggu_pembayaran' => 'warning',
            'menunggu_verifikasi_pembayaran' => 'info',
            'diproses' => 'primary',
            'selesai' => 'success',
            'ditolak' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute(): string
    {
        $texts = [
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'menunggu_verifikasi_pembayaran' => 'Menunggu Verifikasi Pembayaran',
            'diproses' => 'Sedang Diproses',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
        ];
        return $texts[$this->status] ?? $this->status;
    }
}
