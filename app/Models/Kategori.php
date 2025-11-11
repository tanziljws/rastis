<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when kategori is created, updated, or deleted
        static::saved(function () {
            Cache::forget('kategoris_list');
            Cache::forget('kategoris_api');
        });

        static::deleted(function () {
            Cache::forget('kategoris_list');
            Cache::forget('kategoris_api');
        });
    }
}
