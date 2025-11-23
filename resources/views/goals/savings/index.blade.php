@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- 🔙 Back --}}
  <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-4">
    ← Back to Goals
  </a>

  {{-- 🧠 Goal Info --}}
  <div class="card shadow-sm rounded-4 mb-4 border-0 bg-glass position-relative">
    <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 position-relative">

      {{-- ✅ Foto Goal --}}
      @if($goal->photo)
        <img src="{{ asset('storage/' . $goal->photo) }}" alt="{{ $goal->title }}"
             class="rounded-4 goal-image">
      @else
        <img src="{{ asset('images/default_goal.jpg') }}" alt="Goal Default"
             class="rounded-4 goal-image">
      @endif

      {{-- 🧾 Detail --}}
      <div class="flex-fill">
        <h4 class="fw-bold text-dark mb-1">{{ $goal->title }}</h4>
        <p class="text-muted mb-2">{{ $goal->description ?? '-' }}</p>

        @php
          $progress = $goal->amount_target > 0 
                      ? ($goal->amount_current / $goal->amount_target) * 100 
                      : 0;
        @endphp

        {{-- Progress Bar --}}
        <div class="progress rounded-pill progress-mobile">
          <div class="progress-bar bg-success" role="progressbar" 
               style="width: {{ $progress }}%;"></div>
        </div>

        <small class="text-secondary d-block mt-1">
          Rp {{ number_format($goal->amount_current, 0, ',', '.') }} /
          Rp {{ number_format($goal->amount_target, 0, ',', '.') }}
        </small>
      </div>

      {{-- 🏷 STATUS BADGE --}}
      <span id="goalStatus" 
            class="badge status-badge"></span>
    </div>
  </div>

  {{-- 💰 Add Saving Button --}}
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="fw-bold m-0">Savings History</h5>
    <button class="btn btn-success rounded-pill px-4 py-2 fw-semibold" 
            data-bs-toggle="modal" data-bs-target="#addSavingModal">
      + Add Saving
    </button>
  </div>

  {{-- 📋 Savings Table --}}
  <div class="table-responsive shadow-sm rounded-4 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light text-uppercase small text-secondary">
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
            <td class="text-success fw-bold">+Rp {{ number_format($saving->amount, 0, ',', '.') }}</td>
            <td>{{ $saving->note ?? '-' }}</td>
            <td>
              <form action="{{ route('goals.savings.destroy', $saving->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-muted py-4 text-center">No savings added yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- 🧾 Modal Add Saving --}}
<div class="modal fade" id="addSavingModal" tabindex="-1" aria-hidden="true">
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
        <button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-success rounded-pill px-4">Save</button>
      </div>
    </form>
  </div>
</div>

{{-- 💡 STATUS LOGIC --}}
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const amountCurrent = {{ $goal->amount_current }};
    const amountTarget = {{ $goal->amount_target }};
    const badge = document.getElementById("goalStatus");

    if (amountCurrent >= amountTarget && amountTarget > 0) {
      badge.textContent = "Finished";
      badge.classList.add("badge-success-outline");
    } else {
      badge.textContent = "Ongoing";
      badge.classList.add("badge-primary-outline");
    }
  });
</script>

{{-- Toastify --}}
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
  @if (session('success'))
    Toastify({
      text: "{{ session('success') }}",
      duration: 3000,
      gravity: "top",
      position: "right",
      backgroundColor: "#198754",
      close: true,
    }).showToast();
  @endif

  @if (session('error'))
    Toastify({
      text: "{{ session('error') }}",
      duration: 3000,
      gravity: "top",
      position: "right",
      backgroundColor: "#dc3545",
      close: true,
    }).showToast();
  @endif
</script>

{{-- 🌟 MOBILE STYLING FIX ---}}
<style>
  /* Gambar Goal */
  .goal-image {
    width: 150px;
    height: 100px;
    object-fit: cover;
  }

  /* Progress responsive */
  .progress-mobile {
    height: 14px;
    max-width: 300px;
  }

  /* Status Badge */
  .status-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 6px 14px;
    font-size: 0.85rem;
  }

  .badge-success-outline {
    border: 2px solid #198754;
    color: #198754;
    background: white;
  }

  .badge-primary-outline {
    border: 2px solid #0d6efd;
    color: #0d6efd;
    background: white;
  }

  /* 📱 Mobile Optimized */
  @media (max-width: 576px) {

    .goal-image {
      width: 100%;
      height: 160px !important;
    }

    .progress-mobile {
      width: 100% !important;
    }

    .status-badge {
      top: -5px;
      right: -5px;
    }

    .btn {
      font-size: 0.9rem;
    }

    table th, table td {
      font-size: 0.85rem;
      white-space: nowrap;
    }

    .modal-dialog {
      margin: 0 12px;
    }
  }
</style>

@endsection
