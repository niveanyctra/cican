@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-8 ms-5">
            @foreach ($photos as $photo)
                <div class="mb-3">
                    <a href="{{ route('user.show', $photo->user->username) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $photo->user->username }}</a>
                    - {{ $photo->created_at->diffForHumans() }}
                </div>
                <a href="{{ route('photo.show', $photo->id) }}">
                    <img src="{{ Storage::url($photo->path) }}" alt="" width="500px">
                </a>
                @auth
                    <form action="{{ route('like', $photo->id) }}" method="post" class="mt-3">
                        @csrf
                        @php
                            $userLiked = $photo->like->contains('user_id', Auth::user()->id);
                        @endphp
                        <button type="submit" style="border: 0;background-color:white;">
                            <i class="{{ $userLiked ? 'fa-solid fa-heart fa-xl' : 'fa-regular fa-heart fa-xl' }}"></i>
                        </button>
                        {{ $photo->like->count() }} likes
                        <i class="fa-regular fa-comment fa-xl ms-2"></i>
                        {{ $photo->comment->count() }} comments
                    </form>
                @endauth
                <p class="mt-2">
                    <span class="fw-bold">{{ $photo->judul }}</span> <br>
                    {{ $photo->deskripsi }}
                </p>
                @auth
                    <form action="{{ route('comment', $photo->id) }}" method="post" class="d-flex">
                        @csrf
                        <input class="form-control form-control-sm" type="text" name="isi" placeholder="Komentar . . ."
                            style="width:500px">
                        <input type="submit" value="Kirim">
                    </form>
                @endauth
                <hr>
            @endforeach
        </div>
        <div class="col-3">
            <h3>All User</h3>
            @foreach ($users as $user)
                <div class="mb-3">
                    <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                        <div class="card">
                            <div class="card-body">
                                {{ $user->username }} <br>
                                <small style="font-size: 13px">{{ $user->nama }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
@endsection
