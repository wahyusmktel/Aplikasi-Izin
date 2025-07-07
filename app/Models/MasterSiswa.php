<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSiswa extends Model
{
    protected $table = "master_siswa";

    // Jangan lupa tambahkan $fillable
    protected $fillable = ['nis', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'user_id'];

    // Relasi dari MasterSiswa ke akun loginnya
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relasi dari MasterSiswa ke banyak rombel (many-to-many)
    public function rombels()
    {
        return $this->belongsToMany(Rombel::class, 'rombel_siswa');
    }

    /**
     * Mendefinisikan relasi "has many through" ke Perizinan melalui User.
     * Ini adalah "jalan pintas" dari MasterSiswa ke Perizinan.
     */
    public function perizinan()
    {
        return $this->hasManyThrough(
            Perizinan::class, // Model tujuan akhir
            User::class,      // Model perantara
            'id',             // Foreign key di tabel users (yang terhubung ke master_siswa)
            'user_id',        // Foreign key di tabel perizinan (yang terhubung ke users)
            'user_id',        // Local key di tabel master_siswa
            'id'              // Local key di tabel users
        );
    }

    public function dispensasi()
    {
        return $this->belongsToMany(Dispensasi::class, 'dispensasi_siswa');
    }
}
