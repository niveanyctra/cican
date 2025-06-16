@extends('layouts.app')
@section('content')
@include('pages.post.edit')
    <div class="row">
        <!-- Kolom Utama (Feed Postingan) -->
        <div class="col-8 ms-5">
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

                    <!-- Media (Gambar/Video) -->
                    <a href="{{ route('posts.show', $post->id) }}">
                        <img src="{{ Storage::url($post->media->first()->file_url ?? asset('default-image.jpg')) }}"
                            alt="Post Media" width="100%" class="img-fluid mt-2">
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

                    <!-- Caption -->
                    <a href="{{ route('posts.show', $post->id) }}">
                        <p class="mt-2">
                            {{ $post->caption }}
                        </p>
                    </a>

                    <!-- Form Komentar -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                        @csrf
                        <textarea id="comment-input" name="body"
                        class="form-control form-control-sm"
                        placeholder="Komentar . . ." style="width: 500px" rows="2"></textarea>

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

@push('scripts')
    <script>
        function toggleLike(postId) {
            const button = document.getElementById(`like-button-${postId}`);
            const icon = document.getElementById(`like-icon-${postId}`);
            const countElement = document.getElementById(`like-count-${postId}`);

            // Tentukan apakah pengguna sudah menyukai postingan
            const isLiked = icon.classList.contains('fa-solid');

            // Tentukan URL dan method
            const url = `/posts/${postId}/like`;
            const method = isLiked ? 'DELETE' : 'POST';

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        // Update ikon like
                        if (isLiked) {
                            // Jika sebelumnya disukai, ubah ke tidak disukai
                            icon.classList.remove('fa-solid', 'text-danger');
                            icon.classList.add('fa-regular');
                        } else {
                            // Jika sebelumnya tidak disukai, ubah ke disukai
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid', 'text-danger');
                        }

                        // Update jumlah like
                        countElement.textContent = data.likeCount;

                        // Tampilkan pesan sukses (opsional)
                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endpush
