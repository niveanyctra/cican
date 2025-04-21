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
    <div class="d-flex">
        <img src="{{ Storage::url($post->media->first()->file_url ?? 'default-image.jpg') }}" alt="Post Media" width="600px">
        <div class="container" style="margin-left: 50px; max-height: 50px; max-width: 50px;">
            <div class="d-flex justify-content-between">
                <p>
                    <a href="{{ route('user.show', $post->user->username) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $post->user->username }}</a>
                    - {{ $post->created_at->diffForHumans() }}
                </p>
                @auth
                    @if ($post->user_id == Auth::user()->id)
                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#editPostModal">
                                Edit
                            </button>
                            <a href="{{ route('posts.destroy', $post->id) }}" class="btn btn-danger">Hapus</a>
                        </div>
                    @endif
                @endauth
            </div>
            <hr>
            <p>
                {{ $post->caption }} <br> <br>
            </p>
            <hr>
            @foreach ($comments as $comment)
                <p>
                    <a href="{{ route('user.show', $comment->user->username) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $comment->user->username }}</a>
                    {{ $comment->body }}
                    <br>
                    {{ $comment->created_at->diffForHumans() }}
                    @auth
                        @if ($comment->user_id == Auth::user()->id)
                            <a href="{{ route('comments.destroy', $comment->id) }}"
                                class="text-decoration-none text-danger">Hapus</a>
                        @endif
                    @endauth
                </p>
            @endforeach
            <div class="bottom-post">
                <div id="like-section-{{ $post->id }}">
                    <button id="like-button-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
                        <!-- Ikon Like -->
                        <i id="like-icon-{{ $post->id }}"
                            class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
                    </button>
                    <!-- Jumlah Like -->
                    <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                    <i class="fa-regular fa-comment fa-xl ms-2"></i>
                    <span>{{ $post->comments->count() }} comments</span>
                </div>
                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                    @csrf
                    <input class="form-control form-control-sm" type="text" name="body" placeholder="Komentar . . ."
                        style="width: 300px">
                    <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                </form>
            </div>
        </div>
    </div>
@endsection
