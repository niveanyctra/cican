@extends('layouts.app')
@push('styles')
    <style>
        .bottom-post {
            position: fixed;
            bottom: 0;
            margin-bottom: 20px;
        }
    </style>
@endpush
@section('content')
    @include('pages.post.edit')
    <div class="d-flex">
        <!-- Media (Gambar/Video) -->
        <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide">
            <div class="carousel-inner">
                @foreach ($post->media as $index => $media)
                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                        @if ($media->type === 'image')
                            <!-- Gambar -->
                            <img src="{{ Storage::url($media->file_url ?? asset('default-image.jpg')) }}" alt="Post Media"
                                style="max-width: 550px; max-height: 90vh;">
                        @elseif ($media->type === 'video')
                            <!-- Video -->
                            <video controls style="max-width: 550px; max-height: 90vh;">
                                <source src="{{ Storage::url($media->file_url) }}" type="video/mp4">
                                Browser Anda tidak mendukung elemen video.
                            </video>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Tombol Navigasi Carousel -->
            @if ($post->media->count() > 1)
                <button class="carousel-control-prev" type="button" data-bs-target="#postMediaCarousel-{{ $post->id }}"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#postMediaCarousel-{{ $post->id }}"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            @endif
        </div>

        <!-- Informasi Post -->
        <div class="container" style="margin-left: 50px">
            <div class="d-flex justify-content-between">
                <p>
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
                </p>
                @auth
                    @if ($post->user_id == Auth::user()->id)
                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#editPostModal{{ $post->id }}">
                                Edit
                            </button>
                            <a href="{{ route('posts.destroy', $post->id) }}" class="btn btn-danger">Hapus</a>
                        </div>
                    @endif
                @endauth
            </div>
            <hr class="my-3">
            <p>
                {{ $post->caption }} <br> <br>
            </p>
            <hr class="my-3">
            @foreach ($comments as $comment)
                <p>
                    <a href="{{ route('user.show', $comment->user->username) }}"
                        class="text-decoration-none text-dark fw-bold">
                        {{ $comment->user->username }}
                    </a>

                    @php
                        $parsed = preg_replace_callback(
                            '/@([\w]+)/',
                            function ($matches) {
                                $username = e($matches[1]);
                                $url = url("/profile/{$username}");
                                return "<a href=\"{$url}\" class=\"text-primary\">@{$username}</a>";
                            },
                            e($comment->body),
                        );
                    @endphp

                    {!! $parsed !!}

                    <br>
                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    @auth
                        @if ($comment->user_id == Auth::id())
                            <a href="{{ route('comments.destroy', $comment->id) }}"
                                class="text-decoration-none text-danger ms-2">Hapus</a>
                        @endif
                    @endauth
                </p>
            @endforeach

            <div class="bottom-post">
                <div id="like-section-{{ $post->id }}" class="my-2 ms-3">
                    <button id="like-button-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
                        <!-- Ikon Like -->
                        <i id="like-icon-{{ $post->id }}"
                            class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
                    </button>
                    <!-- Jumlah Like -->
                    <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                    <a href="{{ route('posts.show', $post->id) }}">
                        <i class="fa-regular fa-comment fa-xl ms-2"></i>
                        <span>{{ $post->comments->count() }} comments</span>
                    </a>
                </div>
                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                    @csrf
                    <textarea id="comment-input" class="form-control form-control-sm" name="body" placeholder="Komentar . . ."
                        style="width: 400px; resize: none;" rows="1"></textarea>

                    <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                </form>
            </div>
        </div>
    </div>
@endsection
