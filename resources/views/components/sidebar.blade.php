<aside class="w-64 p-6 fixed h-screen navigasi">
    <div class="flex items-center justify-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Narsee</h1>
    </div>
    <nav>
        <ul class="space-y-4 tombol">
            <li class="nav-btn">
                <a href="{{ route('home') }}" class="flex items-center space-x-3">
                    <img src="{{asset('assets/home-icon.svg')}}" alt="">
                    <span>Home</span>
                </a>
            </li>
            <li class="nav-btn">
                <a href="{{ route('textee') }}" class="flex items-center space-x-3">
                    <img src="{{asset('assets/textee-icon.svg')}}" alt="">
                    <span>Textee</span>
                </a>
            </li>
            <li class="nav-btn">
                <a href="{{ route('snapee') }}" class="flex items-center space-x-3">
                    <img src="{{asset('assets/snapee-icon.svg')}}" alt="">
                    <span>Snapee</span>
                </a>
            </li>
            <li class="nav-btn">
                <button type="button" class="flex items-center space-x-3 w-full"
                    data-bs-toggle="modal" data-bs-target="#searchModal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span>Search</span>
            </li>
            {{-- @include('pages.search.search') --}}
            {{-- <li class="nav-btn">
                <a href="/notifications" class="flex items-center space-x-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span>Notifications</span>
                </a>
            </li> --}}
            <li class="nav-btn">
                <button type="button" class="flex items-center space-x-3 w-full"
                    data-bs-toggle="modal" data-bs-target="#createPostModal">
                    <a href="" class="flex items-center space-x-3">
                        <img src="{{asset('assets/create-icon.svg')}}" alt="" >
                        <span>Create Post</span>
                    </a>
                </button>
            </li>
            <li class="nav-btn">
                <a href="{{ route('user.show', Auth::user()->username) }}"
                    class="flex items-center space-x-3">
                    <img src="{{asset('assets/profile-icon.svg')}}" alt="">
                    <span>Profile</span>
                </a>
            </li>
            <li class="nav-btn">
                <a href="/logout" class="flex items-center space-x-3 text-red-500 hover:text-red-700">
                   <svg width="29" height="30" viewBox="0 0 29 30" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M5.95579 23.8388C7.64568 25.587 9.79872 26.7775 12.1427 27.2598C14.4866 27.7421 16.9162 27.4946 19.1241 26.5485C21.332 25.6024 23.2192 24.0002 24.5469 21.9446C25.8747 19.889 26.5833 17.4723 26.5833 15C26.5833 12.5277 25.8747 10.111 24.5469 8.05537C23.2192 5.99976 21.332 4.3976 19.1241 3.45151C16.9162 2.50541 14.4866 2.25787 12.1427 2.74018C9.79872 3.2225 7.64568 4.41301 5.9558 6.16116" stroke="#FF2627" stroke-width="2"/>
<path d="M18.125 15L18.9161 14.3883L19.3891 15L18.9161 15.6117L18.125 15ZM3.625 16C3.07271 16 2.625 15.5523 2.625 15C2.625 14.4477 3.07271 14 3.625 14V16ZM13.2917 8.75L14.0827 8.13825L18.9161 14.3883L18.125 15L17.3339 15.6117L12.5006 9.36175L13.2917 8.75ZM18.125 15L18.9161 15.6117L14.0827 21.8617L13.2917 21.25L12.5006 20.6383L17.3339 14.3883L18.125 15ZM18.125 15V16H3.625V15V14H18.125V15Z" fill="#FF2627"/>
</svg>

                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
