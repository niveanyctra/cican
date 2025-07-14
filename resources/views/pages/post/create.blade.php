<!-- Modal Create Post -->
<div class="modal fade" id="createPostModal" aria-hidden="true" aria-labelledby="createPostModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createPostModalLabel">Choose Your Post Type</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary" data-bs-target="#textPostModal" data-bs-toggle="modal">Textee</button>
                <button type="button" class="btn btn-primary" data-bs-target="#mediaPostModal" data-bs-toggle="modal">Snapee</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="textPostModal" tabindex="-1" aria-labelledby="textPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="textPostModalLabel">Create New Post</h5>
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

                    <!-- Collaboration -->
                    <div class="mb-3">
                        <label for="searchUsersText" class="form-label">Cari Collaborators</label>
                        <input type="text" id="searchUsersText" class="form-control"
                            placeholder="Cari nama atau username">

                        <!-- Daftar Collaborators yang Dipilih -->
                        <div id="selectedCollaboratorsText" class="mt-3"></div>

                        <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                        <input type="hidden" name="collaborators" id="collaboratorsInputCreateText">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#createPostModal"
                        data-bs-toggle="modal">Kembali</button>
                    <button type="reset" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="mediaPostModal" tabindex="-1" aria-labelledby="mediaPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaPostModalLabel">Create New Post</h5>
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
                        <label for="searchUsersMedia" class="form-label">Cari Collaborators</label>
                        <input type="text" id="searchUsersMedia" class="form-control"
                            placeholder="Cari nama atau username">

                        <!-- Daftar Collaborators yang Dipilih -->
                        <div id="selectedCollaboratorsMedia" class="mt-3"></div>

                        <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                        <input type="hidden" name="collaborators" id="collaboratorsInputCreateMedia">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-target="#createPostModal"
                        data-bs-toggle="modal">Kembali</button>
                    <button type="reset" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menginisialisasi pencarian collaborator
            function initializeCollaboratorSearch(searchInputId, selectedCollaboratorsId, collaboratorsInputId, modalType) {
                const searchInput = document.getElementById(searchInputId);
                const resultsContainer = document.createElement('div');
                resultsContainer.className = 'search-results mt-2';
                searchInput.parentNode.appendChild(resultsContainer);

                const selectedCollaboratorsDiv = document.getElementById(selectedCollaboratorsId);
                const collaboratorsInput = document.getElementById(collaboratorsInputId);
                let selectedUsers = [];

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
                                    if (!selectedUsers.includes(user.id) && user.id !== {{ auth()->id() }}) {
                                        const avatar = user.avatar ? `/storage/${user.avatar}` : '{{ asset('default-image.jpg') }}';
                                        html += `
                                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer border-bottom pb-2" onclick="selectUser${modalType}(${user.id}, '${user.name}', '${user.username}')">
                                                <div class="d-flex align-items-center">
                                                    <img src="${avatar}" class="rounded-circle me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="${user.name}">
                                                    <div>
                                                        <strong>${user.name}</strong><br>
                                                        <small class="text-muted">@${user.username}</small>
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
                window[`selectUser${modalType}`] = function(userId, name, username) {
                    // Tambahkan user ke array jika belum ada
                    if (!selectedUsers.includes(userId)) {
                        selectedUsers.push(userId);

                        // Tampilkan user yang dipilih
                        const displayValue = `${name} (@${username})`;
                        const collaboratorDiv = document.createElement('div');
                        collaboratorDiv.className = 'd-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded';
                        collaboratorDiv.setAttribute('data-user-id', userId);
                        collaboratorDiv.innerHTML = `
                            <span>${displayValue}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUser${modalType}(${userId})">
                                <i class="fas fa-times"></i> Hapus
                            </button>
                        `;
                        selectedCollaboratorsDiv.appendChild(collaboratorDiv);

                        // Perbarui nilai input hidden
                        collaboratorsInput.value = selectedUsers.join(',');

                        // Kosongkan hasil pencarian
                        resultsContainer.innerHTML = '';
                        searchInput.value = '';
                    }
                };

                // Fungsi untuk menghapus user dari daftar
                window[`removeUser${modalType}`] = function(userId) {
                    // Hapus user dari array
                    selectedUsers = selectedUsers.filter(id => id !== userId);

                    // Hapus elemen DOM yang sesuai
                    const collaboratorDiv = selectedCollaboratorsDiv.querySelector(`[data-user-id="${userId}"]`);
                    if (collaboratorDiv) {
                        collaboratorDiv.remove();
                    }

                    // Perbarui nilai input hidden
                    collaboratorsInput.value = selectedUsers.join(',');
                };

                // Reset ketika modal dibuka
                const modalElement = document.getElementById(searchInput.closest('.modal').id);
                modalElement.addEventListener('shown.bs.modal', function() {
                    selectedUsers = [];
                    selectedCollaboratorsDiv.innerHTML = '';
                    collaboratorsInput.value = '';
                    searchInput.value = '';
                    resultsContainer.innerHTML = '';
                });
            }

            // Inisialisasi untuk Text Post Modal
            initializeCollaboratorSearch('searchUsersText', 'selectedCollaboratorsText', 'collaboratorsInputCreateText', 'Text');

            // Inisialisasi untuk Media Post Modal
            initializeCollaboratorSearch('searchUsersMedia', 'selectedCollaboratorsMedia', 'collaboratorsInputCreateMedia', 'Media');
        });
    </script>
@endpush
