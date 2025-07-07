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
        /* .sidebar {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 270px;
            height: 100%;
            border-right: 3px solid;
            padding: 50px 0 0 30px;
        }

        .sidebar nav {
            margin-top: 30px;
        }

        .sidebar nav li a {
            text-decoration: none;
            color: black;
            font-size: 20px;
            font-weight: bold;
        }

        main {
            margin: 50px 50px 0 350px;
        } */
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

    <script src="../../js/login.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    </script>
    <script src="https://unpkg.com/tributejs@5.1.3/dist/tribute.js"></script>
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
