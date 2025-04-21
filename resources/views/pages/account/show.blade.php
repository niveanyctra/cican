@extends('layouts.app')

@section('content')
    <div class="container px-40">
        <!-- Header Profil -->
        <div class="d-flex align-items-start mb-4">
            <!-- Avatar -->
            <div class="me-4 profile">
                <img src="{{ Storage::url($user->avatar ?? 'default-avatar.png') }}" alt="Avatar" class="rounded-circle" style="object-fit: cover; width: 70px; height: 70px;">
            </div>

            <!-- Informasi Profil -->
            <div class="flex-grow-1">
                <div class="d-flex gap-4 align-items-center">
                    <h5>{{ $user->username }}</h5>
                    @auth
                        @if ($user->id == Auth::user()->id)
                            <div class="d-flex align-items-center">
                                <a href="{{ route('account.edit') }}" class="btn btn-dark me-2">Edit Profil</a>
                            </div>
                        @endif
                    @endauth
                </div>
                <span class="fw-bold">{{ $user->name }}</span>
                <p>{{ $user->bio ?? '' }}</p>
            </div>
        </div>

        <hr class="my-3">

        <!-- Postingan Pengguna -->
        <div class="row gap-0">
            @foreach ($posts as $post)
                <div class="col-4">
                    <a href="{{ route('posts.show', $post->id) }}">
                        <div
                            style="width: 305px; height: 350px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                            <img src="{{ Storage::url($post->media->first()->file_url ?? 'default-image.jpg') }}"
                                alt="Post Media" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
