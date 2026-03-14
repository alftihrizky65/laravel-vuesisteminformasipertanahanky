<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse|RedirectResponse
    {
        // Check if this is an AJAX request using custom header
        $isAjax = $request->header('X-AJAX-Login') === 'true' || $request->ajax() || $request->wantsJson();
        
        try {
            $request->authenticate();
            $request->session()->regenerate();
            
            $user = $request->user();
            $redirectUrl = route('dashboard', absolute: false);
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl
                ]);
            }
            
            // Redirect based on user role
            if ($user->hasRole('admin')) {
                return redirect()->intended(route('dashboard', absolute: false));
            } elseif ($user->hasRole('staff')) {
                return redirect()->intended(route('dashboard', absolute: false));
            } else {
                return redirect()->intended(route('dashboard', absolute: false));
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $errorKey = array_key_first($errors);
            $errorValue = $errors[$errorKey][0] ?? 'Email atau password salah';
            
            // Determine which field has the error and the message
            $message = '';
            $field = '';
            
            // Check for custom error messages from LoginRequest
            if ($errorValue === 'email_not_found') {
                $message = 'Email salah';
                $field = 'email';
            } elseif ($errorValue === 'password_wrong') {
                $message = 'Password salah';
                $field = 'password';
            } elseif (str_contains($errorValue, 'valid email') || str_contains($errorValue, 'email must')) {
                // Laravel validation error for invalid email format
                $message = 'Format email tidak valid';
                $field = 'email';
            } elseif (str_contains($errorValue, 'required')) {
                // Required field validation
                if ($errorKey === 'email') {
                    $message = 'Email wajib diisi';
                } else {
                    $message = 'Password wajib diisi';
                }
                $field = $errorKey;
            } else {
                $message = 'Email atau password salah';
                $field = 'email';
            }
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error_field' => $field,
                ], 401);
            }
            
            // For non-AJAX, redirect back with error
            return redirect()->back()->with('error', $message)->with('error_field', $field);
        } catch (\Exception $e) {
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah',
                    'error_field' => 'email',
                ], 401);
            }
            
            // For non-AJAX, redirect back with error
            return redirect()->back()->with('error', 'Email atau password salah');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/logout-success');
    }
}
