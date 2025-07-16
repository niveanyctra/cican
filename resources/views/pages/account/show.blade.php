@extends('layouts.app')
@push('styles')
    <style>
        @media (max-width: 768px) {
            .col-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>
@endpush
@section('content')
    @include('pages.post.edit')
    @include('pages.post.show')
    <div class="profile-status">
        <!-- Header Profil -->
        <div class="d-flex foto">
            <!-- Avatar -->
            <div class="me-4 profile-foto">
                <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}" alt="Avatar"
                    class="rounded-3" style="object-fit: cover; width: 200px; height: 200px;">
            </div>

            <!-- Informasi Profil -->
            <div class="flex-grow-1">
                <div class="d-flex gap-4 align-items-center">
                    <h2 class="fw-bold fs-1 pe-5 mb-2">{{ $user->username }}</h2>
                    @auth
                        @if ($user->id !== auth()->id())
                            <button
                                class="btn follow-btn {{ auth()->user()->followings->contains($user->id) ? 'btn-secondary' : 'btn-primary' }}"
                                data-user-id="{{ $user->id }}">
                                {{ auth()->user()->followings->contains($user->id) ? 'Unfollow' : 'Follow' }}
                            </button>
                        @else
                            <div class="d-flex align-items-center">
                                <a href="{{ route('account.edit') }}" class="btn btn-dark me-2">Edit Profil</a>
                            </div>
                        @endif
                    @endauth
                </div>

                <span class="">{{ $user->name }}</span>
                <p>{{ $user->bio ?? '' }}</p>
                
            </div>
        </div>
<div class="follower">
    <a class="pe-5" href="#" data-bs-toggle="modal" data-bs-target="#followersModal">
        <strong>{{ $user->followers->count() }}</strong> Followers
    </a>
                    |
    <a class="ps-5" href="#" data-bs-toggle="modal" data-bs-target="#followingsModal">
        <strong>{{ $user->followings->count() }}</strong> Following
    </a>
</div>
        <hr class="my-3">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs mb-4 justify-between" id="profileTab" role="tablist">
            <li class="nav-item w-50" role="presentation">
                <button class="nav-link active w-100" id="snapee-tab" data-bs-toggle="tab" data-bs-target="#snapee"
                    type="button" role="tab" aria-controls="snapee" aria-selected="true">
                    Snapee
                </button>
            </li>
            <li class="nav-item w-50" role="presentation">
                <button class="nav-link w-100" id="textee-tab" data-bs-toggle="tab" data-bs-target="#textee" type="button"
                    role="tab" aria-controls="textee" aria-selected="false">
                    Textee
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="profileTabContent">
            <!-- Snapee Tab -->
            <div class="tab-pane fade show active" id="snapee" role="tabpanel" aria-labelledby="snapee-tab">
                <div class="row gap-0 ps-3">
                    @foreach ($posts as $post)
                        @if ($post->media->isNotEmpty())
                            <div class="col-4 p-0">
                                <div data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}"
                                    class="d-block h-100">
                                    <div class="position-relative h-100" style="min-height: 350px; max-height: 350px;">
                                        @php
                                            $firstMedia = $post->media->first();
                                            $imageUrl =
                                                $firstMedia->type === 'video' && $firstMedia->thumbnail_url
                                                    ? $firstMedia->thumbnail_url
                                                    : Storage::url($firstMedia->file_url);
                                        @endphp

                                        <img src="{{ $imageUrl }}" alt="Post Media" class="img-fluid w-100 h-100"
                                            style="object-fit: cover;">
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Textee Tab -->
            <div class="tab-pane fade" id="textee" role="tabpanel" aria-labelledby="textee-tab">
                <div class="d-flex flex-column gap-3">
                    @foreach ($posts as $post)
                        @if ($post->media->isEmpty())
                            <div class="card border-0">
                                <div class="card-body">
                                    <!-- Profil dan Caption -->
                                    <div class="gap-2">
                                        <!-- Avatar dan Username -->
                                        <div class="d-flex align-items-center profile">
                                            <img src="{{ Storage::url($post->user->avatar ?? 'default-image.jpg') }}"
                                                alt="Avatar" style="object-fit: cover; width: 40px; height: 40px;"
                                                class="rounded-circle me-2">
                                            <a href="{{ route('user.show', $post->user->username) }}"
                                                class="text-decoration-none text-dark fw-bold">{{ $post->user->username }}</a>

                                            <!-- Tampilkan Collaborator -->
                                            @if ($post->collaborators->count() > 0)
                                                <span class="mx-1">&</span>
                                                <a href="{{ route('user.show', $post->collaborators->first()->username) }}"
                                                    class="text-decoration-none text-dark fw-bold">{{ $post->collaborators->first()->username }}</a>
                                                @if ($post->collaborators->count() > 1)
                                                    <span
                                                        class="text-muted">+{{ $post->collaborators->count() - 1 }}</span>
                                                @endif
                                            @endif

                                            <span class="mx-1">•</span>
                                            <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>

                                            <!-- Menu Titik Tiga untuk Edit/Hapus -->
                                            @if ($post->user_id === auth()->id())
                                                <div class="dropdown ms-auto">
                                                    <button class="btn btn-link p-0" type="button"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button type="button" class="dropdown-item"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editPostModal{{ $post->id }}">
                                                                Edit
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('posts.destroy', $post->id) }}"
                                                                method="GET">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="dropdown-item text-danger">Hapus</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Caption -->
                                        <div data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">
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
                                        </div>

                                        @include('components.like-comments')
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const snapeeBtn = document.getElementById('snapee-tab');
            const texteeBtn = document.getElementById('textee-tab');
            const snapeeContent = document.getElementById('snapee');
            const texteeContent = document.getElementById('textee');

            snapeeBtn.addEventListener('click', () => {
                snapeeContent.classList.add('show', 'active');
                texteeContent.classList.remove('show', 'active');
            });

            texteeBtn.addEventListener('click', () => {
                texteeContent.classList.add('show', 'active');
                snapeeContent.classList.remove('show', 'active');
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('follow-btn')) {
                    const button = e.target;
                    const userId = button.dataset.userId;
                    fetch(`/follow/follow-toggle/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Refresh page after follow/unfollow
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Gagal toggle follow:', error);
                        });
                }
            });
        });
    </script>
@endpush
