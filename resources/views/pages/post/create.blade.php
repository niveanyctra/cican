<!-- Modal Create Post -->
<div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Create New Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Caption -->
                    <div class="mb-3">
                        <label for="caption" class="form-label">Caption</label>
                        <textarea name="caption" id="caption" class="form-control" rows="3" placeholder="Write a caption...">{{ old('caption') }}</textarea>
                        @error('caption')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Media Upload -->
                    <div class="mb-3">
                        <label for="media" class="form-label">Upload Photo/Video</label>
                        <input type="file" name="media[]" id="media"
                            class="form-control @error('media') is-invalid @enderror" multiple
                            accept="image/*,video/*">
                        @error('media')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>
    </div>
</div>
