<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_create_event_register_table.php
    public function up(): void
    {
        Schema::create('event_register', function (Blueprint $table) {
            // PERBAIKAN 1: Tambahkan primary key auto-increment standar
            $table->id();

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('event_session_id')->constrained('event_sessions')->onDelete('cascade');
            
            $table->unsignedTinyInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');

            $table->string('payment_file')->nullable();
            $table->timestamps();

            // PERBAIKAN 2: Hapus primary key komposit lama
            // $table->primary(['user_id', 'event_session_id']);

            // PERBAIKAN 3: Jadikan kolom lama sebagai unique index
            // Ini memastikan seorang pengguna tidak bisa mendaftar ke sesi yang sama lebih dari sekali.
            $table->unique(['user_id', 'event_session_id']);
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_register');
    }
};