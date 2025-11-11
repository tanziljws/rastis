<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Informasi extends Model
{
    protected $fillable = [
        'judul',
        'konten',
        'kategori',
        'gambar',
        'status',
        'tanggal_publish'
    ];

    protected $casts = [
        'tanggal_publish' => 'datetime',
    ];
}
