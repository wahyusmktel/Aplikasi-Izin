<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterGuru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nuptk',
        'nama_lengkap',
        'jenis_kelamin',
        'user_id',
    ];

    /**
     * Mendefinisikan relasi ke model User (akun login guru).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
