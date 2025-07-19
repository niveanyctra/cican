@extends('layouts.app')
@section('content')
    <form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="d-flex w-100 justify-content-center mb-3">
            <img src="{{ Storage::url($user->avatar ?? asset('default-image.jpg')) }}" alt="Avatar" class="rounded-3"
                style="object-fit: cover; width: 200px; height: 200px;">
        </div>
        <div id="updateTrigger" class="d-flex w-100 justify-content-center gap-3" style="cursor: pointer;">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30" fill="none">
                <path
                    d="M29.0655 3.63626C29.4316 4.00238 29.4316 4.59597 29.0655 4.96209L27.1101 6.91749L23.3601 3.16749L25.3155 1.21209C25.6816 0.845971 26.2752 0.845971 26.6413 1.21209L29.0655 3.63626Z"
                    fill="black" />
                <path
                    d="M25.7843 8.24332L22.0343 4.49332L9.25989 17.2677C9.15698 17.3706 9.07944 17.4961 9.03342 17.6341L7.52487 22.1598C7.40272 22.5262 7.75134 22.8749 8.11779 22.7527L12.6434 21.2442C12.7815 21.1981 12.907 21.1206 13.0099 21.0177L25.7843 8.24332Z"
                    fill="black" />
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M1.875 25.3125C1.875 26.8658 3.1342 28.125 4.6875 28.125H25.3125C26.8658 28.125 28.125 26.8658 28.125 25.3125V14.0625C28.125 13.5447 27.7053 13.125 27.1875 13.125C26.6697 13.125 26.25 13.5447 26.25 14.0625V25.3125C26.25 25.8303 25.8303 26.25 25.3125 26.25H4.6875C4.16973 26.25 3.75 25.8303 3.75 25.3125V4.6875C3.75 4.16973 4.16973 3.75 4.6875 3.75H16.875C17.3928 3.75 17.8125 3.33027 17.8125 2.8125C17.8125 2.29473 17.3928 1.875 16.875 1.875H4.6875C3.1342 1.875 1.875 3.1342 1.875 4.6875V25.3125Z"
                    fill="black" />
            </svg>
            <p style="font-size: 25px">Update</p>
        </div>

        <!-- Avatar -->
        <div id="avatarInputContainer" class="mb-1" style="display: none;">
            <label for="avatar" class="form-label" style="font-size: 25px">Avatar</label>
            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror"
                style="font-size: 25px" accept="image/*">
            @error('avatar')
                <div class="invalid-feedback" style="font-size: 25px">{{ $message }}</div>
            @enderror
            <small class="text-muted" style="font-size: 25px">Unggah gambar baru untuk memperbarui avatar.</small>
        </div>

        <!-- Nama Lengkap -->
        <div class="mb-1">
            <label for="name" class="form-label" style="font-size: 25px">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required style="font-size: 25px">
            @error('name')
                <div class="invalid-feedback" style="font-size: 25px">{{ $message }}</div>
            @enderror
        </div>

        <!-- Username -->
        <div class="mb-1">
            <label for="username" class="form-label" style="font-size: 25px">Username</label>
            <input type="text" name="username" id="username"
                class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}"
                required style="font-size: 25px">
            @error('username')
                <div class="invalid-feedback" style="font-size: 25px">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-1">
            <label for="email" class="form-label" style="font-size: 25px">Email</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required style="font-size: 25px">
            @error('email')
                <div class="invalid-feedback" style="font-size: 25px">{{ $message }}</div>
            @enderror
        </div>

        <!-- Bio -->
        <div class="mb-1">
            <label for="bio" class="form-label" style="font-size: 25px">Bio</label>
            <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="3"
                style="font-size: 25px">{{ old('bio', $user->bio) }}</textarea>
            @error('bio')
                <div class="invalid-feedback" style="font-size: 25px">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-end mt-3 gap-3">
            <a href="{{ route('account.destroy') }}" class="btn btn-danger"
            onclick="return confirm('Apakah kamu yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')"
            style="font-size: 25px">
            Delete Account
        </a>
        <button type="submit" class="btn btn-primary" style="font-size: 25px">Save Changes</button>
        </div>
    </form>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const updateTrigger = document.getElementById('updateTrigger');
                const avatarContainer = document.getElementById('avatarInputContainer');

                updateTrigger.addEventListener('click', function() {
                    // Toggle tampilan container avatar
                    if (avatarContainer.style.display === 'none') {
                        avatarContainer.style.display = 'block';
                    } else {
                        avatarContainer.style.display = 'none';
                    }
                });
            });
        </script>
    @endpush
@endsection
