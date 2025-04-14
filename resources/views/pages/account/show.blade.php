@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="d-flex justify-content-between">
            <h5>{{ $user->nama }}</h5>
            @auth
                @if ($user->id == Auth::user()->id)
                    <a href="{{ route('user.edit', $user->id) }}" class="btn btn-dark">Edit Profil</a>
                @endif
            @endauth
        </div>
        <h6>{{ '@' . $user->username }} // {{ $user->email }}</h6>
        <p>{{ $user->alamat }}</p>
        <hr>
        <div class="d-flex justify-content-between mb-4">
            <h6>Album</h6>
            @auth
                @if ($user->id == Auth::user()->id)
                    <a href="{{ route('album.create') }}" class="btn btn-success">Tambah Album</a>
                @endif
            @endauth
        </div>
        @foreach ($albums as $album)
            <div class="d-flex justify-content-between">
                <a href="{{ route('album.show', $album->id) }}" class="text-decoration-none text-dark">
                    <p class="fw-bold">{{ $album->nama }}</p>
                </a>
                <div>
                    <a href="{{ route('album.show', $album->id) }}" class="btn btn-dark">Lihat</a>
                    @auth
                        @if ($user->id == Auth::user()->id)
                            <a href="{{ route('album.edit', $album->id) }}" class="btn btn-secondary">Edit</a>
                        @endif
                    @endauth
                </div>
            </div>
        @endforeach
        <hr>
        <div class="d-flex justify-content-between mb-4">
            <h6>Foto</h6>
        </div>
        <div class="row">
            @foreach ($photos as $photo)
                <div class="col-4 mb-2">
                    <a href="{{ route('photo.show', $photo->id) }}">
                        <img src="{{ Storage::url($photo->path) }}" alt="{{ Storage::url($photo->path) }}" width="300px"
                            height="300px" style="object-fit: cover">
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
