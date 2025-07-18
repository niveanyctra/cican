@if (isset($posts))
    @foreach ($posts as $post)
        <div class="modal fade" id="editPostModal{{ $post->id }}" tabindex="-1" aria-labelledby="editPostModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex w-100 justify-content-center pt-2">
                            <p style="font-size: 50px; line-height: 10px;">Edit Posts</p>
                        </div>
                    </div>
                    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="modal-body">
                            <div class="d-flex">
                                <!-- Media (Gambar/Video) -->
                                <div id="modalMediaEditCarousel-{{ $post->id }}" class="carousel slide mt-2">
                                    <div class="carousel-inner show-post items-center" style="background: black">
                                        <!-- Indikator Carousel -->
                                        @if ($post->media->count() > 1)
                                            <div class="carousel-indicators">
                                                @foreach ($post->media as $index => $media)
                                                    <button type="button"
                                                        data-bs-target="#modalMediaEditCarousel-{{ $post->id }}"
                                                        data-bs-slide-to="{{ $index }}"
                                                        class="{{ $index === 0 ? 'active' : '' }}"
                                                        aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-label="Slide {{ $index + 1 }}"></button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Item Media -->
                                        @foreach ($post->media as $index => $media)
                                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                                <div class="d-flex justify-content-center align-items-center h-100">
                                                    @if ($media->type === 'image')
                                                        <img src="{{ Storage::url($media->file_url) }}" alt="Post Image"
                                                            class="img-fluid"
                                                            style="max-height: 70vh; width: 100%; object-fit: contain;">
                                                    @elseif ($media->type === 'video')
                                                        <video controls class="w-100"
                                                            style="max-height: 70vh; object-fit: contain;">
                                                            <source src="{{ Storage::url($media->file_url) }}"
                                                                type="video/mp4">
                                                            Browser Anda tidak mendukung elemen video.
                                                        </video>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Tombol Navigasi Carousel -->
                                    @if ($post->media->count() > 1)
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#modalMediaEditCarousel-{{ $post->id }}"
                                            data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#modalMediaEditCarousel-{{ $post->id }}"
                                            data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    @endif
                                </div>
                                <div class="w-100 ms-3">
                                    <!-- Caption -->
                                    <div class="mb-3">
                                        <label for="caption{{ $post->id }}" class="form-label mb-0"
                                            style="font-size: 35px">Caption</label>
                                        <textarea name="caption" id="caption{{ $post->id }}" class="form-control @error('caption') is-invalid @enderror"
                                            rows="3" style="font-size: 25px; border: 0;">{{ old('caption', $post->caption) }}</textarea>
                                        @error('caption')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Collaboration -->
                                    <div class="mb-3">
                                        <label for="searchUsersEdit{{ $post->id }}" class="form-label"
                                            style="font-size: 25px">Cari
                                            Collaborators</label>
                                        <input type="text" id="searchUsersEdit{{ $post->id }}"
                                            class="form-control" placeholder="Cari nama atau username"
                                            style="font-size: 25px">

                                        <!-- Daftar Collaborators yang Dipilih -->
                                        <div id="selectedCollaboratorsEdit{{ $post->id }}" class="mt-3">
                                            @foreach ($post->collaborators as $collaborator)
                                                <div class="d-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded"
                                                    data-user-id="{{ $collaborator->id }}">
                                                    <span style="font-size: 25px;">{{ $collaborator->name }}
                                                        ({{ '@' . $collaborator->username }})
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="removeUserEdit{{ $post->id }}({{ $collaborator->id }})"
                                                        style="font-size: 25px;">
                                                        X Hapus
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>


                                        <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                                        <input type="hidden" name="collaborators"
                                            id="collaboratorsInputEdit{{ $post->id }}"
                                            value="{{ $post->collaborators->pluck('id')->join(',') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="reset" class="btn btn-danger" data-bs-dismiss="modal" style="font-size: 25px;">Cancel</button>
                            <button type="submit" class="btn btn-info" style="font-size: 25px;">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Object untuk menyimpan state setiap modal
            const modalStates = {};

            // Fungsi untuk menginisialisasi edit modal untuk setiap post
            function initializeEditModal(postId) {
                // Cek apakah modal sudah diinisialisasi
                if (modalStates[postId]) {
                    return;
                }

                const searchInputEdit = document.getElementById(`searchUsersEdit${postId}`);
                const selectedCollaboratorsDivEdit = document.getElementById(`selectedCollaboratorsEdit${postId}`);
                const collaboratorsInputEdit = document.getElementById(`collaboratorsInputEdit${postId}`);

                // Cek apakah search results container sudah ada
                let resultsContainerEdit = searchInputEdit.parentNode.querySelector('.search-results');
                if (!resultsContainerEdit) {
                    resultsContainerEdit = document.createElement('div');
                    resultsContainerEdit.className = 'search-results mt-2';
                    searchInputEdit.parentNode.appendChild(resultsContainerEdit);
                }

                // Inisialisasi state untuk modal ini
                modalStates[postId] = {
                    selectedUsers: [],
                    searchTimeout: null,
                    initialized: true
                };

                // Fungsi untuk mengupdate selectedUsers dari DOM yang ada
                function updateSelectedUsersFromDOM() {
                    const existingCollaborators = selectedCollaboratorsDivEdit.querySelectorAll('[data-user-id]');
                    modalStates[postId].selectedUsers = [];
                    existingCollaborators.forEach(el => {
                        const userId = parseInt(el.getAttribute('data-user-id'));
                        if (userId && !modalStates[postId].selectedUsers.includes(userId)) {
                            modalStates[postId].selectedUsers.push(userId);
                        }
                    });
                    collaboratorsInputEdit.value = modalStates[postId].selectedUsers.join(',');
                }

                // Update array saat modal dibuka
                const modal = document.getElementById(`editPostModal${postId}`);
                modal.addEventListener('shown.bs.modal', function() {
                    updateSelectedUsersFromDOM();
                    // Clear search results saat modal dibuka
                    resultsContainerEdit.innerHTML = '';
                    searchInputEdit.value = '';
                });

                // Hapus event listener lama jika ada
                const newSearchInput = searchInputEdit.cloneNode(true);
                searchInputEdit.parentNode.replaceChild(newSearchInput, searchInputEdit);

                // Tambahkan event listener baru
                newSearchInput.addEventListener('input', function() {
                    const query = this.value.trim();

                    // Clear timeout sebelumnya
                    if (modalStates[postId].searchTimeout) {
                        clearTimeout(modalStates[postId].searchTimeout);
                    }

                    if (query.length < 2) {
                        resultsContainerEdit.innerHTML = '';
                        return;
                    }

                    // Debounce search untuk menghindari request berlebihan
                    modalStates[postId].searchTimeout = setTimeout(() => {
                        fetch(`{{ route('search.user') }}?query=${encodeURIComponent(query)}`)
                            .then(res => res.json())
                            .then(data => {
                                let html = '';

                                if (data.length === 0) {
                                    html =
                                        '<p class="text-muted" style="font-size: 25px">Tidak ditemukan.</p>';
                                } else {
                                    data.forEach(user => {
                                        const userId = parseInt(user.id);
                                        const currentUserId = parseInt(
                                            {{ auth()->id() }});

                                        // Jangan tampilkan user yang sudah dipilih atau user yang sedang login
                                        if (!modalStates[postId].selectedUsers.includes(
                                                userId) && userId !== currentUserId) {
                                            const avatar = user.avatar ?
                                                `/storage/${user.avatar}` :
                                                '{{ asset('default-image.jpg') }}';
                                            html += `
                                        <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer border-bottom pb-2" onclick="selectUserEdit${postId}(${user.id}, '${user.name}', '${user.username}')">
                                            <div class="d-flex align-items-center">
                                                <img src="${avatar}" class="rounded-circle me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="${user.name}">
                                                <div style="font-size: 25px; line-height: 10px;">
                                                    <p>${user.name}</p><br>
                                                    <p class="text-muted">@${user.username}</p>
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
                                    '<p class="text-danger" style="font-size: 25px;">Gagal memuat data.</p>';
                            });
                    }, 300);
                });

                // Fungsi untuk menangani pemilihan user (dinamis per post)
                window[`selectUserEdit${postId}`] = function(userId, name, username) {
                    const userIdNum = parseInt(userId);

                    // Tambahkan user ke array jika belum ada
                    if (!modalStates[postId].selectedUsers.includes(userIdNum)) {
                        modalStates[postId].selectedUsers.push(userIdNum);

                        // Tampilkan user yang dipilih
                        const displayValue = `${name} (@${username})`;
                        const collaboratorDiv = document.createElement('div');
                        collaboratorDiv.className =
                            'd-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded';
                        collaboratorDiv.setAttribute('data-user-id', userIdNum);
                        collaboratorDiv.innerHTML = `
                    <span style="font-size: 25px;">${displayValue}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUserEdit${postId}(${userIdNum})" style="font-size: 25px;">
                        X Hapus
                    </button>
                `;
                        selectedCollaboratorsDivEdit.appendChild(collaboratorDiv);

                        // Perbarui nilai input hidden
                        collaboratorsInputEdit.value = modalStates[postId].selectedUsers.join(',');

                        // Kosongkan hasil pencarian
                        resultsContainerEdit.innerHTML = '';
                        newSearchInput.value = '';
                    }
                };

                // Fungsi untuk menghapus user dari daftar (dinamis per post)
                window[`removeUserEdit${postId}`] = function(userId) {
                    const userIdNum = parseInt(userId);

                    // Hapus user dari array
                    modalStates[postId].selectedUsers = modalStates[postId].selectedUsers.filter(id => id !==
                        userIdNum);

                    // Hapus elemen DOM yang sesuai
                    const collaboratorDiv = selectedCollaboratorsDivEdit.querySelector(
                        `[data-user-id="${userIdNum}"]`);
                    if (collaboratorDiv) {
                        collaboratorDiv.remove();
                    }

                    // Perbarui nilai input hidden
                    collaboratorsInputEdit.value = modalStates[postId].selectedUsers.join(',');
                };
            }

            // Inisialisasi untuk setiap post
            @if (isset($posts))
                @foreach ($posts as $post)
                    initializeEditModal({{ $post->id }});
                @endforeach
            @endif
        });
    </script>
@endpush
