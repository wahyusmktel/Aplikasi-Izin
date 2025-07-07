<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrakerinJurnal extends Model
{
    use HasFactory;

    protected $fillable = [
        'prakerin_penempatan_id',
        'tanggal',
        'kegiatan_dilakukan',
        'kompetensi_yang_didapat',
        'foto_kegiatan',
        'status_verifikasi',
        'catatan_pembimbing',
    ];

    public function penempatan()
    {
        return $this->belongsTo(PrakerinPenempatan::class, 'prakerin_penempatan_id');
    }
}
