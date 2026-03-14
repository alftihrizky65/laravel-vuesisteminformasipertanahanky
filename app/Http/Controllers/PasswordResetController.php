<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PasswordResetController extends Controller
{
    // Show forgot password form
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    // Send password reset link
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email'),
            function ($user, $token) {
                // Custom email sending using configured mail settings
                \Illuminate\Support\Facades\Mail::send('emails.password-reset', [
                    'user' => $user,
                    'token' => $token,
                    'resetUrl' => route('password.reset', ['token' => $token, 'email' => $user->email])
                ], function ($message) use ($user) {
                    $message->from(config('mail.from.address'), config('mail.from.name'))
                            ->to($user->email)
                            ->subject('Reset Password - GEOTERRAID');
                });
            }
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => 'Link reset password telah dikirim ke email Anda!'])
            : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
    }

    // Send magic link for direct login
    public function sendMagicLink(Request $request)
    {
        // Check if this is verification step or initial request
        if ($request->has('verification_code')) {
            return $this->verifyCode($request);
        }

        // Initial request - validate email and generate code
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        // Generate verification code (6 digits)
        $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Store verification data in session (expires in 10 minutes)
        session([
            'magic_verification' => [
                'user_id' => $user->id,
                'code' => $verificationCode,
                'expires_at' => now()->addMinutes(10)->timestamp,
                'email' => $user->email
            ]
        ]);

        // For demo purposes, show the code. In production, this would be sent via SMS/email
        return back()->with([
            'status' => 'Kode verifikasi telah dibuat!',
            'verification_code' => $verificationCode, // Remove this line in production
            'show_verification' => true
        ]);
    }

    // Verify the code and show magic link
    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|string|size:6',
        ]);

        $verification = session('magic_verification');

        if (!$verification) {
            return back()->withErrors(['verification_code' => 'Sesi verifikasi telah berakhir. Silakan request ulang.']);
        }

        if (now()->timestamp > $verification['expires_at']) {
            session()->forget('magic_verification');
            return back()->withErrors(['verification_code' => 'Kode verifikasi telah kedaluwarsa. Silakan request ulang.']);
        }

        if ($request->verification_code !== $verification['code']) {
            return back()->withErrors(['verification_code' => 'Kode verifikasi salah.']);
        }

        // Code is correct, generate magic link
        $user = \App\Models\User::find($verification['user_id']);
        $magicUrl = \Illuminate\Support\Facades\URL::signedRoute('magic.login', ['user' => $user->id], now()->addHour());

        // Clear verification session
        session()->forget('magic_verification');

        return back()->with([
            'status' => 'Verifikasi berhasil! Berikut adalah Magic Link Anda.',
            'magic_url' => $magicUrl,
            'show_magic_link' => true
        ]);
    }

    // Handle failed verification - redirect to login with kickout
    private function handleVerificationFailure()
    {
        session(['kicked_out' => true]);
        return redirect('/login')->with('error', 'Verifikasi gagal. Data tidak lengkap atau tidak valid.');
    }

    // Direct login to dashboard - called from confirmation popup
    public function directLogin(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // If verification code is also submitted, verify it first
        if ($request->has('verification_code') && $request->verification_code) {
            $verification = session('magic_verification');
            
            if (!$verification) {
                return redirect()->route('password.request')->withErrors(['verification_code' => 'Sesi verifikasi telah berakhir. Silakan request ulang.']);
            }

            if (now()->timestamp > $verification['expires_at']) {
                session()->forget('magic_verification');
                return redirect()->route('password.request')->withErrors(['verification_code' => 'Kode verifikasi telah kedaluwarsa. Silakan request ulang.']);
            }

            if ($request->verification_code !== $verification['code']) {
                return redirect()->route('password.request')->withErrors(['verification_code' => 'Kode verifikasi salah.']);
            }

            // Clear verification session
            session()->forget('magic_verification');
        }

        // Find the user and log them in
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        // Log the user in
        Auth::login($user);

        // Set session flag
        session(['logged_via_magic_link' => true]);

        // Increment login count (safely: only if column exists)
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'login_count')) {
                $user->increment('login_count');
            }
        } catch (\Exception $e) {
            // ignore; failsafe to prevent login from breaking if DB/schema not ready
        }

        return redirect('/dashboard')->with('status', 'Berhasil masuk!');
    }

    // Handle magic link login - show verification popup
    public function handleMagicLink(\App\Models\User $user, Request $request)
    {
        // Store user ID in session for popup verification
        session([
            'show_magic_verification_popup' => true,
            'magic_verification_user_id' => $user->id
        ]);

        // Redirect back to forgot password page to show popup
        return redirect()->route('password.request');
    }

    // Verify the magic link form submission
    public function verifyMagicLinkForm(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|min:2',
            'address' => 'required|string|min:10',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string', // base64 data URL
        ]);

        $user = \App\Models\User::find($request->user_id);

        // Clear popup session
        session()->forget(['show_magic_verification_popup', 'magic_verification_user_id']);

        // Log the user in
        Auth::login($user);

        // Set session flag for magic link login
        session(['logged_via_magic_link' => true]);

        // Increment login count (safely: only if column exists)
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'login_count')) {
                $user->increment('login_count');
            }
        } catch (\Exception $e) {
            // ignore; failsafe to prevent login from breaking if DB/schema not ready
        }

        return redirect('/dashboard')->with('status', 'Berhasil masuk menggunakan Magic Link!');
    }

    // Show reset password form
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Reset password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('status', 'Password berhasil direset!')
            : back()->withErrors(['email' => 'Gagal mereset password.']);
    }
}
