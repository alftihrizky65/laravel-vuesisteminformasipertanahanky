<?php

namespace App\Http\Controllers;

use App\Models\KritikSaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KritikSaranController extends Controller
{
    /**
     * Display a listing of kritik/saran for admin.
     */
    public function index(Request $request)
    {
        $query = KritikSaran::with(['user', 'repliedBy'])->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('jenis') && $request->jenis != '') {
            $query->where('jenis', $request->jenis);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('subjek', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->get();
        return response()->json($data);
    }

    /**
     * Get unread count for notification badge.
     */
    public function unreadCount()
    {
        $count = KritikSaran::where('status', 'baru')->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Store a new kritik/saran (public - no login required).
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'no_hp' => 'nullable|string|max:20',
            'jenis' => 'required|in:kritik,saran,pengaduan',
            'subjek' => 'required|string|max:200',
            'isi' => 'required|string',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'jenis.required' => 'Jenis pesan wajib dipilih',
            'subjek.required' => 'Subjek wajib diisi',
            'isi.required' => 'Isi pesan wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kritikSaran = KritikSaran::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
                'jenis' => $request->jenis,
                'subjek' => $request->subjek,
                'isi' => $request->isi,
                'status' => 'baru',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Terima kasih! Kritik dan saran Anda telah kami terima dan akan diproses.',
                'data' => $kritikSaran
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark as read.
     */
    public function markAsRead($id)
    {
        try {
            $kritikSaran = KritikSaran::findOrFail($id);
            $kritikSaran->update(['status' => 'dibaca']);
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Reply to kritik/saran.
     */
    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'balasan' => 'required|string',
            'status' => 'required|in:diproses,selesai',
        ], [
            'balasan.required' => 'Balasan wajib diisi',
            'status.required' => 'Status wajib dipilih',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kritikSaran = KritikSaran::findOrFail($id);
            $kritikSaran->update([
                'balasan' => $request->balasan,
                'status' => $request->status,
                'dibalas_oleh' => Auth::id(),
                'dibalas_pada' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Balasan berhasil dikirim',
                'data' => $kritikSaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim balasan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete kritik/saran.
     */
    public function destroy($id)
    {
        try {
            $kritikSaran = KritikSaran::findOrFail($id);
            $kritikSaran->delete();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user's own kritik/saran.
     */
    public function myMessages()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu'
            ], 401);
        }

        $data = KritikSaran::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($data);
    }
}
