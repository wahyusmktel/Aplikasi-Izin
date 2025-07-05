<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jam_pelajarans';

    protected $fillable = [
        'jam_ke',
        'jam_mulai',
        'jam_selesai',
        'keterangan',
    ];
}
