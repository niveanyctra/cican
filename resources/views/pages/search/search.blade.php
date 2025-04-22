<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Cari Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="text" id="liveSearchInput" class="form-control" placeholder="Cari berdasarkan nama atau username...">
                <div id="searchResults" class="mt-3"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('liveSearchInput');
    const resultsContainer = document.getElementById('searchResults');
    const csrfToken = '{{ csrf_token() }}';
    const authUserId = {{ auth()->id() }};

    searchInput.addEventListener('input', function () {
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
                        const avatar = user.avatar ? `/storage/${user.avatar}` : '{{ asset('default-image.jpg') }}';
                        html += `
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="${avatar}" class="rounded-circle me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="${user.name}">
                                    <div>
                                        <strong>${user.name}</strong><br>
                                        <small>@${user.username}</small><br>
                                        <small class="text-muted">${user.bio ?? ''}</small>
                                    </div>
                                </div>
                                ${user.id !== authUserId ? `
                                <button class="btn btn-sm ${user.is_followed ? 'btn-secondary' : 'btn-primary'} follow-toggle-btn"
                                    data-user-id="${user.id}">
                                    ${user.is_followed ? 'Unfollow' : 'Follow'}
                                </button>
                                ` : ''}
                            </div>
                        `;
                    });
                }

                resultsContainer.innerHTML = html;
                document.querySelectorAll('.follow-toggle-btn').forEach(button => {
                    button.addEventListener('click', function () {
                        const userId = this.dataset.userId;
                        fetch(`/follow/follow-toggle/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.status === 'followed') {
                                this.classList.remove('btn-primary');
                                this.classList.add('btn-secondary');
                                this.textContent = 'Unfollow';
                            } else if (data.status === 'unfollowed') {
                                this.classList.remove('btn-secondary');
                                this.classList.add('btn-primary');
                                this.textContent = 'Follow';
                            }
                        })
                        .catch(err => {
                            console.error('Follow/Unfollow error:', err);
                        });
                    });
                });
            })
            .catch(err => {
                console.error('Error saat fetch:', err);
                resultsContainer.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            });
    });
});
</script>
@endpush
