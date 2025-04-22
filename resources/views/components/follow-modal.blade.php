   <!-- Followers Modal -->
    <div class="modal fade" id="followersModal" tabindex="-1" aria-labelledby="followersModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Followers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @forelse ($user->followers as $follower)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($follower->avatar ?? asset('default-image.jpg')) }}" class="rounded-circle me-3" width="45" height="45" style="object-fit: cover; max-width: 45px;height: 45px;">
                                <div>
                                    <div class="fw-bold">{{ $follower->name }}</div>
                                    <small class="text-muted">{{ $follower->username }}</small>
                                </div>
                            </div>

                            @auth
                                @if ($follower->id !== auth()->id())
                                    @if (auth()->user()->followings->contains($follower->id))
                                        <button class="btn btn-sm btn-outline-secondary follow-btn" data-user-id="{{ $follower->id }}">Unfollow</button>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary follow-btn" data-user-id="{{ $follower->id }}">Follow</button>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    @empty
                        <p class="text-muted">Belum ada follower.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Followings Modal -->
    <div class="modal fade" id="followingsModal" tabindex="-1" aria-labelledby="followingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Following</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @forelse ($user->followings as $following)
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ Storage::url($following->avatar ?? asset('default-image.jpg')) }}" class="rounded-circle me-3" width="45" height="45" style="object-fit: cover;max-width: 45px;height: 45px;">
                                <div>
                                    <div class="fw-bold">{{ $following->name }}</div>
                                    <small class="text-muted">{{ $following->username }}</small>
                                </div>
                            </div>

                            @auth
                                @if ($following->id !== auth()->id())
                                    @if (auth()->user()->followings->contains($following->id))
                                        <button class="btn btn-sm btn-outline-secondary follow-btn" data-user-id="{{ $following->id }}">Unfollow</button>
                                    @else
                                        <button class="btn btn-sm btn-outline-primary follow-btn" data-user-id="{{ $following->id }}">Follow</button>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    @empty
                        <p class="text-muted">Belum ada following.</p>
                    @endforelse
                </div>
            </div>
        </div>
