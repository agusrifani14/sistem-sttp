<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root[data-theme="light"] {
            --bg-color: linear-gradient(to right, #e3f2fd, #bbdefb);
            --text-color: #111827;
            --card-bg: #fff;
            --border-color: #dee2e6;
            --header-bg: #0d6efd;
            --sidebar-bg: #212529;
            --text-muted-color: #6b7280;
        }
        :root[data-theme="dark"] {
            --bg-color: #020617;
            --text-color: #f9fafb;
            --card-bg: #020617;
            --border-color: #4b5563;
            --header-bg: #111827;
            --sidebar-bg: #020617;
            --text-muted-color: #9ca3af;
        }
        body {
            background: var(--bg-color);
            color: var(--text-color);
            font-family: 'Arial', sans-serif;
            transition: all 0.3s ease;
        }
        .sidebar {
            background: var(--sidebar-bg);
            color: #fff;
            height: 100vh;
            position: fixed;
            width: 250px;
            padding: 20px;
        }
        .sidebar a { color: #fff; text-decoration: none; display: block; padding: 10px; border-radius: 8px; }
        .sidebar a:hover { background: rgba(255,255,255,0.08); }
        .content { margin-left: 250px; padding: 20px; }
        .card {
            background: var(--card-bg);
            color: var(--text-color);
            border-radius: 15px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }
        .header {
            background: var(--header-bg);
            color: #fff;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        .text-muted { color: var(--text-muted-color) !important; }
        .table { color: var(--text-color); }
        [data-theme="dark"] .table { --bs-table-color: var(--text-color); }
        [data-theme="dark"] .table-striped>tbody>tr:nth-of-type(odd) { --bs-table-accent-bg: rgba(255,255,255,0.03); }
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

    <div class="content container">
        <div class="card">
            <div class="header">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h3 class="mb-0"><i class="bi bi-speedometer2 me-2"></i>Dashboard Petugas</h3>
                        <div class="text-white-50">Ringkasan laporan Anda</div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('dashboard.petugas') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-plus-circle"></i> Buat Laporan
                        </a>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-history"></i> Riwayat
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="card h-100">
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
                    <div class="col-12 col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">Hari Ini</div>
                                        <div class="fs-3 fw-bold">{{ $reportsToday ?? 0 }}</div>
                                    </div>
                                    <i class="bi bi-calendar-check fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted">Bulan Ini</div>
                                        <div class="fs-3 fw-bold">{{ $reportsThisMonth ?? 0 }}</div>
                                    </div>
                                    <i class="bi bi-calendar3 fs-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Form -->
                <div class="card mb-4" style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 15px;">
                    <div class="card-body">
                        <form method="GET" action="{{ route('dashboard.petugas.home') }}" class="row g-3">
                            <div class="col-md-4">
                                <label for="tanggal" class="form-label"><i class="bi bi-calendar-event"></i> Tanggal</label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal" value="{{ $searchTanggal ?? '' }}" style="background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color);">
                            </div>
                            <div class="col-md-4">
                                <label for="nama" class="form-label"><i class="bi bi-person"></i> Nama Pelapor</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Cari nama..." value="{{ $searchNama ?? '' }}" style="background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color);">
                            </div>
                            <div class="col-md-4">
                                <label for="kategori" class="form-label"><i class="bi bi-tag"></i> Kategori Kejahatan</label>
                                <select class="form-select" id="kategori" name="kategori" style="background: var(--input-bg); color: var(--text-color); border: 1px solid var(--border-color);">
                                    <option value="">Semua Kategori</option>
                                    <option value="Manipulasi Data Elektronik" @if(($searchKategori ?? '') === 'Manipulasi Data Elektronik') selected @endif>Manipulasi Data Elektronik</option>
                                    <option value="Penipuan Online" @if(($searchKategori ?? '') === 'Penipuan Online') selected @endif>Penipuan Online</option>
                                    <option value="Pencurian Data" @if(($searchKategori ?? '') === 'Pencurian Data') selected @endif>Pencurian Data</option>
                                    <option value="Penyebaran Konten Ilegal" @if(($searchKategori ?? '') === 'Penyebaran Konten Ilegal') selected @endif>Penyebaran Konten Ilegal</option>
                                    <option value="Penghinaan & Fitnah" @if(($searchKategori ?? '') === 'Penghinaan & Fitnah') selected @endif>Penghinaan & Fitnah</option>
                                    <option value="Akses Ilegal" @if(($searchKategori ?? '') === 'Akses Ilegal') selected @endif>Akses Ilegal</option>
                                    <option value="Malware & Virus" @if(($searchKategori ?? '') === 'Malware & Virus') selected @endif>Malware & Virus</option>
                                    <option value="Perdagangan Ilegal Online" @if(($searchKategori ?? '') === 'Perdagangan Ilegal Online') selected @endif>Perdagangan Ilegal Online</option>
                                    <option value="Lainnya" @if(($searchKategori ?? '') === 'Lainnya') selected @endif>Lainnya</option>
                                </select>
                            </div>
                            <div class="col-12 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-grow-1">
                                    <i class="bi bi-search"></i> Cari
                                </button>
                                <a href="{{ route('dashboard.petugas.home') }}" class="btn btn-secondary flex-grow-1">
                                    <i class="bi bi-arrow-clockwise"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Laporan Terbaru</h5>
                    <a href="{{ route('reports.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Pelapor</th>
                                <th>Kategori Kejahatan</th>
                                <th>Korban</th>
                                <th width="140">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports ?? [] as $i => $report)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($report->tanggal)->format('d/m/Y') }}</td>
                                    <td>{{ $report->pelapor_nama }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">{{ $report->kategori_kejahatan }}</span>
                                    </td>
                                    <td>{{ $report->korban_nama }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{ route('reports.downloadWord', $report->id) }}">
                                            <i class="bi bi-file-word"></i> Word
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        @if($searchTanggal || $searchNama || $searchKategori)
                                            Hasil pencarian tidak ditemukan. Coba ubah filter pencarian Anda.
                                        @else
                                            Belum ada laporan. Silakan buat laporan baru.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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

            const icon = toggleButton.querySelector('i');
            if (newTheme === 'dark') {
                icon.className = 'bi bi-sun';
                toggleButton.innerHTML = '<i class="bi bi-sun"></i> Toggle Dark/Light Mode';
            } else {
                icon.className = 'bi bi-moon-stars';
                toggleButton.innerHTML = '<i class="bi bi-moon-stars"></i> Toggle Dark/Light Mode';
            }
        });

        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        const icon = toggleButton.querySelector('i');
        if (savedTheme === 'dark') {
            icon.className = 'bi bi-sun';
            toggleButton.innerHTML = '<i class="bi bi-sun"></i> Toggle Dark/Light Mode';
        }
    </script>
</body>
</html>

