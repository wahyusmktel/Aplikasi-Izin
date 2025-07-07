<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrakerinIndustri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_industri',
        'alamat',
        'kota',
        'telepon',
        'email_pic',
        'nama_pic',
    ];
}
