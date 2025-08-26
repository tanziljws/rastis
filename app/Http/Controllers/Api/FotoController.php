<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FotoResource;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

/**
 * @group Fotos
 * 
 * API endpoints untuk manajemen foto dengan upload file.
 * 
 * Fotos dapat diakses oleh guest (read-only) dan admin (full CRUD dengan file upload).
 */

class FotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $fotos = Foto::with(['galery'])
            ->when($request->galery_id, function($query, $galeryId) {
                $query->where('galery_id', $galeryId);
            })
            ->when($request->search, function($query, $search) {
                $query->where('judul', 'like', "%{$search}%")
                      ->orWhere('file', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return FotoResource::collection($fotos);
    }

    /**
     * Upload foto baru
     * 
     * Endpoint ini hanya dapat diakses oleh admin (dengan token) untuk upload foto baru.
     * File akan disimpan di storage/app/public/foto/ dan dapat diakses via URL publik.
     * 
     * @authenticated
     * 
     * @bodyParam galery_id integer required ID galery. Example: 1
     * @bodyParam file file required File gambar (jpeg,jpg,png,gif,webp, max 5MB). No-example
     * @bodyParam judul string nullable Judul foto. Example: Judul Foto
     * 
     * @response 201 scenario="Upload berhasil" {
     *   "data": {
     *     "id": 1,
     *     "file": "foto/1703123456_test_image.jpg",
     *     "file_url": "http://localhost:8000/storage/foto/1703123456_test_image.jpg",
     *     "judul": "Judul Foto",
     *     "galery": {
     *       "id": 1,
     *       "position": 1,
     *       "status": "active",
     *       "post": {
     *         "id": 1,
     *         "judul": "Pembukaan Tahun Ajaran Baru 2024/2025",
     *         "isi": "Selamat datang di tahun ajaran baru...",
     *         "status": "published",
     *         "kategori": {
     *           "id": 1,
     *           "judul": "Informasi Terkini"
     *         },
     *         "petugas": {
     *           "id": 1,
     *           "username": "admin"
     *         },
     *         "galeries_count": 3,
     *         "created_at": "2024-01-01T00:00:00.000000Z",
     *         "updated_at": "2024-01-01T00:00:00.000000Z"
     *       },
     *       "fotos_count": 1,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     },
     *     "created_at": "2024-12-21T10:30:00.000000Z",
     *     "updated_at": "2024-12-21T10:30:00.000000Z"
     *   }
     * }
     * 
     * @response 422 scenario="Validasi gagal" {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "file": ["The file must be a file of type: jpeg, jpg, png, gif, webp."],
     *     "galery_id": ["The galery id field is required."]
     *   }
     * }
     */
    public function store(Request $request): FotoResource
    {
        $validated = $request->validate([
            'galery_id' => 'required|exists:galery,id',
            'file' => 'required|file|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max
            'judul' => 'nullable|string|max:255',
        ]);

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('foto', $fileName, 'public');
            
            $validated['file'] = $filePath;
        }

        $foto = Foto::create($validated);

        return new FotoResource($foto->load('galery'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Foto $foto): FotoResource
    {
        $foto->load(['galery']);
        
        return new FotoResource($foto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Foto $foto): FotoResource
    {
        $validated = $request->validate([
            'galery_id' => 'required|exists:galery,id',
            'file' => 'nullable|file|mimes:jpeg,jpg,png,gif,webp|max:5120', // 5MB max
            'judul' => 'nullable|string|max:255',
        ]);

        // Handle file upload if new file is provided
        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($foto->file && Storage::disk('public')->exists($foto->file)) {
                Storage::disk('public')->delete($foto->file);
            }
            
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('foto', $fileName, 'public');
            
            $validated['file'] = $filePath;
        }

        $foto->update($validated);

        return new FotoResource($foto->load('galery'));
    }

    /**
     * Hapus foto dan file fisik
     * 
     * Endpoint ini hanya dapat diakses oleh admin (dengan token) untuk menghapus foto.
     * Operasi ini akan menghapus record dari database dan file fisik dari storage.
     * 
     * @authenticated
     * 
     * @urlParam foto integer required ID foto. Example: 1
     * 
     * @response 204 scenario="Berhasil dihapus" {
     *   "No Content"
     * }
     * 
     * @response 401 scenario="Token tidak valid" {
     *   "message": "Unauthenticated."
     * }
     * 
     * @response 404 scenario="Foto tidak ditemukan" {
     *   "message": "No query results for model [App\\Models\\Foto] 999"
     * }
     */
    public function destroy(Foto $foto): Response
    {
        // Delete physical file if exists
        if ($foto->file && Storage::disk('public')->exists($foto->file)) {
            Storage::disk('public')->delete($foto->file);
        }

        $foto->delete();

        return response()->noContent();
    }
}
