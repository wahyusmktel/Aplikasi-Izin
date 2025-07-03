<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSiswa extends Model
{
    protected $table = "master_siswa";

    // Jangan lupa tambahkan $fillable
    protected $fillable = ['nis', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'user_id'];

    // Relasi dari MasterSiswa ke akun loginnya
    public function user() {
        return $this->belongsTo(User::class);
    }
    // Relasi dari MasterSiswa ke banyak rombel (many-to-many)
    public function rombels() {
        return $this->belongsToMany(Rombel::class, 'rombel_siswa');
    }
}
