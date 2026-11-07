<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin123@gmail.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'whatsapp' => '08123456789',
            ]
        );

        // Petugas
        User::updateOrCreate(
            ['email' => 'petugas123@gmail.com'],
            [
                'name' => 'Petugas STTP',
                'password' => bcrypt('petugas123'),
                'role' => 'petugas',
                'whatsapp' => '08198765432',
            ]
        );
    }
}
