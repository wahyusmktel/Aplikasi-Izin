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
        Schema::table('keterlambatans', function (Blueprint $table) {
            // Mengubah kolom status untuk menerima nilai baru
            $table->enum('status', [
                'dicatat_security',
                'diverifikasi_piket',
                'selesai', // Nilai baru
                'terlambat' // Nilai baru
            ])->default('dicatat_security')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            // Mengembalikan ke definisi lama jika di-rollback
            $table->enum('status', [
                'dicatat_security',
                'diverifikasi_piket'
            ])->default('dicatat_security')->change();
        });
    }
};
