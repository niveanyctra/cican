<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function home()
    {
        // Ambil semua pengguna, diurutkan berdasarkan waktu pembuatan (terbaru)
        $users = User::orderBy('created_at', 'desc')->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments']) // Eager loading relasi
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('index', compact('users', 'posts'));
    }
    public function textee()
    {
        // Ambil semua pengguna, diurutkan berdasarkan waktu pembuatan (terbaru)
        $users = User::orderBy('created_at', 'desc')->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments'])
            ->whereDoesntHave('media') // filter yang tidak punya media
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('textee', compact('users', 'posts'));
    }
    public function snapee()
    {
        // Ambil semua pengguna, diurutkan berdasarkan waktu pembuatan (terbaru)
        $users = User::orderBy('created_at', 'desc')->get();

        // Ambil semua postingan, diurutkan berdasarkan waktu pembuatan (terbaru)
        $posts = Post::with(['user', 'media', 'likes', 'comments'])
            ->whereHas('media') // filter yang punya media
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('snapee', compact('users', 'posts'));
    }
}
