<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // Pastikan baris ini ditambahkan

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Matikan pengecekan foreign key agar truncate tidak error
        Schema::disableForeignKeyConstraints();

        // Panggil seeder dalam urutan yang logis
        $this->call([
            StatusSeeder::class,        // Wajib ada sebelum yang lain
            UserSeeder::class,          // Buat pengguna dengan peran
            EventCategorySeeder::class, // Buat kategori
            // EventSeeder::class,      // Jika Anda membuat seeder untuk event
            // EventSessionSeeder::class, // Jika Anda membuat seeder untuk sesi
        ]);

        // Nyalakan kembali pengecekan foreign key setelah selesai
        Schema::enableForeignKeyConstraints();
    }
}