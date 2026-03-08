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
        Schema::create('bobot_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriterias')->onDelete('cascade');
            $table->decimal('nilai_input', 8, 2)->nullable(); // Nilai L (nilai input)
            $table->decimal('nilai_penyebut', 10, 2)->default(1); // Total L
            $table->decimal('hasil_bobot', 5, 4); // L / Total(L)
            $table->timestamps();
            
            // Ensure one bobot per kriteria
            $table->unique('kriteria_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bobot_kriterias');
    }
};
