<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Foto extends Model
{
    use HasFactory;

    protected $table = 'foto';
    
    protected $fillable = [
        'galery_id',
        'file',
        'judul'
    ];

    /**
     * Get the galery that owns the foto.
     */
    public function galery()
    {
        return $this->belongsTo(Galery::class, 'galery_id');
    }
}
