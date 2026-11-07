<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'petugas_nama', 'petugas_pangkat', 'petugas_nrp', 'petugas_jabatan',
        'hari', 'tanggal', 'jam', 'kategori_kejahatan',
        'pelapor_nama', 'pelapor_nik', 'pelapor_tempat_lahir', 'pelapor_tanggal_lahir', 
        'pelapor_ttl', 'pelapor_agama', 'pelapor_kewarganegaraan', 'pelapor_alamat', 
        'pelapor_telp', 'pelapor_email',
        'korban_nama', 'korban_nik', 'korban_tempat_lahir', 'korban_tanggal_lahir', 
        'korban_ttl', 'korban_agama', 'korban_kewarganegaraan', 'korban_alamat', 
        'korban_telp', 'korban_email',
        'deskripsi', 'bukti', 'user_id'
    ];
}