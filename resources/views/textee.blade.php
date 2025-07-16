@extends('layouts.app')
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
