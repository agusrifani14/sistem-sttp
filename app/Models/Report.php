<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'petugas_nama', 'petugas_pangkat', 'petugas_nrp', 'petugas_jabatan',
        'hari', 'tanggal', 'jam',
        'pelapor_nama', 'pelapor_nik', 'pelapor_ttl', 'pelapor_agama', 
        'pelapor_kewarganegaraan', 'pelapor_alamat', 'pelapor_telp',
        'korban_nama', 'korban_nik', 'korban_ttl', 'korban_agama', 
        'korban_kewarganegaraan', 'korban_alamat', 'korban_telp',
        'deskripsi', 'bukti', 'user_id'
    ];
}