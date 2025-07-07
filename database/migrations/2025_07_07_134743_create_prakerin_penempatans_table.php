<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prakerin_penempatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('master_siswa_id')->constrained('master_siswa')->onDelete('cascade');
            $table->foreignId('prakerin_industri_id')->constrained('prakerin_industris')->onDelete('cascade');
            $table->foreignId('master_guru_id')->constrained('master_gurus')->onDelete('cascade')->comment('Guru Pembimbing dari Sekolah');
            $table->string('nama_pembimbing_industri');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->enum('status', ['aktif', 'selesai', 'dibatalkan'])->default('aktif');
            $table->timestamps();

            // Satu siswa hanya bisa ditempatkan sekali dalam satu waktu
            $table->unique(['master_siswa_id', 'tanggal_mulai']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prakerin_penempatans');
    }
};
