<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Post;
use App\Models\Galery;
use App\Models\Foto;
use App\Models\Profile;
use App\Models\Agenda;
use App\Models\Informasi;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        return view('admin.dashboard', compact('stats'));
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
        $fotos = Foto::with(['galery.post', 'kategori'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        // Cache kategori list for 1 hour
        $kategoris = \Illuminate\Support\Facades\Cache::remember('kategoris_list', 3600, function () {
            return Kategori::orderBy('judul')->get();
        });
        return view('admin.fotos', compact('fotos', 'kategoris'));
    }

    public function profiles()
    {
        // Get or create Profil (single record for school profile)
        $profil = Profil::first();
        if (!$profil) {
            // Create default profil if doesn't exist
            $profil = Profil::create([
                'nama_sekolah' => 'SMKN 4 Kota Bogor',
                'deskripsi' => 'SMK Negeri 4 Kota Bogor adalah sekolah menengah kejuruan yang berkomitmen untuk mencetak generasi unggul, berkarakter, dan siap kerja.',
                'alamat' => 'Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, Kota Bogor, Jawa Barat 16137',
                'telepon' => null,
                'email' => null,
                'website' => null,
                'visi' => 'Menjadi SMK unggul yang menghasilkan lulusan berkarakter, kompeten, dan berdaya saing global.',
                'misi' => '1. Menyelenggarakan pendidikan yang berkualitas dan berwawasan global
2. Mengembangkan potensi siswa secara optimal melalui kegiatan akademik dan non-akademik
3. Menanamkan nilai-nilai karakter dan akhlak mulia
4. Mewujudkan lingkungan sekolah yang nyaman, aman, dan kondusif
5. Mengembangkan kerjasama dengan berbagai pihak untuk meningkatkan kualitas pendidikan',
                'sejarah' => null
            ]);
        }
        return view('admin.profiles', compact('profil'));
    }
    
    public function profilesUpdate(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'alamat' => 'required|string|max:500',
            'telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $profil = Profil::first();
        
        if (!$profil) {
            $profil = new Profil();
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($profil->logo && Storage::disk('public')->exists($profil->logo)) {
                Storage::disk('public')->delete($profil->logo);
            }

            // Store new logo
            $path = $request->file('logo')->store('logos', 'public');
            $profil->logo = $path;
            
            // Ensure file permissions
            if (Storage::disk('public')->exists($path)) {
                chmod(Storage::disk('public')->path($path), 0644);
            }
        }

        // Update other fields
        $profil->nama_sekolah = $validated['nama_sekolah'];
        $profil->deskripsi = $validated['deskripsi'];
        $profil->alamat = $validated['alamat'];
        $profil->telepon = $validated['telepon'] ?? null;
        $profil->email = $validated['email'] ?? null;
        $profil->website = $validated['website'] ?? null;
        $profil->visi = $validated['visi'] ?? null;
        $profil->misi = $validated['misi'] ?? null;
        $profil->sejarah = $validated['sejarah'] ?? null;
        
        $profil->save();

        return redirect()->route('admin.profiles')->with('success', 'Profil sekolah berhasil diperbarui!');
    }

    public function getGaleries()
    {
        $galeries = Galery::with('post')->get();
        return response()->json([
            'data' => $galeries->map(function($galery) {
                return [
                    'id' => $galery->id,
                    'title' => $galery->post ? $galery->post->judul : 'Galery ' . $galery->id
                ];
            })
        ]);
    }

    public function getCategories()
    {
        // Cache categories for API
        $categories = \Illuminate\Support\Facades\Cache::remember('kategoris_api', 3600, function () {
            return Kategori::orderBy('judul')->get();
        });
        return response()->json([
            'data' => $categories->map(function($kategori) {
                return [
                    'id' => $kategori->id,
                    'judul' => $kategori->judul
                ];
            })
        ])->header('Access-Control-Allow-Origin', '*')
          ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
          ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
    }

    // Agenda Management Methods
    public function agenda()
    {
        $agendas = Agenda::orderBy('tanggal_mulai', 'desc')->paginate(10);
        return view('admin.agenda', compact('agendas'));
    }

    public function agendaCreate()
    {
        return view('admin.agenda-create');
    }

    public function agendaStore(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'waktu_mulai' => 'nullable|string',
            'waktu_selesai' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        Agenda::create($validated);
        return redirect()->route('admin.agenda')->with('success', 'Agenda berhasil ditambahkan!');
    }

    public function agendaEdit(Agenda $agenda)
    {
        return view('admin.agenda-edit', compact('agenda'));
    }

    public function agendaUpdate(Request $request, Agenda $agenda)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'nullable|date',
            'waktu_mulai' => 'nullable|string',
            'waktu_selesai' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'status' => 'required|in:aktif,selesai,dibatalkan'
        ]);

        $agenda->update($validated);
        return redirect()->route('admin.agenda')->with('success', 'Agenda berhasil diperbarui!');
    }

    public function agendaDestroy(Agenda $agenda)
    {
        $agenda->delete();
        return redirect()->route('admin.agenda')->with('success', 'Agenda berhasil dihapus!');
    }

    // Informasi Management Methods
    public function informasi()
    {
        $informasis = Informasi::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.informasi', compact('informasis'));
    }

    public function informasiCreate()
    {
        return view('admin.informasi-create');
    }

    public function informasiStore(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
            'tanggal_publish' => 'nullable|date'
        ]);

        if ($validated['status'] === 'published' && !$validated['tanggal_publish']) {
            $validated['tanggal_publish'] = now();
        }

        Informasi::create($validated);
        return redirect()->route('admin.informasi')->with('success', 'Informasi berhasil ditambahkan!');
    }

    public function informasiEdit(Informasi $informasi)
    {
        return view('admin.informasi-edit', compact('informasi'));
    }

    public function informasiUpdate(Request $request, Informasi $informasi)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'kategori' => 'required|string|max:100',
            'status' => 'required|in:draft,published',
            'tanggal_publish' => 'nullable|date'
        ]);

        if ($validated['status'] === 'published' && !$validated['tanggal_publish']) {
            $validated['tanggal_publish'] = now();
        }

        $informasi->update($validated);
        return redirect()->route('admin.informasi')->with('success', 'Informasi berhasil diperbarui!');
    }

    public function informasiDestroy(Informasi $informasi)
    {
        $informasi->delete();
        return redirect()->route('admin.informasi')->with('success', 'Informasi berhasil dihapus!');
    }

    // Hero Background Management Methods
    public function heroBackground()
    {
        $profil = Profil::first();
        return view('admin.hero-background', compact('profil'));
    }

    public function heroBackgroundUpdate(Request $request)
    {
        $validated = $request->validate([
            'hero_background' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ]);

        $profil = Profil::first();
        
        if (!$profil) {
            // Create new profil if doesn't exist with all required fields
            $profil = new Profil();
            $profil->nama_sekolah = 'SMKN 4 Kota Bogor';
            $profil->deskripsi = 'Deskripsi sekolah';
            $profil->alamat = 'Jl. Raya Tajur, Kp. Buntar RT.02/RW.08, Kel. Muara sari, Kec. Bogor Selatan, RT.03/RW.08, Muarasari, Kec. Bogor Sel., Kota Bogor, Jawa Barat 16137';
        }

        // Handle file upload
        if ($request->hasFile('hero_background')) {
            // Delete old file if exists
            if ($profil->hero_background && Storage::disk('public')->exists($profil->hero_background)) {
                Storage::disk('public')->delete($profil->hero_background);
            }

            // Store new file
            $path = $request->file('hero_background')->store('hero-backgrounds', 'public');
            $profil->hero_background = $path;
        }

        $profil->save();

        return redirect()->route('admin.hero-background')->with('success', 'Hero background berhasil diperbarui!');
    }
}
