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
        Schema::create('perizinan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siswa yang mengajukan
            $table->date('tanggal_izin');
            $table->enum('jenis_izin', ['sakit', 'izin', 'dispen']); // Jenis izin utama
            $table->text('keterangan');
            $table->string('dokumen_pendukung')->nullable(); // Path file surat dokter, dll.
            $table->enum('status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users'); // ID Wali Kelas yg menyetujui/menolak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perizinans');
    }
};
