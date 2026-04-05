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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
        
            // 1. Menggunakan nama kolom yang benar
            $table->unsignedBigInteger('event_register_user_id')->nullable();
            $table->unsignedBigInteger('event_register_event_session_id')->nullable();
            
            $table->timestamps();
        
            // 2. Mereferensikan ke kolom yang benar di tabel 'event_register'
            $table->foreign(
                ['event_register_user_id', 'event_register_event_session_id'], 
                'files_event_register_fk'
            )
            ->references(['user_id', 'event_session_id'])->on('event_register')
            ->onDelete('set null')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};