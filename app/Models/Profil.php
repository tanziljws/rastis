<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profil extends Model
{
    protected $fillable = [
        'nama_sekolah',
        'deskripsi',
        'alamat',
        'telepon',
        'email',
        'website',
        'logo',
        'hero_background',
        'visi',
        'misi',
        'sejarah'
    ];
}
