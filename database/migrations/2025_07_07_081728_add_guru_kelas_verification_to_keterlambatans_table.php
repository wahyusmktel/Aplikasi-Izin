<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            // Kolom untuk verifikasi oleh Guru Kelas
            $table->foreignId('verifikasi_oleh_guru_kelas_id')->nullable()->after('jadwal_pelajaran_id')->constrained('users');
            $table->timestamp('waktu_verifikasi_guru_kelas')->nullable()->after('verifikasi_oleh_guru_kelas_id');
        });
    }

    public function down(): void
    {
        Schema::table('keterlambatans', function (Blueprint $table) {
            $table->dropForeign(['verifikasi_oleh_guru_kelas_id']);
            $table->dropColumn(['verifikasi_oleh_guru_kelas_id', 'waktu_verifikasi_guru_kelas']);
        });
    }
};
