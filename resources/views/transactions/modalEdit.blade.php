<div class="modal fade" id="editModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $transaction->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="editModalLabel{{ $transaction->id }}">Edit Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="{{ route('transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text" name="description" class="form-control" value="{{ $transaction->description }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Category</label>
            <input type="text" name="category" class="form-control" value="{{ $transaction->category }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="amount" class="form-control" value="{{ $transaction->amount }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Tipe</label>
            <select name="type" class="form-select" required>
              <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Income</option>
              <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning text-white">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
