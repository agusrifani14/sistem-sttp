<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
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