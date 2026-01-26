<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Cyber Crime</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  <style>
    :root[data-theme="light"] {
      --text-color: #212529;
      --bg-color: #f8f9fa;
      --card-bg: #fff;
      --input-bg: #fff;
      --input-text: #212529;
      --border-color: #dee2e6;
      --header-bg: #0d6efd;
      --sidebar-bg: #212529;
    }
    
    :root[data-theme="dark"] {
      --text-color: #f9fafb;
      --bg-color: #020617;
      --card-bg: #020617;
      --input-bg: #020617;
      --input-text: #f9fafb;
      --border-color: #4b5563;
      --header-bg: #111827;
      --sidebar-bg: #020617;
    }

    body { 
      background: var(--bg-color); 
      color: var(--text-color);
      transition: all 0.3s ease;
    }
    
    .card {
      background: var(--card-bg);
      border-color: var(--border-color);
      color: var(--text-color);
    }
    
    .form-control, .form-select {
      background: var(--input-bg);
      color: var(--input-text);
      border-color: var(--border-color);
    }
    
    .form-control:focus, .form-select:focus {
      background: var(--input-bg);
      color: var(--input-text);
      border-color: #0d6efd;
    }
    
    .form-label {
      color: var(--text-color);
    }

    .sidebar { 
      height: 100vh; 
      background: var(--sidebar-bg); 
      color: white; 
      transition: width 0.3s; 
    }
    [data-theme="dark"] .sidebar {
      background: var(--sidebar-bg);
    }
    .sidebar a { color: white; text-decoration: none; display: block; padding: 12px; border-radius: 6px; }
    .sidebar a:hover { background: #343a40; }
    .sidebar.collapsed { width: 70px; position: absolute; z-index: 1000; overflow: hidden; }
    .sidebar.collapsed a span, .sidebar.collapsed h4 { display: none; }
    .content { transition: margin-left 0.3s; }
    .navbar-custom { 
      background: var(--header-bg); 
      color: white; 
    }
    [data-theme="dark"] .navbar-custom {
      background: var(--header-bg);
    }
    .bg-primary {
      background-color: var(--header-bg) !important;
    }
    [data-theme="dark"] .bg-primary {
      background-color: var(--header-bg) !important;
    }
    .card-header.bg-primary, .card-header.bg-info {
      background-color: var(--header-bg) !important;
    }
    [data-theme="dark"] .card-header.bg-info {
      background-color: #0e7490 !important;
    }
    .table {
      background: var(--card-bg);
      color: var(--text-color);
    }
    .table th {
      background: var(--header-bg);
      color: white;
      border-color: var(--border-color);
    }
    .table td {
      border-color: var(--border-color);
    }
    .modal-content {
      background: var(--card-bg);
      color: var(--text-color);
    }
    [data-theme="dark"] .modal-content {
      background: var(--card-bg);
    }
    .modal-header, .modal-footer {
      border-color: var(--border-color);
    }
    .card { transition: transform 0.3s; }
    .card:hover { transform: scale(1.02); }

    /* Striping untuk dark mode supaya baris masih jelas tapi teks tetap kontras */
    [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) {
      --bs-table-accent-bg: #020617;
      color: var(--text-color);
    }
    
    /* Remove all modal animations */
    .modal.fade .modal-dialog {
      transform: none;
    }
    
    .modal.show .modal-dialog {
      transform: none;
    }
    
    .modal-content {
      animation: none;
    }
    
    /* Keep other styles */
    .btn-warning {
      transition: all 0.3s ease;
    }
    
    .btn-warning:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>
  <!-- Header with Logo -->
  <div class="container-fluid bg-primary text-white py-2">
    <div class="row align-items-center">
      <div class="col-auto">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" height="50">
      </div>
      <div class="col">
        <h4 class="mb-0">Ditreskrimsus Unit 2 Subdit V</h4>
      </div>
      <div class="col-auto">
        <button id="toggleMode" class="btn btn-light btn-sm">
          <i class="bi bi-moon-stars"></i> Dark Mode
        </button>
      </div>
    </div>
  </div>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar (sama seperti sebelumnya) -->
    <div class="col-md-2 sidebar p-3" id="sidebar">
      <h4 class="text-center mb-4">👮 Cyber Crime</h4>
      <a href="#"><i class="bi bi-speedometer2"></i> <span>Dashboard</span></a>
      <form action="{{ route('logout') }}" method="POST" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-danger w-100">Logout</button>
      </form>
    </div>

    <!-- Main Content -->
    <div class="col-md-10 content p-0" id="main-content">
      <!-- Navbar (sama) -->
      <nav class="navbar navbar-expand-lg navbar-custom px-3">
        <!-- ... (sama seperti sebelumnya) -->
      </nav>

      <!-- Content Body -->
      <div class="p-4">
        <div class="row g-3 mb-4">
          <div class="col-12 col-md-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-muted">Total Users</div>
                    <div class="fs-3 fw-bold">{{ $totalUsers ?? 0 }}</div>
                  </div>
                  <i class="bi bi-people fs-2"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-muted">Admin</div>
                    <div class="fs-3 fw-bold">{{ $totalAdmin ?? 0 }}</div>
                  </div>
                  <i class="bi bi-shield-lock fs-2"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-muted">Petugas</div>
                    <div class="fs-3 fw-bold">{{ $totalPetugas ?? 0 }}</div>
                  </div>
                  <i class="bi bi-person-badge fs-2"></i>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-3">
            <div class="card shadow-sm">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <div class="text-muted">Total Laporan</div>
                    <div class="fs-3 fw-bold">{{ $totalReports ?? 0 }}</div>
                  </div>
                  <i class="bi bi-clipboard-data fs-2"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

        <h4 class="mb-4">👥 Manage Users</h4>
        
        <!-- Fixed grid layout -->
        <div class="row gx-4 gy-4">
          <!-- Create User Form - Always full width on mobile, half on desktop -->
          <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="bi bi-person-plus me-2"></i>
                <span>Buat User Baru</span>
              </div>
              <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Username/Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                  </div>
                  <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                  </div>
                  <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                      <option value="admin">Admin</option>
                      <option value="petugas">Petugas</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Buat User</button>
                </form>
              </div>
            </div>
          </div>

          <!-- Users List - Always full width on mobile, half on desktop -->
          <div class="col-12 col-lg-6">
            <div class="card h-100 shadow-sm">
              <div class="card-header bg-info text-white d-flex align-items-center">
                <i class="bi bi-people me-2"></i>
                <span>List Users & Reset Password</span>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-striped table-hover align-middle">
                    <thead>
                      <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($users ?? [] as $user)
                      <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                          <button type="button" class="btn btn-warning btn-sm" 
                                  data-bs-toggle="modal" 
                                  data-bs-target="#resetModal{{ $user->id }}">
                              <i class="bi bi-key"></i> Reset
                          </button>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Keep modals outside main container but inside body -->
@foreach($users ?? [] as $user)
<div class="modal" id="resetModal{{ $user->id }}" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reset Password - {{ $user->name }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('users.reset', $user->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Reset Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const toggleButton = document.getElementById('toggleMode');
    if (toggleButton) {
        toggleButton.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update icon tombol
            const icon = toggleButton.querySelector('i');
            if (newTheme === 'dark') {
                icon.className = 'bi bi-sun';
                toggleButton.innerHTML = '<i class="bi bi-sun"></i> Light Mode';
            } else {
                icon.className = 'bi bi-moon-stars';
                toggleButton.innerHTML = '<i class="bi bi-moon-stars"></i> Dark Mode';
            }
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Set icon sesuai theme
        const icon = toggleButton.querySelector('i');
        if (savedTheme === 'dark') {
            icon.className = 'bi bi-sun';
            toggleButton.innerHTML = '<i class="bi bi-sun"></i> Light Mode';
        }
    }
</script>
</body>
</html>