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
            --text-color: #000;
            --card-bg: #fff;
            --input-bg: #fff;
            --input-text: #000;
            --border-color: #dee2e6;
        }
        :root[data-theme="dark"] {
            /* Lebih gelap dan rata supaya teks putih lebih jelas */
            --bg-color: #020617;
            --text-color: #f9fafb;
            --card-bg: #020617;
            --input-bg: #020617;
            --input-text: #f9fafb;
            --border-color: #4b5563;
            --label-color: #e5e7eb;
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
            border: 1px solid var(--border-color);
        }
        .card:hover { transform: translateY(-5px); }
        .btn { border-radius: 50px; }
        .form-control, .form-select { 
            border-radius: 10px; 
            color: var(--input-text); 
            background: var(--input-bg);
            border: 1px solid var(--border-color);
        }
        .form-control:focus, .form-select:focus {
            color: var(--input-text);
            background: var(--input-bg);
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        .form-label {
            color: var(--label-color, #212529);
        }
        [data-theme="dark"] .form-label {
            color: var(--label-color);
        }
        .header { 
            background: var(--header-bg, #2196f3); 
            color: white; 
            padding: 20px; 
            border-radius: 15px 15px 0 0; 
        }
        [data-theme="dark"] .header {
            background: var(--header-bg);
        }
        .card-header {
            background: var(--header-bg, #0d6efd) !important;
        }
        [data-theme="dark"] .card-header {
            background: var(--header-bg) !important;
        }
        .text-muted {
            color: var(--text-muted-color, #6c757d) !important;
        }
        [data-theme="dark"] .text-muted {
            color: var(--text-muted-color) !important;
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
        .content { margin-left: 250px; padding: 20px; margin-top: 88px; } 
        .template-hint {
            background: #e7f3ff;
            border-left: 4px solid #2196f3;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            font-size: 0.9em;
        }
        [data-theme="dark"] .template-hint {
            background: #1e3a8a;
            border-left-color: #3b82f6;
            color: #e5e7eb;
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

        /* top fixed bar */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 64px;
            background: linear-gradient(90deg,#0b74ff,#0a58d9);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 1050;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
            transition: all 0.3s ease;
        }
        [data-theme="dark"] .topbar {
            background: linear-gradient(90deg, #1e40af, #1e3a8a);
        }
        .topbar .left { display:flex; align-items:center; gap:12px; }
        .topbar img { height:40px; width:auto; display:block; }
        .topbar .title { font-weight:700; font-size:1.05rem; }

        /* Placeholder agar tetap terbaca di dark mode */
        .form-control::placeholder {
            color: #9ca3af;
        }
        [data-theme="dark"] .form-control::placeholder {
            color: #6b7280;
        }
    </style>
</head>
<body>
    <!-- fixed top bar -->
    <div class="topbar">
        <div class="left">
            <img src="{{ asset('images/logo.png') }}" alt="Logo POLRI">
            <div class="title">Ditreskrimsus Unit 2 Subdit V</div>
        </div>
        <div class="right">
            <!-- optional small helper on right (kept empty or add small text/icon) -->
        </div>
    </div>

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

    <!-- Content -->
    <div class="content container">
        <div class="card">
            <div class="header text-center">
                <h2><i class="bi bi-shield-check me-2"></i>Dashboard Petugas</h2>
                <p>Halo Petugas 👮! Buat laporan STTP dengan mudah dan aman.</p>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="{{ asset('images/logo1.png') }}" alt="Logo POLRI" style="height: 100px">
                    <h4 class="mt-3">KEPOLISIAN NEGARA REPUBLIK INDONESIA<br>DAERAH SUMATERA SELATAN<br>DIREKTORAT RESERSE KRIMINAL KHUSUS</h4>
                    <h5 class="mt-3">SURAT TANDA TERIMA PENGADUAN</h5>
                </div>
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                    @csrf
                    
                    <!-- Detail Petugas -->
                    <div class="col-12">
                        <div class="card mb-3">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">YANG BERTANDA TANGAN DIBAWAH INI:</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">NAMA</label>
                                            <input type="text" class="form-control" name="petugas_nama" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">PANGKAT / NRP</label>
                                            <div class="row">
                                                <div class="col">
                                                    <input type="text" class="form-control" name="petugas_pangkat" placeholder="Pangkat" required>
                                                </div>
                                                <div class="col">
                                                    <input type="text" class="form-control" name="petugas_nrp" placeholder="NRP" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">JABATAN</label>
                                            <input type="text" class="form-control" name="petugas_jabatan" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">WAKTU PENGADUAN</label>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="hari" placeholder="Hari" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="date" class="form-control" name="tanggal" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="text" class="form-control" name="jam" placeholder="Jam WIB" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pelapor -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">IDENTITAS PENASEHAT HUKUM/PELAPOR</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">1. NAMA LENGKAP</label>
                                    <input type="text" class="form-control" name="pelapor_nama" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">2. NIK</label>
                                    <input type="text" class="form-control" name="pelapor_nik" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">3. TEMPAT, TGL LAHIR</label>
                                    <input type="text" class="form-control" name="pelapor_ttl" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">4. AGAMA</label>
                                    <select class="form-select" name="pelapor_agama" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="ISLAM">ISLAM</option>
                                        <option value="KRISTEN">KRISTEN</option>
                                        <option value="KATOLIK">KATOLIK</option>
                                        <option value="HINDU">HINDU</option>
                                        <option value="BUDHA">BUDHA</option>
                                        <option value="KONGHUCU">KONGHUCU</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">5. KEWARGANEGARAAN</label>
                                    <input type="text" class="form-control" name="pelapor_kewarganegaraan" value="INDONESIA" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">6. ALAMAT LENGKAP</label>
                                    <textarea class="form-control" name="pelapor_alamat" rows="3" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">7. NO.TELP/EMAIL</label>
                                    <input type="text" class="form-control" name="pelapor_telp" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Korban -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">IDENTITAS KORBAN</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label class="form-label fw-bold">1. NAMA LENGKAP</label>
                                    <input type="text" class="form-control" name="korban_nama" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">2. NIK</label>
                                    <input type="text" class="form-control" name="korban_nik" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">3. TEMPAT, TGL LAHIR</label>
                                    <input type="text" class="form-control" name="korban_ttl" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">4. AGAMA</label>
                                    <select class="form-select" name="korban_agama" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="ISLAM">ISLAM</option>
                                        <option value="KRISTEN">KRISTEN</option>
                                        <option value="KATOLIK">KATOLIK</option>
                                        <option value="HINDU">HINDU</option>
                                        <option value="BUDHA">BUDHA</option>
                                        <option value="KONGHUCU">KONGHUCU</option>
                                    </select>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">5. KEWARGANEGARAAN</label>
                                    <input type="text" class="form-control" name="korban_kewarganegaraan" value="INDONESIA" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">6. ALAMAT LENGKAP</label>
                                    <textarea class="form-control" name="korban_alamat" rows="3" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label fw-bold">7. NO.TELP/EMAIL</label>
                                    <input type="text" class="form-control" name="korban_telp" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Kejadian dengan Template -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">URAIAN KEJADIAN</h5>
                            </div>
                            <div class="card-body">
                                <div class="template-hint">
                                    <strong><i class="bi bi-lightbulb"></i> Template Uraian:</strong><br>
                                    "Telah melaporkan tentang peristiwa Tindak Pidana diduga berupa setiap orang dengan sengaja dan tanpa hak atau melawan hukum melakukan manipulasi, penciptaan, perubahan, penghilangan, pengrusakan Informasi Elektronik dan/atau Dokumen Elektronik dengan tujuan agar Informasi Elektronik dan/atau Dokumen Elektronik tersebut dianggap seolah-olah data yang otentik, sebagaimana dimaksud dalam pasal 35 UU ITE Undang–Undang RI No. 19 tahun 2016 tentang perubahan Undang–undang nomor 11 tahun 2008 tentang Informasi dan Transaksi Elektronik, berawal pada hari [HARI] tanggal [TANGGAL] sekitar pukul [JAM] WIB mendapatkan, korban mendapatkan pesan masuk lewat via whatsapp dengan No. [NOMOR PELAKU] yang mana pelaku mengaku sebagai [IDENTITAS PALSU PELAKU]. Korban melaporkan kejadian tersebut kepihak yang berwajib."
                                </div>
                                
                                <div class="mb-3">
                                    <textarea class="form-control" name="deskripsi" rows="8" required 
                                        placeholder="Jelaskan kronologi kejadian secara detail dengan mengikuti template di atas..." 
                                        style="min-height: 200px; resize: vertical; font-size: 14px; line-height: 1.5;"></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label fw-bold">BUKTI PENDUKUNG</label>
                                    <input type="file" class="form-control" name="bukti[]" multiple accept="image/*">
                                    <small class="text-muted">Upload foto bukti pendukung (dapat multiple file, maksimal 2MB per file)</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-save"></i> SIMPAN LAPORAN PENGADUAN
                        </button>
                    </div>
                </form>
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

    // Auto-expand textarea saat focus
    document.addEventListener('DOMContentLoaded', function() {
        const textarea = document.querySelector('textarea[name="deskripsi"]');
        if (textarea) {
            textarea.addEventListener('focus', function() {
                this.style.height = '300px';
            });
            
            textarea.addEventListener('blur', function() {
                if (this.value === '') {
                    this.style.height = '200px';
                }
            });
        }
    });
</script>
</body>
</html>