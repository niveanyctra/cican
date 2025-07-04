<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="container">
        <!-- Login Form -->
        <div class="form-box login">
            <form action="{{ route('login.process') }}" method="post">
                @csrf
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" name="username" id="username"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan username"
                        value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="forgot-link">
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit" class="btn">Login</button>
                {{-- <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class="bx bxl-google"></i></a>
                    <a href="#"><i class="bx bxl-facebook"></i></a>
                    <a href="#"><i class="bx bxl-github"></i></a>
                    <a href="#"><i class="bx bxl-linkedin"></i></a>
                </div> --}}
            </form>
        </div>

        <!-- Register Form -->
        <div class="form-box register">
            <form action="{{ route('register.process') }}" method="post">
                @csrf
                <h1>Registration</h1>
                <div class="input-box">
                    <input type="text" name="username" id="username"
                        class="form-control @error('username') is-invalid @enderror" placeholder="Masukkan username"
                        value="{{ old('username') }}" required>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password"
                        class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan password"
                        required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-lock-alt"></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" id="email"
                        class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan email"
                        value="{{ old('email') }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-user"></i>
                </div>
                <div class="input-box">
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan nama lengkap"
                        value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <i class="bx bxs-user"></i>
                </div>
                {{-- <div class="input-box">
                    <input type="email" placeholder="Email" required />
                    <i class="bx bxs-envelope"></i>
                </div> --}}
                <button type="submit" class="btn">Register</button>
                {{-- <p>or register with social platforms</p>
                <div class="social-icons">
                    <a href="#"><i class="bx bxl-google"></i></a>
                    <a href="#"><i class="bx bxl-facebook"></i></a>
                </div> --}}
            </form>
        </div>

        <!-- Toggle Panel -->
        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/login.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
