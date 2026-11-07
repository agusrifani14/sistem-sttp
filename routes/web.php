<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Route inisialisasi database (jalankan sekali di browser setelah deploy)
Route::get('/init-admin', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        
        // Buat atau update admin langsung dengan Hash::make
        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name'     => 'Administrator',
                'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
                'role'     => 'admin',
                'whatsapp' => '08123456789',
            ]
        );

        // Buat atau update petugas
        $petugas = \App\Models\User::updateOrCreate(
            ['email' => 'petugas123@gmail.com'],
            [
                'name'     => 'Petugas STTP',
                'password' => \Illuminate\Support\Facades\Hash::make('petugas123'),
                'role'     => 'petugas',
                'whatsapp' => '08198765432',
            ]
        );

        $driver = config('database.default');
        $dbPath = config("database.connections.{$driver}.database");
        $allUsers = \App\Models\User::select('id', 'name', 'email', 'role')->get();

        // Tes autentikasi langsung
        $testAuth = \Illuminate\Support\Facades\Auth::validate([
            'email'    => 'admin123@gmail.com',
            'password' => 'admin123',
        ]);

        return response()->json([
            'status'         => 'success',
            'database'       => $driver,
            'database_path'  => $dbPath,
            'test_login'     => $testAuth ? 'VALID (Password OK)' : 'GAGAL (Password Missmatch)',
            'users_in_db'    => $allUsers,
            'message'        => 'Database siap! Silakan login di /login dengan admin123@gmail.com / admin123',
        ], 200, [], JSON_PRETTY_PRINT);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
});

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// OTP Verification (WhatsApp)
Route::get('/otp/verify', [AuthController::class, 'showOtpForm'])->name('otp.verify');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify.submit')->middleware('throttle:5,1');
Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend')->middleware('throttle:3,1');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Dashboard dan Manage Users (hanya admin)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        $users = \App\Models\User::all();
        $totalUsers = $users->count();
        $totalAdmin = $users->where('role', 'admin')->count();
        $totalPetugas = $users->where('role', 'petugas')->count();
        $totalReports = \App\Models\Report::count();

        return view('dashboard', compact('users', 'totalUsers', 'totalAdmin', 'totalPetugas', 'totalReports'));
    })->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{id}/reset', [UserController::class, 'resetPassword'])->name('users.reset');
    Route::get('/users/{id}/reset-form', [UserController::class, 'showResetForm'])->name('users.showResetForm');
});

// Petugas Dashboard dan Reports (hanya petugas)
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/dashboard-petugas/home', [ReportController::class, 'dashboard'])->name('dashboard.petugas.home');
    Route::get('/dashboard-petugas', [ReportController::class, 'create'])->name('dashboard.petugas');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/riwayat', [ReportController::class, 'index'])->name('reports.index');
    
    // ✅ DOWNLOAD ROUTES
    Route::get('/reports/{id}/download/word', [ReportController::class, 'downloadWord'])
        ->name('reports.downloadWord');
    
    Route::get('/reports/{id}/download/pdf', [ReportController::class, 'downloadPdf'])
        ->name('reports.downloadPdf');
    
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])
        ->name('reports.destroy');
});