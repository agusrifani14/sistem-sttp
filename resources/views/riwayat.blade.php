<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root[data-theme="light"] {
            --bg-color: linear-gradient(to right, #e3f2fd, #bbdefb);
            --text-color: #000;
            --card-bg: #fff;
            --table-bg: #fff;
            --table-border: #dee2e6;
        }
        :root[data-theme="dark"] {
            --bg-color: #020617;
            --text-color: #f9fafb;
            --card-bg: #020617;
            --table-bg: #020617;
            --table-border: #4b5563;
            --text-muted-color: #9ca3af;
            --header-bg: #111827;
            --sidebar-bg: #020617;
        }
        body { 
            background: var(--bg-color); 
            color: var(--text-color); 
            font-family: 'Arial', sans-serif;
            transition: all 0.3s ease;
        }
        .card { 
            background: var(--card-bg);
            color: var(--text-color);
            border-radius: 15px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            transition: transform 0.3s; 
            border: 1px solid var(--table-border);
        }
        .card:hover { transform: translateY(-5px); }
        .btn { border-radius: 50px; }
        .header { 
            background: var(--header-bg, #2196f3); 
            color: white; 
            padding: 20px; 
            border-radius: 15px 15px 0 0; 
        }
        [data-theme="dark"] .header {
            background: var(--header-bg);
        }
        .sidebar { 
            background: #212529; 
            color: white; 
            height: 100vh; 
            position: fixed; 
            width: 250px; 
            padding: 20px; 
        }
        [data-theme="dark"] .sidebar {
            background: var(--sidebar-bg);
        }
        .sidebar a { color: white; text-decoration: none; display: block; padding: 10px; }
        .sidebar a:hover { background: #343a40; border-radius: 5px; }
        .content { margin-left: 250px; padding: 20px; }
        .table {
            background: var(--table-bg);
            color: var(--text-color);
            border-color: var(--table-border);
        }
        .table th {
            background: var(--header-bg, #2196f3);
            color: white;
            border-color: var(--table-border);
        }
        [data-theme="dark"] .table th {
            background: var(--header-bg);
        }
        .table td {
            border-color: var(--table-border);
            vertical-align: middle;
        }
        .text-muted {
            color: var(--text-muted-color, #6c757d) !important;
        }
        [data-theme="dark"] .text-muted {
            color: var(--text-muted-color) !important;
        }
        .alert-success {
            background-color: #d1e7dd;
            border-color: #badbcc;
            color: #0f5132;
        }
        [data-theme="dark"] .alert-success {
            background-color: #064e3b;
            border-color: #065f46;
            color: #6ee7b7;
        }
        [data-theme="dark"] .btn-close {
            filter: invert(1);
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4><i class="bi bi-shield-check me-2"></i> Menu Petugas</h4>
        <a href="{{ route('dashboard.petugas.home') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('dashboard.petugas') }}"><i class="bi bi-plus-circle"></i> Buat Laporan Baru</a>
        <a href="{{ route('reports.index') }}"><i class="bi bi-history"></i> Riwayat Laporan</a>
        <hr>
        <button id="toggleMode" class="btn btn-secondary w-100">
            <i class="bi bi-moon-stars"></i> Toggle Dark/Light Mode
        </button>
        <form action="{{ route('logout') }}" method="POST" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-danger w-100"><i class="bi bi-box-arrow-right"></i> Logout</button>
        </form>
    </div>

    <div class="content container mt-5">
        <div class="card">
            <div class="header text-center">
                <h2><i class="bi bi-history me-2"></i>Riwayat Laporan</h2>
                <p>Kelola dan download laporan STTP yang telah dibuat</p>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($reports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Korban</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $report)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $report->pelapor_nama }}</td>
                                <td>{{ $report->korban_nama }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('reports.downloadWord', $report->id) }}" class="btn btn-primary" title="Download Word">
                                            <i class="bi bi-file-word"></i> Word
                                        </a>
                                        <a href="{{ route('reports.downloadPdf', $report->id) }}" class="btn btn-success" title="Download PDF">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                        <form action="{{ route('reports.destroy', $report->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')"
                                                    title="Hapus Laporan">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">Belum ada laporan</h4>
                    <p class="text-muted">Silakan buat laporan baru terlebih dahulu</p>
                    <a href="{{ route('dashboard.petugas') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Laporan Baru
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleButton = document.getElementById('toggleMode');
        toggleButton.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Update icon tombol
            const icon = toggleButton.querySelector('i');
            if (newTheme === 'dark') {
                icon.className = 'bi bi-sun';
                toggleButton.innerHTML = '<i class="bi bi-sun"></i> Toggle Dark/Light Mode';
            } else {
                icon.className = 'bi bi-moon-stars';
                toggleButton.innerHTML = '<i class="bi bi-moon-stars"></i> Toggle Dark/Light Mode';
            }
        });

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        
        // Set icon sesuai theme
        const icon = toggleButton.querySelector('i');
        if (savedTheme === 'dark') {
            icon.className = 'bi bi-sun';
            toggleButton.innerHTML = '<i class="bi bi-sun"></i> Toggle Dark/Light Mode';
        }
    </script>
</body>
</html>