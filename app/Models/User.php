<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi untuk siswa: satu siswa hanya punya satu wali kelas
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    // Relasi untuk wali kelas: satu wali kelas punya banyak siswa
    public function siswa()
    {
        return $this->hasMany(User::class, 'wali_kelas_id');
    }

    // Relasi dari User ke data induk siswanya
    public function masterSiswa() {
        return $this->hasOne(MasterSiswa::class);
    }
    // Relasi dari User (Wali Kelas) ke rombel yang diampunya
    public function rombels() {
        return $this->hasMany(Rombel::class, 'wali_kelas_id');
    }

    /**
     * Mendefinisikan relasi dari User ke Perizinan.
     * Satu user (siswa) bisa memiliki banyak perizinan.
     */
    public function perizinan()
    {
        return $this->hasMany(Perizinan::class);
    }
}
