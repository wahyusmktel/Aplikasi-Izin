    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('keterlambatans', function (Blueprint $table) {
                $table->id();
                $table->uuid('uuid')->unique();
                $table->foreignId('master_siswa_id')->constrained('master_siswa');

                // Tahap 1: Dicatat oleh Security
                $table->foreignId('dicatat_oleh_security_id')->constrained('users');
                $table->timestamp('waktu_dicatat_security');
                $table->text('alasan_siswa');

                // Tahap 2: Diverifikasi oleh Guru Piket
                $table->foreignId('diverifikasi_oleh_piket_id')->nullable()->constrained('users');
                $table->timestamp('waktu_verifikasi_piket')->nullable();
                $table->text('tindak_lanjut_piket')->nullable();
                $table->foreignId('jadwal_pelajaran_id')->nullable()->constrained();

                // Status Proses
                $table->enum('status', ['dicatat_security', 'diverifikasi_piket'])->default('dicatat_security');

                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('keterlambatans');
        }
    };
