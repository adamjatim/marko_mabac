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
        Schema::create('mobils', function (Blueprint $table) {
            $table->id();
            $table->string('merk');
            $table->string('model');
            $table->string('tahun');
            $table->string('tipe'); // City Car, Sedan, MPV, Compact SUV, Premium SUV, Electric Car
            $table->decimal('harga_baru', 15, 2);
            $table->decimal('harga_jual_kembali', 15, 2);
            $table->integer('fitur_keamanan'); // jumlah fitur
            $table->integer('fitur_kenyamanan'); // jumlah fitur
            // $table->decimal('jarak_tempuh', 8, 2); // km/liter
            $table->decimal('efisiensi_bahan_bakar', 8, 2); // km/liter
            // $table->integer('kapasitas_mesin'); // cc
            $table->integer('performa'); // cc
            $table->decimal('pajak', 15, 2);
            $table->text('gambar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobils');
    }
};
