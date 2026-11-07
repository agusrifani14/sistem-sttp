<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{

    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        $totalReports = Report::where('user_id', $userId)->count();
        $reportsToday = Report::where('user_id', $userId)
            ->whereDate('created_at', Carbon::today())
            ->count();
        $reportsThisMonth = Report::where('user_id', $userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Build query with search filters
        $query = Report::where('user_id', $userId);

        // Filter by date (tanggal)
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->input('tanggal'));
        }

        // Filter by reporter/legal advisor name (pelapor_nama)
        if ($request->filled('nama')) {
            $query->where('pelapor_nama', 'like', '%' . $request->input('nama') . '%');
        }

        // Filter by crime category (kategori_kejahatan)
        if ($request->filled('kategori')) {
            $query->where('kategori_kejahatan', $request->input('kategori'));
        }

        $recentReports = $query->latest()->take(5)->get();

        // Get search parameters to pass back to view
        $searchTanggal = $request->input('tanggal');
        $searchNama = $request->input('nama');
        $searchKategori = $request->input('kategori');

        return view('dashboard_petugas_home', compact(
            'totalReports',
            'reportsToday',
            'reportsThisMonth',
            'recentReports',
            'searchTanggal',
            'searchNama',
            'searchKategori'
        ));
    }


    private function intToRoman($num)
    {
        $map = [
            'M'  => 1000, 'CM' => 900,
            'D'  => 500,  'CD' => 400,
            'C'  => 100,  'XC' => 90,
            'L'  => 50,   'XL' => 40,
            'X'  => 10,   'IX' => 9,
            'V'  => 5,    'IV' => 4,
            'I'  => 1
        ];

        $returnValue = '';
        foreach ($map as $roman => $value) {
            while ($num >= $value) {
                $returnValue .= $roman;
                $num -= $value;
            }
        }
        return $returnValue;
    }


    private function generateNomor()
    {
        $year = date('Y');
        $count = Report::whereYear('created_at', $year)->count() + 1;
        $roman = $this->intToRoman($count);

        return "STTP/{$roman}/V/RES.2.5./{$year}/Ditreskrimsus";
    }

    private function safe($value)
    {
        return htmlspecialchars((string)$value, ENT_XML1 | ENT_COMPAT, 'UTF-8');
    }

    /* --------------------------------------------------------------------------
     *  CREATE → HALAMAN PETUGAS
     * ------------------------------------------------------------------------*/
    public function create()
    {
        return view('dashboard_petugas');
    }

    /* --------------------------------------------------------------------------
     *  STORE → SIMPAN LAPORAN
     * ------------------------------------------------------------------------*/
    public function store(Request $request)
    {
        $validated = $request->validate([
            'petugas_nama' => 'required|string',
            'petugas_pangkat' => 'required|string',
            'petugas_nrp' => 'required|string',
            'petugas_jabatan' => 'required|string',
            'hari' => 'required|string',
            'tanggal' => 'required|date',
            'jam' => 'required|string',
            'kategori_kejahatan' => 'required|string',
            'pelapor_nama' => 'required|string',
            'pelapor_nik' => 'required|regex:/^[0-9]{16}$/',
            'pelapor_tempat_lahir' => 'required|regex:/^[a-zA-Z\s]+$/i',
            'pelapor_tanggal_lahir' => 'required|date',
            'pelapor_ttl' => 'nullable|string',
            'pelapor_agama' => 'required|string',
            'pelapor_kewarganegaraan' => 'required|string',
            'pelapor_alamat' => 'required|string',
            'pelapor_telp' => 'required|regex:/^[0-9]{9,13}$/',
            'pelapor_email' => 'required|email',
            'korban_nama' => 'required|string',
            'korban_nik' => 'required|regex:/^[0-9]{16}$/',
            'korban_tempat_lahir' => 'required|regex:/^[a-zA-Z\s]+$/i',
            'korban_tanggal_lahir' => 'required|date',
            'korban_ttl' => 'nullable|string',
            'korban_agama' => 'required|string',
            'korban_kewarganegaraan' => 'required|string',
            'korban_alamat' => 'required|string',
            'korban_telp' => 'required|regex:/^[0-9]{9,13}$/',
            'korban_email' => 'required|email',
            'deskripsi' => 'required|string',
            'bukti.*' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $validated['user_id'] = Auth::id();

        // Generate combined TTL fields from separated fields
        $validated['pelapor_ttl'] = $validated['pelapor_tempat_lahir'] . ', ' . $validated['pelapor_tanggal_lahir'];
        $validated['korban_ttl'] = $validated['korban_tempat_lahir'] . ', ' . $validated['korban_tanggal_lahir'];

        // upload bukti
        if ($request->hasFile('bukti')) {
            $paths = [];
            foreach ($request->file('bukti') as $file) {
                $paths[] = $file->store('bukti', 'public');
            }
            $validated['bukti'] = json_encode($paths);
        }

        Report::create($validated);

        return back()->with('success', 'Laporan berhasil disimpan!');
    }

    private function findAuthorizedReport($id): Report
    {
        $query = Report::where('id', $id);
        if (Auth::user()?->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        return $query->firstOrFail();
    }

    /* --------------------------------------------------------------------------
     *  INDEX → RIWAYAT LAPORAN
     * ------------------------------------------------------------------------*/
    public function index()
    {
        $reports = Report::where('user_id', Auth::id())->latest()->get();
        return view('riwayat', compact('reports'));
    }

    /* --------------------------------------------------------------------------
     *  DOWNLOAD WORD (MENGGUNAKAN TEMPLATE DOCX ASLI)
     * ------------------------------------------------------------------------*/
    public function downloadWord($id)
    {
        $report = $this->findAuthorizedReport($id);
        $nomor = $this->generateNomor();

        // Format tanggal - HANYA tanggal, tanpa "Palembang"
        $formattedDate = Carbon::parse($report->tanggal)->translatedFormat('d F Y');
        $tanggalOnly = $formattedDate;

        $templatePath = storage_path('app/templates/sttp_template (2).docx');
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template tidak ditemukan!');
        }

        // Buat folder temp jika belum ada
        $tempDir = storage_path('app/temp');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tpl = new TemplateProcessor($templatePath);

        // ISI FIELD TEMPLATE
        $tpl->setValue('nomor', $this->safe($nomor));
        $tpl->setValue('tanggal_surat', $this->safe($tanggalOnly));
        $tpl->setValue('lokasi_surat', 'Palembang');

        $tpl->setValue('hari', $this->safe($report->hari));
        $tpl->setValue('tanggal', $this->safe($report->tanggal));
        $tpl->setValue('jam', $this->safe($report->jam));

        // Petugas
        $tpl->setValue('petugas_nama', $this->safe(strtoupper($report->petugas_nama)));
        $tpl->setValue('petugas_pangkat', $this->safe($report->petugas_pangkat));
        $tpl->setValue('petugas_nrp', $this->safe($report->petugas_nrp));
        $tpl->setValue('petugas_jabatan', $this->safe($report->petugas_jabatan));

        // Pelapor
        $tpl->setValue('pelapor_nama', $this->safe(strtoupper($report->pelapor_nama)));
        $tpl->setValue('pelapor_nik', $this->safe($report->pelapor_nik));
        $tpl->setValue('pelapor_tempat_lahir', $this->safe(strtoupper($report->pelapor_tempat_lahir)));
        $tpl->setValue('pelapor_tanggal_lahir', $this->safe($report->pelapor_tanggal_lahir));
        $tpl->setValue('pelapor_ttl', $this->safe(strtoupper($report->pelapor_tempat_lahir . ', ' . $report->pelapor_tanggal_lahir)));
        $tpl->setValue('pelapor_agama', $this->safe(strtoupper($report->pelapor_agama)));
        $tpl->setValue('pelapor_kewarganegaraan', $this->safe(strtoupper($report->pelapor_kewarganegaraan)));
        $tpl->setValue('pelapor_alamat', $this->safe(strtoupper($report->pelapor_alamat)));
        $tpl->setValue('pelapor_telp', $this->safe($report->pelapor_telp));
        $tpl->setValue('pelapor_email', $this->safe($report->pelapor_email));

        // Korban
        $tpl->setValue('korban_nama', $this->safe(strtoupper($report->korban_nama)));
        $tpl->setValue('korban_nik', $this->safe($report->korban_nik));
        $tpl->setValue('korban_tempat_lahir', $this->safe(strtoupper($report->korban_tempat_lahir)));
        $tpl->setValue('korban_tanggal_lahir', $this->safe($report->korban_tanggal_lahir));
        $tpl->setValue('korban_ttl', $this->safe(strtoupper($report->korban_tempat_lahir . ', ' . $report->korban_tanggal_lahir)));
        $tpl->setValue('korban_agama', $this->safe(strtoupper($report->korban_agama)));
        $tpl->setValue('korban_kewarganegaraan', $this->safe(strtoupper($report->korban_kewarganegaraan)));
        $tpl->setValue('korban_alamat', $this->safe(strtoupper($report->korban_alamat)));
        $tpl->setValue('korban_telp', $this->safe($report->korban_telp));
        $tpl->setValue('korban_email', $this->safe($report->korban_email));

        $tpl->setValue('deskripsi', $this->safe($report->deskripsi));

        // ✅ GAMBAR BUKTI - MULTIPLE IMAGES DENGAN CLEANUP PLACEHOLDER KOSONG
        if ($report->bukti) {
            $images = json_decode($report->bukti, true);

            // Isi placeholder yang ada fotonya
            foreach ($images as $index => $imgPath) {
                $placeholder = $index == 0 ? 'bukti_image' : 'bukti_image' . ($index + 1);
                $full = storage_path('app/public/' . $imgPath);

                if (file_exists($full)) {
                    $tpl->setImageValue($placeholder, [
                        'path' => $full,
                        'width' => 380,
                        'height' => 280,
                        'ratio' => true
                    ]);
                }
            }

            // Total placeholder yang ada di Word
            $totalPlaceholder = 6;

            // Kosongkan placeholder yang tidak terisi
            for ($i = count($images) + 1; $i <= $totalPlaceholder; $i++) {
                $placeholder = $i == 1 ? 'bukti_image' : 'bukti_image' . $i;
                $tpl->setValue($placeholder, '');
            }
        }

        $output = storage_path("app/temp/STTP-$id.docx");
        $tpl->saveAs($output);

        return response()->download($output)->deleteFileAfterSend(true);
    }

    /* --------------------------------------------------------------------------
     *  PDF (MENGGUNAKAN DOMPDF DARI BLADE VIEW)
     * ------------------------------------------------------------------------*/
    public function downloadPdf($id)
    {
        $report = $this->findAuthorizedReport($id);
        $nomor = $this->generateNomor();

        // Format tanggal
        $formattedDate = Carbon::parse($report->tanggal)->translatedFormat('d F Y');

        $data = [
            'report' => $report,
            'nomor' => $nomor,
            'tanggalSurat' => $formattedDate,
        ];

        // Load view blade untuk PDF (gunakan view `sttp` yang sudah ada)
        $pdf = Pdf::loadView('sttp', $data)
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        return $pdf->download('STTP_' . $id . '.pdf');
    }

    /* --------------------------------------------------------------------------
     *  DELETE LAPORAN
     * ------------------------------------------------------------------------*/
    public function destroy($id)
    {
        $report = $this->findAuthorizedReport($id);

        if ($report->bukti) {
            foreach (json_decode($report->bukti) as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $report->delete();
        return redirect()->route('reports.index');
    }
}   