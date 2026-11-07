<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - STTP DITRESKRIMSUS POLDA SUMSEL</title>
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
            --text-muted: #94a3b8;
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
        .login-container {
            width: 100%;
            max-width: 420px;
            background: var(--card-base);
            border: 1px solid var(--border-base);
            border-radius: 18px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            padding: 2.25rem;
            text-align: center;
        }
        .instansi-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #38bdf8;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            padding: 4px 10px;
            border-radius: 9999px;
            margin-bottom: 0.75rem;
        }
        .login-title {
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: #ffffff;
        }
        .form-label {
            font-size: 0.85rem;
            font-weight: 500;
            color: #cbd5e1;
            margin-bottom: 0.35rem;
        }
        .form-control {
            background-color: #0f172a;
            border: 1px solid #334155;
            color: #f8fafc;
            border-radius: 10px;
            padding: 0.65rem 0.85rem;
            font-size: 0.92rem;
            transition: all 0.15s ease;
        }
        .form-control::placeholder { color: #64748b; }
        .form-control:focus {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.25);
            background-color: #0f172a;
            color: #fff;
        }
        .btn-login {
            background-color: var(--accent-blue);
            border: none;
            font-weight: 600;
            font-size: 0.95rem;
            color: #ffffff;
            border-radius: 10px;
            padding: 0.7rem;
            transition: all 0.2s ease;
        }
        .btn-login:hover { 
            background-color: var(--accent-hover); 
            color: #ffffff;
            transform: translateY(-1px);
        }
        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #fca5a5;
            font-size: 0.85rem;
            border-radius: 10px;
            padding: 0.75rem;
        }
        .login-logo {
            max-height: 72px;
            object-fit: contain;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.4));
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo / Foto di atas form -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo" onerror="this.style.display='none'">

        <div>
            <span class="instansi-badge">
                <i class="bi bi-shield-lock-fill"></i> Ditreskrimsus Polda Sumsel
            </span>
        </div>

        <h4 class="login-title">Sistem STTP</h4>
        <p class="text-secondary small mb-3">Sistem Informasi Tanda Terima Laporan Polisi</p>

        @if ($errors->any())
            <div class="alert alert-danger text-start mb-3">
                <i class="bi bi-exclamation-circle-fill me-1"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="text-start">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Petugas / Admin</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@polri.go.id" required autofocus>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-login w-100 mt-2 mb-3">Masuk ke Sistem</button>
            <div class="text-center" style="font-size: 0.82rem; color: #94a3b8;">
                Lupa password? Silakan hubungi <strong>Admin Siber</strong>.
            </div>
        </form>
    </div>
</body>
</html>
