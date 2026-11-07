<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use App\Services\OtpService;

class AuthController extends Controller
{
    protected OtpService $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    // ─────────────────────────────────────────
    // STEP 1: Tampilkan form login
    // ─────────────────────────────────────────
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ─────────────────────────────────────────
    // STEP 1: Proses login → kirim OTP ke WA
    // ─────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        // Validasi kredensial
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Login gagal. Periksa kembali email dan password Anda.',
            ])->onlyInput('email');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return back()->withErrors(['email' => 'Terjadi kesalahan autentikasi. Coba lagi.'])->onlyInput('email');
        }

        // ── ADMIN: langsung masuk, tanpa OTP ────────────────────────────
        if ($user->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // ── PETUGAS: wajib verifikasi OTP via WhatsApp ──────────────────
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Pastikan nomor WhatsApp sudah terdaftar
        if (empty($user->whatsapp)) {
            return back()->withErrors([
                'email' => 'Nomor WhatsApp belum terdaftar. Hubungi admin untuk mendaftarkan nomor WhatsApp Anda.',
            ])->onlyInput('email');
        }

        // Kirim OTP ke WhatsApp
        $sent = $this->otpService->sendOtp($user);

        if (!$sent) {
            return back()->withErrors([
                'email' => 'Gagal mengirim OTP ke WhatsApp. Coba lagi atau hubungi admin.',
            ])->onlyInput('email');
        }

        // Simpan user_id di session sementara (belum login penuh)
        $request->session()->put('otp_user_id', $user->id);

        return redirect()->route('otp.verify');
    }

    // ─────────────────────────────────────────
    // STEP 2: Tampilkan form verifikasi OTP
    // ─────────────────────────────────────────
    public function showOtpForm(Request $request)
    {
        // Pastikan ada session otp_user_id
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        // Sembunyikan sebagian nomor WA: 08xx****xxxx
        $wa = $user->whatsapp;
        $maskedWa = substr($wa, 0, 4) . str_repeat('*', max(0, strlen($wa) - 7)) . substr($wa, -3);

        return view('auth.otp-verify', compact('maskedWa'));
    }

    // ─────────────────────────────────────────
    // STEP 2: Proses verifikasi OTP
    // ─────────────────────────────────────────
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        $result = $this->otpService->verifyOtp($user, $request->otp);

        if ($result === 'expired') {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan login ulang untuk mendapatkan OTP baru.',
            ]);
        }

        if ($result === 'invalid') {
            return back()->withErrors([
                'otp' => 'Kode OTP tidak valid. Periksa kembali kode yang dikirimkan.',
            ]);
        }

        // OTP valid → login penuh
        $request->session()->forget('otp_user_id');
        Auth::login($user);
        $request->session()->regenerate();

        return $user->role === 'admin'
            ? redirect()->route('dashboard')
            : redirect()->route('dashboard.petugas');
    }

    // ─────────────────────────────────────────
    // STEP 2: Kirim ulang OTP
    // ─────────────────────────────────────────
    public function resendOtp(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        $sent = $this->otpService->sendOtp($user);

        if (!$sent) {
            return back()->withErrors([
                'otp' => 'Gagal mengirim ulang OTP. Coba lagi atau hubungi admin.',
            ]);
        }

        return back()->with('success', 'OTP baru sudah dikirimkan ke WhatsApp Anda.');
    }

    // ─────────────────────────────────────────
    // Proses logout
    // ─────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ─────────────────────────────────────────
    // Reset password (dipertahankan)
    // ─────────────────────────────────────────
    public function showResetFormBasic()
    {
        return view('auth.reset-password');
    }

    public function resetPasswordBasic(Request $request)
    {
        $request->validate([
            'email'    => 'required|email|exists:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password berhasil direset. Silahkan login.');
    }

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showResetRequestForm()
    {
        return view('auth.reset-request');
    }

    public function submitResetRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        return redirect()->route('dashboard')
            ->with('reset_email', $request->email)
            ->with('info', 'Silahkan hubungi admin untuk reset password.');
    }
}
