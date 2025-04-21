<!-- Live Search Modal -->
<div class="modal fade" id="liveSearchModal" tabindex="-1" aria-labelledby="liveSearchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="liveSearchModalLabel">Live Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" id="liveSearchInput" class="form-control" placeholder="Search...">
                </div>
                <ul class="list-group" id="searchResults">
                    <!-- Search results will be dynamically populated here -->
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('liveSearchInput').addEventListener('input', function () {
        const query = this.value.trim();
        const resultsContainer = document.getElementById('searchResults');
        resultsContainer.innerHTML = '';

        if (query.length > 0) {
            // Simulate fetching search results
            const results = ['Result 1', 'Result 2', 'Result 3'].filter(item =>
                item.toLowerCase().includes(query.toLowerCase())
            );

            if (results.length > 0) {
                results.forEach(result => {
                    const listItem = document.createElement('li');
                    listItem.className = 'list-group-item';
                    listItem.textContent = result;
                    resultsContainer.appendChild(listItem);
                });
            } else {
                const noResults = document.createElement('li');
                noResults.className = 'list-group-item text-muted';
                noResults.textContent = 'No results found';
                resultsContainer.appendChild(noResults);
            }
        }
    });
</script>
