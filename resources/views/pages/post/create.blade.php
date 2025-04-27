<!-- Modal Create Post -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Caption -->
                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption</label>
                        <textarea name="caption" id="caption" class="form-control" rows="3" placeholder="Write a caption...">{{ old('caption') }}</textarea>
                        @error('caption')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Media Upload -->
                    <div class="mb-3">
                        <label for="media" class="form-label">Upload Photo/Video</label>
                        <input type="file" name="media[]" id="media"
                            class="form-control @error('media') is-invalid @enderror" multiple accept="image/*,video/*">
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Collaboration -->
                    <div class="mb-3">
                        <label for="searchUsers" class="form-label">Cari Collaborators</label>
                        <input type="text" id="searchUsers" class="form-control"
                            placeholder="Cari nama atau username">

                        <!-- Daftar Collaborators yang Dipilih -->
                        <div id="selectedCollaborators" class="mt-3"></div>

                        <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                        <input type="hidden" name="collaborators" id="collaboratorsInput">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchUsers');
            const resultsContainer = document.createElement('div'); // Wadah hasil pencarian
            searchInput.parentNode.appendChild(resultsContainer);
            const selectedCollaboratorsDiv = document.getElementById('selectedCollaborators');
            const collaboratorsInput = document.getElementById('collaboratorsInput');
            let selectedUsers = []; // Array untuk menyimpan ID collaborator yang dipilih

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    resultsContainer.innerHTML = '';
                    return;
                }

                fetch(`{{ route('search.user') }}?query=${encodeURIComponent(query)}`)
                    .then(res => res.json())
                    .then(data => {
                        let html = '';

                        if (data.length === 0) {
                            html = '<p class="text-muted">Tidak ditemukan.</p>';
                        } else {
                            data.forEach(user => {
                                // Jangan tampilkan user yang sudah dipilih atau user yang sedang login
                                if (!selectedUsers.includes(user.id) && user.id !==
                                    {{ auth()->id() }}) {
                                    const avatar = user.avatar ? `/storage/${user.avatar}` :
                                        '{{ asset('default-image.jpg') }}';
                                    html += `
                                <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" onclick="selectUser(${user.id}, '${user.name}', '${user.username}')">
                                    <div class="d-flex align-items-center">
                                        <img src="${avatar}" class="rounded-circle me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="${user.name}">
                                        <div>
                                            <strong>${user.name}</strong><br>
                                            <small>@${user.username}</small><br>
                                        </div>
                                    </div>
                                </div>
                            `;
                                }
                            });
                        }
                        resultsContainer.innerHTML = html;
                    })
                    .catch(err => {
                        console.error('Error saat fetch:', err);
                        resultsContainer.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
                    });
            });

            // Fungsi untuk menangani pemilihan user
            window.selectUser = function(userId, name, username) {
                // Tambahkan user ke array jika belum ada
                if (!selectedUsers.includes(userId)) {
                    selectedUsers.push(userId);

                    // Tampilkan user yang dipilih
                    const displayValue = `${name} (@${username})`;
                    const collaboratorDiv = document.createElement('div');
                    collaboratorDiv.className = 'd-flex align-items-center mb-2';
                    collaboratorDiv.innerHTML = `
                <span class="me-2">${displayValue}</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeUser(${userId})">Hapus</button>
            `;
                    selectedCollaboratorsDiv.appendChild(collaboratorDiv);

                    // Perbarui nilai input hidden
                    collaboratorsInput.value = selectedUsers.join(',');

                    // Kosongkan hasil pencarian
                    resultsContainer.innerHTML = '';
                    searchInput.value = ''; // Reset input pencarian
                }
            };

            // Fungsi untuk menghapus user dari daftar
            window.removeUser = function(userId) {
                // Hapus user dari array
                selectedUsers = selectedUsers.filter(id => id !== userId);

                // Hapus elemen DOM yang sesuai
                const collaboratorDivs = selectedCollaboratorsDiv.querySelectorAll('div');
                collaboratorDivs.forEach(div => {
                    const button = div.querySelector('button');
                    if (button && button.onclick.toString().includes(`removeUser(${userId})`)) {
                        div.remove();
                    }
                });

                // Perbarui nilai input hidden
                collaboratorsInput.value = selectedUsers.join(',');
            };
        });
    </script>
@endpush
