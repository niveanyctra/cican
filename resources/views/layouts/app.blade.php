<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap demo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://unpkg.com/tributejs@5.1.3/dist/tribute.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="../../css/login.css"> --}}
    <style>
        .profile img {
            max-width: 150px;
            height: 150px;
        }
    </style>
    @stack('styles')
</head>

<body class="flex flex-col bg-gray-100 min-h-screen">
    @auth
        <div class="flex flex-1">
            @include('components.sidebar')
            <main class="flex-1 ml-64 p-8">
                @yield('content')
                <!-- Modal -->
                @include('pages.post.create')
                @include('pages.search.search')
                @if (isset($user))
                    @include('components.modal.follower')
                    @include('components.modal.following')
                @endif
                @include('components.modal.who-likes')
            </main>
        </div>
    @else
        @include('components.navbar')
        <main class="flex-1 mx-64 p-8">
            @yield('content')
        </main>
    @endauth

    <script src="../../js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </script>
    <script src="https://unpkg.com/tributejs@5.1.3/dist/tribute.js"></script>
    <script>
        function toggleLike(postId) {
            // Cari semua elemen like untuk post ini
            // Gunakan querySelectorAll untuk mendapatkan semua elemen dengan ID yang sama
            const allLikeButtons = document.querySelectorAll(`[id^="like-button-feed-${postId}"]`);
            const allLikeIcons = document.querySelectorAll(`[id^="like-icon-feed-${postId}"]`);
            const allLikeCounts = document.querySelectorAll(`[id^="like-count-feed-${postId}"]`);

            // Jika tidak ada elemen yang ditemukan, coba dengan ID alternatif
            if (allLikeIcons.length === 0) {
                console.error('Like elements not found for post ID:', postId);
                return;
            }

            // Tentukan apakah pengguna sudah menyukai postingan (dari icon pertama yang ditemukan)
            const firstIcon = allLikeIcons[0];
            const isLiked = firstIcon.classList.contains('fa-solid');

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
                        // Update semua ikon like yang ditemukan
                        allLikeIcons.forEach(iconElement => {
                            if (isLiked) {
                                // Jika sebelumnya disukai, ubah ke tidak disukai
                                iconElement.classList.remove('fa-solid', 'text-danger');
                                iconElement.classList.add('fa-regular');
                            } else {
                                // Jika sebelumnya tidak disukai, ubah ke disukai
                                iconElement.classList.remove('fa-regular');
                                iconElement.classList.add('fa-solid', 'text-danger');
                            }
                        });

                        // Update jumlah like untuk semua elemen count yang ditemukan
                        allLikeCounts.forEach(countEl => {
                            countEl.textContent = `${data.likeCount} likes`;
                        });

                        console.log(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tribute = new Tribute({
                trigger: "@",
                values: async (text, cb) => {
                    if (text.length >= 2) {
                        const res = await fetch(`/mention/users?query=${text}`);
                        const users = await res.json();
                        cb(users.map(user => ({
                            key: user.username,
                            value: user.username
                        })));
                    } else {
                        cb([]);
                    }
                },
                selectTemplate: function(item) {
                    return `@${item.original.key}`;
                }
            });


            const commentInputSnapee =
                document.getElementById('comment-input-snapee')
            if (commentInputSnapee) {
                tribute.attach(commentInputSnapee);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tribute = new Tribute({
                trigger: "@",
                values: async (text, cb) => {
                    if (text.length >= 2) {
                        const res = await fetch(`/mention/users?query=${text}`);
                        const users = await res.json();
                        cb(users.map(user => ({
                            key: user.username,
                            value: user.username
                        })));
                    } else {
                        cb([]);
                    }
                },
                selectTemplate: function(item) {
                    return `@${item.original.key}`;
                }
            });


            const commentInputtextee =
                document.getElementById('comment-input-textee')
            if (commentInputtextee) {
                tribute.attach(commentInputtextee);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tribute = new Tribute({
                trigger: "@",
                values: async (text, cb) => {
                    if (text.length >= 2) {
                        const res = await fetch(`/mention/users?query=${text}`);
                        const users = await res.json();
                        cb(users.map(user => ({
                            key: user.username,
                            value: user.username
                        })));
                    } else {
                        cb([]);
                    }
                },
                selectTemplate: function(item) {
                    return `@${item.original.key}`;
                }
            });


            const commentInputModal =
                document.getElementById('comment-input-modal');
            if (commentInputModal) {
                tribute.attach(commentInputModal);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tribute = new Tribute({
                trigger: "@",
                values: async (text, cb) => {
                    if (text.length >= 2) {
                        const res = await fetch(`/mention/users?query=${text}`);
                        const users = await res.json();
                        cb(users.map(user => ({
                            key: user.username,
                            value: user.username
                        })));
                    } else {
                        cb([]);
                    }
                },
                selectTemplate: function(item) {
                    return `@${item.original.key}`;
                }
            });


            const commentInput = document.getElementById('comment-input-index') ||
                document.getElementById('comment-input-snapee') ||
                document.getElementById('comment-input-textee') ||
                document.getElementById('comment-input');
            if (commentInput) {
                tribute.attach(commentInput);
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tribute = new Tribute({
                trigger: "@",
                values: async (text, cb) => {
                    if (text.length >= 2) {
                        const res = await fetch(`/mention/users?query=${text}`);
                        const users = await res.json();
                        cb(users.map(user => ({ key: user.username, value: user.username })));
                    } else {
                        cb([]);
                    }
                },
                selectTemplate: function (item) {
                    return `@${item.original.key}`;
                }
            });

            const commentInput = document.getElementById('caption');
            tribute.attach(caption);
        });
    </script>
    @stack('scripts')
</body>

</html>
