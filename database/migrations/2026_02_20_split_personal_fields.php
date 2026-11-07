<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pisahkan fields TTL dan telepon/email menjadi fields terpisah
     * - pelapor_ttl → pelapor_tempat_lahir + pelapor_tanggal_lahir
     * - pelapor_telp → tetap + tambah pelapor_email
     * - korban_ttl → korban_tempat_lahir + korban_tanggal_lahir
     * - korban_telp → tetap + tambah korban_email
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Tambah fields baru untuk pelapor
            $table->string('pelapor_tempat_lahir')->nullable()->after('pelapor_nik');
            $table->date('pelapor_tanggal_lahir')->nullable()->after('pelapor_tempat_lahir');
            $table->string('pelapor_email')->nullable()->after('pelapor_telp');

            // Tambah fields baru untuk korban
            $table->string('korban_tempat_lahir')->nullable()->after('korban_nik');
            $table->date('korban_tanggal_lahir')->nullable()->after('korban_tempat_lahir');
            $table->string('korban_email')->nullable()->after('korban_telp');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            // Drop tambahan fields jika migration di-rollback
            $table->dropColumn([
                'pelapor_tempat_lahir',
                'pelapor_tanggal_lahir',
                'pelapor_email',
                'korban_tempat_lahir',
                'korban_tanggal_lahir',
                'korban_email'
            ]);
        });
    }
};
