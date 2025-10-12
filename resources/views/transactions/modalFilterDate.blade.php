<!-- 📅 Modal Filter Tanggal -->
<div class="modal fade" id="filterDateModal" tabindex="-1" aria-labelledby="filterDateModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-semibold" id="filterDateModalLabel">
          <i class="bi bi-calendar3 me-2 text-success"></i>Filter Berdasarkan Tanggal
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="filterDateForm">
          <div class="mb-3">
            <label for="startDate" class="form-label">Dari Tanggal</label>
            <input type="date" class="form-control" id="startDate" required>
          </div>
          <div class="mb-3">
            <label for="endDate" class="form-label">Sampai Tanggal</label>
            <input type="date" class="form-control" id="endDate" required>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-success text-white" id="applyDateFilter">Terapkan</button>
      </div>
    </div>
  </div>
</div>
