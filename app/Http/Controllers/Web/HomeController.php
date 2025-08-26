<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Foto;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get school profile
        $profile = Profile::first();
        
        // Get latest photos for gallery
        $photos = Foto::with(['galery.post'])
            ->whereHas('galery', function($query) {
                $query->where('status', 'active');
            })
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();
        
        return view('home', compact('profile', 'photos'));
    }
}
