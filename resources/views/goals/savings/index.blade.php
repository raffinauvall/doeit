@extends('layouts.app')

@section('content')
<div class="container mt-3">

  {{-- 🔙 Back --}}
  <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-3 py-1">
    ← Back to Goals
  </a>

  {{-- 🧠 Goal Info --}}
  <div class="card shadow-sm rounded-4 mb-4 border-0 position-relative">
    <div class="card-body p-3">

      {{-- Foto Goal --}}
      <div class="w-100 mb-3">
        @if($goal->photo)
          <img src="{{ asset('storage/' . $goal->photo) }}" class="goal-image-mobile rounded-4">
        @else
          <img src="{{ asset('images/default_goal.jpg') }}" class="goal-image-mobile rounded-4">
        @endif
      </div>

      {{-- Judul + Badge Status --}}
      <div class="d-flex justify-content-between align-items-center mb-1">
        <h4 class="fw-bold m-0">{{ $goal->title }}</h4>
        <span id="goalStatus" class="goal-status-badge"></span>
      </div>

      {{-- Deskripsi --}}
      <p class="text-muted mb-2">{{ $goal->description ?? '-' }}</p>

      @php
        $progress = $goal->amount_target > 0 
                    ? ($goal->amount_current / $goal->amount_target) * 100 
                    : 0;
      @endphp

      {{-- Progress --}}
      <div class="progress rounded-pill" style="height: 14px;">
        <div class="progress-bar bg-success" style="width: {{ $progress }}%;"></div>
      </div>

      <small class="text-secondary d-block mt-1">
        Rp {{ number_format($goal->amount_current, 0, ',', '.') }} /
        Rp {{ number_format($goal->amount_target, 0, ',', '.') }}
      </small>
    </div>
  </div>

  {{-- 💰 Add Saving --}}
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h5 class="fw-bold m-0">Savings History</h5>

    <button class="btn btn-success rounded-pill px-3 py-1 fw-semibold"
            data-bs-toggle="modal" data-bs-target="#addSavingModal">
      + Add
    </button>
  </div>

  {{-- 📋 Table --}}
  <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
    <table class="table table-hover align-middle mb-0 small">
      <thead class="table-light text-uppercase text-secondary small">
        <tr>
          <th>Date</th>
          <th>Amount</th>
          <th>Note</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($savings as $saving)
        <tr>
          <td>{{ \Carbon\Carbon::parse($saving->saved_at)->format('d M Y') }}</td>
          <td class="text-success fw-bold">+Rp {{ number_format($saving->amount) }}</td>
          <td>{{ $saving->note ?? '-' }}</td>
          <td>
            <form action="{{ route('goals.savings.destroy', $saving->id) }}" method="POST">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm rounded-pill px-3 py-1">
                <i class="bi bi-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center text-muted py-4">No savings added yet.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</div>

{{-- Modal Add --}}
<div class="modal fade" id="addSavingModal" tabindex="-1">
  <div class="modal-dialog">
    <form action="{{ route('goals.savings.store', $goal->id) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Add Saving</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <div class="mb-3">
          <label class="form-label">Amount</label>
          <input type="number" name="amount" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" name="saved_at" class="form-control">
        </div>

        <div class="mb-3">
          <label class="form-label">Note (optional)</label>
          <textarea name="note" class="form-control" rows="2"></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary rounded-pill">Cancel</button>
        <button class="btn btn-success rounded-pill px-4">Save</button>
      </div>
    </form>
  </div>
</div>

{{-- Status Logic --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
  const amountCurrent = {{ $goal->amount_current }};
  const amountTarget = {{ $goal->amount_target }};
  const badge = document.getElementById("goalStatus");

  if (amountTarget > 0 && amountCurrent >= amountTarget) {
    badge.textContent = "Finished";
    badge.classList.add("badge-finished");
  } else {
    badge.textContent = "Ongoing";
    badge.classList.add("badge-ongoing");
  }
});
</script>

{{-- Styles --}}
<style>
  .goal-image-mobile {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  /* Badge Status – sejajar judul */
  .goal-status-badge {
    padding: 5px 12px;
    font-size: 0.8rem;
    border-radius: 50px;
    background: #fff;
    border: 2px solid #0d6efd;
    color: #0d6efd;
    white-space: nowrap;
  }

  .badge-finished {
    border-color: #198754;
    color: #198754;
  }

  @media (max-width: 576px) {
    table th, table td {
      font-size: 0.82rem;
      white-space: nowrap;
    }
  }
</style>

@endsection
