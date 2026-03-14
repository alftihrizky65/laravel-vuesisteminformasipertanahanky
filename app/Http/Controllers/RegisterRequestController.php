<?php

namespace App\Http\Controllers;

use App\Models\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log as FacadesLog;

class RegisterRequestController extends Controller
{
    /**
     * Display a listing for admin.
     */
    public function index(Request $request)
    {
        // Check if user is authenticated and has admin/staff role
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }
        
        $query = RegisterRequest::with('processedBy')->orderBy('created_at', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%' . $request->search . '%')
                  ->orWhere('nik', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $data = $query->get()->toArray();
        return response()->json($data);
    }

    /**
     * Store a new registration request.
     */
    public function store(Request $request)
    {
        // Determine registration type
        $jenisRegistrasi = $request->jenis_registrasi ?? 'umum';
        
        if ($jenisRegistrasi === 'umum') {
            // UMUM MODE: Simple form, auto-create user account, auto-login
            return $this->handleUmumRegistration($request);
        } else {
            // STAFF MODE: Full form, requires admin approval
            return $this->handleStaffRegistration($request);
        }
    }

    /**
     * Handle umum (public) registration - auto-create user and auto-login
     */
    private function handleUmumRegistration(Request $request)
    {
        // Simplified validation for umum
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:users,email', // Use NIK as unique identifier
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_telepon' => 'required|string|max:15',
            'email' => 'required|email|unique:users,email',
            'alasan_pendaftaran' => 'required|string',
        ], [
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'email.unique' => 'Email sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate random password
            $randomPassword = Str::random(12);
            
            // Create user account directly
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email,
                'password' => Hash::make($randomPassword),
                'language_preference' => 'id',
                'is_approved' => true, // Auto-approved
            ]);

            // Assign 'user' role
            try {
                $user->assignRole('user');
            } catch (\Exception $e) {
                // Role might not exist yet
            }

            // Auto-login
            Auth::login($user);

            // Generate temp password for display
            $tempPassword = $randomPassword;

            // Store temp password in session for display
            session(['temp_password' => $tempPassword, 'user_just_registered' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil!',
                'auto_login' => true,
                'redirect' => url('/dashboard')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal registrasi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle staff registration - full form, requires admin approval
     */
    private function handleStaffRegistration(Request $request)
    {
        // Full validation for staff
        $rules = [
            'nama_lengkap' => 'required|string|max:255',
            'nik' => 'required|string|size:16|unique:register_requests,nik',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'rt' => 'required|string|max:3',
            'rw' => 'required|string|max:3',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'no_telepon' => 'required|string|max:15',
            'email' => 'required|email',
            'alasan_pendaftaran' => 'required|string',
            'nip' => 'nullable|string|max:18',
            'jabatan' => 'required|string|max:100',
            'instansi' => 'required|string|max:200',
            'unit_kerja' => 'required|string|max:200',
            'foto_ktp' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
            'foto_formal' => 'required|file|image|mimes:jpg,jpeg,png|max:2048',
        ];

        $validator = Validator::make($request->all(), $rules, [
            'nik.size' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->except(['_token']);
            $data['jenis_registrasi'] = 'staff';
            
            // Handle file uploads
            if ($request->hasFile('foto_ktp')) {
                $data['foto_ktp'] = $request->file('foto_ktp')->store('uploads/ktp', 'public');
            }
            if ($request->hasFile('foto_profil')) {
                $data['foto_profil'] = $request->file('foto_profil')->store('uploads/profil', 'public');
            }
            if ($request->hasFile('foto_formal')) {
                $data['foto_formal'] = $request->file('foto_formal')->store('uploads/formal', 'public');
            }

            $registerRequest = RegisterRequest::create($data);

            // Simpan email ke session untuk tracking
            session(['pending_registration_email' => $request->email]);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi finalisasi berhasil! Sistem akan membaca dalam 1x24 jam. Silakan cek berkala email Anda.',
                'auto_login' => false,
                'register_request_id' => $registerRequest->id
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve registration request and create user.
     */
    public function approve($id)
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        try {
            $registerRequest = RegisterRequest::findOrFail($id);
            
            // Generate random password for the user
            $randomPassword = Str::random(12);
            
            // Create user from registration request
            $user = User::create([
                'name' => $registerRequest->nama_lengkap,
                'email' => $registerRequest->email,
                'password' => Hash::make($randomPassword),
                'language_preference' => 'id',
                'is_approved' => true,
                'registration_reason' => $registerRequest->alasan_pendaftaran,
                'profile_photo_path' => $registerRequest->foto_profil,
            ]);

            // Assign 'user' role (or 'staff' if the registration was for staff)
            $role = $registerRequest->jenis_registrasi === 'staff' ? 'staff' : 'user';
            try {
                $user->assignRole($role);
            } catch (\Exception $e) {
                // Role might not exist
            }

            // Update registration request status
            $registerRequest->update([
                'status' => 'disetujui',
                'diproses_oleh' => auth()->id(),
                'diproses_pada' => now(),
                'temp_password' => $randomPassword, // Store password for display on status page
            ]);

            // Removed: Kirim email notifikasi persetujuan (no longer sending email)
            // $this->sendApprovalEmail($registerRequest, $user, $randomPassword);

            return response()->json([
                'success' => true,
                'message' => 'Pengguna berhasil disetujui dan dibuatkan akun.',
                'temp_password' => $randomPassword
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Kirim email notifikasi persetujuan
     */
    private function sendApprovalEmail($registerRequest, $user, $password)
    {
        $email = $registerRequest->email;
        $nama = $registerRequest->nama_lengkap;
        $jenis = $registerRequest->jenis_registrasi === 'staff' ? 'Staff/ASN' : 'Umum';
        
        $subject = 'PERSETUJUAN REGISTRASI - Sistem Informasi Pertanahan';
        
        $message = "
Halo {$nama},

Kami informasikan bahwa permintaan registrasi Anda sebagai {$jenis} di Sistem Informasi Pertanahan telah DITERIMA dan DISETUJUI.

Berikut adalah informasi akun Anda:
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Email       : {$email}
Password    : {$password}
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Silakan login menggunakan email dan password di atas melalui halaman:
" . url('/login') . "

Jika Anda membutuhkan bantuan, silakan hubungi administrator.

Hormat kami,
Admin Sistem Informasi Pertanahan
";
        
        try {
            mail($email, $subject, $message);
        } catch (\Exception $e) {
            // Log error but don't break the flow
            \Illuminate\Support\Facades\Log::error('Gagal kirim email persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Reject registration request.
     */
    public function reject(Request $request, $id)
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        try {
            $registerRequest = RegisterRequest::findOrFail($id);
            $registerRequest->update([
                'status' => 'ditolak',
                'catatan_admin' => $request->reason,
                'diproses_oleh' => auth()->id(),
                'diproses_pada' => now(),
            ]);

            // Kirim email notifikasi penolakan
            $this->sendRejectionEmail($registerRequest, $request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Permintaan ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Kirim email notifikasi penolakan
     */
    private function sendRejectionEmail($registerRequest, $reason)
    {
        $email = $registerRequest->email;
        $nama = $registerRequest->nama_lengkap;
        $jenis = $registerRequest->jenis_registrasi === 'staff' ? 'Staff/ASN' : 'Umum';
        
        $subject = 'PENOLAKAN REGISTRASI - Sistem Informasi Pertanahan';
        
        $message = "
Halo {$nama},

Kami informasikan bahwa permintaan registrasi Anda sebagai {$jenis} di Sistem Informasi Pertanahan telah DITOLAK.

Alasan Penolakan:
{$reason}

Jika Anda membutuhkan informasi lebih lanjut atau ingin mengajukan kembali, silakan hubungi administrator.

Hormat kami,
Admin Sistem Informasi Pertanahan
";
        
        try {
            mail($email, $subject, $message);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim email penolakan: ' . $e->getMessage());
        }
    }

    /**
     * Delete registration request.
     */
    public function destroy($id)
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        try {
            $registerRequest = RegisterRequest::findOrFail($id);
            
            // Delete associated files
            if ($registerRequest->foto_ktp) {
                Storage::disk('public')->delete($registerRequest->foto_ktp);
            }
            if ($registerRequest->foto_profil) {
                Storage::disk('public')->delete($registerRequest->foto_profil);
            }
            
            $registerRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permintaan dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk delete registration requests.
     */
    public function bulkDestroy(Request $request)
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:register_requests,id'
        ]);

        try {
            $requests = RegisterRequest::whereIn('id', $request->ids)->get();
            
            foreach ($requests as $req) {
                if ($req->foto_ktp) {
                    Storage::disk('public')->delete($req->foto_ktp);
                }
                if ($req->foto_profil) {
                    Storage::disk('public')->delete($req->foto_profil);
                }
                $req->delete();
            }

            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' permintaan dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get pending count.
     */
    public function pendingCount()
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            return response()->json(['error' => 'Akses ditolak'], 403);
        }

        $count = RegisterRequest::where('status', 'pending')->count();
        return response()->json(['count' => $count]);
    }
    
    /**
     * Check registration status by email or ID (for tracking by user)
     */
    public function checkStatus(Request $request, $id = null)
    {
        // Check if ID is provided in the URL path
        if (!$id) {
            $id = $request->query('id');
        }
        
        if ($id) {
            // Check by ID
            $registerRequest = RegisterRequest::find($id);
            
            if (!$registerRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'request' => $registerRequest
            ]);
        }
        
        // Check by email (legacy support)
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Email diperlukan'
            ], 400);
        }
        
        $registerRequest = RegisterRequest::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->first();
        
        if (!$registerRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Pengajuan tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'request' => $registerRequest
        ]);
    }
    
    /**
     * Search registration by number (public search)
     */
    public function searchByNumber(Request $request)
    {
        $nomor = $request->query('nomor');
        
        if (!$nomor) {
            return response()->json([
                'success' => false,
                'message' => 'NIK diperlukan'
            ], 400);
        }
        
        // Try to find by NIK first (primary search)
        $registerRequest = RegisterRequest::where('nik', $nomor)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // If not found by NIK, try by ID (remove leading zeros)
        if (!$registerRequest) {
            $id = (int) ltrim($nomor, '0');
            if ($id > 0) {
                $registerRequest = RegisterRequest::find($id);
            }
        }
        
        // If still not found, try by email
        if (!$registerRequest) {
            $registerRequest = RegisterRequest::where('email', 'like', '%' . $nomor . '%')
                ->orderBy('created_at', 'desc')
                ->first();
        }
        
        if (!$registerRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Data registrasi tidak ditemukan'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'request' => $registerRequest
        ]);
    }
}
