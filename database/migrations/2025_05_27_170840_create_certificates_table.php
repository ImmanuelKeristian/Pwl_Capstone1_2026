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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            
            // PERUBAHAN: Sekarang hanya menggunakan satu foreign key
            $table->foreignId('event_register_id')
                  ->unique() // Memastikan satu pendaftaran hanya punya satu sertifikat
                  ->constrained('event_register')
                  ->onDelete('cascade');
        
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};