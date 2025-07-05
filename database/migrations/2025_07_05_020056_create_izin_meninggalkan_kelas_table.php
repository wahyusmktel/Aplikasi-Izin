<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('izin_meninggalkan_kelas', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->constrained()->comment('Siswa yang mengajukan');
            $table->foreignId('rombel_id')->constrained()->comment('Rombel siswa saat itu');
            $table->string('tujuan');
            $table->text('keterangan')->nullable();

            $table->timestamp('estimasi_kembali')->nullable();
            $table->timestamp('waktu_keluar_sebenarnya')->nullable();
            $table->timestamp('waktu_kembali_sebenarnya')->nullable();

            $table->enum('status', [
                'diajukan',
                'disetujui_guru_kelas',
                'disetujui_guru_piket',
                'diverifikasi_security',
                'selesai', // Sudah kembali ke kelas
                'ditolak',
                'terlambat'
            ])->default('diajukan');

            // Kolom untuk mencatat persetujuan
            $table->foreignId('guru_kelas_approval_id')->nullable()->constrained('users')->comment('Guru Kelas yang menyetujui');
            $table->timestamp('guru_kelas_approved_at')->nullable();

            $table->foreignId('guru_piket_approval_id')->nullable()->constrained('users')->comment('Guru Piket yang menyetujui');
            $table->timestamp('guru_piket_approved_at')->nullable();

            $table->foreignId('security_verification_id')->nullable()->constrained('users')->comment('Security yang memverifikasi');
            $table->timestamp('security_verified_at')->nullable();

            $table->text('alasan_penolakan')->nullable();
            $table->foreignId('ditolak_oleh')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('izin_meninggalkan_kelas');
    }
};
