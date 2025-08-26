<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    
    protected $fillable = [
        'judul'
    ];

    /**
     * Get the posts for this kategori.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'kategori_id');
    }
}
