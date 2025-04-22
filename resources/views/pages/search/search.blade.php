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
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    const resultsContainer = document.getElementById('searchResults');
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
                        const avatar = user.avatar ? `/storage/${user.avatar}` : '/images/default-avatar.png';
                        html += `
                            <div class="d-flex align-items-center mb-3">
                                <img src="${avatar}" class="rounded-circle me-3" style="object-fit: cover; width: 40px; height: 40px;" alt="${user.name}">
                                <div>
                                    <strong>${user.name}</strong><br>
                                    <small>@${user.username}</small><br>
                                    <small class="text-muted">${user.bio ?? ''}</small>
                                </div>
                            </div>
                        `;
                    });
                }
                resultsContainer.innerHTML = html;
            })
            .catch(err => {
                console.error('Error saat fetch:', err);
                resultsContainer.innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
            });
    });
});
</script>
@endpush
