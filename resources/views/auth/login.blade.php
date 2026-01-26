<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - STTP POLDA SUMSEL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #0f0f0f, #1a1a2e);
            height: 100vh;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-container {
            max-width: 400px;
            background: #2e2e3e;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.6);
            padding: 2rem;
            text-align: center;
        }
        .login-title {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #9aa9ff;
        }
        .form-control {
            background-color: #3d3d5c;
            border: 1px solid #666;
            color: #fff;
        }
        .form-control::placeholder { color: #bbb; }
        .form-control:focus {
            border-color: #9aa9ff;
            box-shadow: 0 0 0 0.2rem rgba(154, 169, 255, 0.25);
            background-color: #444;
            color: #fff;
        }
        .btn-login {
            background-color: #4e54c8;
            border: none;
            font-weight: 600;
            color: #fff;
        }
        .btn-login:hover { background-color: #3c41b4; }
        .alert-danger {
            background-color: #ff4d4d;
            border: none;
            color: #fff;
        }
        label.form-label { color: #ccc; }
        .login-logo {
            max-height: 80px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Logo / Foto di atas form -->
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="login-logo">

        <h4 class="login-title">Sistem STTP</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
            <div class="text-center" style="font-size: 0.95em; color: #9aa9ff;">
                Lupa password? Silakan hubungi admin untuk reset password.
            </div>
        </form>
    </div>
</body>
</html>
