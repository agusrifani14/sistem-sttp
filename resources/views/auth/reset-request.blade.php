<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background: linear-gradient(135deg, #0d6efd, #6610f2); 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .card { 
            background: white; 
            border-radius: 20px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.2); 
            padding: 40px; 
            width: 400px; 
        }
    </style>
</head>
<body>
    <div class="card">
        <h3 class="text-center mb-4">Reset Password Request</h3>
        <form action="{{ route('password.request.submit') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Submit Request</button>
            <a href="{{ route('login') }}" class="btn btn-link w-100">Kembali ke Login</a>
        </form>
    </div>
</body>
</html>
