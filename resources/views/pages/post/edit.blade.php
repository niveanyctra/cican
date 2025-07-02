@if (isset($posts))
    @foreach ($posts as $post)
        <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostModalLabel">Edit Postingan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <!-- Caption -->
                            <div class="mb-3">
                                <label for="caption{{ $post->id }}" class="form-label">Caption</label>
                                <textarea name="caption" id="caption{{ $post->id }}" class="form-control @error('caption') is-invalid @enderror"
                                    rows="3">{{ old('caption', $post->caption) }}</textarea>
                                @error('caption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Collaboration -->
                            <div class="mb-3">
                                <label for="searchUsersEdit" class="form-label">Cari Collaborators</label>
                                <input type="text" id="searchUsersEdit" class="form-control"
                                    placeholder="Cari nama atau username">

                                <!-- Daftar Collaborators yang Dipilih -->
                                <div id="selectedCollaboratorsEdit" class="mt-3">
                                    @foreach ($post->collaborators as $collaborator)
                                        <div class="d-flex align-items-center mb-2"
                                            data-user-id="{{ $collaborator->id }}">
                                            <span class="me-2">{{ $collaborator->name }}
                                                {{ '@' . $collaborator->username }}</span>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="removeUser({{ $collaborator->id }})">Hapus</button>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                                <input type="hidden" name="collaborators" id="collaboratorsInputEdit"
                                    value="{{ $post->collaborators->pluck('id')->join(',') }}">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPostModalLabel">Edit Postingan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body">
                        <!-- Caption -->
                        <div class="mb-3">
                            <label for="caption{{ $post->id }}" class="form-label">Caption</label>
                            <textarea name="caption" id="caption{{ $post->id }}" class="form-control @error('caption') is-invalid @enderror"
                                rows="3">{{ old('caption', $post->caption) }}</textarea>
                            @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Collaboration -->
                        <div class="mb-3">
                            <label for="searchUsersEdit" class="form-label">Cari Collaborators</label>
                            <input type="text" id="searchUsersEdit" class="form-control"
                                placeholder="Cari nama atau username">

                            <!-- Daftar Collaborators yang Dipilih -->
                            <div id="selectedCollaboratorsEdit" class="mt-3">
                                @foreach ($post->collaborators as $collaborator)
                                    <div class="d-flex align-items-center mb-2" data-user-id="{{ $collaborator->id }}">
                                        <span class="me-2">{{ $collaborator->name }}
                                            {{ '@' . $collaborator->username }}</span>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeUser({{ $collaborator->id }})">Hapus</button>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                            <input type="hidden" name="collaborators" id="collaboratorsInputEdit"
                                value="{{ $post->collaborators->pluck('id')->join(',') }}">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInputEdit = document.getElementById('searchUsersEdit');
            const resultsContainerEdit = document.createElement('div'); // Wadah hasil pencarian
            searchInputEdit.parentNode.appendChild(resultsContainerEdit);
            const selectedCollaboratorsDivEdit = document.getElementById('selectedCollaboratorsEdit');
            const collaboratorsInputEdit = document.getElementById('collaboratorsInputEdit');
            let selectedUsersEdit = collaboratorsInputEdit.value.split(',').filter(id => id).map(
            Number); // Array untuk menyimpan ID collaborator yang dipilih

            searchInputEdit.addEventListener('input', function() {
                const query = this.value.trim();

                if (query.length < 2) {
                    resultsContainerEdit.innerHTML = '';
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
                                if (!selectedUsersEdit.includes(user.id) && user.id !==
                                    {{ auth()->id() }}) {
                                    const avatar = user.avatar ? `/storage/${user.avatar}` :
                                        '{{ asset('default-image.jpg') }}';
                                    html += `
                                <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer" onclick="selectUserEdit(${user.id}, '${user.name}', '${user.username}')">
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
                        resultsContainerEdit.innerHTML = html;
                    })
                    .catch(err => {
                        console.error('Error saat fetch:', err);
                        resultsContainerEdit.innerHTML =
                        '<p class="text-danger">Gagal memuat data.</p>';
                    });
            });

            // Fungsi untuk menangani pemilihan user
            window.selectUserEdit = function(userId, name, username) {
                // Tambahkan user ke array jika belum ada
                if (!selectedUsersEdit.includes(userId)) {
                    selectedUsersEdit.push(userId);

                    // Tampilkan user yang dipilih
                    const displayValueEdit = `${name} (@${username})`;
                    const collaboratorDivEdit = document.createElement('div');
                    collaboratorDivEdit.className = 'd-flex align-items-center mb-2';
                    collaboratorDivEdit.innerHTML = `
                <span class="me-2">${displayValueEdit}</span>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeUser(${userId})">Hapus</button>
            `;
                    selectedCollaboratorsDivEdit.appendChild(collaboratorDivEdit);

                    // Perbarui nilai input hidden
                    collaboratorsInputEdit.value = selectedUsersEdit.join(',');

                    // Kosongkan hasil pencarian
                    resultsContainerEdit.innerHTML = '';
                    searchInputEdit.value = ''; // Reset input pencarian
                }
            };

            // Fungsi untuk menghapus user dari daftar
            window.removeUser = function(userId) {
                // Hapus user dari array
                selectedUsersEdit = selectedUsersEdit.filter(id => id !== userId);

                // Hapus elemen DOM yang sesuai
                const collaboratorDivsEdit = selectedCollaboratorsDivEdit.querySelectorAll('div');
                collaboratorDivsEdit.forEach(div => {
                    const button = div.querySelector('button');
                    if (button && button.onclick.toString().includes(`removeUser(${userId})`)) {
                        div.remove();
                    }
                });

                // Perbarui nilai input hidden
                collaboratorsInputEdit.value = selectedUsersEdit.join(',');
            };
        });
    </script>
@endpush
