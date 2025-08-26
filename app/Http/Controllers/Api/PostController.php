<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Posts
 * 
 * API endpoints untuk manajemen posts (artikel/berita).
 * 
 * Posts dapat diakses oleh guest (read-only) dan admin (full CRUD).
 */

class PostController extends Controller
{
    /**
     * Dapatkan daftar posts
     * 
     * Endpoint ini dapat diakses oleh guest (tanpa token) untuk melihat daftar posts.
     * Mendukung pagination, search, dan filtering.
     * 
     * @queryParam search string Search dalam judul dan isi post. Example: sekolah
     * @queryParam kategori_id integer Filter berdasarkan kategori. Example: 1
     * @queryParam status string Filter berdasarkan status (published/draft/archived). Example: published
     * @queryParam per_page integer Jumlah item per halaman (default: 15). Example: 10
     * 
     * @response 200 scenario="Berhasil" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "judul": "Pembukaan Tahun Ajaran Baru 2024/2025",
     *       "isi": "Selamat datang di tahun ajaran baru...",
     *       "status": "published",
     *       "kategori": {
     *         "id": 1,
     *         "judul": "Informasi Terkini"
     *       },
     *       "petugas": {
     *         "id": 1,
     *         "username": "admin"
     *       },
     *       "galeries_count": 3,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   ],
     *   "links": {
     *     "first": "http://localhost:8000/api/posts?page=1",
     *     "last": "http://localhost:8000/api/posts?page=1",
     *     "prev": null,
     *     "next": null
     *   },
     *   "meta": {
     *     "current_page": 1,
     *     "from": 1,
     *     "last_page": 1,
     *     "per_page": 15,
     *     "to": 5,
     *     "total": 5
     *   }
     * }
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $posts = Post::with(['kategori', 'petugas'])
            ->withCount('galeries')
            ->when($request->search, function($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('isi', 'like', "%{$search}%");
            })
            ->when($request->kategori_id, function($query, $kategoriId) {
                $query->where('kategori_id', $kategoriId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return PostResource::collection($posts);
    }

    /**
     * Buat post baru
     * 
     * Endpoint ini hanya dapat diakses oleh admin (dengan token) untuk membuat post baru.
     * 
     * @authenticated
     * 
     * @bodyParam judul string required Judul post. Example: Judul Post Baru
     * @bodyParam kategori_id integer nullable ID kategori. Example: 1
     * @bodyParam isi string required Isi post. Example: Ini adalah isi post yang lengkap...
     * @bodyParam status string required Status post (published/draft/archived). Example: published
     * 
     * @response 201 scenario="Berhasil dibuat" {
     *   "data": {
     *     "id": 6,
     *     "judul": "Judul Post Baru",
     *     "isi": "Ini adalah isi post yang lengkap...",
     *     "status": "published",
     *     "kategori": {
     *       "id": 1,
     *       "judul": "Informasi Terkini"
     *     },
     *     "petugas": {
     *       "id": 1,
     *       "username": "admin"
     *     },
     *     "galeries_count": 0,
     *     "created_at": "2024-12-21T10:30:00.000000Z",
     *     "updated_at": "2024-12-21T10:30:00.000000Z"
     *   }
     * }
     * 
     * @response 422 scenario="Validasi gagal" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "judul": ["The judul field is required."],
     *     "isi": ["The isi field is required."],
     *     "status": ["The status field is required."]
     *   }
     * }
     */
    public function store(Request $request): PostResource
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'isi' => 'required|string',
            'status' => 'required|in:published,draft,archived',
        ]);

        $validated['petugas_id'] = $request->user()->id;

        $post = Post::create($validated);

        return new PostResource($post->load(['kategori', 'petugas']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostResource
    {
        $post->load(['kategori', 'petugas'])->loadCount('galeries');
        
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): PostResource
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'nullable|exists:kategori,id',
            'isi' => 'required|string',
            'status' => 'required|in:published,draft,archived',
        ]);

        $post->update($validated);

        return new PostResource($post->load(['kategori', 'petugas']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }
}
