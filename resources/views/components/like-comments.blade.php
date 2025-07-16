<div id="like-section-feed-{{ $post->id }}" class="my-2 ms-3 d-flex gap-5" style="cursor: pointer;">
    <div class="d-flex gap-1">
        <button id="like-button-feed-{{ $post->id }}" onclick="toggleLike({{ $post->id }})">
            <!-- Ikon Like -->
            <i id="like-icon-feed-{{ $post->id }}"
                class="{{ auth()->check() && $post->likes->contains(auth()->id()) ? 'fa-solid fa-heart fa-xl text-danger' : 'fa-regular fa-heart fa-xl' }}"></i>
        </button>
        <div data-bs-toggle="modal" data-bs-target="#showWhoLikeModal{{ $post->id }}">
            <span id="like-count-feed-{{ $post->id }}">{{ $post->likes->count() }}
                likes</span>
        </div>
    </div>
    <div data-bs-toggle="modal" data-bs-target="#showPostModal{{ $post->id }}">
        <i class="fa-regular fa-comment fa-xl ms-2"></i>
        <span>{{ $post->comments->count() }} comments</span>
    </div>
</div>
