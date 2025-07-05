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
            Schema::table('izin_meninggalkan_kelas', function (Blueprint $table) {
                // Kolom ini bisa null jika izin diajukan saat jam istirahat
                $table->foreignId('jadwal_pelajaran_id')->nullable()->after('rombel_id')->constrained()->onDelete('set null');
            });
        }

        public function down(): void
        {
            Schema::table('izin_meninggalkan_kelas', function (Blueprint $table) {
                $table->dropForeign(['jadwal_pelajaran_id']);
                $table->dropColumn('jadwal_pelajaran_id');
            });
        }
};
