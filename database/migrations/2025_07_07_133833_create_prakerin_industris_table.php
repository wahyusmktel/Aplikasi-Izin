<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prakerin_industris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_industri');
            $table->text('alamat');
            $table->string('kota');
            $table->string('telepon')->nullable();
            $table->string('email_pic')->nullable();
            $table->string('nama_pic')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prakerin_industris');
    }
};
