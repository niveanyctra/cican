<!-- Followings Modal -->
<div class="modal fade" id="followingsModal" tabindex="-1" aria-labelledby="followingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-center pt-2">
                    <p style="font-size: 50px; line-height: 10px;">Followers</p>
                </div>
            </div>
            <div class="modal-body">
                @forelse ($user->followings as $following)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <a href="{{ route('user.show', $following->username) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center">
                            <img src="{{ Storage::url($following->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                                style="object-fit: cover; width: 50px; height: 50px;" class="rounded-circle me-2">
                            <div style="line-height: 12px">
                                <p style="font-size: 30px">{{ $following->name }}</p> <br>
                                <p style="font-size: 25px; opacity: 50%;">{{ '@' . $following->username }}</p>
                            </div>
                        </a>

                        @auth
                            @if ($following->id !== auth()->id())
                                @if (auth()->user()->followings->contains($following->id))
                                    <button class="btn btn-sm btn-outline-secondary follow-btn"
                                        data-user-id="{{ $following->id }}" style="font-size: 25px">Unfollow</button>
                                @else
                                    <button class="btn btn-sm btn-outline-primary follow-btn"
                                        data-user-id="{{ $following->id }}" style="font-size: 25px">Follow</button>
                                @endif
                            @endif
                        @endauth
                    </div>
                @empty
                    <p class="text-muted" style="font-size: 30px">Belum ada following.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
