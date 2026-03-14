<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// === CONTROLLER YANG SUDAH ADA ===
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PolygonController;
use App\Http\Controllers\PolylineController;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\RealtimeNotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NewsController;

// === TAMBAHAN BARU UNTUK PROFIL & FOTO & PASSWORD ===
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SertifikatController;

Route::middleware(['auth'])->group(function () {

    // 2. ROUTE GEOJSON UNTUK MINI MAP DI DASHBOARD & PETA
    // Route::prefix('penduduk')->name('penduduk.')->group(function () {
    //     Route::get('/penduduk', [PendudukController::class, 'geojson'])->name('penduduk');
    // });

    Route::prefix('polygon')->name('polygon.')->group(function () {
        Route::get('/polygon', [PolygonController::class, 'geojson'])->name('polygon');
    });

    Route::prefix('polyline')->name('polyline.')->group(function () {
        Route::get('/polyline', [PolylineController::class, 'geojson'])->name('polyline');
    });

    Route::prefix('marker')->name('marker.')->group(function () {
        Route::get('/marker', [MarkerController::class, 'geojson'])->name('marker');
    });

});

// ========================================
// 1. LOGIN & LOGOUT & REGISTER
// ========================================
Route::get('/login', function () {
    if (Auth::check()) return redirect('/');
    return view('login');
})->name('login');

Route::get('/register', function () {
    if (Auth::check()) return redirect('/');
    return view('register');
})->name('register');

Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
    ]);

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
    ]);

    // Assign default 'user' role
    try {
        $user->assignRole('user');
    } catch (\Exception $e) {
        // Role might not exist yet, ignore
    }

    Auth::login($user);
    return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang ' . $user->name);
});

Route::post('/login', function (Request $request) {
    $isAjax = $request->header('X-AJAX-Login') === 'true' || $request->ajax() || $request->wantsJson();
    
    $email = $request->input('email');
    $password = $request->input('password');
    
    // Validate email format first - simple check for @ symbol
    if (strpos($email, '@') === false) {
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Email harus ada @',
                'error_field' => 'email_format'
            ], 401);
        }
        return back()->with('error', 'Email harus ada @');
    }
    
    // Get the failed attempts from session
    $failedAttempts = session('login_failed_attempts', 0);
    
    // Check if already locked out
    if ($failedAttempts >= 5) {
        $lockoutTime = session('login_lockout_time');
        if ($lockoutTime && now()->lessThan($lockoutTime)) {
            $secondsRemaining = now()->diffInSeconds($lockoutTime);
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login. Silakan tunggu.',
                    'error_field' => 'locked',
                    'lockout_seconds' => $secondsRemaining
                ], 401);
            }
            return back()->with('error', 'Terlalu banyak percobaan login. Silakan tunggu.');
        } else {
            // Lockout expired, reset attempts
            session(['login_failed_attempts' => 0, 'login_lockout_time' => null]);
            $failedAttempts = 0;
        }
    }
    
    // Check if email exists
    $user = \App\Models\User::where('email', $email)->first();
    
    if (!$user) {
        // Email doesn't exist - increment failed attempts
        $failedAttempts++;
        session(['login_failed_attempts' => $failedAttempts]);
        
        // Check if should lock out
        if ($failedAttempts >= 5) {
            $lockoutTime = now()->addSeconds(30);
            session(['login_lockout_time' => $lockoutTime]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login!',
                    'error_field' => 'locked',
                    'failed_attempts' => $failedAttempts,
                    'lockout_seconds' => 30
                ], 401);
            }
            return back()->with('error', 'Terlalu banyak percobaan login!');
        }
        
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Email salah',
                'error_field' => 'email',
                'failed_attempts' => $failedAttempts
            ], 401);
        }
        return back()->with('error', 'Email salah');
    }
    
    // Email exists, check password using Hash::check
    if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        // Password is wrong - increment failed attempts
        $failedAttempts++;
        session(['login_failed_attempts' => $failedAttempts]);
        
        // Check if should lock out
        if ($failedAttempts >= 5) {
            $lockoutTime = now()->addSeconds(30);
            session(['login_lockout_time' => $lockoutTime]);
            
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terlalu banyak percobaan login!',
                    'error_field' => 'locked',
                    'failed_attempts' => $failedAttempts,
                    'lockout_seconds' => 30
                ], 401);
            }
            return back()->with('error', 'Terlalu banyak percobaan login!');
        }
        
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Password salah',
                'error_field' => 'password',
                'failed_attempts' => $failedAttempts
            ], 401);
        }
        return back()->with('error', 'Password salah');
    }
    
    // Email and password correct - reset failed attempts and login
    session(['login_failed_attempts' => 0, 'login_lockout_time' => null]);
    
    if (Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
        $request->session()->regenerate();

        // Increment login count
        $user = Auth::user();
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'login_count')) {
                $user->increment('login_count');
            }
        } catch (\Exception $e) {
            // ignore
        }
        
        if ($isAjax) {
            return response()->json([
                'success' => true,
                'redirect' => '/dashboard'
            ]);
        }
        
        return redirect('/dashboard');
    }
    
    // If we get here, something else went wrong
    $failedAttempts++;
    session(['login_failed_attempts' => $failedAttempts]);
    
    if ($isAjax) {
        return response()->json([
            'success' => false,
            'message' => 'Email dan password salah',
            'error_field' => 'both',
            'failed_attempts' => $failedAttempts
        ], 401);
    }
    
    return back()->with('error', 'Email dan password salah');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/logout-success');
})->name('logout');

// Logout Success Page
Route::get('/logout-success', function () {
    return view('logout-success');
});

// Magic Link Routes (replaces password reset)
Route::get('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\PasswordResetController::class, 'sendMagicLink'])->name('magic.link');
Route::post('/forgot-password/direct', [App\Http\Controllers\PasswordResetController::class, 'directLogin'])->name('magic.link.direct');
Route::post('/verify-code', [App\Http\Controllers\PasswordResetController::class, 'verifyCode'])->name('verify.code');
Route::get('/magic-login/{user}', [App\Http\Controllers\PasswordResetController::class, 'handleMagicLink'])->name('magic.login')->middleware('signed');
Route::post('/magic-link-verify', [App\Http\Controllers\PasswordResetController::class, 'verifyMagicLinkForm'])->name('magic.link.verify');

// ========================================
// 2. SEMUA YANG HARUS LOGIN DULU
// ========================================
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // KANTOR ATR/BPN
    Route::get('/kantor-atr', function () {
        return view('kantor-atr');
    });

    // === USER MASYARAKAT HANYA BISA INI ===
    Route::get('/peta', fn() => view('lihat-peta'));
    Route::get('/ten', fn() => view('tentang'));
    // UPDATE: Chatbot sekarang BISA DIAKSES TANPA LOGIN (PUBLIC) agar tombol di landing page bisa langsung masuk
    // Route::get('/chatbot', fn() => view('chatbot'));

    // ===================================================================
    // PENGATURAN PROFIL — BARU & BENAR (PAKAI PATCH (TIDAK ERROR LAGI!)
    // ===================================================================
    Route::get('/pengaturan', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');           // PATCH
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo');     // POST foto
    Route::post('/password/update', [ProfileController::class, 'updatePassword'])->name('password.update'); // POST password

    // ===================================================================
    // ROUTE LAMA TETAP DIPER TAHAN KAN (TIDAK DIHAPUS SATU PUN!)
    // ===================================================================
    Route::post('/pengaturan/profile', [App\Http\Controllers\PengaturanController::class, 'updateProfile']);
    Route::post('/pengaturan/password', [App\Http\Controllers\PengaturanController::class, 'updatePassword']);
    Route::post('/pengaturan/lang', [App\Http\Controllers\PengaturanController::class, 'setLanguage']);

    // === HANYA ADMIN & STAFF YANG BISA MASUK SINI ===
    Route::get('/petnah', function () {
        $user = Auth::user();
        if ($user->hasRole('admin') || $user->hasRole('staff')) {
            return view('peta-pertanahan');
        }
        return redirect('/peta')->with('error', 'Akses ditolak! Anda bukan staff/admin.');
    });

    // User management (admin and staff)
    Route::get('/users', function() {
        if(!auth()->user() || !auth()->user()->hasRole(['admin', 'staff'])){
            abort(403, 'Akses ditolak');
        }
        return view('users.iframe');
    })->middleware('auth')->name('users.index');

    Route::get('/users/list', [App\Http\Controllers\UserManagementController::class, 'list'])->middleware('auth');
    Route::get('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'show'])->middleware('auth');
    Route::post('/users', [App\Http\Controllers\UserManagementController::class, 'store'])->middleware('auth');
    Route::post('/users/invite', [App\Http\Controllers\UserManagementController::class, 'invite'])->middleware('auth');
    Route::post('/users/invite/form', [App\Http\Controllers\UserManagementController::class, 'inviteForm'])->middleware('auth');
    Route::get('/users/invite/manual/{inviteCode}', [App\Http\Controllers\UserManagementController::class, 'manualInvite'])->middleware('auth');
    Route::put('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'update'])->middleware('auth');
    Route::delete('/users/{id}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->middleware('auth');

    Route::get('/datnah', function () {
        if (Auth::user()->hasRole('admin|staff')) {
            return view('datatanah');
        }
        return redirect('/')->with('error', 'Akses ditolak!');
    })->name('data-tanah.index');

    Route::get('/lap', function () {
        if (Auth::user()->hasRole('admin|staff')) {
            return view('laporan');
        }
        return redirect('/')->with('error', 'Akses ditolak!');
    })->name('laporan.index');

    // Laporan Sertifikasi (print-ready form)
    Route::get('/laporan/sertifikasi', function () {
        if (Auth::user() && Auth::user()->hasRole('admin|staff')) {
            return view('reports.sertifikasi');
        }
        return redirect('/')->with('error', 'Akses ditolak!');
    })->name('laporan.sertifikasi')->middleware('auth');

    // Laporan Generator — generates CSV exports from Users or Penduduk
    Route::get('/laporan/generate', function (\Illuminate\Http\Request $request) {
        if (!Auth::user() || !Auth::user()->hasRole('admin|staff')) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $source = $request->query('source', 'users');
        $format = $request->query('format', 'csv');
        if ($format !== 'csv') {
            return redirect()->back()->with('error', 'Format tidak didukung saat ini.');
        }

        if ($source === 'users') {
            $rows = \App\Models\User::with('roles')->get();
            $filename = 'laporan_users_'.date('Ymd_His').'.csv';
            $headers = ['ID','Name','Email','Roles','Approved','Created At'];
            $callback = function() use ($rows, $headers) {
                $FH = fopen('php://output', 'w');
                fputcsv($FH, $headers);
                foreach ($rows as $u) {
                    $roles = $u->roles->pluck('name')->join('|');
                    fputcsv($FH, [$u->id, $u->name, $u->email, $roles, $u->is_approved ? 'Yes' : 'No', $u->created_at]);
                }
                fclose($FH);
            };
        } else {
            $rows = \App\Models\Penduduk::all();
            $filename = 'laporan_penduduk_'.date('Ymd_His').'.csv';
            $headers = ['ID','NIK','Nama','Alamat','RT','RW','Provinsi','Latitude','Longitude'];
            $callback = function() use ($rows, $headers) {
                $FH = fopen('php://output', 'w');
                fputcsv($FH, $headers);
                foreach ($rows as $p) {
                    fputcsv($FH, [$p->id, $p->nik, $p->nama, $p->alamat, $p->rt, $p->rw, $p->provinsi, $p->latitude, $p->longitude]);
                }
                fclose($FH);
            };
        }

        return response()->streamDownload($callback, $filename, ['Content-Type' => 'text/csv']);

    })->name('laporan.generate')->middleware('auth');

    // Laporan Print view: supports users and penduduk print-ready single-page layouts
    Route::get('/laporan/print', function (\Illuminate\Http\Request $request) {
        if (!Auth::user() || !Auth::user()->hasRole('admin|staff')) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        $source = $request->query('source', 'users');
        if ($source === 'users') {
            $users = \App\Models\User::with('roles')->get();
            return view('reports.print_users', compact('users'));
        }
        if ($source === 'lahan') {
            // If a dedicated Lahan model exists, use it. Otherwise map Polygon as a fallback.
            if (class_exists(\App\Models\Lahan::class)) {
                $lahan = \App\Models\Lahan::all();
            } else {
                $raw = \App\Models\Polygon::all();
                $lahan = $raw->map(function($p) {
                    return (object)[
                        'id_lahan' => $p->id,
                        'luas' => $p->luas ?? null,
                        'lokasi' => $p->nama ?? null,
                        'rt' => $p->rt ?? null,
                        'rw' => $p->rw ?? null,
                        'provinsi' => $p->provinsi ?? null,
                    ];
                });
            }
            return view('reports.print_lahan', compact('lahan'));
        }
        $penduduk = \App\Models\Penduduk::all();
        return view('reports.print_penduduk', compact('penduduk'));

    })->name('laporan.print')->middleware('auth');

    // Realtime notifications (JSON / SSE)
    Route::get('/realtime/notifications', [RealtimeNotificationController::class, 'index']);
    Route::get('/sse/notifications', [RealtimeNotificationController::class, 'stream']);

    // Route for formpengaduanataunotif
    Route::get('/formpengaduanataunotif', function () {
        return view('formpengaduanataunotif');
    })->name('formpengaduanataunotif');

    Route::get('/paper', function () {
        if (Auth::user()->hasRole('admin|staff')) {
            return view('geopaper');
        }
        return redirect('/')->with('error', 'Akses ditolak!');
    });

    // === HANYA ADMIN ===
    Route::get('/data', function () {
        if (Auth::user()->hasRole('admin')) {
            return app(App\Http\Controllers\DataController::class)->index();
        }
        return redirect('/')->with('error', 'Hanya Admin!');
    })->name('data.index')->middleware('role:admin');

    Route::resource('jenis-tanah', App\Http\Controllers\JenisTanahController::class)->middleware('role:admin');

    // Penduduk, Polygon, Polyline, Marker → HANYA ADMIN
    Route::prefix('penduduk')->group(function () {
        Route::get('/penduduk', [App\Http\Controllers\PendudukController::class, 'index']);
        Route::post('/penduduk', [App\Http\Controllers\PendudukController::class, 'store']);
        Route::get('/penduduk/{id}', [App\Http\Controllers\PendudukController::class, 'show']);
        Route::put('/penduduk/{id}', [App\Http\Controllers\PendudukController::class, 'update']);
        Route::delete('/penduduk/{id}', [App\Http\Controllers\PendudukController::class, 'destroy']);
        Route::post('/penduduk/bulk-delete', [App\Http\Controllers\PendudukController::class, 'bulkDestroy']);
    })->middleware('role:admin');

    Route::prefix('polygon')->group(function () {
        Route::get('/polygon', [App\Http\Controllers\PolygonController::class, 'index']);
        Route::post('/polygon', [App\Http\Controllers\PolygonController::class, 'store']);
        Route::get('/polygon/{id}', [App\Http\Controllers\PolygonController::class, 'show']);
        Route::put('/polygon/{id}', [App\Http\Controllers\PolygonController::class, 'update']);
        Route::delete('/polygon/{id}', [App\Http\Controllers\PolygonController::class, 'destroy']);
    })->middleware('role:admin');

    Route::prefix('polyline')->group(function () {
        Route::get('/polyline', [App\Http\Controllers\PolylineController::class, 'index']);
        Route::post('/polyline', [App\Http\Controllers\PolylineController::class, 'store']);
        Route::get('/polyline/{id}', [App\Http\Controllers\PolylineController::class, 'show']);
        Route::put('/polyline/{id}', [App\Http\Controllers\PolylineController::class, 'update']);
        Route::delete('/polyline/{id}', [App\Http\Controllers\PolylineController::class, 'destroy']);
    })->middleware('role:admin');

    Route::prefix('marker')->group(function () {
        Route::get('/marker', [App\Http\Controllers\MarkerController::class, 'index']);
        Route::post('/marker', [App\Http\Controllers\MarkerController::class, 'store']);
        Route::get('/marker/{id}', [App\Http\Controllers\MarkerController::class, 'show']);
        Route::put('/marker/{id}', [App\Http\Controllers\MarkerController::class, 'update']);
        Route::delete('/marker/{id}', [App\Http\Controllers\MarkerController::class, 'destroy']);
    })->middleware('role:admin');

    // CEK DB
    Route::get('/cekdb', fn() => "DB: " . DB::connection()->getDatabaseName());

    // Admin: list feedbacks (simple view)
    Route::get('/feedbacks', function () {
        if (!auth()->user() || !auth()->user()->hasRole(['admin','staff'])) {
            abort(403, 'Akses ditolak');
        }
        $items = \App\Models\Feedback::orderBy('created_at', 'desc')->limit(200)->get();
        return view('admin.feedbacks', compact('items'));
    })->middleware('auth');
});

// === KRITIK DAN SARAN SYSTEM ===
// Public routes for kritik/saran form
Route::prefix('kritik-saran')->group(function () {
    Route::get('/', function() {
        return view('formpengaduanataunotif');
    });
    Route::post('/', [App\Http\Controllers\KritikSaranController::class, 'store']);
    Route::get('/my-messages', [App\Http\Controllers\KritikSaranController::class, 'myMessages']);
});

// Admin routes for Kritik/Saran management
Route::prefix('admin/kritik-saran')->group(function () {
    Route::get('/', function() {
        return view('formpengaduanataunotif');
    });
    Route::get('/data', [App\Http\Controllers\KritikSaranController::class, 'index']);
    Route::get('/unread-count', [App\Http\Controllers\KritikSaranController::class, 'unreadCount']);
    Route::post('/{id}/reply', [App\Http\Controllers\KritikSaranController::class, 'reply']);
    Route::post('/{id}/read', [App\Http\Controllers\KritikSaranController::class, 'markAsRead']);
    Route::delete('/{id}', [App\Http\Controllers\KritikSaranController::class, 'destroy']);
});

// === REGISTER REQUEST SYSTEM ===
// Public registration form
Route::get('/register-request', function() {
    return view('register');
});

Route::post('/register-request', [App\Http\Controllers\RegisterRequestController::class, 'store']);

// Route untuk halaman registrasi Staff/ASN
Route::get('/register-staff', function() {
    return view('register-staff');
});

// Route untuk download kartu pendaftaran
Route::get('/kartu-pendaftaran/{id}', function($id, \Illuminate\Http\Request $httpRequest) {
    $request = \App\Models\RegisterRequest::findOrFail($id);
    return view('kartu-pendaftaran', compact('request'));
});

// API untuk cek status registrasi (by ID)
Route::get('/api/registration-status/{id}', [App\Http\Controllers\RegisterRequestController::class, 'checkStatus']);

// API untuk mencari registrasi (public search)
Route::get('/api/registration-search', [App\Http\Controllers\RegisterRequestController::class, 'searchByNumber']);

// Route untuk halaman status pencarian (tanpa ID)
Route::get('/status-pendaftaran', function() {
    return view('status-pendaftaran');
});

// Route untuk halaman status pendaftaran (dengan ID)
Route::get('/status-pendaftaran/{id}', function($id) {
    return view('status-pendaftaran');
});

// Route to clear registration session (for newly registered users)
Route::post('/clear-registration-session', function() {
    session()->forget(['user_just_registered', 'temp_password']);
    return response()->json(['success' => true]);
})->middleware('auth');

// Admin routes for registration requests management
Route::middleware(['auth'])->prefix('admin/register-requests')->group(function () {
    Route::get('/', function() {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'staff'])) {
            abort(403, 'Akses ditolak');
        }
        return view('admin.register-requests');
    });
    Route::get('/data', [App\Http\Controllers\RegisterRequestController::class, 'index']);
    Route::get('/{id}', [App\Http\Controllers\RegisterRequestController::class, 'show']);
    Route::post('/{id}/approve', [App\Http\Controllers\RegisterRequestController::class, 'approve']);
    Route::post('/{id}/reject', [App\Http\Controllers\RegisterRequestController::class, 'reject']);
    Route::delete('/{id}', [App\Http\Controllers\RegisterRequestController::class, 'destroy']);
    Route::post('/bulk-delete', [App\Http\Controllers\RegisterRequestController::class, 'bulkDestroy']);
    Route::get('/pending-count', [App\Http\Controllers\RegisterRequestController::class, 'pendingCount']);
});

// ========================================
// 3. FRONTEND PUBLIC
// ========================================
Route::get('/', function () {
    return view('frontend');
})->name('frontend');

// === UPDATE: ROUTE CHATBOT DIPINDAHKAN KE PUBLIC AGAR BISA DIAKSES DARI LANDING PAGE TANPA LOGIN ===
Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot');

// Public view for realtime notifications (landing page link)
Route::get('/notifikasi_realtime', function () {
    return view('notifikasi_realtime');
})->name('notifikasi.realtime');

// Feedback (kritik & saran) sent to admin email
Route::post('/feedback', [App\Http\Controllers\FeedbackController::class, 'send'])->name('feedback.send');

// Mail test route (local only) — visit /mail/test to verify SMTP delivery
Route::get('/mail/test', function () {
    // Allow only local requests or local environment
    if (!in_array(request()->ip(), ['127.0.0.1', '::1']) && !app()->environment('local')) {
        abort(403, 'Forbidden');
    }

    try {
        \Illuminate\Support\Facades\Mail::raw('Ini adalah email uji dari GEOTERRAID. Jika Anda menerima ini, SMTP terkonfigurasi.', function ($message) {
            $message->from(env('MAIL_FROM_ADDRESS', 'no-reply@geoterraid.local'), env('MAIL_FROM_NAME', 'No Reply - SIP'));
            $message->to(env('ADMIN_EMAIL', 'alftihrizky65@gmail.com'))->subject('TEST: GEOTERRAID Mail Delivery');
        });
        return 'Kirim email uji — sukses. Silakan cek inbox ' . env('ADMIN_EMAIL', 'alftihrizky65@gmail.com');
    } catch (\Exception $e) {
        // Save error to a dedicated log file to aid debugging without leaking stack to users
        try {
            $logPath = storage_path('logs/mail_test.log');
            $entry = "[" . now()->toDateTimeString() . "] Mail send failed: " . $e->getMessage() . PHP_EOL;
            file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX);
        } catch (\Exception $e2) {
            // ignore secondary logging failures
            \Illuminate\Support\Facades\Log::error('Failed to write mail_test.log: ' . $e2->getMessage());
        }

        // Friendly message with next steps
        return 'Gagal mengirim email uji. Error telah dicatat; silakan periksa EMAIL_SETUP.md dan jalankan /mail/test setelah memperbarui .env (gunakan Gmail App Password atau Mailtrap).';
    }
})->name('mail.test');

// ========================================
// SERTIFIKAT ROUTES
// ========================================

// USER ROUTES - Cek dan Pengajuan Sertifikat
Route::get('/cek-sertifikat', [SertifikatController::class, 'cekSertifikat'])->name('sertifikat.cek');
Route::post('/cek-sertifikat/cari', [SertifikatController::class, 'searchByNik'])->name('sertifikat.search');
Route::get('/pengajuan-sertifikat', [SertifikatController::class, 'pengajuanSertifikat'])->name('sertifikat.pengajuan');
Route::post('/pengajuan-sertifikat', [SertifikatController::class, 'storePengajuan'])->name('sertifikat.store');
Route::get('/sertifikat/{id}/pembayaran', [SertifikatController::class, 'formPembayaran'])->name('sertifikat.pembayaran.form');
Route::post('/sertifikat/{id}/pembayaran', [SertifikatController::class, 'prosesPembayaran'])->name('sertifikat.pembayaran.proses');
Route::get('/sertifikat/{id}/struk', [SertifikatController::class, 'strukPembayaran'])->name('sertifikat.struk');

// ADMIN/STAFF ROUTES
Route::middleware(['auth'])->prefix('admin/sertifikat')->name('sertifikat.admin.')->group(function () {
    Route::get('/', [SertifikatController::class, 'adminIndex'])->name('index');
    Route::get('/{id}', [SertifikatController::class, 'adminShow'])->name('show');
    Route::put('/{id}/status', [SertifikatController::class, 'adminUpdateStatus'])->name('status');
    Route::put('/{id}/pengiriman', [SertifikatController::class, 'adminUpdateShipping'])->name('pengiriman');
    Route::put('/payment/{id}/verify', [SertifikatController::class, 'adminVerifyPayment'])->name('payment.verify');
    Route::post('/pengajuan', [SertifikatController::class, 'adminStorePengajuan'])->name('pengajuan.store');
    Route::get('/stats', [SertifikatController::class, 'adminStats'])->name('stats');
    Route::delete('/{id}', [SertifikatController::class, 'adminDestroy'])->name('destroy');
    Route::post('/bulk-delete', [SertifikatController::class, 'adminBulkDelete'])->name('bulk-delete');
});

// ========================================
// 4. SEMUA YANG BELUM LOGIN → KE LOGIN
// ========================================
Route::get('{any}', function () {
    return redirect('/login');
})->where('any', '.*')->middleware('guest');