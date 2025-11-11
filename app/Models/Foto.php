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
        'kategori_id',
        'file',
        'judul',
        'batch_id',
        'thumbnail'
    ];

    /**
     * Get the galery that owns the foto.
     */
    public function galery()
    {
        return $this->belongsTo(Galery::class, 'galery_id');
    }

    /**
     * Get the kategori that owns the foto.
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Get the likes for the foto.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the foto.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if a user has liked this foto.
     */
    public function isLikedBy($userId)
    {
        if (!$userId) return false;
        return $this->likes()->where('user_id', $userId)->exists();
    }

    /**
     * Get the file URL attribute (optimized - no file_exists checks)
     */
    public function getFileUrlAttribute()
    {
        if (str_contains($this->file, '/')) {
            // Full path stored (e.g., "fotos/filename.jpg")
            return asset('storage/' . $this->file);
        } else {
            // Just filename stored, assume fotos folder
            return asset('storage/fotos/' . $this->file);
        }
    }

    /**
     * Get the thumbnail URL attribute
     */
    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        
        // Fallback to original if no thumbnail
        return $this->file_url;
    }
}
