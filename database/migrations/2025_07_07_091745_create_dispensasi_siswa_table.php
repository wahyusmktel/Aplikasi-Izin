<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispensasi_id')->constrained()->onDelete('cascade');
            $table->foreignId('master_siswa_id')->constrained('master_siswa')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispensasi_siswa');
    }
};
