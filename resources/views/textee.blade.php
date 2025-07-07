@extends('layouts.app')
@section('content')
    @include('pages.post.edit')
    <div class="row">
        <!-- Kolom Utama (Feed Postingan) -->
        <div class="col-8 ms-5">
            <div class="mb-3 px-32 w-100">
                <div class="d-flex" data-bs-toggle="modal" data-bs-target="#searchModal">
                    <input type="text" class="form-control" placeholder="Search" class="form-control" autocomplete="off"
                        style="border-radius: 10px">
                </div>
            </div>
            @foreach ($posts as $post)
                <div class="mb-3 px-32">
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

                    <!-- Caption -->
                    <a href="{{ route('posts.show', $post->id) }}">
                        <p class="mt-2">
                            @php
                                $parsed1 = preg_replace_callback(
                                    '/@([\w]+)/',
                                    function ($matches1) {
                                        $username1 = e($matches1[1]);
                                        $url1 = url("/{$username1}");
                                        return "<span class=\"text-primary\">@{$username1}</span>";
                                    },
                                    $post->caption,
                                );
                            @endphp

                            {!! $parsed1 !!}
                        </p>
                    </a>

                    <!-- Like dan Komentar -->
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

                    <!-- Form Komentar -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                        @csrf
                        <textarea id="comment-input" name="body" class="form-control form-control-sm" placeholder="Komentar . . ."
                            style="width: 500px" rows="2"></textarea>

                        <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                    </form>

                    <hr>
                </div>
            @endforeach
        </div>

        <!-- Sidebar Pengguna -->
        <div class="col-3">
            <h3 class="mb-3">All Users</h3>
            @foreach ($users as $user)
                <div class="mb-3">
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
