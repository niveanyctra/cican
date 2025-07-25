<?php

namespace App\Http\Controllers\Backend;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountController extends Controller
{
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Minimal 8 karakter dengan konfirmasi
        ]);

        // Simpan data pengguna ke database
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'username' => $request->username,
        ]);

        // Redirect ke halaman login atau dashboard
        return redirect('login');
    }

    public function show($username)
    {
        // Cari pengguna berdasarkan username
        $user = User::where('username', $username)->firstOrFail();

        // Ambil ID postingan milik pengguna dan postingan kolaborasi
        $postIds = DB::table('posts')
            ->where('user_id', $user->id) // Postingan milik pengguna
            ->orWhereExists(function ($query) use ($user) {
                $query->select(DB::raw(1))
                    ->from('collaborations')
                    ->whereColumn('collaborations.post_id', 'posts.id')
                    ->where('collaborations.user_id', $user->id); // Postingan kolaborasi
            })
            ->pluck('id');

        // Ambil postingan berdasarkan ID yang ditemukan
        $posts = Post::with(['media', 'likes', 'comments'])
            ->whereIn('id', $postIds)
            ->orderBy('created_at', 'desc')
            ->get();

        // Kirim data ke view
        return view('pages.account.show', compact('user', 'posts'));
    }

    /**
     * Menampilkan formulir edit profil.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        // Cek apakah pengguna yang sedang login adalah pemilik profil
        $user = Auth::user();

        // Kirim data pengguna ke view
        return view('pages.account.edit', compact('user'));
    }

    /**
     * Memperbarui data profil pengguna.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $id = Auth::id();
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maks 2MB
        ]);

        $user = User::findOrFail($id);

        // Update avatar jika ada file baru
        if ($request->hasFile('avatar')) {
            // Hapus avatar lama jika ada
            if ($user->avatar) {
                Storage::disk('public')->delete('avatars/' . basename($user->avatar));
            }

            // Simpan avatar baru
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Update data lainnya
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->save();

        // Redirect ke halaman profil dengan pesan sukses
        return redirect()->route('user.show', $user->username)->with('success', 'Profil berhasil diperbarui!');
    }

    public function destroy()
    {
        $id = Auth::id();
        $user = User::findOrFail($id);

        // Hapus avatar jika ada
        if ($user->avatar) {
            Storage::disk('public')->delete('avatars/' . basename($user->avatar));
        }

        // Hapus akun pengguna
        $user->delete();

        // Logout pengguna setelah akun dihapus
        Auth::logout();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus.');
    }
}
