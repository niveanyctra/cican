@extends('layouts.app')
@push('styles')
    <style>
        .carousel-item {
            height: 80vh;
            /* Tetapkan tinggi container */
            min-height: 500px;
            /* Tinggi minimum untuk mobile */
        }

        .carousel-inner .d-flex {
            height: 100%;
        }
    </style>
@endpush
@section('content')
    @include('pages.post.edit')
    @include('pages.post.show')
    <div class="row">
        <!-- Kolom Utama (Feed Postingan) -->
        <div class="col-7 ms-5">
            <!-- Search bar -->
            <div class="rounded-circle w-100 search">
                <div class="d-flex" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <input type="text" class="form-control" placeholder="Search" class="form-control" autocomplete="off"
                        style="border-radius: 20px; font-size: 38px;">
                </div>
            </div>
            @foreach ($posts as $post)
                <div class="mb-3 align-items-center all-post">
                    <!-- Avatar dan Username -->
                    <div class="d-flex align-items-center profile">
                        <img src="{{ Storage::url($post->user->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                            style="object-fit: cover; width: 42px; height: 42px;" class="rounded-circle me-2">
                        <a href="{{ route('user.show', $post->user->username) }}" class="text-decoration-none"
                            style="font-size: 38px; font-weight: 400">{{ $post->user->username }}</a>

                        <!-- Tampilkan Collaborator -->
                        @if ($post->collaborators->count() > 0)
                            <span class="mx-1">&</span>
                            <a href="{{ route('user.show', $post->collaborators->first()->username) }}"
                                class="text-decoration-none"
                                style="font-size: 38px; font-weight: 400">{{ $post->collaborators->first()->username }}</a>
                            @if ($post->collaborators->count() > 1)
                                <span class="text-muted">+{{ $post->collaborators->count() - 1 }}</span>
                            @endif
                        @endif

                        <span class="mx-1">•</span>
                        <span class="text-muted" style="font-size: 32px">{{ $post->created_at->diffForHumans() }}</span>

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

                    @if ($post->media->isNotEmpty())
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
                            <p class="caption-text" style="font-size: 32px">
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
                    @else
                        <!-- Caption -->
                        <div data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">
                            <p class="caption-text ps-5" style="font-size: 32px">
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
                        <div class="ps-5">
                            @include('components.like-comments')
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sidebar Pengguna -->
        <div class="col-4 all-user">
            <p class="userhead ms-3">Friends</p>
            <hr>
            <div class="mx-4">
                @foreach ($users as $user)
                    <div class="my-4">
                        <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}" alt="Avatar"
                                    style="object-fit: cover; width: 42px; height: 42px;" class="rounded-circle me-2">
                                <div style="line-height: 15px">
                                    <p style="font-size: 36px">{{ $user->name }}</p> <br>
                                    <p style="font-size: 32px; opacity: 50%;">{{ '@' . $user->username }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @stack('modal')
@endsection
