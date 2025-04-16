@extends('layouts.app')
@section('content')
<div class="row">
    <!-- Kolom Utama (Feed Postingan) -->
    <div class="col-8 ms-5">
        @foreach ($posts as $post)
            <div class="mb-3">
                <!-- Avatar dan Username -->
                <div class="d-flex align-items-center">
                    <img src="{{ Storage::url($post->user->avatar ?? 'default-avatar.png') }}" alt="Avatar" width="40" height="40" class="rounded-circle me-2">
                    <a href="{{ route('user.show', $post->user->username) }}" class="text-decoration-none text-dark fw-bold">{{ $post->user->username }}</a>
                    <span class="mx-1">•</span>
                    <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>

                    <!-- Menu Titik Tiga untuk Edit/Hapus -->
                        @if ($post->user_id === auth()->id())
                            <div class="dropdown ms-auto">
                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editPostModal">
                                            Edit
                                        </button>
                                        {{-- <a class="dropdown-item" href="{{ route('posts.edit', $post->id) }}">Edit</a> --}}
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
                    <img src="{{ Storage::url($post->media->first()->file_url ?? 'default-image.jpg') }}" alt="Post Media" width="500px" class="img-fluid mt-2">
                </a>

                <!-- Like dan Komentar -->
                <div id="like-section-{{ $post->id }}">
                    <button
                        id="like-button-{{ $post->id }}"
                        onclick="toggleLike({{ $post->id }})"
                    >
                        <!-- Ikon Like -->
                        <i
                            id="like-icon-{{ $post->id }}"
                            class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"
                        ></i>
                    </button>
                    <!-- Jumlah Like -->
                    <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
                                        <i class="fa-regular fa-comment fa-xl ms-2"></i>
                                        <span>{{ $post->comments->count() }} comments</span>
                </div>

                <!-- Caption -->
                <p class="mt-2">
                    {{ $post->caption }}
                </p>

                <!-- Form Komentar -->
                    <form action="{{ route('comments.store', $post->id) }}" method="POST" class="d-flex">
                        @csrf
                        <input class="form-control form-control-sm" type="text" name="body" placeholder="Komentar . . ." style="width: 500px">
                        <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                    </form>

                <hr>
            </div>
        @endforeach
    </div>

    <!-- Sidebar Pengguna -->
    <div class="col-3">
        <h3>All Users</h3>
        @foreach ($users as $user)
            <div class="mb-3">
                <a href="{{ route('user.show', $user->username) }}" class="text-decoration-none text-dark">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($user->avatar ?? 'default-avatar.png') }}" alt="Avatar" width="40" height="40" class="rounded-circle me-2">
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
