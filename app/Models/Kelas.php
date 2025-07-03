<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    // Jangan lupa tambahkan $fillable
    protected $fillable = ['nama_kelas', 'jurusan'];

    // Relasi dari Kelas ke banyak rombel
    public function rombels() {
        return $this->hasMany(Rombel::class);
    }
}
