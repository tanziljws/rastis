<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Foto;
use App\Models\Profil;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Load data directly from database to prevent API call issues and flickering
        $profile = Profile::first();
        
        // Load profil for hero background
        $profil = Profil::first();
        $heroBackground = null;
        if ($profil && $profil->hero_background) {
            // Ensure the path is correct for public access
            $path = $profil->hero_background;
            // If path doesn't start with storage/, add it
            if (!str_starts_with($path, 'storage/')) {
                $path = 'storage/' . $path;
            }
            $heroBackground = asset($path);
        }
        
        // Load photos directly with optimized query (no file_exists checks - use accessor)
        $photos = \App\Models\Foto::with(['galery.post', 'kategori'])
            ->where(function($q) {
                $q->whereHas('galery', function($subQ) {
                    $subQ->where('status', 'active');
                })->orWhereNull('galery_id'); // Include standalone photos
            })
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('welcome', [
            'profile' => $profile,
            'photos' => $photos,
            'heroBackground' => $heroBackground,
        ]);
    }
}
