<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>STTP {{ $report->id ?? '' }}</title>
    <style>
        @page { size: A4; margin: 1.5cm; }
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        .kop-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .kop-logo {
            width: 80px;
            height: auto;
            margin-right: 20px;
        }
        .kop-text {
            text-align: center;
        }
        .kop-text .main-title {
            font-weight: bold;
            text-decoration: underline;
            font-size: 14pt;
            margin-bottom: 5px;
        }
        .kop-text .sub-title {
            font-weight: bold;
            font-size: 11pt;
            margin-bottom: 3px;
        }
        .header-text {
            font-weight: bold;
            text-decoration: underline;
        }
        .content { margin: 20px 0; }
        .indent { margin-left: 20px; }
        .bold { font-weight: bold; }
        .bukti-title {
            text-align: center;
            font-weight: bold;
            margin: 40px 0 20px 0;
            text-decoration: underline;
        }
        .bukti-image {
            width: 400px;
            height: auto;
            margin: 10px auto;
            display: block;
            border: 1px solid #000;
        }
        .image-placeholder {
            width: 400px;
            height: 300px;
            border: 2px dashed #000;
            margin: 10px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-style: italic;
            color: #555;
        }

        /* Layout tanda tangan yang diperbaiki */
        .signature-section {
            margin-top: 60px;
            page-break-inside: avoid;
        }
        .signature-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-left, .signature-right {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            margin-top: 80px;
            margin-bottom: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .signature-nrp {
            font-size: 11pt;
        }
        .nomor-surat {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Kop Surat yang Diperbaiki -->
    <div class="kop-surat">
        <div class="kop-header">
            <img src="{{ public_path('images/logo1.png') }}" alt="Logo POLRI" class="kop-logo">
            <div class="kop-text">
                <div class="main-title">KEPOLISIAN NEGARA REPUBLIK INDONESIA</div>
                <div class="sub-title">DAERAH SUMATERA SELATAN</div>
                <div class="sub-title">DIREKTORAT RESERSE KRIMINAL KHUSUS</div>
                <div style="font-size: 10pt; margin-top: 5px;">
                    <strong>Ditreskrimsus Unit 2 Subdit V</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="nomor-surat">
        <div class="bold">SURAT TANDA TERIMA PENGADUAN</div>
        <div>Nomor : {{ $nomor ?? 'STTP/V/RES.2.5./2025/Ditreskrimsus' }}</div>
    </div>

    <div class="content">
        <div>-----Yang bertanda tangan dibawah ini saya:-----</div>
        <div class="indent">
            <table style="width: 100%">
                <tr>
                    <td style="width: 150px">Nama</td>
                    <td>: {{ strtoupper($report->petugas_nama ?? '') }}</td>
                </tr>
                <tr>
                    <td>Pangkat</td>
                    <td>: {{ $report->petugas_pangkat ?? '' }} Nrp {{ $report->petugas_nrp ?? '' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>: {{ $report->petugas_jabatan ?? '' }}</td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 10px;">
            Pada hari {{ $report->hari ?? '' }} tanggal {{ $report->tanggal ?? '' }} Jam {{ $report->jam ?? '' }} 
            waktu setempat, telah datang ke Subdit V Siber seorang mengaku:
        </div>

        <div class="bold" style="margin-top: 10px;">Selaku Penasehat Hukum</div>
        <div class="indent">
            <ol style="margin: 0;">
                <li>NAMA : {{ strtoupper($report->pelapor_nama ?? '') }}</li>
                <li>NIK : {{ $report->pelapor_nik ?? '' }}</li>
                <li>TEMPAT, TGL LAHIR : {{ strtoupper($report->pelapor_ttl ?? '') }}</li>
                <li>AGAMA : {{ strtoupper($report->pelapor_agama ?? '') }}</li>
                <li>KEWARGANEGARAAN : {{ strtoupper($report->pelapor_kewarganegaraan ?? '') }}</li>
                <li>ALAMAT : {{ strtoupper($report->pelapor_alamat ?? '') }}</li>
                <li>TELP/FAX/EMAIL : {{ $report->pelapor_telp ?? '' }}</li>
            </ol>
        </div>

        <div class="bold" style="margin-top: 10px;">Selaku Korban/Pelapor</div>
        <div class="indent">
            <ol style="margin: 0;">
                <li>NAMA : {{ strtoupper($report->korban_nama ?? '') }}</li>
                <li>NIK : {{ $report->korban_nik ?? '' }}</li>
                <li>TEMPAT, TGL LAHIR : {{ strtoupper($report->korban_ttl ?? '') }}</li>
                <li>AGAMA : {{ strtoupper($report->korban_agama ?? '') }}</li>
                <li>KEWARGANEGARAAN : {{ strtoupper($report->korban_kewarganegaraan ?? '') }}</li>
                <li>ALAMAT : {{ strtoupper($report->korban_alamat ?? '') }}</li>
                <li>TELP/FAX/EMAIL : {{ $report->korban_telp ?? '' }}</li>
            </ol>
        </div>

        <div style="margin-top: 20px; text-align: justify;">
            {{ $report->deskripsi ?? '' }}
        </div>

        <div style="margin-top: 20px;">
            Demikian Surat Tanda Penerimaan Pengaduan ini dibuat untuk dapat dipergunakan seperlunya.
        </div>

        <!-- Tanda Tangan: Penasehat Hukum (kiri) + Penerima Pengaduan (kanan) -->
        <div class="signature-section">
            <div class="signature-container">
                <div class="signature-left">
                    <div>Penasehat Hukum</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ strtoupper($report->pelapor_nama ?? '-') }}</div>
                </div>

                <div class="signature-right">
                    <div>Penerima Pengaduan</div>
                    <div class="signature-line"></div>
                    <div class="signature-name">{{ strtoupper($report->petugas_nama ?? '-') }}</div>
                    <div class="signature-nrp">{{ $report->petugas_pangkat ?? '' }} NRP {{ $report->petugas_nrp ?? '' }}</div>
                </div>
            </div>
        </div>

        <!-- Bukti Pendukung: Diletakkan di bawah tanda tangan -->
        @if($report->bukti)
            <div class="bukti-title">BUKTI PENDUKUNG LAPORAN:</div>
            <div class="bukti-section">
                @foreach(json_decode($report->bukti) as $path)
                    @if(Storage::disk('public')->exists($path))
                        <img src="{{ storage_path('app/public/' . $path) }}" class="bukti-image">
                    @endif
                @endforeach
            </div>
        @else
            <div class="bukti-title">BUKTI PENDUKUNG LAPORAN:</div>
            <div class="image-placeholder">
                [Tidak ada bukti pendukung yang diupload]
            </div>
        @endif
    </div>
</body>
</html>