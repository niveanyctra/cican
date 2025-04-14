<div class="sidebar">
    <a href="{{ route('home') }}" class="navbar-brand fw-bold fs-3">Gallery 15</a>
    <nav>
        <li class="nav-link">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
        </li>
        @auth
            <li class="nav-link">
                <a href="{{ route('user.show', Auth::user()->username) }}"><i class="fa-solid fa-user"></i> Profil</a>
            </li>
            <li class="nav-link">
                <a href="{{ route('logout') }}" class="text-danger"><i class="fa-solid fa-right-from-bracket"></i>
                    Logout</a>
            </li>
        @else
            <li class="nav-link">
                <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Masuk</a>
            </li>
            <li class="nav-link">
                <a href="{{ route('register') }}"><i class="fa-solid fa-pen"></i> Register</a>
            </li>
        @endauth
    </nav>
</div>
