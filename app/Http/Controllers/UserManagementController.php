<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserManagementController extends Controller
{
    public function list()
    {
        if (!auth()->check() || !auth()->user()->hasRole(['admin', 'staff'])) {
            abort(403, 'Akses ditolak');
        }

        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()->toArray(),
                'is_approved' => $user->is_approved,
                'language_preference' => $user->language_preference,
                'created_at' => $user->created_at,
            ];
        });

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,staff,admin',
            'language_preference' => 'nullable|in:id,en',
            'is_approved' => 'nullable|boolean',
            'registration_reason' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'language_preference' => $request->language_preference ?? 'id',
                'is_approved' => $request->is_approved ?? true,
                'registration_reason' => $request->registration_reason,
                'email_verified_at' => now(),
            ]);

            $user->assignRole($request->role);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil dibuat!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat user: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:user,staff,admin',
            'language_preference' => 'nullable|in:id,en',
            'is_approved' => 'nullable|boolean',
            'registration_reason' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'language_preference' => $request->language_preference ?? 'id',
                'is_approved' => $request->is_approved ?? true,
                'registration_reason' => $request->registration_reason,
            ]);

            if ($request->password) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            $user->syncRoles([$request->role]);

            DB::commit();

            return response()->json([
                'message' => 'User berhasil diupdate!',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal update user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Tidak bisa menghapus akun sendiri'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus!']);
    }

    public function invite(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:user,staff,admin',
        ]);

        try {
            DB::beginTransaction();

            $inviteCode = Str::random(32);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(32)), // Temporary password for invited users
                'invite_code' => $inviteCode,
                'language_preference' => 'id',
                'is_approved' => false,
            ]);

            // Assign role
            $user->assignRole($request->role);

            $inviteUrl = url('/register?invite=' . $inviteCode);

            // Prepare email content
            $subject = 'Undangan Bergabung - Sistem Informasi Pertanahan Nasional';
            $body = "Halo {$user->name},\n\n" .
                   "Anda telah diundang untuk bergabung dengan Sistem Informasi Pertanahan Nasional.\n\n" .
                   "Silakan klik link berikut untuk mendaftar:\n" .
                   "{$inviteUrl}\n\n" .
                   "Kode Undangan: {$inviteCode}\n\n" .
                   "Salam,\n" .
                   "CEO Moch Rizky Gunawan\n" .
                   "Sistem Informasi Pertanahan Nasional";

            // URL encode for Gmail compose
            $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($user->email) .
                       "&su=" . urlencode($subject) .
                       "&body=" . urlencode($body);

            DB::commit();

            return response()->json([
                'message' => 'Undangan berhasil dikirim!',
                'gmail_url' => $gmailUrl,
                'user' => $user
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal membuat user: ' . $e->getMessage()], 500);
        }
    }

    public function manualInvite($inviteCode)
    {
        try {
            $user = User::where('invite_code', $inviteCode)->firstOrFail();

            $inviteUrl = url('/register?invite=' . $inviteCode);

            // Prepare email content
            $subject = 'Undangan Bergabung - Sistem Informasi Pertanahan Nasional';
            $body = "Halo {$user->name},\n\n" .
                   "Anda telah diundang untuk bergabung dengan Sistem Informasi Pertanahan Nasional.\n\n" .
                   "Silakan klik link berikut untuk mendaftar:\n" .
                   "{$inviteUrl}\n\n" .
                   "Kode Undangan: {$inviteCode}\n\n" .
                   "Salam,\n" .
                   "CEO Moch Rizky Gunawan\n" .
                   "Sistem Informasi Pertanahan Nasional";

            // URL encode for Gmail compose
            $gmailUrl = "https://mail.google.com/mail/?view=cm&fs=1&to=" . urlencode($user->email) .
                       "&su=" . urlencode($subject) .
                       "&body=" . urlencode($body);

            return redirect($gmailUrl);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Kode undangan tidak valid');
        }
    }

    public function showRegisterForm(Request $request)
    {
        $inviteCode = $request->query('invite');

        if (!$inviteCode) {
            return redirect('/')->with('error', 'Kode undangan diperlukan');
        }

        try {
            $user = User::where('invite_code', $inviteCode)->firstOrFail();

            if ($user->password) {
                return redirect('/login')->with('info', 'Akun sudah terdaftar. Silakan login.');
            }

            return view('register', compact('user', 'inviteCode'));
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Kode undangan tidak valid');
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invite_code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'pin' => 'required|string|size:6|regex:/^\d{6}$/',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed: ' . $validator->errors()->first()], 422);
        }

        try {
            $user = User::where('invite_code', $request->invite_code)->firstOrFail();

            if ($user->password) {
                return response()->json(['message' => 'Akun sudah terdaftar'], 400);
            }

            // Here you could verify the PIN if needed, but for now we'll skip it
            // In a real app, you'd store the PIN and verify it

            DB::beginTransaction();

            $user->update([
                'password' => Hash::make($request->password),
                'email_verified_at' => now(),
                'is_approved' => true,
            ]);

            DB::commit();

            return response()->json(['message' => 'Registrasi berhasil!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal mendaftar: ' . $e->getMessage()], 500);
        }
    }
}
