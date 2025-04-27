@extends('layouts.app')

@section('content')
    <div class="container px-40">
        <!-- Header Profil -->
        <div class="d-flex align-items-start mb-4">
            <!-- Avatar -->
            <div class="me-4 profile">
                <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}" alt="Avatar"
                    class="rounded-circle" style="object-fit: cover; width: 70px; height: 70px;">
            </div>

            <!-- Informasi Profil -->
            <div class="flex-grow-1">
                <div class="d-flex gap-4 align-items-center">
                    <h5>{{ $user->username }}</h5>

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

                <span class="fw-bold">{{ $user->name }}</span>
                <p>{{ $user->bio ?? '' }}</p>
                <div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#followersModal">
                        <strong>{{ $user->followers->count() }}</strong> Followers
                    </a>
                    |
                    <a href="#" data-bs-toggle="modal" data-bs-target="#followingsModal">
                        <strong>{{ $user->followings->count() }}</strong> Following
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-3">

        <div class="row gap-0">
            @foreach ($posts as $post)
                <div class="col-4">
                    <a href="{{ route('posts.show', $post->id) }}">
                        <div
                            style="width: 305px; height: 350px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                            <img src="{{ Storage::url($post->media->first()->file_url ?? asset('default-image.jpg')) }}"
                                alt="Post Media" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>


    </div>
@endsection

@push('scripts')
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
                            if (data.status === 'followed') {
                                button.classList.remove('btn-outline-primary');
                                button.classList.add('btn-outline-secondary');
                                button.textContent = 'Unfollow';
                            } else if (data.status === 'unfollowed') {
                                button.classList.remove('btn-outline-secondary');
                                button.classList.add('btn-outline-primary');
                                button.textContent = 'Follow';
                            }
                        })
                        .catch(error => {
                            console.error('Gagal toggle follow:', error);
                        });
                }
            });
        });
    </script>
@endpush
