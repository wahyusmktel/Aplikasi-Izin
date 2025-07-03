<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perizinan extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'perizinan'; // Eksplisit mendefinisikan nama tabel

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tanggal_izin',
        'jenis_izin',
        'keterangan',
        'dokumen_pendukung',
        'status',
        'alasan_penolakan',
        'disetujui_oleh',
    ];

    /**
     * Mendefinisikan relasi ke model User (siswa yang mengajukan).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Mendefinisikan relasi ke model User (pihak yang menyetujui/menolak).
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }
}
