<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- <style>
        .sidebar {
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
        }
    </style> --}}
    @stack('styles')
</head>

<body class="flex bg-gray-100 min-h-screen">
    @include('components.sidebar')
    <main class="ml-64 p-8 w-full">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
