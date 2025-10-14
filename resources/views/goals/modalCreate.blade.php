<!-- 🟢 Modal Create Goal -->
<div class="modal fade" id="createGoalModal" tabindex="-1" aria-labelledby="createGoalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold text-success">Add New Goal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('goals.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <input type="hidden" name="users_id" value="{{ Auth::id() }}">
            <label class="form-label fw-semibold">Goal Title</label>
            <input type="text" name="title" class="form-control rounded-pill" placeholder="Ex: New Motorcycle" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Target Amount (Rp)</label>
            <input type="number" name="amount_target" class="form-control rounded-pill" placeholder="Ex: 15000000" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Description</label>
            <textarea name="description" class="form-control rounded-3" rows="3" placeholder="Tell something about this goal..."></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Goal Image (optional)</label>
            <input type="file" name="photo" class="form-control rounded-pill">
          </div>
        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success rounded-pill px-4">Save Goal</button>
        </div>
      </form>
    </div>
  </div>
</div>
