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
        Schema::create('status', function (Blueprint $table) {
            // SQL had INT(1) UNSIGNED. unsignedTinyInteger is 0-255.
            // If it's just a small number of statuses but you used INT(1) as a standard int,
            // then unsignedInteger('id') is fine. For this example, assuming few statuses.
            $table->unsignedTinyInteger('id'); // For INT(1) UNSIGNED
            $table->primary('id');
            // If you want this to be auto-incrementing (though unusual for status IDs typically seeded), use:
            // $table->tinyIncrements('id');
            // Or if INT(1) was meant as a standard INT but just small:
            // $table->unsignedInteger('id'); $table->primary('id');

            $table->string('name')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status');
    }
};