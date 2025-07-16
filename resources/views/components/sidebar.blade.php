<aside class="w-64 p-6 fixed h-screen navigasi">
    <div class="flex items-center justify-center mb-8">
        <p style="font-family: 'QurovaDEMO', sans-serif; font-size: 54px; color: white">Narsée</p>
    </div>
    <nav>
        <ul class="tombol">
            <li class="mb-1">
                <a href="{{ route('home') }}" class="flex items-center space-x-3 nav-btn {{ request()->routeIs('home') ? 'active' : '' }}">
                    <img src="{{ asset('assets/icons/home-icon.svg') }}" alt="">
                    <span>Home</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('textee') }}" class="flex items-center space-x-3 nav-btn {{ request()->routeIs('textee') ? 'active' : '' }}">
                    <img src="{{ asset('assets/icons/textee-icon.svg') }}" alt="">
                    <span>Textée</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="{{ route('snapee') }}" class="flex items-center space-x-3 nav-btn {{ request()->routeIs('snapee') ? 'active' : '' }}">
                    <img src="{{ asset('assets/icons/snapee-icon.svg') }}" alt="">
                    <span>Snapée</span>
                </a>
            </li>
            <li class="mb-1">
                <button type="button" class="flex items-center space-x-3 w-full nav-btn" data-bs-toggle="modal"
                    data-bs-target="#searchModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Search</span>
                </button>
            </li>
            <li class="mb-1">
                <button type="button" class="flex items-center space-x-3 w-full nav-btn" data-bs-toggle="modal"
                    data-bs-target="#createPostModal">
                    <img src="{{ asset('assets/icons/create-icon.svg') }}" alt="">
                    <span>Create Post</span>
                </button>
            </li>
            <li class="mb-1">
                <a href="{{ route('user.show', Auth::user()->username) }}" class="flex items-center space-x-3 nav-btn {{ request()->is('user/'.Auth::user()->username) ? 'active' : '' }}">
                    <img src="{{ asset('assets/icons/profile-icon.svg') }}" alt="">
                    <span>Profile</span>
                </a>
            </li>
            <li class="mb-1">
                <a href="/logout" class="flex items-center space-x-3 nav-btn logout-btn">
                    <img src="{{ asset('assets/icons/logout-icon.svg') }}" alt="">
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
