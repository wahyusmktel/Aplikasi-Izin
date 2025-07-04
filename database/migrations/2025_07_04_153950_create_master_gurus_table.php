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
            Schema::create('master_gurus', function (Blueprint $table) {
                $table->id();
                $table->string('nuptk')->unique()->nullable();
                $table->string('nama_lengkap');
                $table->enum('jenis_kelamin', ['L', 'P']);
                // Relasi ke akun login, bisa null jika akun belum dibuat
                $table->foreignId('user_id')->nullable()->unique()->constrained()->onDelete('set null');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('master_gurus');
        }
    };
