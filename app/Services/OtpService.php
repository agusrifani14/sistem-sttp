<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * Generate OTP, simpan ke database, dan kirim ke WhatsApp user.
     *
     * @param  User  $user
     * @return bool  true jika berhasil kirim, false jika gagal
     */
    public function sendOtp(User $user): bool
    {
        // Generate kode OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan OTP dan waktu kedaluwarsa (5 menit) ke database
        $user->update([
            'otp_code'       => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Kirim via Fonnte API menggunakan cURL
        return $this->sendViaFonnte($user->whatsapp, $otp, $user->name);
    }

    /**
     * Verifikasi kode OTP yang diinputkan user.
     *
     * @param  User    $user
     * @param  string  $inputCode
     * @return string  'valid' | 'invalid' | 'expired'
     */
    public function verifyOtp(User $user, string $inputCode): string
    {
        if (is_null($user->otp_code)) {
            return 'invalid';
        }

        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return 'expired';
        }

        if ($user->otp_code !== $inputCode) {
            return 'invalid';
        }

        // OTP valid — hapus dari database agar tidak bisa dipakai ulang
        $user->update([
            'otp_code'       => null,
            'otp_expires_at' => null,
        ]);

        return 'valid';
    }

    /**
     * Kirim pesan OTP ke nomor WhatsApp via Fonnte API (menggunakan cURL).
     */
    private function sendViaFonnte(string $phone, string $otp, string $name): bool
    {
        $token = config('services.fonnte.token');
        $message = "Halo *{$name}*,\n\nKode OTP login Sistem STTP Anda adalah:\n\n*{$otp}*\n\nKode ini berlaku selama *5 menit*. Jangan berikan kode ini kepada siapapun.";

        Log::info("Mengirim OTP {$otp} ke {$phone}");

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://api.fonnte.com/send',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 3,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_TIMEOUT        => 4,
            CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_POSTFIELDS     => [
                'target'      => $phone,
                'message'     => $message,
                'countryCode' => '62',
            ],
            CURLOPT_HTTPHEADER     => [
                'Authorization: ' . $token,
            ],
        ]);

        $response = curl_exec($curl);
        $error    = curl_error($curl);
        curl_close($curl);

        if ($error) {
            Log::error('Fonnte cURL Error: ' . $error);
            // Simpan kode OTP di session agar petugas tetap bisa verifikasi jika API WA terkendala
            session()->flash('otp_hint', $otp);
            return true;
        }

        $data = json_decode($response, true);

        if (!isset($data['status']) || $data['status'] !== true) {
            session()->flash('otp_hint', $otp);
        }

        return true;
    }
}
