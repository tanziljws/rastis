<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $kategoris = Kategori::withCount('posts')
            ->when($request->search, function($query, $search) {
                $query->where('judul', 'like', "%{$search}%");
            })
            ->orderBy('judul')
            ->paginate($request->per_page ?? 15);

        return KategoriResource::collection($kategoris);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): KategoriResource
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255|unique:kategori,judul',
        ]);

        $kategori = Kategori::create($validated);

        return new KategoriResource($kategori);
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori): KategoriResource
    {
        $kategori->loadCount('posts');
        
        return new KategoriResource($kategori);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori): KategoriResource
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255|unique:kategori,judul,' . $kategori->id,
        ]);

        $kategori->update($validated);

        return new KategoriResource($kategori);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori): Response
    {
        $kategori->delete();

        return response()->noContent();
    }
}
