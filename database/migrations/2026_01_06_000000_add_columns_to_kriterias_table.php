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
        Schema::table('kriterias', function (Blueprint $table) {
            // Tambah kolom is_active setelah kolom tipe
            $table->boolean('is_active')->default(true)->after('tipe');
            // Tambah kolom keterangan setelah bobot_default
            $table->text('keterangan')->nullable()->after('bobot_default');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kriterias', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'keterangan']);
        });
    }
};
