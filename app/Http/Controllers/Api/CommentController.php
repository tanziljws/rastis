<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Foto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Get comments for an album (based on judul)
     * Returns comments from the representative photo (first photo in album)
     */
    public function index($fotoId)
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
        
        $comments = $albumRepresentative->comments()->with('user:id,name')->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function($comment) {
                return [
                    'id' => $comment->id,
                    'content' => $comment->content,
                    'user_name' => $comment->user->name,
                    'user_id' => $comment->user_id,
                    'created_at' => $comment->created_at->format('d M Y, H:i'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                ];
            }),
            'count' => $comments->count()
        ]);
    }

    /**
     * Store a new comment for an album (based on judul)
     * Stores comment on the representative photo (first photo in album)
     */
    public function store(Request $request, $fotoId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
                'redirect' => route('login')
            ], 401);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

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

        // Store comment on representative photo
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'foto_id' => $albumRepresentative->id, // Store on representative photo
            'content' => $validated['content'],
        ]);

        $comment->load('user:id,name');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'user_name' => $comment->user->name,
                'user_id' => $comment->user_id,
                'created_at' => $comment->created_at->format('d M Y, H:i'),
                'created_at_human' => $comment->created_at->diffForHumans(),
            ],
            'message' => 'Komentar berhasil ditambahkan'
        ]);
    }

    /**
     * Delete a comment
     */
    public function destroy($commentId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ], 401);
        }

        $comment = Comment::findOrFail($commentId);

        // Only allow user to delete their own comment
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menghapus komentar ini.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}
