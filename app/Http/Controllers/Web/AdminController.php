<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Post;
use App\Models\Galery;
use App\Models\Foto;
use App\Models\Profile;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_kategori' => Kategori::count(),
            'total_posts' => Post::count(),
            'total_galeries' => Galery::count(),
            'total_fotos' => Foto::count(),
            'total_profiles' => Profile::count(),
        ];

        $recentPosts = Post::with(['kategori', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentPhotos = Foto::with(['galery.post'])
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'recentPhotos'));
    }

    public function kategori()
    {
        $kategoris = Kategori::withCount('posts')->paginate(10);
        return view('admin.kategori', compact('kategoris'));
    }

    public function posts()
    {
        $posts = Post::with(['kategori', 'petugas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.posts', compact('posts'));
    }

    public function galeries()
    {
        $galeries = Galery::with(['post', 'fotos'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.galeries', compact('galeries'));
    }

    public function fotos()
    {
        $fotos = Foto::with(['galery.post'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('admin.fotos', compact('fotos'));
    }

    public function profiles()
    {
        $profiles = Profile::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.profiles', compact('profiles'));
    }
}
