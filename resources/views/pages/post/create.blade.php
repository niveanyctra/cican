<!-- Modal Create Post -->
<div class="modal fade" id="createPostModal" aria-hidden="true" aria-labelledby="createPostModalLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-5" style="line-height: 30px">
                    <p style="font-size: 54px">
                        Choose your Post Type
                    </p>
                    <p style="font-size: 34px">
                        what are you going to post?
                    </p>
                </div>
                <div class="d-flex text-center w-100 justify-evenly">
                    <div data-bs-target="#textPostModal" data-bs-toggle="modal" style="cursor: pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="115" height="115" viewBox="0 0 135 135"
                            fill="none">
                            <path
                                d="M122.379 0C129.347 0.000233659 134.999 5.04133 134.999 11.2549V107.896C134.999 109.643 134.547 111.362 133.671 112.937L122.685 132.5C121.718 134.247 119.794 135.076 117.917 134.999H4.20703C1.8836 134.999 0 133.319 0 131.247C3.30919e-05 129.175 1.88362 127.495 4.20703 127.495H110.838L102.666 112.925C101.797 111.363 101.344 109.645 101.344 107.903V11.2549C101.344 5.04128 106.997 0.000150875 113.965 0H122.379ZM116.085 127.495H118.139C119.088 127.495 119.964 127.777 120.668 128.25L118.167 123.789L116.085 127.495ZM109.853 108.693C109.926 109.001 110.041 109.299 110.196 109.579L118.166 123.789L126.141 109.589C126.299 109.304 126.415 109.003 126.489 108.693H109.853ZM113.964 7.50293C111.643 7.50323 109.757 9.18526 109.757 11.2549V101.19H126.585V11.2549C126.585 9.18507 124.699 7.50293 122.378 7.50293H113.964Z"
                                fill="black" />
                        </svg>
                        <p style="font-size: 34px">
                            Textée
                        </p>
                    </div>
                    <div data-bs-target="#mediaPostModal" data-bs-toggle="modal" style="cursor: pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="129" height="106" viewBox="0 0 149 126"
                            fill="none">
                            <path
                                d="M98.1631 0C108.821 0 118.08 5.96639 122.772 14.7334H137.362C143.517 14.7334 148.5 19.7102 148.5 25.8486V99.9492C148.5 106.088 143.517 111.064 137.362 111.064H123.121L131.75 119.676C133.2 121.123 133.2 123.469 131.75 124.916C130.3 126.363 127.95 126.363 126.5 124.916L112.62 111.064H11.1377C4.98332 111.064 2.47749e-05 106.088 0 99.9492V25.8486C0.000143734 19.7102 4.98339 14.7334 11.1377 14.7334H73.5537C78.3302 5.79095 87.7118 0.000148348 98.1631 0ZM11.1367 22.1445C9.08435 22.1447 7.4248 23.8025 7.4248 25.8496V90.3848L51.7676 46.1387C53.507 44.4028 56.3251 44.4028 58.0645 46.1387L92.0371 80.043L103.739 68.3652C105.473 66.6348 108.267 66.6354 110.045 68.3662L131.867 90.1514C133.317 91.5985 133.316 93.9449 131.866 95.3916C130.416 96.8383 128.065 96.8378 126.615 95.3906L106.892 75.6992L97.2881 85.2832L115.697 103.655H137.362C139.415 103.655 141.074 101.997 141.074 99.9502V25.8496C141.074 23.8024 139.415 22.1447 137.362 22.1445H125.463C125.843 23.9761 126.044 25.8733 126.044 27.8174C126.044 43.1809 113.562 55.6348 98.1631 55.6348C85.0996 55.6346 73.8694 46.5976 70.9922 34.0537C70.5347 32.0591 71.7845 30.0718 73.7832 29.6152C75.7818 29.1588 77.773 30.4058 78.2305 32.4004C80.3391 41.5928 88.5799 48.2244 98.1631 48.2246C109.462 48.2246 118.619 39.088 118.619 27.8174C118.619 25.8489 118.34 23.9455 117.818 22.1445H11.1367ZM7.5166 100.771C7.89044 102.423 9.36739 103.655 11.1367 103.655H105.196L54.916 53.4756L7.5166 100.771ZM98.1631 7.41016C91.9551 7.41028 86.2629 10.1937 82.46 14.7334H113.861C110.109 10.2577 104.47 7.41016 98.1631 7.41016Z"
                                fill="black" />
                        </svg>
                        <p style="font-size: 34px">
                            Snapée
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="textPostModal" tabindex="-1" aria-labelledby="textPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center" style="line-height: 30px">
                        <p style="font-size: 54px">
                            Textée
                        </p>
                        <p style="font-size: 34px">
                            what are you going to post?
                        </p>
                    </div>

                    <div
                        style="border: 1px solid #000; padding: 20px; border-radius: 20px; margin: 50px; box-shadow: 2px 4px 4px 0px rgba(0, 0, 0, 0.25);">
                        <div class="mb-3 d-flex">
                            <img src="{{ Storage::url(Auth::user()->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                                style="object-fit: cover; width: 42px; height: 42px;" class="rounded-circle me-2">
                            <textarea name="caption" id="caption" class="form-control me-2" rows="3" placeholder="Write a caption..."
                                style="font-size: 25px; border: 0;">{{ old('caption') }}</textarea>
                            @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <button type="submit" style="height: 40px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                    viewBox="0 0 40 40" fill="none">
                                    <rect width="40" height="40" rx="7" fill="#24AFC1" />
                                    <path
                                        d="M31.9485 17.0012C33.2667 17.5562 33.3856 19.3791 32.1507 20.1006L14.3786 30.4837C13.0013 31.2888 11.3585 29.9628 11.8542 28.4465L14.0899 21.5916C14.1883 21.2897 14.5127 21.1248 14.8146 21.2233C15.1164 21.3218 15.2814 21.6461 15.183 21.948L12.9473 28.8042C12.7805 29.3154 13.3347 29.7619 13.799 29.4904L31.5711 19.1074C31.9874 18.8639 31.9473 18.2488 31.5028 18.0614L12.5344 10.0744C12.0371 9.86525 11.546 10.3807 11.7786 10.8676L15.6824 19.0395L29.187 18.1612C29.5036 18.1408 29.7768 18.3807 29.7977 18.6971C29.8183 19.014 29.578 19.288 29.2612 19.3086L15.3687 20.2128C15.3659 20.213 15.3631 20.2118 15.3603 20.212C15.3483 20.2126 15.3363 20.2103 15.3242 20.2102C15.2988 20.2099 15.2739 20.2103 15.2493 20.2067C15.234 20.2045 15.2193 20.1993 15.2043 20.1958C15.1094 20.1742 15.0228 20.1318 14.9517 20.0691C14.9375 20.0566 14.9254 20.042 14.9124 20.028C14.9001 20.0148 14.8869 20.0026 14.8759 19.9882C14.8687 19.9788 14.8633 19.9682 14.8566 19.9583C14.8412 19.9354 14.826 19.9123 14.8139 19.8871L14.8132 19.8864L10.7404 11.3637C10.0521 9.92269 11.5088 8.39571 12.9808 9.01489L31.9485 17.0012Z"
                                        fill="black" />
                                </svg>
                            </button>
                        </div>

                        <!-- Collaboration -->
                        <div class="mb-3">
                            <label for="searchUsersText" class="form-label" style="font-size: 25px">Cari
                                Collaborators</label>
                            <input type="text" id="searchUsersText" class="form-control"
                                placeholder="Cari nama atau username" style="font-size: 25px">

                            <!-- Daftar Collaborators yang Dipilih -->
                            <div id="selectedCollaboratorsText" class="mt-3"></div>

                            <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                            <input type="hidden" name="collaborators" id="collaboratorsInputCreateText">
                        </div>

                        <div class="d-flex justify-end w-100 gap-2">
                            <button type="button" class="btn btn-info" data-bs-target="#mediaPostModal"
                                data-bs-toggle="modal" style="font-size: 25px">Switch to Snapée</button>
                            <button type="reset" class="btn btn-danger" style="font-size: 25px">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="mediaPostModal" tabindex="-1" aria-labelledby="mediaPostModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="text-center" style="line-height: 30px">
                        <p style="font-size: 54px">
                            Textée
                        </p>
                        <p style="font-size: 34px">
                            what are you going to post?
                        </p>
                    </div>
                    <div
                        style="border: 1px solid #000; padding: 20px; border-radius: 20px; margin: 50px; box-shadow: 2px 4px 4px 0px rgba(0, 0, 0, 0.25);">
                        <div class="mb-3 d-flex">
                            <img src="{{ Storage::url(Auth::user()->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                                style="object-fit: cover; width: 42px; height: 42px;" class="rounded-circle me-2">

                            <div class="d-flex flex-column w-100 me-2">
                                <div class="mb-3">
                                    <input type="file" name="media[]" id="media"
                                        class="form-control @error('media') is-invalid @enderror" multiple
                                        accept="image/*,video/*" style="font-size: 25px; border: 0;">
                                    @error('media')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <textarea name="caption" id="caption" class="form-control" rows="3" placeholder="Write a caption..."
                                    style="font-size: 25px; border: 0;">{{ old('caption') }}</textarea>
                                @error('caption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" style="height: 40px">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                    viewBox="0 0 40 40" fill="none">
                                    <rect width="40" height="40" rx="7" fill="#24AFC1" />
                                    <path
                                        d="M31.9485 17.0012C33.2667 17.5562 33.3856 19.3791 32.1507 20.1006L14.3786 30.4837C13.0013 31.2888 11.3585 29.9628 11.8542 28.4465L14.0899 21.5916C14.1883 21.2897 14.5127 21.1248 14.8146 21.2233C15.1164 21.3218 15.2814 21.6461 15.183 21.948L12.9473 28.8042C12.7805 29.3154 13.3347 29.7619 13.799 29.4904L31.5711 19.1074C31.9874 18.8639 31.9473 18.2488 31.5028 18.0614L12.5344 10.0744C12.0371 9.86525 11.546 10.3807 11.7786 10.8676L15.6824 19.0395L29.187 18.1612C29.5036 18.1408 29.7768 18.3807 29.7977 18.6971C29.8183 19.014 29.578 19.288 29.2612 19.3086L15.3687 20.2128C15.3659 20.213 15.3631 20.2118 15.3603 20.212C15.3483 20.2126 15.3363 20.2103 15.3242 20.2102C15.2988 20.2099 15.2739 20.2103 15.2493 20.2067C15.234 20.2045 15.2193 20.1993 15.2043 20.1958C15.1094 20.1742 15.0228 20.1318 14.9517 20.0691C14.9375 20.0566 14.9254 20.042 14.9124 20.028C14.9001 20.0148 14.8869 20.0026 14.8759 19.9882C14.8687 19.9788 14.8633 19.9682 14.8566 19.9583C14.8412 19.9354 14.826 19.9123 14.8139 19.8871L14.8132 19.8864L10.7404 11.3637C10.0521 9.92269 11.5088 8.39571 12.9808 9.01489L31.9485 17.0012Z"
                                        fill="black" />
                                </svg>
                            </button>
                        </div>

                        <!-- Collaboration -->
                        <div class="mb-3">
                            <label for="searchUsersMedia" class="form-label" style="font-size: 25px">Cari
                                Collaborators</label>
                            <input type="text" id="searchUsersMedia" class="form-control"
                                placeholder="Cari nama atau username" style="font-size: 25px">

                            <!-- Daftar Collaborators yang Dipilih -->
                            <div id="selectedCollaboratorsMedia" class="mt-3"></div>

                            <!-- Input Hidden untuk Menyimpan ID Collaborators -->
                            <input type="hidden" name="collaborators" id="collaboratorsInputCreateMedia">
                        </div>

                        <div class="d-flex justify-end w-100 gap-2">
                            <button type="button" class="btn btn-info" data-bs-target="#textPostModal"
                                data-bs-toggle="modal" style="font-size: 25px">Switch to Textée</button>
                            <button type="reset" class="btn btn-danger" style="font-size: 25px">Cancel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk menginisialisasi pencarian collaborator
            function initializeCollaboratorSearch(searchInputId, selectedCollaboratorsId, collaboratorsInputId,
                modalType) {
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
                                html =
                                    '<p class="text-muted" style="font-size: 25px">Tidak ditemukan.</p>';
                            } else {
                                data.forEach(user => {
                                    // Jangan tampilkan user yang sudah dipilih atau user yang sedang login
                                    if (!selectedUsers.includes(user.id) && user.id !==
                                        {{ auth()->id() }}) {
                                        const avatar = user.avatar ? `/storage/${user.avatar}` :
                                            '{{ asset('default-image.jpg') }}';
                                        html += `
                                            <div class="d-flex justify-content-between align-items-center mb-3 cursor-pointer border-bottom pb-2" onclick="selectUser${modalType}(${user.id}, '${user.name}', '${user.username}')">
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
                            resultsContainer.innerHTML = html;
                        })
                        .catch(err => {
                            console.error('Error saat fetch:', err);
                            resultsContainer.innerHTML =
                                '<p class="text-danger" style="font-size: 25px;">Gagal memuat data.</p>';
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
                        collaboratorDiv.className =
                            'd-flex align-items-center justify-content-between mb-2 p-2 bg-light rounded';
                        collaboratorDiv.setAttribute('data-user-id', userId);
                        collaboratorDiv.innerHTML = `
                            <span style="font-size: 25px;">${displayValue}</span>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeUser${modalType}(${userId})" style="font-size: 25px;">
                                X Hapus
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
                    const collaboratorDiv = selectedCollaboratorsDiv.querySelector(
                        `[data-user-id="${userId}"]`);
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
            initializeCollaboratorSearch('searchUsersText', 'selectedCollaboratorsText',
                'collaboratorsInputCreateText', 'Text');

            // Inisialisasi untuk Media Post Modal
            initializeCollaboratorSearch('searchUsersMedia', 'selectedCollaboratorsMedia',
                'collaboratorsInputCreateMedia', 'Media');
        });
    </script>
@endpush
