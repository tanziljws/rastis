<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Petugas extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'petugas';
    
    protected $fillable = [
        'username',
        'password'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the posts for this petugas.
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'petugas_id');
    }
}
