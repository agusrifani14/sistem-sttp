<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi OTP - STTP POLDA SUMSEL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --bg-base: #090d16;
            --card-base: #111827;
            --border-base: #1f2937;
            --accent-blue: #2563eb;
            --accent-hover: #1d4ed8;
        }
        body {
            background-color: var(--bg-base);
            background-image: 
                radial-gradient(at 0% 0%, rgba(37, 99, 235, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(30, 58, 138, 0.15) 0px, transparent 50%);
            min-height: 100vh;
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 1.5rem;
            color: #f1f5f9;
        }
        .otp-container {
            max-width: 420px;
            width: 100%;
            background: var(--card-base);
            border: 1px solid var(--border-base);
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            padding: 2.25rem;
            text-align: center;
        }
        .otp-icon {
            font-size: 2.8rem;
            color: #38bdf8;
            margin-bottom: 0.5rem;
        }
        .otp-title {
            font-weight: 700;
            font-size: 1.35rem;
            color: #ffffff;
            margin-bottom: 0.35rem;
        }
        .otp-subtitle {
            color: #94a3b8;
            font-size: 0.88rem;
            margin-bottom: 1.5rem;
        }
        .otp-wa-number {
            color: #22c55e;
            font-weight: 600;
        }
        .form-control {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #fff;
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: 0.5rem;
            text-align: center;
            border-radius: 12px;
            padding: 0.65rem;
        }
        .form-control::placeholder { color: #475569; letter-spacing: 0.25rem; }
        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
            background-color: #0f172a;
            color: #fff;
        }
        .btn-verify {
            background-color: var(--accent-blue);
            border: none;
            font-weight: 600;
            color: #fff;
            border-radius: 10px;
            padding: 0.7rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .btn-verify:hover { background-color: var(--accent-hover); color: #fff; transform: translateY(-1px); }
        .btn-resend {
            background: #1e293b;
            border: 1px solid #334155;
            color: #cbd5e1;
            border-radius: 10px;
            padding: 0.55rem;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-resend:hover { background: #334155; color: #fff; }
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            border-radius: 10px;
            font-size: 0.85rem;
        }
        .alert-success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #86efac;
            border-radius: 10px;
            font-size: 0.85rem;
        }
        .back-link {
            color: #94a3b8;
            font-size: 0.85rem;
            text-decoration: none;
        }
        .back-link:hover { color: #f1f5f9; }
            display: inline-block;
            margin-top: 1rem;
        }
        .back-link:hover { color: #fff; }
        /* Timer countdown */
        #timer-wrap {
            color: #888;
            font-size: 0.82rem;
            margin-top: 0.5rem;
        }
        #timer { color: #9aa9ff; font-weight: 600; }
        /* Input OTP digits animation */
        .form-control:not(:placeholder-shown) {
            border-color: #9aa9ff;
        }
    </style>
</head>
<body>
    <div class="otp-container">
        <div class="otp-icon">📱</div>
        <h4 class="otp-title">Verifikasi WhatsApp</h4>
        <p class="otp-subtitle">
            Kode OTP 6 digit dikirim ke nomor<br>
            <span class="otp-wa-number">{{ $maskedWa }}</span>
        </p>

        {{-- Pesan sukses (kirim ulang) --}}
        @if (session('success'))
            <div class="alert alert-success mb-3">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-3">{{ $errors->first() }}</div>
        @endif

        {{-- Form verifikasi OTP --}}
        <form action="{{ route('otp.verify.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input
                    type="text"
                    class="form-control"
                    id="otp"
                    name="otp"
                    placeholder="000000"
                    maxlength="6"
                    inputmode="numeric"
                    pattern="[0-9]{6}"
                    autocomplete="one-time-code"
                    autofocus
                    required
                >
            </div>
            <button type="submit" class="btn btn-verify w-100 mb-2">Verifikasi OTP</button>
        </form>

        {{-- Kirim ulang OTP --}}
        <form action="{{ route('otp.resend') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="btn btn-resend w-100" id="btn-resend" disabled>
                Kirim Ulang OTP <span id="timer-wrap">(<span id="timer">60</span>s)</span>
            </button>
        </form>

        <a href="{{ route('login') }}" class="back-link">← Kembali ke Login</a>
    </div>

    <script>
        // Countdown 60 detik sebelum tombol "Kirim Ulang" aktif
        let seconds = 60;
        const timerEl = document.getElementById('timer');
        const btnResend = document.getElementById('btn-resend');
        const timerWrap = document.getElementById('timer-wrap');

        const interval = setInterval(() => {
            seconds--;
            timerEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(interval);
                btnResend.disabled = false;
                timerWrap.style.display = 'none';
            }
        }, 1000);

        // Hanya boleh angka di input OTP
        document.getElementById('otp').addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').substring(0, 6);
        });
    </script>
</body>
</html>
