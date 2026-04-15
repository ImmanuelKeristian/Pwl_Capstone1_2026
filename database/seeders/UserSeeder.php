<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum mengisi
        // User::truncate();

        // Buat pengguna dengan peran yang berbeda-beda
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@app.com',
            'password' => Hash::make('password'), // password = password
            'role' => 'administrator',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Panitia Kegiatan',
            'email' => 'panitia@app.com',
            'password' => Hash::make('password'),
            'role' => 'panitia_kegiatan',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Tim Keuangan',
            'email' => 'finance@app.com',
            'password' => Hash::make('password'),
            'role' => 'tim_keuangan',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Member User',
            'email' => 'member@app.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'email_verified_at' => now(),
        ]);
        
        User::factory()->count(10)->create(); // Tambahkan beberapa pengguna acak menggunakan factory
    }
}
