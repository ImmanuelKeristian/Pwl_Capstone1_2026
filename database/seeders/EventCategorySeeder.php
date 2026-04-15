<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel sebelum mengisi untuk menghindari duplikasi
        // DB::table('event_categories')->truncate();

        DB::table('event_categories')->insert([
            ['name' => 'Seminar'],
            ['name' => 'Workshop'],
            ['name' => 'Lomba'],
            ['name' => 'Talkshow'],
            ['name' => 'Webinar'],
            ['name' => 'Konferensi'],
        ]);
    }
}