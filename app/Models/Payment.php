<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_id',
        'nik',
        'nama_pemilik',
        'jumlah_pembayaran',
        'metode_pembayaran',
        'bank',
        'nomor_rekening',
        'atas_nama',
        'status',
        'bukti_transfer',
        'screenshot_pembayaran',
        'verified_by',
        'verified_at',
        'catatan_verifikasi',
        'nomor_transaksi',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'jumlah_pembayaran' => 'decimal:2',
        'verified_at' => 'datetime',
        'tanggal_pembayaran' => 'datetime',
    ];

    // Payment status constants
    const STATUS_PENDING = 'pending';
    const STATUS_VERIFIED = 'verified';
    const STATUS_REJECTED = 'rejected';
    const STATUS_REFUNDED = 'refunded';

    // Payment methods
    const METHOD_TRANSFER_BANK = 'transfer_bank';
    const METHOD_E_WALLET = 'e_wallet';
    const METHOD_KANTOR_POS = 'kantor_pos';

    public function certificate(): BelongsTo
    {
        return $this->belongsTo(Certificate::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function isVerified(): bool
    {
        return $this->status === self::STATUS_VERIFIED;
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'verified' => 'success',
            'rejected' => 'danger',
            'refunded' => 'info',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute(): string
    {
        $texts = [
            'pending' => 'Menunggu Verifikasi',
            'verified' => 'Terverifikasi',
            'rejected' => 'Ditolak',
            'refunded' => 'Dikembalikan',
        ];
        return $texts[$this->status] ?? $this->status;
    }

    public static function generateTransactionNumber(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(uniqid());
        return "TRX-{$date}-{$random}";
    }
}
