@extends('layouts.app')
@section('content')
<div class="row">
    <!-- Kolom Utama (Feed Postingan) -->
    <div class="col-8 ms-5">
        @foreach ($posts as $post)
            <div class="mb-3">
                <!-- Avatar dan Username -->
                <div class="d-flex align-items-center">
                    <img src="{{ Storage::url($post->user->avatar ?? 'default-avatar.png') }}" alt="Avatar" width="40" height="40" class="rounded-circle me-2">
                    <a href="{{ route('user.show', $post->user->username) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->username }}</a>
                    <span class="mx-1">•</span>
                    <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>

                    <!-- Menu Titik Tiga untuk Edit/Hapus -->
                    @auth
                        @if ($post->user_id === auth()->id())
                            <div class="dropdown ms-auto">
                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a></li>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    @endauth
                </div>

                <!-- Media (Gambar/Video) -->
                <a href="{{ route('posts.show', $post->id) }}">
                    <img src="{{ Storage::url($post->media->first()->file_url ?? 'default-image.jpg') }}" alt="Post Media" width="500px" class="img-fluid mt-2">
                </a>

                <!-- Like dan Komentar -->
                @auth
                    <form action="{{ route('likes.store', $post->id) }}" method="POST" class="mt-3 d-inline">
                        @csrf
                        @php
                            $userLiked = $post->likes->contains(auth()->id());
                        @endphp
                        <button type="submit" style="border: 0; background-color: white;">
                            <i class="{{ $userLiked ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
                        </button>
                    </form>
                    <span>{{ $post->likes->count() }} likes</span>

                    <i class="fa-regular fa-comment fa-xl ms-2"></i>
                    <span>{{ $post->comments->count() }} comments</span>
                @endauth

                <!-- Caption -->
                <p class="mt-2">
                    {{ $post->caption }}
                </p>

                <!-- Form Komentar -->
                @auth
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                        @csrf
                        <input class="form-control form-control-sm" type="text" name="body" placeholder="Komentar . . ." style="width: 500px">
                        <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                    </form>
                @endauth

                <hr>
            </div>
        @endforeach
    </div>

    <!-- Sidebar Pengguna -->
    <div class="col-3">
        <h3>All Users</h3>
        @foreach ($users as $user)
            <div class="mb-3">
                <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($user->avatar ?? 'default-avatar.png') }}" alt="Avatar" width="40" height="40" class="rounded-circle me-2">
                                <div>
                                    <strong>{{ $user->username }}</strong> <br>
                                    <small style="font-size: 13px">{{ $user->name }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
@endsection
