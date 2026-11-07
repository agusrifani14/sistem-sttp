<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat kolom pelapor_ttl dan korban_ttl menjadi nullable
     * Karena sudah dipecah menjadi tempat_lahir dan tanggal_lahir
     */
    public function up(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('pelapor_ttl')->nullable()->change();
            $table->string('korban_ttl')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->string('pelapor_ttl')->change();
            $table->string('korban_ttl')->change();
        });
    }
};
