@push('styles')
    <style>
        .carousel-inner.show-post {
            height: 80vh;
            /* Tetapkan tinggi container */
            min-height: 500px;
            /* Tinggi minimum untuk mobile */
            width: 500px
        }
    </style>
@endpush
@include('pages.post.edit')
@if (isset($posts))
    @foreach ($posts as $post)
        @if ($post->media->isNotEmpty())
            <div class="modal fade" id="showPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="shoPostModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="d-flex">
                                <!-- Media (Gambar/Video) -->
                                <div id="postMediaCarousel-{{ $post->id }}" class="carousel slide mt-2">
                                    <div class="carousel-inner show-post items-center" style="background: black">
                                        <!-- Indikator Carousel -->
                                        @if ($post->media->count() > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($post->media as $index => $media)
                                                    <button type="button"
                                                        data-bs-target="#postMediaCarousel-{{ $post->id }}"
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
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    @if ($media->type === 'image')
                                                        <img src="{{ Storage::url($media->file_url) }}" alt="Post Image"
                                                            class="img-fluid"
                                                            style="max-height: 80vh; width: 100%; object-fit: contain;">
                                                    @elseif ($media->type === 'video')
                                                        <video controls class="w-100"
                                                            style="max-height: 80vh; object-fit: contain;">
                                                            <source src="{{ Storage::url($media->file_url) }}"
                                                                type="video/mp4">
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
                                            data-bs-target="#postMediaCarousel-{{ $post->id }}"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#postMediaCarousel-{{ $post->id }}"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Informasi Post -->
                                <div class="container d-flex flex-column" style="margin-left: 50px; max-height: 80vh;">
                                    <!-- Header Post -->
                                    <div class="d-flex justify-content-between flex-shrink-0">
                                        <p>
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
                                        </p>
                                        @auth
                                            @if ($post->user_id == Auth::user()->id)
                                                <div>
                                                    <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                                                        data-bs-target="#editPostModal{{ $post->id }}">
                                                        Edit
                                                    </button>
                                                    <a href="{{ route('posts.destroy', $post->id) }}"
                                                        class="btn btn-danger">Hapus</a>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                    <hr class="my-3">

                                    <!-- Scrollable Content Area -->
                                    <div class="flex-grow-1 overflow-auto" style="min-height: 0;">
                                        <!-- Caption -->
                                        <div class="mb-3">
                                            <p>{{ $post->caption }}</p>
                                        </div>
                                        <hr class="my-3">

                                        <!-- Comments -->
                                        <div class="comments-section">
                                            @foreach ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                                <div class="mb-3">
                                                    <p class="mb-1">
                                                        <a href="{{ route('user.show', $comment->user->username) }}"
                                                            class="text-decoration-none text-dark fw-bold">
                                                            {{ $comment->user->username }}
                                                        </a>

                                                        @php
                                                            $parsed = preg_replace_callback(
                                                                '/@([\w]+)/',
                                                                function ($matches) {
                                                                    $username = e($matches[1]);
                                                                    $url = url("/profile/{$username}");
                                                                    return "<a href=\"{$url}\" class=\"text-primary\">@{$username}</a>";
                                                                },
                                                                e($comment->body),
                                                            );
                                                        @endphp

                                                        {!! $parsed !!}
                                                    </p>
                                                    <small
                                                        class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                    @auth
                                                        @if ($comment->user_id == Auth::id())
                                                            <a href="{{ route('comments.destroy', $comment->id) }}"
                                                                class="text-decoration-none text-danger ms-2">Hapus</a>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Fixed Bottom Section -->
                                    <div class="bottom-post flex-shrink-0 pt-3" style="border-top: 1px solid #dee2e6;">
                                        <!-- Like Section -->
                                        <div id="like-section-modal-{{ $post->id }}" class="my-2 d-flex">
                                            <div class="d-flex gap-1">
                                                <button id="like-button-feed-{{ $post->id }}"
                                                    onclick="toggleLike({{ $post->id }})">
                                                    <!-- Ikon Like -->
                                                    <i id="like-icon-feed-{{ $post->id }}"
                                                        class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
                                                </button>
                                                <div data-bs-toggle="modal"
                                                    data-bs-target="#showWhoLikeModal{{ $post->id }}">
                                                    <span
                                                        id="like-count-feed-{{ $post->id }}">{{ $post->likes->count() }}
                                                        likes</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('posts.show', $post->id) }}">
                                                <i class="fa-regular fa-comment fa-xl ms-2"></i>
                                                <span>{{ $post->comments->count() }} comments</span>
                                            </a>
                                        </div>

                                        <!-- Comment Form -->
                                        <form action="{{ route('comments.store', $post->id) }}" method="POST"
                                            class="d-flex">
                                            @csrf
                                            <input type="text" id="comment-input"
                                                class="form-control form-control-sm" name="body"
                                                placeholder="Komentar . . ." style="width: 100%; resize: none;">
                                            <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="modal fade" id="showPostModal{{ $post->id }}" tabindex="-1"
                aria-labelledby="shoPostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="d-flex">
                                <!-- Informasi Post -->
                                <div class="container d-flex flex-column"
                                    style="margin-left: 50px; max-height: 80vh;">
                                    <!-- Header Post -->
                                    <div class="d-flex justify-content-between flex-shrink-0">
                                        <p>
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
                                        </p>
                                        @auth
                                            @if ($post->user_id == Auth::user()->id)
                                                <div>
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editPostModal{{ $post->id }}">
                                                        Edit
                                                    </button>
                                                    <a href="{{ route('posts.destroy', $post->id) }}"
                                                        class="btn btn-danger">Hapus</a>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                    <hr class="my-3">

                                    <!-- Scrollable Content Area -->
                                    <div class="flex-grow-1 overflow-auto" style="min-height: 0;">
                                        <!-- Caption -->
                                        <div class="mb-3">
                                            <p>{{ $post->caption }}</p>
                                        </div>
                                        <hr class="my-3">

                                        <!-- Comments -->
                                        <div class="comments-section">
                                            @foreach ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                                <div class="mb-3">
                                                    <p class="mb-1">
                                                        <a href="{{ route('user.show', $comment->user->username) }}"
                                                            class="text-decoration-none text-dark fw-bold">
                                                            {{ $comment->user->username }}
                                                        </a>

                                                        @php
                                                            $parsed = preg_replace_callback(
                                                                '/@([\w]+)/',
                                                                function ($matches) {
                                                                    $username = e($matches[1]);
                                                                    $url = url("/profile/{$username}");
                                                                    return "<a href=\"{$url}\" class=\"text-primary\">@{$username}</a>";
                                                                },
                                                                e($comment->body),
                                                            );
                                                        @endphp

                                                        {!! $parsed !!}
                                                    </p>
                                                    <small
                                                        class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                                    @auth
                                                        @if ($comment->user_id == Auth::id())
                                                            <a href="{{ route('comments.destroy', $comment->id) }}"
                                                                class="text-decoration-none text-danger ms-2">Hapus</a>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Fixed Bottom Section -->
                                    <div class="bottom-post flex-shrink-0 pt-3"
                                        style="border-top: 1px solid #dee2e6;">
                                        <!-- Like Section -->
                                        <div id="like-section-modal-{{ $post->id }}" class="my-2 d-flex">
                                            <div class="d-flex gap-1">
                                                <button id="like-button-feed-{{ $post->id }}"
                                                    onclick="toggleLike({{ $post->id }})">
                                                    <!-- Ikon Like -->
                                                    <i id="like-icon-feed-{{ $post->id }}"
                                                        class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
                                                </button>
                                                <div data-bs-toggle="modal"
                                                    data-bs-target="#showWhoLikeModal{{ $post->id }}">
                                                    <span
                                                        id="like-count-feed-{{ $post->id }}">{{ $post->likes->count() }}
                                                        likes</span>
                                                </div>
                                            </div>
                                            <a href="{{ route('posts.show', $post->id) }}">
                                                <i class="fa-regular fa-comment fa-xl ms-2"></i>
                                                <span>{{ $post->comments->count() }} comments</span>
                                            </a>
                                        </div>

                                        <!-- Comment Form -->
                                        <form action="{{ route('comments.store', $post->id) }}" method="POST"
                                            class="d-flex">
                                            @csrf
                                            <input type="text" id="comment-input"
                                                class="form-control form-control-sm" name="body"
                                                placeholder="Komentar . . ." style="width: 100%; resize: none;">
                                            <input type="submit" value="Kirim" class="btn btn-sm btn-primary ms-2">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endif
