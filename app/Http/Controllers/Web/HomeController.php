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
        // Load profil sekolah (integrated with admin profile management)
        $profil = Profil::first();
        
        // If no profil exists, create default
        if (!$profil) {
            $profil = Profil::create([
                'nama_sekolah' => 'SMKN 4 Kota Bogor',
                'deskripsi' => 'SMK Negeri 4 Kota Bogor adalah sekolah menengah kejuruan yang berkomitmen untuk mencetak generasi unggul, berkarakter, dan siap kerja.',
                'alamat' => 'Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137',
                'visi' => 'Menjadi SMK unggul yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.',
                'misi' => '1. Menyelenggarakan pendidikan yang berkualitas dan berwawasan global
2. Mengembangkan potensi siswa secara optimal melalui kegiatan akademik dan non-akademik
3. Menanamkan nilai-nilai karakter dan akhlak mulia
4. Mewujudkan lingkungan sekolah yang nyaman, aman, dan kondusif
5. Mengembangkan kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan',
            ]);
        }
        
        // Load hero background
        $heroBackground = null;
        if ($profil && $profil->hero_background) {
            $path = $profil->hero_background;
            if (!str_starts_with($path, 'storage/')) {
                $path = 'storage/' . $path;
            }
            $heroBackground = asset($path);
        }
        
        // Load photos directly with optimized query
        $photos = \App\Models\Foto::with(['galery.post', 'kategori'])
            ->where(function($q) {
                $q->whereHas('galery', function($subQ) {
                    $subQ->where('status', 'active');
                })->orWhereNull('galery_id');
            })
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        return view('welcome', [
            'profil' => $profil,
            'photos' => $photos,
            'heroBackground' => $heroBackground,
        ]);
    }
}
