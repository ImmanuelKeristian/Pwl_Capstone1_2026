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
        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            
            // PERUBAHAN: Memisahkan tanggal dan waktu untuk kemudahan input
            $table->date('session_date'); // Tanggal spesifik sesi
            $table->time('start_time');   // Jam mulai sesi
            $table->time('end_time');     // Jam selesai sesi
        
            $table->string('location')->nullable();
            $table->string('speaker')->nullable();
            $table->unsignedInteger('max_participants');
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_sessions');
    }
};
