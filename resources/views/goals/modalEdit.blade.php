<!-- ✏️ Modal Edit Goal -->
<div class="modal fade" id="editGoalModal{{ $goal->id }}" tabindex="-1" aria-labelledby="editGoalLabel{{ $goal->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-warning">Edit Goal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('goals.update', $goal->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Goal Title</label>
            <input type="text" name="title" class="form-control rounded-pill" value="{{ $goal->title }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Target Amount (Rp)</label>
            <input type="number" name="amount_target" class="form-control rounded-pill" value="{{ $goal->amount_target }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control rounded-3" rows="3">{{ $goal->description }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Goal Image (optional)</label>
            <input type="file" name="photo" class="form-control rounded-pill">
            @if($goal->photo)
              <small class="text-muted">Current image: {{ basename($goal->photo) }}</small>
            @endif
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning rounded-pill px-4 text-white">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
