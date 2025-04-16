<?php

namespace App\Http\Controllers\Backend\Post;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Pastikan pengguna belum menyukai postingan
        if (!$post->likes()->where('user_id', Auth::id())->exists()) {
            $post->likes()->attach(Auth::id());

            // Kirim notifikasi ke pemilik postingan jika yang menyukai bukan pemilik
            if ($post->user_id !== Auth::id()) {
                $post->user->notify(new \App\Notifications\PostLikedNotification($post));
            }

            return response()->json([
                'message' => 'You liked the post.',
                'like_count' => $post->likes()->count(),
            ]);
        }

        return response()->json([
            'message' => 'You already liked this post.',
            'like_count' => $post->likes()->count(),
        ], 400); // Status 400 untuk indikasi kesalahan
    }

    public function destroy(Post $post)
    {
        // Hapus like jika pengguna sudah menyukai postingan
        if ($post->likes()->where('user_id', Auth::id())->exists()) {
            $post->likes()->detach(Auth::id());

            return response()->json([
                'message' => 'You unliked the post.',
                'like_count' => $post->likes()->count(),
            ]);
        }

        return response()->json([
            'message' => 'You have not liked this post.',
            'like_count' => $post->likes()->count(),
        ], 400); // Status 400 untuk indikasi kesalahan
    }

    public function like(Request $request, $id)
    {
        // Cari postingan
        $post = Post::findOrFail($id);
        $user = Auth::user();

        // Cek apakah pengguna sudah menyukai postingan
        $isLiked = $post->likes()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            // Jika sudah menyukai, hapus like dari tabel pivot
            $post->likes()->detach($user->id);
        } else {
            // Jika belum menyukai, tambahkan like ke tabel pivot
            $post->likes()->attach($user->id);
        }

        // Hitung jumlah like terbaru
        $likeCount = $post->likes()->count();

        // Kirim respons JSON untuk AJAX
        return response()->json([
            'message' => $isLiked ? 'Post unliked successfully.' : 'Post liked successfully.',
            'likeCount' => $likeCount,
        ]);
    }
}
