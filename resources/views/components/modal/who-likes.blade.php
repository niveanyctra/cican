@if (isset($posts))
    @foreach ($posts as $post)
        <div class="modal fade" id="showWhoLikeModal{{ $post->id }}" tabindex="-1"
            aria-labelledby="showWhoLikeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showWhoLikeModalLabel">Like Postingan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="d-flex">
                            @if ($post->likes->count() > 0)
                                <div class="list-group w-100">
                                    @foreach ($post->likes as $like)
                                        <a href="{{ route('user.show', $like->username) }}"
                                            class="list-group-item list-group-item-action d-flex align-items-center">
                                            <img src="{{ Storage::url($like->avatar ?? 'default-image.jpg') }}"
                                                alt="Avatar" style="object-fit: cover; width: 40px; height: 40px;"
                                                class="rounded-circle me-2">
                                            <span>{{ $like->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                                @else
                                No one likes this post yet.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
