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
        User::create([
            'name' => 'Administrator',
            'email' => 'admin123@gmail.com', 
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // Petugas
        User::create([
            'name' => 'Petugas STTP',
            'email' => 'petugas123@gmail.com',
            'password' => bcrypt('petugas123'),
            'role' => 'petugas',
        ]);
    }
}
