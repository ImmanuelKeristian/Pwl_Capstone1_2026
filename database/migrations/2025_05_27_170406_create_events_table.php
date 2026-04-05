<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_category_id')->constrained('event_categories')->onDelete('cascade');
            $table->string('title'); // Judul Event Induk
            $table->text('description'); // Deskripsi Umum
            $table->string('poster_kegiatan')->nullable(); // Poster Utama
            
            // PERUBAHAN: Menambahkan rentang tanggal untuk Event Induk
            $table->date('start_date'); // Tanggal mulai keseluruhan event
            $table->date('end_date');   // Tanggal berakhir keseluruhan event
        
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};