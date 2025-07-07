<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prakerin_jurnals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prakerin_penempatan_id')->constrained('prakerin_penempatans')->onDelete('cascade');
            $table->date('tanggal');
            $table->text('kegiatan_dilakukan');
            $table->text('kompetensi_yang_didapat');
            $table->string('foto_kegiatan')->nullable();
            $table->enum('status_verifikasi', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->text('catatan_pembimbing')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prakerin_jurnals');
    }
};
