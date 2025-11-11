<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Foto;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Toggle like/unlike an album (based on judul)
     * Uses first photo in album as representative
     */
    public function toggle(Request $request, $fotoId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
                'redirect' => route('login')
            ], 401);
        }

        $foto = Foto::findOrFail($fotoId);
        
        // Get the album representative (first photo with same judul)
        // If judul is empty, use the photo itself
        $judul = $foto->judul ?? '';
        if (empty($judul)) {
            // Single photo album - use itself
            $albumRepresentative = $foto;
        } else {
            // Get first photo with same judul as representative
            $albumRepresentative = Foto::where('judul', $judul)
                ->orderBy('created_at', 'asc')
                ->first();
        }
        
        if (!$albumRepresentative) {
            $albumRepresentative = $foto;
        }
        
        $userId = Auth::id();
        $representativeFotoId = $albumRepresentative->id;

        // Check like on representative photo
        $like = Like::where('user_id', $userId)
            ->where('foto_id', $representativeFotoId)
            ->first();

        if ($like) {
            // Unlike
            $like->delete();
            $liked = false;
        } else {
            // Like
            Like::create([
                'user_id' => $userId,
                'foto_id' => $representativeFotoId, // Store on representative photo
            ]);
            $liked = true;
        }

        $likeCount = $albumRepresentative->likes()->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $likeCount,
            'message' => $liked ? 'Album disukai' : 'Like dihapus'
        ]);
    }

    /**
     * Get like status for an album (based on judul)
     */
    public function status($fotoId)
    {
        $foto = Foto::findOrFail($fotoId);
        
        // Get the album representative (first photo with same judul)
        $judul = $foto->judul ?? '';
        if (empty($judul)) {
            $albumRepresentative = $foto;
        } else {
            $albumRepresentative = Foto::where('judul', $judul)
                ->orderBy('created_at', 'asc')
                ->first();
        }
        
        if (!$albumRepresentative) {
            $albumRepresentative = $foto;
        }
        
        $userId = Auth::id();

        return response()->json([
            'liked' => $albumRepresentative->isLikedBy($userId),
            'like_count' => $albumRepresentative->likes()->count(),
        ]);
    }
}
