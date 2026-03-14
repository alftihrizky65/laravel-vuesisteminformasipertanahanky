<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SertifikatController extends Controller
{
    /**
     * Display cek sertifikat form for user
     */
    public function cekSertifikat()
    {
        return view('sertifikat.ceksertifikat');
    }

    /**
     * Search certificate by NIK
     */
    public function searchByNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
        ]);

        $certificate = Certificate::where('nik', $request->nik)->first();

        if (!$certificate) {
            return response()->json([
                'success' => false,
                'message' => 'Data sertifikat tidak ditemukan untuk NIK tersebut.',
            ]);
        }

        $payment = $certificate->payment;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $certificate->id,
                'nik' => $certificate->nik,
                'nama_lengkap' => $certificate->nama_lengkap,
                'alamat_rumah' => $certificate->alamat_rumah,
                'jenis_sertifikat' => $certificate->jenis_sertifikat,
                'status' => $certificate->status,
                'status_text' => $certificate->status_text,
                'status_badge' => $certificate->status_badge,
                'status_pengiriman' => $certificate->status_pengiriman,
                'nomor_resi' => $certificate->nomor_resi,
                'tanggal_expired_pajak' => $certificate->tanggal_expired_pajak,
                'is_expired' => $certificate->isExpired(),
                'is_paid' => $certificate->isPaid(),
                'payment' => $payment ? [
                    'status' => $payment->status,
                    'status_text' => $payment->status_text,
                    'jumlah_pembayaran' => $payment->jumlah_pembayaran,
                    'metode_pembayaran' => $payment->metode_pembayaran,
                    'tanggal_pembayaran' => $payment->tanggal_pembayaran,
                ] : null,
            ],
        ]);
    }

    /**
     * Display pengajuan sertifikat form for user
     */
    public function pengajuanSertifikat()
    {
        return view('sertifikat.pengajuan');
    }

    /**
     * Store new certificate application
     */
    public function storePengajuan(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'kelurahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kabupaten_kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'jenis_sertifikat' => 'required|string',
            'luas_tanah' => 'nullable|numeric',
            'luas_bangunan' => 'nullable|numeric',
            'deskripsi_lokasi' => 'nullable|string',
            'tanggal_expired_pajak' => 'nullable|date',
            'foto_ktp' => 'nullable|image|max:2048',
            'foto_rumah' => 'nullable|image|max:2048',
        ]);

        // Check if NIK already has pending certificate
        $existing = Certificate::where('nik', $request->nik)
            ->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi_pembayaran', 'diproses'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih memiliki pengajuan sertifikat yang sedang diproses.',
            ], 422);
        }

        $certificate = Certificate::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat_rumah' => $request->alamat_rumah,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten_kota' => $request->kabupaten_kota,
            'provinsi' => $request->provinsi,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'jenis_sertifikat' => $request->jenis_sertifikat,
            'luas_tanah' => $request->luas_tanah,
            'luas_bangunan' => $request->luas_bangunan,
            'deskripsi_lokasi' => $request->deskripsi_lokasi,
            'tanggal_expired_pajak' => $request->tanggal_expired_pajak,
            'status' => 'menunggu_pembayaran',
        ]);

        // Handle file uploads
        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('sertifikat/ktp', 'public');
            $certificate->foto_ktp = $path;
            $certificate->save();
        }

        if ($request->hasFile('foto_rumah')) {
            $path = $request->file('foto_rumah')->store('sertifikat/rumah', 'public');
            $certificate->foto_rumah = $path;
            $certificate->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan sertifikat berhasil dibuat. Silakan lakukan pembayaran.',
            'data' => [
                'id' => $certificate->id,
                'nik' => $certificate->nik,
            ],
        ]);
    }

    /**
     * Display payment form for certificate
     */
    public function formPembayaran($id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('sertifikat.pembayaran', compact('certificate'));
    }

    /**
     * Process payment for certificate
     */
    public function prosesPembayaran(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $request->validate([
            'metode_pembayaran' => 'required|string',
            'bank' => 'required_if:metode_pembayaran,transfer_bank|nullable|string',
            'nomor_rekening' => 'required_if:metode_pembayaran,transfer_bank|nullable|string',
            'atas_nama' => 'required|string',
            'bukti_transfer' => 'required|image|max:2048',
            'screenshot_pembayaran' => 'required|image|max:2048',
        ]);

        // Calculate payment amount based on certificate type
        $biaya = $this->calculateBiaya($certificate->jenis_sertifikat);

        // Create payment record
        $payment = Payment::create([
            'certificate_id' => $certificate->id,
            'nik' => $certificate->nik,
            'nama_pemilik' => $certificate->nama_lengkap,
            'jumlah_pembayaran' => $biaya,
            'metode_pembayaran' => $request->metode_pembayaran,
            'bank' => $request->bank,
            'nomor_rekening' => $request->nomor_rekening,
            'atas_nama' => $request->atas_nama,
            'status' => 'pending',
            'nomor_transaksi' => Payment::generateTransactionNumber(),
            'tanggal_pembayaran' => now(),
        ]);

        // Handle file uploads
        if ($request->hasFile('bukti_transfer')) {
            $path = $request->file('bukti_transfer')->store('sertifikat/pembayaran', 'public');
            $payment->bukti_transfer = $path;
            $payment->save();
        }

        if ($request->hasFile('screenshot_pembayaran')) {
            $path = $request->file('screenshot_pembayaran')->store('sertifikat/pembayaran', 'public');
            $payment->screenshot_pembayaran = $path;
            $payment->save();
        }

        // Update certificate status
        $certificate->status = 'menunggu_verifikasi_pembayaran';
        $certificate->bukti_pembayaran = $payment->bukti_transfer;
        $certificate->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diupload. Menunggu verifikasi dari admin.',
            'data' => [
                'nomor_transaksi' => $payment->nomor_transaksi,
                'jumlah_pembayaran' => $payment->jumlah_pembayaran,
            ],
        ]);
    }

    /**
     * Display digital receipt after payment
     */
    public function strukPembayaran($id)
    {
        $certificate = Certificate::with('payment')->findOrFail($id);
        $payment = $certificate->payment;

        if (!$payment) {
            abort(404, 'Data pembayaran tidak ditemukan');
        }

        return view('sertifikat.struk', compact('certificate', 'payment'));
    }

    /**
     * Calculate fee based on certificate type
     */
    private function calculateBiaya($jenisSertifikat)
    {
        $biaya = [
            'SHM' => 350000,  // Sertifikat Hak Milik
            'SHGB' => 250000, // Sertifikat Hak Guna Bangunan
            'HGB' => 250000,  // Hak Guna Bangunan
            'SHM_IBM' => 400000, // Sertifikat Hak Milik Induk Bidan Modal
            'GIRIK' => 150000,   // Girik
            'APBT' => 200000,     // Akta Perolehan Hak Tanah dan Bangunan
            'SHP' => 300000,     // Sertifikat Hak Pakai
        ];

        return $biaya[$jenisSertifikat] ?? 250000;
    }

    // ==================== ADMIN/STAFF METHODS ====================

    /**
     * Display list of all certificate applications for admin/staff
     */
    public function adminIndex(Request $request)
    {
        $query = Certificate::with('payment', 'processedBy');

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // Search by NIK or name
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nik', 'like', "%{$request->search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$request->search}%");
            });
        }

        $certificates = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('sertifikat.admin.index', compact('certificates'));
    }

    /**
     * Display certificate details for admin/staff
     */
    public function adminShow($id)
    {
        $certificate = Certificate::with('payment', 'processedBy')->findOrFail($id);
        return view('sertifikat.admin.show', compact('certificate'));
    }

    /**
     * Update certificate status (admin/staff)
     */
    public function adminUpdateStatus(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu_pembayaran,menunggu_verifikasi_pembayaran,diproses,selesai,ditolak',
            'catatan' => 'nullable|string',
        ]);

        $certificate->status = $request->status;
        $certificate->catatan = $request->catatan;
        $certificate->processed_by = Auth::id();
        $certificate->processed_at = now();
        $certificate->save();

        return response()->json([
            'success' => true,
            'message' => 'Status sertifikat berhasil diperbarui.',
        ]);
    }

    /**
     * Update shipping status (admin/staff)
     */
    public function adminUpdateShipping(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $request->validate([
            'status_pengiriman' => 'required|in:belum_dikirim,sedang_dikirim,terkirim',
            'nomor_resi' => 'nullable|string',
        ]);

        $certificate->status_pengiriman = $request->status_pengiriman;
        $certificate->nomor_resi = $request->nomor_resi;

        if ($request->status_pengiriman === 'sedang_dikirim') {
            $certificate->tanggal_pengiriman = now();
        } elseif ($request->status_pengiriman === 'terkirim') {
            $certificate->tanggal_terkirim = now();
        }

        $certificate->save();

        return response()->json([
            'success' => true,
            'message' => 'Status pengiriman berhasil diperbarui.',
        ]);
    }

    /**
     * Verify payment (admin/staff)
     */
    public function adminVerifyPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);

        $request->validate([
            'status' => 'required|in:verified,rejected',
            'catatan_verifikasi' => 'nullable|string',
        ]);

        $payment->status = $request->status;
        $payment->verified_by = Auth::id();
        $payment->verified_at = now();
        $payment->catatan_verifikasi = $request->catatan_verifikasi;
        $payment->save();

        // Update certificate status
        $certificate = $payment->certificate;
        if ($request->status === 'verified') {
            $certificate->status = 'diproses';
        } else {
            $certificate->status = 'ditolak';
        }
        $certificate->save();

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil diverifikasi.',
        ]);
    }

    /**
     * Submit certificate application for citizen (admin/staff)
     */
    public function adminStorePengajuan(Request $request)
    {
        $request->validate([
            'nik' => 'required|digits:16',
            'nama_lengkap' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'kelurahan' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kabupaten_kota' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'nomor_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'jenis_sertifikat' => 'required|string',
            'nomor_sertifikat' => 'nullable|string',
            'luas_tanah' => 'nullable|numeric',
            'luas_bangunan' => 'nullable|numeric',
            'deskripsi_lokasi' => 'nullable|string',
            'tanggal_expired_pajak' => 'nullable|date',
            'foto_ktp' => 'nullable|image|max:2048',
            'foto_rumah' => 'nullable|image|max:2048',
            'langsung_proses' => 'nullable|boolean',
        ]);

        $certificate = Certificate::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat_rumah' => $request->alamat_rumah,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kabupaten_kota' => $request->kabupaten_kota,
            'provinsi' => $request->provinsi,
            'nomor_hp' => $request->nomor_hp,
            'email' => $request->email,
            'jenis_sertifikat' => $request->jenis_sertifikat,
            'nomor_sertifikat' => $request->nomor_sertifikat,
            'luas_tanah' => $request->luas_tanah,
            'luas_bangunan' => $request->luas_bangunan,
            'deskripsi_lokasi' => $request->deskripsi_lokasi,
            'tanggal_expired_pajak' => $request->tanggal_expired_pajak,
            'status' => $request->langsung_proses ? 'diproses' : 'menunggu_pembayaran',
            'processed_by' => Auth::id(),
            'processed_at' => $request->langsung_proses ? now() : null,
        ]);

        // Handle file uploads
        if ($request->hasFile('foto_ktp')) {
            $path = $request->file('foto_ktp')->store('sertifikat/ktp', 'public');
            $certificate->foto_ktp = $path;
            $certificate->save();
        }

        if ($request->hasFile('foto_rumah')) {
            $path = $request->file('foto_rumah')->store('sertifikat/rumah', 'public');
            $certificate->foto_rumah = $path;
            $certificate->save();
        }

        return response()->json([
            'success' => true,
            'message' => $request->langsung_proses 
                ? 'Pengajuan sertifikat berhasil dibuat dan langsung diproses.' 
                : 'Pengajuan sertifikat warga berhasil dibuat.',
            'data' => [
                'id' => $certificate->id,
                'nik' => $certificate->nik,
            ],
        ]);
    }

    /**
     * Get statistics for admin dashboard
     */
    public function adminStats()
    {
        $stats = [
            'total' => Certificate::count(),
            'menunggu_pembayaran' => Certificate::where('status', 'menunggu_pembayaran')->count(),
            'menunggu_verifikasi' => Certificate::where('status', 'menunggu_verifikasi_pembayaran')->count(),
            'diproses' => Certificate::where('status', 'diproses')->count(),
            'selesai' => Certificate::where('status', 'selesai')->count(),
            'ditolak' => Certificate::where('status', 'ditolak')->count(),
            'belum_dikirim' => Certificate::where('status_pengiriman', 'belum_dikirim')->count(),
            'sedang_dikirim' => Certificate::where('status_pengiriman', 'sedang_dikirim')->count(),
            'terkirim' => Certificate::where('status_pengiriman', 'terkirim')->count(),
            'pembayaran_pending' => Payment::where('status', 'pending')->count(),
            'pembayaran_verified' => Payment::where('status', 'verified')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Delete single certificate
     */
    public function adminDestroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        
        // Delete related payment first
        if ($certificate->payment) {
            $certificate->payment->delete();
        }
        
        // Delete uploaded files
        if ($certificate->foto_ktp) {
            Storage::disk('public')->delete($certificate->foto_ktp);
        }
        if ($certificate->foto_rumah) {
            Storage::disk('public')->delete($certificate->foto_rumah);
        }
        
        $certificate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data sertifikat berhasil dihapus.',
        ]);
    }

    /**
     * Bulk delete certificates
     */
    public function adminBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
        ]);

        $count = 0;
        foreach ($request->ids as $id) {
            $certificate = Certificate::find($id);
            if ($certificate) {
                // Delete related payment
                if ($certificate->payment) {
                    $certificate->payment->delete();
                }
                
                // Delete uploaded files
                if ($certificate->foto_ktp) {
                    Storage::disk('public')->delete($certificate->foto_ktp);
                }
                if ($certificate->foto_rumah) {
                    Storage::disk('public')->delete($certificate->foto_rumah);
                }
                
                $certificate->delete();
                $count++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} data sertifikat berhasil dihapus.",
        ]);
    }
}
