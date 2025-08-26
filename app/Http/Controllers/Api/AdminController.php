<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Post;
use App\Models\Galery;
use App\Models\Foto;
use App\Models\Profile;
use Illuminate\Http\Request;

/**
 * @group Admin Dashboard
 * 
 * API endpoints untuk dashboard admin dengan statistik dan data ringkasan.
 * 
 * Semua endpoint ini memerlukan autentikasi admin.
 */

class AdminController extends Controller
{
    /**
     * Dashboard statistik admin
     * 
     * Endpoint ini menampilkan statistik ringkasan untuk dashboard admin.
     * 
     * @authenticated
     * 
     * @response 200 scenario="Berhasil" {
     *   "message": "Admin dashboard accessed successfully",
     *   "stats": {
     *     "total_kategori": 3,
     *     "total_posts": 5,
     *     "total_galeries": 12,
     *     "total_fotos": 48,
     *     "total_profiles": 3
     *   }
     * }
     * 
     * @response 401 scenario="Token tidak valid" {
     *   "message": "Unauthenticated."
     * }
     */
    public function dashboard()
    {
        $stats = [
            'total_kategori' => Kategori::count(),
            'total_posts' => Post::count(),
            'total_galeries' => Galery::count(),
            'total_fotos' => Foto::count(),
            'total_profiles' => Profile::count(),
        ];

        return response()->json([
            'message' => 'Admin dashboard accessed successfully',
            'stats' => $stats
        ]);
    }

    /**
     * Get all categories
     */
    public function categories()
    {
        $categories = Kategori::all();
        
        return response()->json([
            'categories' => $categories
        ]);
    }

    /**
     * Get all posts with relationships
     */
    public function posts()
    {
        $posts = Post::with(['kategori', 'petugas'])->get();
        
        return response()->json([
            'posts' => $posts
        ]);
    }
}
