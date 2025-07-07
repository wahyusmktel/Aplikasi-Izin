<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dispensasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('keterangan');

            // PERBAIKAN: Tambahkan ->nullable() agar bisa bernilai NULL
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();

            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->foreignId('diajukan_oleh_id')->constrained('users');
            $table->foreignId('disetujui_oleh_id')->nullable()->constrained('users');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dispensasis');
    }
};
