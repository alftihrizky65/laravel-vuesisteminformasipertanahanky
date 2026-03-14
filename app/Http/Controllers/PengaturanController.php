<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PengaturanController extends Controller
{
    public function index()
    {
        return view('pengaturan');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate(['name' => 'required|string|max:255', 'email' => 'required|email|max:255']);
        $user->update($data);
        return response()->json(['ok' => true, 'user' => $user]);
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate(['current_password' => 'required', 'password' => 'required|min:6|confirmed']);
        if (!Hash::check($data['current_password'], $user->password)) {
            return response()->json(['ok' => false, 'message' => 'Password saat ini salah'], 422);
        }
        $user->password = Hash::make($data['password']);
        $user->save();
        return response()->json(['ok' => true]);
    }

    public function setLanguage(Request $request)
    {
        $request->validate(['lang' => 'required|in:id,en']);
        $user = Auth::user();

        // Store in session
        session(['app_lang' => $request->lang]);

        // Save to database (always, since column exists)
        $user->update(['language_preference' => $request->lang]);

        app()->setLocale($request->lang);

        return response()->json([
            'success' => true,
            'message' => 'Bahasa berhasil diubah!'
        ]);
    }
}
