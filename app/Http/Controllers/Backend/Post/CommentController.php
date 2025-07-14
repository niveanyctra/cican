<?php

namespace App\Http\Controllers\Backend\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Helpers\TagHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        // Validasi input
        $request->validate([
            'body' => 'required|string|max:2000', // Isi komentar wajib, maks 2000 karakter
            'parent_id' => 'nullable|exists:comments,id', // Untuk reply komentar
        ]);

        // Simpan komentar baru
        $comment = Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'body' => $request->body,
            'parent_id' => $request->parent_id, // Null jika bukan reply
        ]);

        // Simpan tag pengguna di komentar
        // $mentionedUsers = TagHelper::extractMentions($request->body);
        // $comment->taggedUsers()->attach($mentionedUsers);

        // // Kirim notifikasi ke pemilik postingan jika komentator bukan pemilik
        // if ($post->user_id !== Auth::id()) {
        //     $post->user->notify(new \App\Notifications\CommentNotification($comment));
        // }

        // // Kirim notifikasi ke pengguna yang ditag
        // foreach ($mentionedUsers as $userId) {
        //     $user = User::find($userId);
        //     $user->notify(new \App\Notifications\TaggedInCommentNotification($comment));
        // }

        return back();
        // return response()->json([
        //     'message' => 'Comment added successfully.',
        //     'comment' => $comment->load('user'), // Muat data user untuk frontend
        // ]);
    }
    public function destroy(Comment $comment)
    {
        // Pastikan pengguna yang menghapus adalah pemilik komentar
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Hapus relasi tag sebelum menghapus komentar
        $comment->taggedUsers()->detach();

        // Hapus komentar
        $comment->delete();

        return back();
        // return response()->json([
        //     'message' => 'Comment deleted successfully.',
        // ]);
    }
    public function mention(Request $request)
    {
        $query = $request->query('query');
        $currentUserId = Auth::id();

        $users = User::where('username', 'like', "%{$query}%")
            ->where('id', '!=', $currentUserId)
            ->limit(5)
            ->get(['username']);

        return response()->json($users);
    }

}
