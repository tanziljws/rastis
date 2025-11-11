<?php

namespace App\Http\Controllers;

use App\Models\Foto;
use App\Models\Galery;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Foto::with(['galery.post', 'kategori']);

        // Filter by kategori
        if ($request->has('kategori') && $request->kategori) {
            $query->where('kategori_id', $request->kategori);
        }

        // Search by judul
        if ($request->has('search') && $request->search) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // Get all photos (with or without galery)
        // Photos with active galery OR photos without galery (standalone photos)
        $fotos = $query->where(function($q) {
                $q->whereHas('galery', function($subQ) {
                    $subQ->where('status', 'active');
                })->orWhereNull('galery_id'); // Include standalone photos
            })
            ->orderBy('created_at', 'desc')
            ->get(); // Get all, not paginated, because we'll group by judul
        
        // Group photos by judul (album system)
        // If judul is empty, use a unique identifier per photo
        $albums = $fotos->load(['likes', 'comments'])->groupBy(function($foto) {
            $judul = $foto->judul ?? '';
            if (empty($judul)) {
                // If no judul, each photo is its own album
                return 'photo_' . $foto->id;
            }
            return $judul;
        })->map(function($group, $judul) {
            // For album-based like/comment, use first photo as representative
            // All likes/comments are stored on the first photo
            $firstFoto = $group->first();
            
            return [
                'judul' => $judul,
                'fotos' => $group,
                'count' => $group->count(),
                'first_foto' => $firstFoto,
                'kategori' => $firstFoto->kategori,
                'created_at' => $firstFoto->created_at,
                // Like & comment count from representative photo (first photo)
                'total_likes' => $firstFoto->likes->count(),
                'total_comments' => $firstFoto->comments->count(),
            ];
        })->sortByDesc(function($album) {
            return $album['created_at'];
        });

        // Paginate albums (not individual photos)
        $perPage = 12;
        $currentPage = $request->get('page', 1);
        $items = $albums->values();
        $total = $items->count();
        $items = $items->slice(($currentPage - 1) * $perPage, $perPage);
        $albumsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get all categories for filter (with caching)
        $kategoris = \Illuminate\Support\Facades\Cache::remember('kategoris_list', 3600, function () {
            return \App\Models\Kategori::orderBy('judul')->get();
        });

        return view('galeri.index', compact('albumsPaginated', 'kategoris'));
    }

    /**
     * Show album detail (all photos with same judul)
     */
    public function showAlbum(Request $request, $judul)
    {
        // Decode judul from URL
        $judul = urldecode($judul);
        
        // Check if it's a single photo (photo_ID format)
        if (str_starts_with($judul, 'photo_')) {
            $fotoId = str_replace('photo_', '', $judul);
            $fotos = Foto::with(['galery.post', 'kategori', 'likes', 'comments.user'])
                ->where(function($q) {
                    $q->whereHas('galery', function($subQ) {
                        $subQ->where('status', 'active');
                    })->orWhereNull('galery_id');
                })
                ->where('id', $fotoId)
                ->orderBy('created_at', 'asc')
                ->get();
        } else {
            // Get all photos with this judul
            $fotos = Foto::with(['galery.post', 'kategori', 'likes', 'comments.user'])
                ->where(function($q) {
                    $q->whereHas('galery', function($subQ) {
                        $subQ->where('status', 'active');
                    })->orWhereNull('galery_id');
                })
                ->where('judul', $judul)
                ->orderBy('created_at', 'asc')
                ->get();
        }
        
        if ($fotos->isEmpty()) {
            abort(404, 'Album tidak ditemukan');
        }
        
        $firstFoto = $fotos->first();
        // Use actual judul from first photo, or fallback
        $displayJudul = $firstFoto->judul ?? 'Tanpa Judul';
        
        // Get current user for like status
        $currentUserId = auth()->id();
        
        // For album-based like/comment, use first photo as representation
        // All likes/comments are stored on the first photo, representing the entire album
        $albumRepresentativeFoto = $firstFoto;
        $albumLikeCount = $albumRepresentativeFoto->likes()->count();
        $albumCommentCount = $albumRepresentativeFoto->comments()->count();
        $albumLiked = $albumRepresentativeFoto->isLikedBy($currentUserId);
        
        return view('galeri.album', compact(
            'fotos', 
            'judul', 
            'displayJudul', 
            'firstFoto', 
            'currentUserId',
            'albumRepresentativeFoto',
            'albumLikeCount',
            'albumCommentCount',
            'albumLiked'
        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tambah-foto');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kategori_id' => 'required|exists:kategori,id',
                'file' => 'required|file|mimes:jpeg,jpg,png|max:5120', // 5MB max
                'judul' => 'nullable|string|max:255',
                'batch_id' => 'nullable|string|max:255',
            ]);

            // For standalone photos, we don't need a galery_id (it will be nullable)
            $galery = Galery::where('status', 'active')->first();

            // Check if file is present and valid
            if (!$request->hasFile('file')) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File foto tidak ditemukan.'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Gagal upload.');
            }

            $file = $request->file('file');
            
            // Check if file upload was successful
            if (!$file->isValid()) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File foto tidak valid: ' . $file->getError()
                    ], 400);
                }
                return redirect()->back()->with('error', 'Gagal upload.');
            }

            // Process and compress image
            try {
                $imagePaths = ImageService::processImage($file, 'fotos', 1920, 85);
                $path = $imagePaths['original'];
                $thumbnailPath = $imagePaths['thumbnail'];
            } catch (\Exception $e) {
                // Fallback to regular upload if image processing fails
                $path = $file->store('fotos', 'public');
                $thumbnailPath = null;
            }

            // Check if file was stored successfully
            if (!$path) {
                if (request()->expectsJson() || request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menyimpan file foto. Periksa permission folder storage.'
                    ], 500);
                }
                return redirect()->back()->with('error', 'Gagal upload.');
            }

            // Use batch_id from request if provided (for bulk upload grouping)
            $batchId = $validated['batch_id'] ?? null;
            
            // Create foto record with full path for consistency
            $foto = Foto::create([
                'galery_id' => $galery ? $galery->id : null, // Allow null for standalone photos
                'kategori_id' => $validated['kategori_id'],
                'judul' => $validated['judul'] ?? null,
                'file' => $path, // Store full path for consistency
                'batch_id' => $batchId, // Group photos uploaded together
                'thumbnail' => $thumbnailPath, // Store thumbnail path
            ]);

            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil diupload!',
                    'data' => $foto->load('kategori')
                ]);
            }

            return redirect()->route('admin.fotos')->with('success', 'Foto berhasil diupload.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            return redirect()->back()->with('error', 'Gagal upload.')->withInput();
        } catch (\Exception $e) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal upload.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Foto $foto)
    {
        $foto->load(['galery']);
        return view('galeri.show', compact('foto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Foto $foto)
    {
        $fotos = Foto::with(['galery.post'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        $galeries = Galery::with('post')->get();
        return view('admin.fotos', compact('foto', 'fotos', 'galeries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Foto $foto)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'file' => 'nullable|file|mimes:jpeg,jpg,png|max:2048',
            'judul' => 'nullable|string|max:255',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file and thumbnail if exists
            ImageService::deleteImage($foto->file);
            
            // Process and compress new image
            try {
                $imagePaths = ImageService::processImage($request->file('file'), 'fotos', 1920, 85);
                $validated['file'] = $imagePaths['original'];
                $validated['thumbnail'] = $imagePaths['thumbnail'];
            } catch (\Exception $e) {
                // Fallback to regular upload
                $validated['file'] = $request->file('file')->store('fotos', 'public');
                $validated['thumbnail'] = null;
            }
        }

        $foto->update($validated);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil diperbarui!',
                'data' => $foto->load('kategori')
            ]);
        }

        return redirect()->back()->with('success', 'Foto berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Foto $foto)
    {
        // Delete physical file and thumbnail if exists
        if ($foto->file) {
            ImageService::deleteImage($foto->file);
        }

        $foto->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus!'
            ]);
        }

        return redirect()->back()->with('success', 'Foto berhasil dihapus!');
    }
}
