<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galery extends Model
{
    use HasFactory;

    protected $table = 'galery';
    
    protected $fillable = [
        'post_id',
        'position',
        'status'
    ];

    /**
     * Get the post that owns the galery.
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    /**
     * Get the fotos for this galery.
     */
    public function fotos()
    {
        return $this->hasMany(Foto::class, 'galery_id');
    }
}
