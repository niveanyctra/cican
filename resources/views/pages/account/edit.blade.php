<form action="{{ route('account.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <!-- Nama Lengkap -->
    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
            value="{{ old('name', $user->name) }}" required>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Username -->
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" name="username" id="username"
            class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}"
            required>
        @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Email -->
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Bio -->
    <div class="mb-3">
        <label for="bio" class="form-label">Bio</label>
        <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror" rows="3">{{ old('bio', $user->bio) }}</textarea>
        @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <!-- Avatar -->
    <div class="mb-3">
        <label for="avatar" class="form-label">Avatar</label>
        <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror">
        @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Unggah gambar baru untuk memperbarui avatar.</small>
    </div>

    <!-- Tombol Simpan -->
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>
