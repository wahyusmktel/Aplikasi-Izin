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
        Schema::create('rombel_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rombel_id')->constrained()->onDelete('cascade');
            $table->foreignId('master_siswa_id')->constrained('master_siswa')->onDelete('cascade');
            $table->unique(['rombel_id', 'master_siswa_id']); // Pastikan satu siswa tidak duplikat di rombel yg sama
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombel_siswa');
    }
};
