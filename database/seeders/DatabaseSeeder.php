<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder dalam urutan yang logis
        $this->call([
            StatusSeeder::class,        // Wajib ada sebelum yang lain
            UserSeeder::class,          // Buat pengguna dengan peran
            EventCategorySeeder::class, // Buat kategori
            // EventSeeder::class,      // Jika Anda membuat seeder untuk event
            // EventSessionSeeder::class, // Jika Anda membuat seeder untuk sesi
        ]);
    }
}
