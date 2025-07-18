<!-- Follow Back Modal -->
<div class="modal fade" id="followBackModal" tabindex="-1" aria-labelledby="followBackModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex w-100 justify-content-center pt-2">
                    <p style="font-size: 50px; line-height: 10px;">Follow Back</p>
                </div>
            </div>
            <div class="modal-body">
                @foreach ($followBacks as $follower)
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <a href="{{ route('user.show', $follower->username) }}"
                            class="list-group-item list-group-item-action d-flex align-items-center">
                            <img src="{{ Storage::url($follower->avatar ?? 'default-image.jpg') }}" alt="Avatar"
                                style="object-fit: cover; width: 50px; height: 50px;" class="rounded-circle me-2">
                            <div style="line-height: 12px">
                                <p style="font-size: 30px">{{ $follower->name }}</p> <br>
                                <p style="font-size: 25px; opacity: 50%;">{{ "@{$follower->username}" }}</p>
                            </div>
                        </a>

                        @auth
                            @if ($follower->id !== auth()->id())
                            <button
                                class="btn btn-sm btn-primary follow-btn w-40"
                                data-user-id="{{ $follower->id }}" style="font-size: 20px">
                                {{ auth()->user()->followings->contains($follower->id) ? 'Unfollow' : 'Follow Back' }}
                            </button>
                            @endif
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                if (e.target.classList.contains('follow-btn')) {
                    const button = e.target;
                    const userId = button.dataset.userId;
                    fetch(`/follow/follow-toggle/${userId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            // Refresh page after follow/unfollow
                            window.location.reload();
                        })
                        .catch(error => {
                            console.error('Gagal toggle follow:', error);
                        });
                }
            });
        });
    </script>

@endpush
