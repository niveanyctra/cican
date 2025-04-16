<?php

namespace App\Http\Controllers\Backend\Post;

use App\Models\Post;
use App\Models\User;
use App\Models\Media;
use App\Helpers\TagHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'caption' => 'nullable|string|max:2000', // Caption opsional, maks 2000 karakter
            'media.*' => 'required|array|min:1|max:5', // Minimal 1, maksimal 5 file
            'media.*' => 'file|mimes:jpeg,png,jpg,mp4|max:20480', // Maks 20MB per file
            'collaborators' => 'nullable|array', // Kolaborator opsional
            'location' => 'nullable|string|max:255', // Lokasi opsional
        ]);

        // Simpan data post
        $post = Post::create([
            'user_id' => Auth::user()->id,
            'caption' => $request->caption,
            'location' => $request->location,
        ]);

        // Simpan media (gambar/video)
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $filePath = $file->store('posts', 'public'); // Simpan di storage/app/public/posts
                $type = $file->getMimeType() === 'video/mp4' ? 'video' : 'image';
                $thumbnailPath = null;

                // Jika video, buat thumbnail menggunakan FFmpeg atau layanan lain
                if ($type === 'video') {
                    $thumbnailPath = $this->generateThumbnail($file); // Fungsi generate thumbnail
                }

                Media::create([
                    'post_id' => $post->id,
                    'file_url' => $filePath, // URL akses publik
                    'type' => $type,
                    'order' => $index,
                    'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
                ]);
            }
        }

        // Simpan tag pengguna di caption
        $mentionedUsers = TagHelper::extractMentions($request->caption);
        $post->taggedUsers()->attach($mentionedUsers);

        // Simpan kolaborator (jika ada)
        if ($request->collaborators) {
            $post->collaborators()->attach($request->collaborators);
        }

        // Kirim notifikasi ke pengguna yang ditag
        foreach ($mentionedUsers as $userId) {
            $user = User::find($userId);
            $user->notify(new \App\Notifications\TaggedInPostNotification($post));
        }

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cari postingan
        $post = Post::with(['user', 'likes', 'comments.user'])->findOrFail($id);

        // Ambil semua komentar
        $comments = $post->comments()->orderBy('created_at', 'desc')->get();

        return view('pages.post.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Validasi input
        $request->validate([
            'caption' => 'nullable|string|max:2000', // Caption opsional, maks 2000 karakter
            'media.*' => 'nullable|array|min:0|max:5', // Minimal 0, maksimal 5 file baru
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,mp4|max:20480', // Maks 20MB per file
            'collaborators' => 'nullable|array', // Kolaborator opsional
            'location' => 'nullable|string|max:255', // Lokasi opsional
        ]);

        // Perbarui data post
        $post->update([
            'caption' => $request->caption,
            'location' => $request->location,
        ]);

        // Hapus media lama jika ada request untuk menghapus
        if ($request->has('delete_media')) {
            foreach ($request->delete_media as $mediaId) {
                $media = Media::find($mediaId);
                if ($media && $media->post_id === $post->id) {
                    Storage::delete(str_replace('/storage', '/public', $media->file_url)); // Hapus file lokal
                    $media->delete(); // Hapus dari database
                }
            }
        }

        // Tambahkan media baru jika ada
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $index => $file) {
                $filePath = $file->store('posts', 'public'); // Simpan di storage/app/public/posts
                $type = $file->getMimeType() === 'video/mp4' ? 'video' : 'image';
                $thumbnailPath = null;

                // Jika video, buat thumbnail menggunakan FFmpeg
                if ($type === 'video') {
                    $thumbnailPath = $this->generateThumbnail($file);
                }

                Media::create([
                    'post_id' => $post->id,
                    'file_url' => Storage::url($filePath),
                    'type' => $type,
                    'order' => $index + $post->media()->count(),
                    'thumbnail_url' => $thumbnailPath ? Storage::url($thumbnailPath) : null,
                ]);
            }
        }

        // Perbarui tag pengguna di caption
        $mentionedUsers = TagHelper::extractMentions($request->caption);
        $post->taggedUsers()->sync($mentionedUsers); // Sync untuk mengganti tag lama dengan yang baru

        // Perbarui kolaborator (jika ada)
        if ($request->collaborators) {
            $post->collaborators()->sync($request->collaborators); // Sync untuk mengganti kolaborator lama
        }

        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Hapus semua media terkait
        foreach ($post->media as $media) {
            Storage::delete(str_replace('/storage', '/public', $media->file_url)); // Hapus file lokal
            $media->delete(); // Hapus dari database
        }

        // Hapus relasi tag, kolaborator, komentar, dan like
        $post->taggedUsers()->detach();
        $post->collaborators()->detach();
        $post->comments()->delete();
        $post->likes()->detach();

        // Hapus postingan
        $post->delete();

        return redirect()->back()->with('success', 'Post deleted successfully!');
    }

    private function generateThumbnail($file)
    {
        $tempPath = $file->getPathname();
        $thumbnailName = uniqid() . '.jpg';
        $thumbnailPath = 'thumbnails/' . $thumbnailName;

        // Gunakan FFmpeg untuk membuat thumbnail
        $command = "ffmpeg -i {$tempPath} -ss 00:00:01.000 -vframes 1 storage/app/public/{$thumbnailPath}";
        exec($command);

        return $thumbnailPath;
    }
}
