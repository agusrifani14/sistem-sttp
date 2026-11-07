<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            User::updateOrCreate(
                ['email' => 'admin123@gmail.com'],
                [
                    'name'     => 'Administrator',
                    'password' => Hash::make('admin123'),
                    'role'     => 'admin',
                    'whatsapp' => '08123456789',
                ]
            );

            User::updateOrCreate(
                ['email' => 'petugas123@gmail.com'],
                [
                    'name'     => 'Petugas STTP',
                    'password' => Hash::make('petugas123'),
                    'role'     => 'petugas',
                    'whatsapp' => '08198765432',
                ]
            );
        } catch (\Throwable $e) {
            // Abaikan jika tabel belum siap
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
