@extends('layouts.app')
@section('content')
    @include('pages.post.edit')
    @include('pages.post.show')
    <div class="row">
        <!-- Kolom Utama (Feed Postingan) -->
        <div class="col-7 ms-5">
            <div class="searchbar">
                <div class="d-flex" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <input type="text" class="form-control" placeholder="Search" class="form-control" autocomplete="off"
                        style="border-radius: 10px;">
                </div>
            </div>
            @foreach ($posts as $post)
                <div class="mb-3 align-items-center all-post">
                    <!-- Avatar dan Username -->
                    <div class="d-flex align-items-center profile">
                        <img src="{{ Storage::url($post->user->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                            style="object-fit: cover; width: 40px; height: 40px;" class="rounded-circle me-2">
                        <a href="{{ route('user.show', $post->user->username) }}"
                            class="text-decoration-none text-dark fw-bold">{{ $post->user->username }}</a>

                        <!-- Tampilkan Collaborator -->
                        @if ($post->collaborators->count() > 0)
                            <span class="mx-1">&</span>
                            <a href="{{ route('user.show', $post->collaborators->first()->username) }}"
                                class="text-decoration-none text-dark fw-bold">{{ $post->collaborators->first()->username }}</a>
                            @if ($post->collaborators->count() > 1)
                                <span class="text-muted">+{{ $post->collaborators->count() - 1 }}</span>
                            @endif
                        @endif

                        <span class="mx-1">•</span>
                        <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>

                        <!-- Menu Titik Tiga untuk Edit/Hapus -->
                        @if ($post->user_id === auth()->id())
                            <div class="dropdown ms-auto">
                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editPostModal{{ $post->id }}">
                                            Edit
                                        </button>
                                    </li>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>

                    <!-- Media (Gambar/Video) -->

                    <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide mt-2">
                            <div class="carousel-inner rounded-xl items-center" style="background: black">
                                <!-- Indikator Carousel -->
                                @if ($post->media->count() > 1)
                                    <div class="carousel-indicators">
                                        @foreach ($post->media as $index => $media)
                                            <button type="button" data-bs-target="#postMediaCarousel-{{ $post->id }}"
                                                data-bs-slide-to="{{ $index }}"
                                                class="{{ $index === 0 ? 'active' : '' }}"
                                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Item Media -->
                                @foreach ($post->media as $index => $media)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div class="d-flex justify-content-center h-100">
                                            @if ($media->type === 'image')
                                                <img src="{{ Storage::url($media->file_url) }}" alt="Post Image"
                                                    class="img-fluid post-media"
                                                    style="max-height: auto; max-width: auto; object-fit: contain;">
                                            @elseif ($media->type === 'video')
                                                <video controls class="w-100 post-media"
                                                    style="max-height: auto; object-fit: contain;">
                                                    <source src="{{ Storage::url($media->file_url) }}" type="video/mp4">
                                                    Browser Anda tidak mendukung elemen video.
                                                </video>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Tombol Navigasi Carousel -->
                            @if ($post->media->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#postMediaCarousel-{{ $post->id }}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            @endif
                        </div>


                    @include('components.like-comments')

                    <!-- Caption -->
                    <div data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">
                        <p class="mt-2">
                            @php
                                $parsed = preg_replace_callback(
                                    '/@([\w]+)/',
                                    function ($matches) {
                                        $username = e($matches[1]);
                                        $url = url("/{$username}");
                                        return "<a href=\"{$url}\" class=\"text-primary\">@{$username}</a>";
                                    },
                                    e($post->caption),
                                );
                            @endphp

                            {!! $parsed !!}
                        </p>
                    </div>

                    <!-- Form Komentar -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                        @csrf
                        <textarea id="" name="body" class="form-control form-control-sm comment-input-snapee" placeholder="Komentar . . ."
                            style="width: 500px" rows="2"></textarea>

                        <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                    </form>

                    <hr>
                </div>
            @endforeach
        </div>

        <!-- Sidebar Pengguna -->
        <div class="col-4 all-user">
            <h3 class="mb-3 userhead">All Users</h3>
            @foreach ($users as $user)
                <div class="mb-3 userprof">
                    <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}"
                                        alt="Avatar" style="object-fit: cover; width: 40px; height: 40px;"
                                        class="rounded-circle me-2">
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
    @stack('modal')
@endsection
