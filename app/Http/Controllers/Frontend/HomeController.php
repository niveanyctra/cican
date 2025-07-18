<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {
        $currentUserId = Auth::user()->id;

        $users = User::with('followers', 'followings')
            ->whereHas('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $followBacks = User::with('followers', 'followings')
            ->whereDoesntHave('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments']) // Eager loading relasi
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('index', compact('users', 'posts', 'followBacks'));
    }
    public function textee()
    {
        $currentUserId = Auth::user()->id;

        $users = User::with('followers', 'followings')
            ->whereHas('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $followBacks = User::with('followers', 'followings')
            ->whereDoesntHave('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments'])
            ->whereDoesntHave('media') // filter yang tidak punya media
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('textee', compact('users', 'posts', 'followBacks'));
    }
    public function snapee()
    {
        $currentUserId = Auth::user()->id;

        $users = User::with('followers', 'followings')
            ->whereHas('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $followBacks = User::with('followers', 'followings')
            ->whereDoesntHave('followers', function ($query) use ($currentUserId) {
                $query->where('follower_id', $currentUserId);
            })
            ->whereHas('followings', function ($query) use ($currentUserId) {
                $query->where('following_id', $currentUserId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments'])
            ->whereHas('media') // filter yang punya media
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('snapee', compact('users', 'posts', 'followBacks'));
    }
}
