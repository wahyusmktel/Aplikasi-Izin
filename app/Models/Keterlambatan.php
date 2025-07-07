<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'master_siswa_id',
        'dicatat_oleh_security_id',
        'waktu_dicatat_security',
        'alasan_siswa',
        'diverifikasi_oleh_piket_id',
        'waktu_verifikasi_piket',
        'tindak_lanjut_piket',
        'jadwal_pelajaran_id',
        'verifikasi_oleh_guru_kelas_id',
        'waktu_verifikasi_guru_kelas',
        'status',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(fn($model) => $model->uuid = (string) Str::uuid());
    }

    public function siswa()
    {
        return $this->belongsTo(MasterSiswa::class, 'master_siswa_id');
    }
    public function security()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh_security_id');
    }
    public function guruPiket()
    {
        return $this->belongsTo(User::class, 'diverifikasi_oleh_piket_id');
    }
    public function jadwalPelajaran()
    {
        return $this->belongsTo(JadwalPelajaran::class);
    }
    public function guruKelasVerifier()
    {
        return $this->belongsTo(User::class, 'verifikasi_oleh_guru_kelas_id');
    }
}
