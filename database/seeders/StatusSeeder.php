<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pemeriksaan foreign key sementara
        Schema::disableForeignKeyConstraints();

        // Kosongkan tabel
        DB::table('status')->truncate();

        // Aktifkan kembali pemeriksaan foreign key
        Schema::enableForeignKeyConstraints();

        // Masukkan data status yang dibutuhkan dengan alur yang benar
        DB::table('status')->insert([
            ['id' => 1, 'name' => 'Menunggu Pembayaran'],
            ['id' => 2, 'name' => 'Menunggu Konfirmasi'], // <-- STATUS BARU DITAMBAHKAN
            ['id' => 3, 'name' => 'Pembayaran Diterima'], // ID diubah dari 2 menjadi 3
            ['id' => 4, 'name' => 'Hadir'],                // ID diubah dari 3 menjadi 4
            ['id' => 5, 'name' => 'Tidak Hadir'],           // ID diubah dari 4 menjadi 5
            ['id' => 6, 'name' => 'Dibatalkan'],            // ID diubah dari 5 menjadi 6
        ]);
    }
}