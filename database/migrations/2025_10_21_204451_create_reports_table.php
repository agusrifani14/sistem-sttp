<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('petugas_nama');
            $table->string('petugas_pangkat');
            $table->string('petugas_nrp');
            $table->string('petugas_jabatan');
            $table->string('hari');
            $table->date('tanggal');
            $table->string('jam');
            $table->string('pelapor_nama');
            $table->string('pelapor_nik');
            $table->string('pelapor_ttl');
            $table->string('pelapor_agama');
            $table->string('pelapor_kewarganegaraan');
            $table->text('pelapor_alamat');
            $table->string('pelapor_telp');
            $table->string('korban_nama');
            $table->string('korban_nik');
            $table->string('korban_ttl');
            $table->string('korban_agama');
            $table->string('korban_kewarganegaraan');
            $table->text('korban_alamat');
            $table->string('korban_telp');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};