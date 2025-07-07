<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispensasi extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_kegiatan',
        'keterangan',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'diajukan_oleh_id',
        'disetujui_oleh_id',
        'alasan_penolakan'
    ];

    public function siswa()
    {
        return $this->belongsToMany(MasterSiswa::class, 'dispensasi_siswa');
    }

    public function diajukanOleh()
    {
        return $this->belongsTo(User::class, 'diajukan_oleh_id');
    }

    public function disetujuiOleh()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh_id');
    }
}
