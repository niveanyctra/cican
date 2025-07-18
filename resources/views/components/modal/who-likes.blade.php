@if (isset($posts))
    @foreach ($posts as $post)
        <div class="modal fade" id="showWhoLikeModal{{ $post->id }}" tabindex="-1"
            aria-labelledby="showWhoLikeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex w-100 justify-content-center pt-2">
                            <p style="font-size: 50px; line-height: 10px;">Likes</p>
                        </div>
                    </div>

                    <div class="modal-body">
                        <div class="d-flex">
                            @if ($post->likes->count() > 0)
                                @foreach ($post->likes as $like)
                                    <a href="{{ route('user.show', $like->username) }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center">
                                        <img src="{{ Storage::url($like->avatar ?? 'default-image.jpg') }}"
                                            alt="Avatar" style="object-fit: cover; width: 50px; height: 50px;"
                                            class="rounded-circle me-2">
                                        <div style="line-height: 10px">
                                            <p style="font-size: 30px">{{ $like->name }}</p> <br>
                                            <p style="font-size: 25px; opacity: 50%;">{{ '@' . $like->username }}</p>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <p style="font-size: 35px">
                                    No one likes this post yet.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
