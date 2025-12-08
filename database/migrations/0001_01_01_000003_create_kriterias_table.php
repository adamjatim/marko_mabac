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
        Schema::create('kriterias', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // e.g., "Harga Baru", "Fitur Keamanan"
            $table->enum('tipe', ['benefit', 'cost']); // benefit = lebih tinggi lebih baik, cost = lebih rendah lebih baik
            $table->decimal('bobot_default', 5, 2); // Default weight
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterias');
    }
};
