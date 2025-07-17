@push('styles')
    <style>
        .carousel-inner.show-post {
            height: 70vh;
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
                            <!-- Header Post -->
                            <div class="d-flex justify-content-between align-items-center pe-3 flex-shrink-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ Storage::url($post->user->avatar ?? 'default-image.jpg') }}"
                                        alt="Avatar" style="object-fit: cover; width: 62px; height: 62px;"
                                        class="rounded-circle me-2">
                                    <p style="font-size: 35px">
                                        <a href="{{ route('user.show', $post->user->username) }}"
                                            class="text-decoration-none text-dark">{{ $post->user->username }}</a>

                                        <!-- Tampilkan Collaborator -->
                                        @if ($post->collaborators->count() > 0)
                                            <span class="mx-1">&</span>
                                            <a href="{{ route('user.show', $post->collaborators->first()->username) }}"
                                                class="text-decoration-none text-dark">{{ $post->collaborators->first()->username }}</a>
                                            @if ($post->collaborators->count() > 1)
                                                <span class="text-muted">+{{ $post->collaborators->count() - 1 }}</span>
                                            @endif
                                        @endif

                                        <span class="mx-1">•</span>
                                        <span class="text-muted">{{ $post->created_at->diffForHumans() }}</span>
                                    </p>
                                </div>
                                @auth
                                    @if ($post->user_id == Auth::user()->id)
                                        <div class="dropdown ms-auto">
                                            <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="fa-solid fa-ellipsis-vertical" style="color: black"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#editPostModal{{ $post->id }}"
                                                        style="font-size: 25px">
                                                        Edit
                                                    </button>
                                                </li>
                                                <hr>
                                                <li>
                                                    <form action="{{ route('posts.destroy', $post->id) }}" method="GET">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-danger"
                                                            style="font-size: 25px">Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                @endauth
                            </div>
                            <hr class="my-3">
                            <div class="d-flex">
                                <!-- Media (Gambar/Video) -->
                                <div id="modalMediaCarousel-{{ $post->id }}" class="carousel slide mt-2">
                                    <div class="carousel-inner show-post items-center" style="background: black">
                                        <!-- Indikator Carousel -->
                                        @if ($post->media->count() > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($post->media as $index => $media)
                                                    <button type="button"
                                                        data-bs-target="#modalMediaCarousel-{{ $post->id }}"
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
                                                        <img src="{{ Storage::url($media->file_url) }}"
                                                            alt="Post Image" class="img-fluid"
                                                            style="max-height: 70vh; width: 100%; object-fit: contain;">
                                                    @elseif ($media->type === 'video')
                                                        <video controls class="w-100"
                                                            style="max-height: 70vh; object-fit: contain;">
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
                                            data-bs-target="#modalMediaCarousel-{{ $post->id }}"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#modalMediaCarousel-{{ $post->id }}"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>

                                <!-- Informasi Post -->
                                <div class="container d-flex flex-column" style="margin-left: 50px; max-height: 70vh;">
                                    <!-- Scrollable Content Area -->
                                    <div class="flex-grow-1 overflow-auto" style="min-height: 0;">
                                        <!-- Caption -->
                                        <div class="mb-3">
                                            <p style="font-size: 25px">
                                                {{-- {{ $post->caption }} --}}
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
                                        <hr class="my-3">

                                        <!-- Comments -->
                                        <div class="comments-section">
                                            @foreach ($post->comments()->orderBy('created_at', 'desc')->get() as $comment)
                                                <div class="mb-2" style="line-height: 20px">
                                                    <div class="d-flex">
                                                        <img src="{{ Storage::url($comment->user->avatar ?? 'default-image.jpg') }}"
                                                            alt="Avatar"
                                                            style="object-fit: cover; width: 42px; height: 42px;"
                                                            class="rounded-circle me-2">
                                                        <div>
                                                            <p class="mb-1" style="font-size: 25px">
                                                                <a href="{{ route('user.show', $comment->user->username) }}"
                                                                    class="text-decoration-none text-dark fw-bold">
                                                                    {{ $comment->user->username }}
                                                                </a>
                                                                <br>
                                                                @php
                                                                    $parsed = preg_replace_callback(
                                                                        '/@([\w]+)/',
                                                                        function ($matches) {
                                                                            $username = e($matches[1]);
                                                                            $url = url("/{$username}");
                                                                            return "<a href=\"{$url}\" class=\"text-primary\">@{$username}</a>";
                                                                        },
                                                                        e($comment->body),
                                                                    );
                                                                @endphp
                                                                {!! $parsed !!}
                                                                <br>
                                                                <span class="d-flex" style="font-size: 20px">
                                                                    <span
                                                                        class="text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                                                                    @auth
                                                                        @if ($comment->user_id == Auth::id())
                                                                            <a href="{{ route('comments.destroy', $comment->id) }}"
                                                                                class="text-decoration-none text-danger ms-2">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="15" height="18"
                                                                                    viewBox="0 0 15 18" fill="none">
                                                                                    <path
                                                                                        d="M5.89286 1.125H9.10714C9.40301 1.125 9.64286 1.37684 9.64286 1.6875V2.8125H5.35714V1.6875C5.35714 1.37684 5.59699 1.125 5.89286 1.125ZM10.7143 2.8125V1.6875C10.7143 0.75552 9.99474 0 9.10714 0H5.89286C5.00526 0 4.28571 0.755519 4.28571 1.6875V2.8125H1.6132C1.60956 2.81246 1.60591 2.81246 1.60226 2.8125H0.535714C0.239847 2.8125 0 3.06434 0 3.375C0 3.68566 0.239847 3.9375 0.535714 3.9375H1.11257L2.02625 15.9294C2.11534 17.0988 3.04508 18 4.16228 18H10.8377C11.9549 18 12.8847 17.0988 12.9738 15.9294L13.8874 3.9375H14.4643C14.7602 3.9375 15 3.68566 15 3.375C15 3.06434 14.7602 2.8125 14.4643 2.8125H13.3977C13.3941 2.81246 13.3904 2.81246 13.3868 2.8125H10.7143ZM12.8126 3.9375L11.9057 15.8397C11.8612 16.4244 11.3963 16.875 10.8377 16.875H4.16228C3.60368 16.875 3.13881 16.4244 3.09426 15.8397L2.18743 3.9375H12.8126ZM4.78997 5.06347C5.08533 5.04523 5.33884 5.28184 5.35622 5.59197L5.89193 15.1545C5.90931 15.4646 5.68396 15.7308 5.3886 15.749C5.09325 15.7673 4.83973 15.5307 4.82235 15.2205L4.28664 5.65803C4.26927 5.34791 4.49461 5.08171 4.78997 5.06347ZM10.21 5.06347C10.5054 5.08171 10.7307 5.34791 10.7134 5.65803L10.1776 15.2205C10.1603 15.5307 9.90676 15.7673 9.6114 15.749C9.31604 15.7308 9.09069 15.4646 9.10807 15.1545L9.64378 5.59197C9.66116 5.28184 9.91467 5.04523 10.21 5.06347ZM7.5 5.0625C7.79587 5.0625 8.03572 5.31434 8.03572 5.625V15.1875C8.03572 15.4982 7.79587 15.75 7.5 15.75C7.20413 15.75 6.96429 15.4982 6.96429 15.1875V5.625C6.96429 5.31434 7.20413 5.0625 7.5 5.0625Z"
                                                                                        fill="#E50001" />
                                                                                </svg>
                                                                            </a>
                                                                        @endif
                                                                    @endauth
                                                                </span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Fixed Bottom Section -->
                                    <div class="bottom-post flex-shrink-0 pt-3"
                                        style="border-top: 1px solid #dee2e6;">
                                        @include('components.like-comments')

                                        <!-- Comment Form -->
                                        <form action="{{ route('comments.store', $post->id) }}" method="POST"
                                            class="d-flex">
                                            @csrf
                                            <input type="text" id="comment-input-modal-{{ $post->id }}"
                                                class="form-control comment-input" name="body"
                                                placeholder="Komentar . . ." style="width: 100%; font-size: 25px; border: 0;">
                                            <button type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="52" height="52"
                                                    viewBox="0 0 52 52" fill="none">
                                                    <path
                                                        d="M48.3286 22.0309C50.3998 22.9029 50.5866 25.7661 48.6462 26.8999L20.7212 43.2149C18.557 44.4804 15.9758 42.3983 16.7542 40.0157L20.2672 29.2432C20.4219 28.769 20.9322 28.5098 21.4065 28.6644C21.8805 28.8193 22.139 29.329 21.9846 29.8031L18.4724 40.5763C18.2098 41.3796 19.0802 42.0817 19.8098 41.6551L47.7348 25.34C48.3895 24.9575 48.3265 23.9902 47.6276 23.6959L17.8237 11.1469C17.0422 10.8182 16.2697 11.6281 16.6352 12.3933L22.7701 25.2331L43.9882 23.8531C44.4861 23.8207 44.916 24.1991 44.9484 24.697C44.9805 25.1947 44.6029 25.624 44.1051 25.6564L22.2759 27.077C21.8791 27.1027 21.5263 26.8678 21.3828 26.5194L15.0045 13.1723C13.9229 10.9081 16.2111 8.50853 18.524 9.48127L48.3286 22.0309Z"
                                                        fill="black" />
                                                    <path
                                                        d="M48.3286 22.0309L48.1346 22.4917L48.1346 22.4917L48.3286 22.0309ZM48.6462 26.8999L48.8984 27.3316L48.8985 27.3316L48.6462 26.8999ZM20.7212 43.2149L20.469 42.7832L20.4688 42.7833L20.7212 43.2149ZM16.7542 40.0157L17.2295 40.171L17.2296 40.1708L16.7542 40.0157ZM20.2672 29.2432L19.7918 29.0881L19.7918 29.0882L20.2672 29.2432ZM21.4065 28.6644L21.5618 28.1892L21.5615 28.1891L21.4065 28.6644ZM21.9846 29.8031L22.4599 29.9581L22.46 29.958L21.9846 29.8031ZM18.4724 40.5763L18.9476 40.7316L18.9478 40.7313L18.4724 40.5763ZM19.8098 41.6551L19.5576 41.2233L19.5574 41.2234L19.8098 41.6551ZM47.7348 25.34L47.987 25.7718L47.9871 25.7718L47.7348 25.34ZM47.6276 23.6959L47.4335 24.1567L47.4335 24.1567L47.6276 23.6959ZM17.8237 11.1469L18.0178 10.6861L18.0176 10.686L17.8237 11.1469ZM16.6352 12.3933L16.184 12.6088L16.1841 12.6088L16.6352 12.3933ZM22.7701 25.2331L22.319 25.4487L22.4649 25.754L22.8026 25.7321L22.7701 25.2331ZM43.9882 23.8531L43.9558 23.3541L43.9557 23.3541L43.9882 23.8531ZM44.9484 24.697L45.4473 24.6647L45.4473 24.6645L44.9484 24.697ZM44.1051 25.6564L44.0727 25.1575L44.0727 25.1575L44.1051 25.6564ZM22.2759 27.077L22.3082 27.576L22.3084 27.576L22.2759 27.077ZM21.3828 26.5194L21.8451 26.3289L21.8399 26.3162L21.834 26.3038L21.3828 26.5194ZM15.0045 13.1723L14.5533 13.3878L14.5533 13.3879L15.0045 13.1723ZM18.524 9.48127L18.718 9.02046L18.7179 9.02037L18.524 9.48127ZM48.3286 22.0309L48.1346 22.4917C49.826 23.2039 49.9786 25.5423 48.394 26.4682L48.6462 26.8999L48.8985 27.3316C51.1946 25.99 50.9735 22.602 48.5226 21.5701L48.3286 22.0309ZM48.6462 26.8999L48.394 26.4682L20.469 42.7832L20.7212 43.2149L20.9734 43.6466L48.8984 27.3316L48.6462 26.8999ZM20.7212 43.2149L20.4688 42.7833C18.7016 43.8166 16.5939 42.1163 17.2295 40.171L16.7542 40.0157L16.279 39.8605C15.3577 42.6803 18.4124 45.1441 20.9736 43.6465L20.7212 43.2149ZM16.7542 40.0157L17.2296 40.1708L20.7425 29.3982L20.2672 29.2432L19.7918 29.0882L16.2789 39.8607L16.7542 40.0157ZM20.2672 29.2432L20.7425 29.3983C20.8116 29.1866 21.0396 29.0707 21.2515 29.1398L21.4065 28.6644L21.5615 28.1891C20.8247 27.9488 20.0322 28.3513 19.7918 29.0881L20.2672 29.2432ZM21.4065 28.6644L21.2512 29.1397C21.4625 29.2087 21.5782 29.4362 21.5092 29.6483L21.9846 29.8031L22.46 29.958C22.6998 29.2218 22.2984 28.4298 21.5618 28.1892L21.4065 28.6644ZM21.9846 29.8031L21.5092 29.6482L17.997 40.4213L18.4724 40.5763L18.9478 40.7313L22.4599 29.9581L21.9846 29.8031ZM18.4724 40.5763L17.9971 40.421C17.5917 41.6615 18.9355 42.7455 20.0622 42.0867L19.8098 41.6551L19.5574 41.2234C19.2248 41.4179 18.828 41.0978 18.9476 40.7316L18.4724 40.5763ZM19.8098 41.6551L20.062 42.0868L47.987 25.7718L47.7348 25.34L47.4826 24.9083L19.5576 41.2233L19.8098 41.6551ZM47.7348 25.34L47.9871 25.7718C48.9975 25.1813 48.9002 23.6893 47.8216 23.2351L47.6276 23.6959L47.4335 24.1567C47.7527 24.2911 47.7816 24.7336 47.4826 24.9083L47.7348 25.34ZM47.6276 23.6959L47.8216 23.2351L18.0178 10.6861L17.8237 11.1469L17.6297 11.6078L47.4335 24.1567L47.6276 23.6959ZM17.8237 11.1469L18.0176 10.686C16.8125 10.1792 15.6203 11.4287 16.184 12.6088L16.6352 12.3933L17.0864 12.1777C16.919 11.8275 17.272 11.4573 17.6299 11.6078L17.8237 11.1469ZM16.6352 12.3933L16.1841 12.6088L22.319 25.4487L22.7701 25.2331L23.2213 25.0176L17.0864 12.1777L16.6352 12.3933ZM22.7701 25.2331L22.8026 25.7321L44.0206 24.352L43.9882 23.8531L43.9557 23.3541L22.7377 24.7342L22.7701 25.2331ZM43.9882 23.8531L44.0206 24.352C44.2423 24.3376 44.4349 24.5063 44.4494 24.7294L44.9484 24.697L45.4473 24.6645C45.3971 23.8918 44.7299 23.3038 43.9558 23.3541L43.9882 23.8531ZM44.9484 24.697L44.4494 24.7292C44.4638 24.951 44.2954 25.143 44.0727 25.1575L44.1051 25.6564L44.1376 26.1554C44.9104 26.1051 45.4973 25.4384 45.4473 24.6647L44.9484 24.697ZM44.1051 25.6564L44.0727 25.1575L22.2434 26.5781L22.2759 27.077L22.3084 27.576L44.1376 26.1554L44.1051 25.6564ZM22.2759 27.077L22.2436 26.5781C22.068 26.5894 21.9098 26.4859 21.8451 26.3289L21.3828 26.5194L20.9205 26.7098C21.1429 27.2497 21.6902 27.616 22.3082 27.576L22.2759 27.077ZM21.3828 26.5194L21.834 26.3038L15.4556 12.9567L15.0045 13.1723L14.5533 13.3879L20.9317 26.735L21.3828 26.5194ZM15.0045 13.1723L15.4556 12.9567C14.5722 11.1073 16.4409 9.14762 18.3302 9.94217L18.524 9.48127L18.7179 9.02037C15.9812 7.86943 13.2736 10.7088 14.5533 13.3878L15.0045 13.1723ZM18.524 9.48127L18.33 9.94209L48.1346 22.4917L48.3286 22.0309L48.5226 21.5701L18.718 9.02046L18.524 9.48127Z"
                                                        fill="black" />
                                                </svg>
                                            </button>
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
                                    style="margin-left: 50px; max-height: 70vh;">
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
                                            <p>
                                                {{-- {{ $post->caption }} --}}
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
                                                                    $url = url("/{$username}");
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
                                        @include('components.like-comments')

                                        <!-- Comment Form -->
                                        <form action="{{ route('comments.store', $post->id) }}" method="POST"
                                            class="d-flex">
                                            @csrf
                                            <input type="text" id="comment-input-modal-text-{{ $post->id }}"
                                                class="form-control form-control-sm comment-input" name="body"
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
