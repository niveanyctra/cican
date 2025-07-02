<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://unpkg.com/tributejs@5.1.3/dist/tribute.css">

    <style>
        .profile img{
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
            </main>
        </div>
    @else
        @include('components.navbar')
        <main class="flex-1 mx-64 p-8">
            @yield('content')
        </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://unpkg.com/tributejs@5.1.3/dist/tribute.js"></script>
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

            const commentInput = document.getElementById('comment-input');
            tribute.attach(commentInput);
        });
        </script>
    @stack('scripts')
</body>

</html>
