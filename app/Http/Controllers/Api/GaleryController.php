<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GaleryResource;
use App\Models\Galery;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class GaleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $galeries = Galery::with(['post'])
            ->withCount('fotos')
            ->when($request->post_id, function($query, $postId) {
                $query->where('post_id', $postId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('position')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 15);

        return GaleryResource::collection($galeries);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): GaleryResource
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $galery = Galery::create($validated);

        return new GaleryResource($galery->load('post'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Galery $galery): GaleryResource
    {
        $galery->load(['post'])->loadCount('fotos');
        
        return new GaleryResource($galery);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Galery $galery): GaleryResource
    {
        $validated = $request->validate([
            'post_id' => 'required|exists:posts,id',
            'position' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $galery->update($validated);

        return new GaleryResource($galery->load('post'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Galery $galery): Response
    {
        $galery->delete();

        return response()->noContent();
    }
}
