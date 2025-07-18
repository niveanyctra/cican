@extends('layouts.app')
@push('styles')
    <style>
        .carousel-item {
            height: 70vh;
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
    @include('components.modal.follow-back')
    @include('pages.post.show')
    <div class="row">
        <!-- Kolom Utama (Feed Postingan) -->
        <div class="col-7 ms-5">
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
                                    <i class="fa-solid fa-ellipsis-vertical" style="color: black"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#editPostModal{{ $post->id }}" style="font-size: 25px">
                                            Edit
                                        </button>
                                    </li>
                                    <hr>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post->id) }}" method="GET">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger" style="font-size: 25px">Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>

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
                </div>
            @endforeach
        </div>

        <!-- Sidebar Pengguna -->
        <div class="col-4 all-user">
            <div class="d-flex align-items-center justify-content-between me-3">
                <p class="userhead ms-3">Friends</p>
                <div data-bs-toggle="modal" data-bs-target="#followBackModal" style="cursor: pointer;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 25 25"
                        fill="none">
                        <path
                            d="M19.5312 25C22.5516 25 25 22.5516 25 19.5312C25 16.5109 22.5516 14.0625 19.5312 14.0625C16.5109 14.0625 14.0625 16.5109 14.0625 19.5312C14.0625 22.5516 16.5109 25 19.5312 25ZM20.3125 17.1875V18.75H21.875C22.3065 18.75 22.6562 19.0998 22.6562 19.5312C22.6562 19.9627 22.3065 20.3125 21.875 20.3125H20.3125V21.875C20.3125 22.3065 19.9627 22.6562 19.5312 22.6562C19.0998 22.6562 18.75 22.3065 18.75 21.875V20.3125H17.1875C16.756 20.3125 16.4062 19.9627 16.4062 19.5312C16.4062 19.0998 16.756 18.75 17.1875 18.75H18.75V17.1875C18.75 16.756 19.0998 16.4062 19.5312 16.4062C19.9627 16.4062 20.3125 16.756 20.3125 17.1875Z"
                            fill="black" />
                        <path
                            d="M17.1875 7.8125C17.1875 10.4013 15.0888 12.5 12.5 12.5C9.91117 12.5 7.8125 10.4013 7.8125 7.8125C7.8125 5.22367 9.91117 3.125 12.5 3.125C15.0888 3.125 17.1875 5.22367 17.1875 7.8125ZM12.5 10.9375C14.2259 10.9375 15.625 9.53839 15.625 7.8125C15.625 6.08661 14.2259 4.6875 12.5 4.6875C10.7741 4.6875 9.375 6.08661 9.375 7.8125C9.375 9.53839 10.7741 10.9375 12.5 10.9375Z"
                            fill="black" />
                        <path
                            d="M12.9001 21.875C12.7237 21.3758 12.602 20.8508 12.5423 20.3071H4.6875C4.68973 19.9215 4.92774 18.7663 5.98765 17.7064C7.0068 16.6873 8.92325 15.625 12.5 15.625C12.907 15.625 13.2925 15.6388 13.6577 15.6646C14.0095 15.1313 14.432 14.6487 14.9119 14.2302C14.1829 14.1221 13.3812 14.0625 12.5 14.0625C4.6875 14.0625 3.125 18.75 3.125 20.3125C3.125 21.875 4.6875 21.875 4.6875 21.875H12.9001Z"
                            fill="black" />
                    </svg>
                </div>
            </div>
            <hr>
            <div class="mx-4">
                @if ($users->isEmpty())
                    <p class="text-muted" style="font-size: 27px">Kamu belum berteman dengan siapapun</p>
                @else
                    @foreach ($users as $user)
                        <div class="my-4">
                            <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}"
                                        alt="Avatar" style="object-fit: cover; width: 42px; height: 42px;"
                                        class="rounded-circle me-2">
                                    <div style="line-height: 12px">
                                        <p style="font-size: 36px">{{ $user->name }}</p> <br>
                                        <p style="font-size: 32px; opacity: 50%;">{{ '@' . $user->username }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    @stack('modal')
@endsection
