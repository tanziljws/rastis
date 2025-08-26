<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    
    protected $fillable = [
        'judul',
        'kategori_id',
        'isi',
        'petugas_id',
        'status'
    ];

    /**
     * Get the kategori that owns the post.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Get the petugas that owns the post.
     */
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id');
    }

    /**
     * Get the galeries for this post.
     */
    public function galeries()
    {
        return $this->hasMany(Galery::class, 'post_id');
    }
}
