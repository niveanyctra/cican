@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Header Profil -->
        <div class="d-flex align-items-start mb-4">
            <!-- Avatar -->
            <div class="me-4">
                <img src="{{ Storage::url($user->avatar ?? 'default-avatar.png') }}" alt="Avatar" width="150"
                    height="150" class="rounded-circle" style="object-fit: cover;">
            </div>

            <!-- Informasi Profil -->
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <h5>{{ $user->username }}</h5>
                    @auth
                        @if ($user->id == Auth::user()->id)
                            <div class="d-flex align-items-center">
                                <a href="{{ route('account.edit') }}" class="btn btn-dark me-2">Edit Profil</a>
                                <a href="#" class="text-dark"><i class="fa-solid fa-gear"></i></a> <!-- Gear Icon -->
                            </div>
                        @endif
                    @endauth
                </div>
                <h6>{{ $user->name }}</h6>
                <p>{{ $user->bio ?? '' }}</p>
            </div>
        </div>

        <hr>

        <!-- Postingan Pengguna -->
        <div class="row">
            @foreach ($posts as $post)
                <div class="col-4 mb-4">
                    <a href="{{ route('posts.show', $post->id) }}">
                        <img src="{{ Storage::url($post->media->first()->file_url ?? 'default-image.jpg') }}"
                            alt="Post Media" width="300px" height="300px" style="object-fit: cover;" class="img-fluid">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
