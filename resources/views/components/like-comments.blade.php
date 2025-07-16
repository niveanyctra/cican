<div id="like-section-feed-{{ $post->id }}" class="my-2 ms-2 d-flex gap-3" style="cursor: pointer;">
    <!-- Like Button & Count -->
    <div class="d-flex align-items-center gap-2">
        <!-- Tombol Like dengan Ikon -->
        <button id="like-button-feed-{{ $post->id }}" onclick="toggleLike({{ $post->id }})"
            class="d-flex align-items-center p-0 bg-transparent border-0" style="font-size: 25px;">
            <i id="like-icon-feed-{{ $post->id }}"
                class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' }}"
                style="font-size: 25px;"></i>
        </button>
        <!-- Jumlah Like -->
        <div data-bs-toggle="modal" data-bs-target="#showWhoLikeModal{{ $post->id }}">
            <span id="like-count-feed-{{ $post->id }}" style="font-size: 26px;">{{ $post->likes->count() }}
                likes</span>
        </div>
    </div>

    <!-- Comment Section -->
    <div class="d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">
        <!-- Ikon Komentar -->
        <i class="fa-regular fa-comment ms-2" style="font-size: 25px;"></i>
        <!-- Jumlah Komentar -->
        <span style="font-size: 26px;">{{ $post->comments->count() }} comments</span>
    </div>
</div>
