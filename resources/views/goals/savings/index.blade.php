@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- 🔙 Back --}}
  <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-4">
    ← Back to Goals
  </a>

  {{-- 🧠 Goal Info --}}
  <div class="card shadow-sm rounded-4 mb-4 border-0 bg-glass position-relative">
    <div class="card-body d-flex align-items-center gap-4 position-relative">

      {{-- ✅ Foto Goal --}}
      @if($goal->photo)
        <img src="{{ asset('storage/' . $goal->photo) }}" alt="{{ $goal->title }}" 
             class="rounded-4" style="width: 150px; height: 100px; object-fit: cover;">
      @else
        <img src="{{ asset('images/default_goal.jpg') }}" alt="Goal Default" 
             class="rounded-4" style="width: 150px; height: 100px; object-fit: cover;">
      @endif

      {{-- 🧾 Detail --}}
      <div>
        <h4 class="fw-bold text-dark mb-1">{{ $goal->title }}</h4>
        <p class="text-muted mb-2">{{ $goal->description ?? '-' }}</p>

        {{-- Progress Bar --}}
        @php
          $progress = $goal->amount_target > 0 
                      ? ($goal->amount_current / $goal->amount_target) * 100 
                      : 0;
        @endphp
        <div class="progress rounded-pill" style="height: 15px; width: 300px;">
          <div class="progress-bar bg-success" role="progressbar" 
               style="width: {{ $progress }}%;" 
               aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
          </div>
        </div>
        <small class="text-secondary d-block mt-1">
          Rp {{ number_format($goal->amount_current, 0, ',', '.') }} /
          Rp {{ number_format($goal->amount_target, 0, ',', '.') }}
        </small>
      </div>

      {{-- 🏷️ STATUS BADGE (pojok kanan atas) --}}
      <span id="goalStatus" 
            class="badge position-absolute top-0 end-0 m-3 rounded-pill px-3 py-2 shadow-sm fs-6"></span>

    </div>
  </div>

  {{-- 💰 Add Saving Button --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold">Savings History</h5>
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
            <td colspan="4" class="text-muted py-4">No savings added yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- 🧾 Modal Add Saving --}}
<div class="modal fade" id="addSavingModal" tabindex="-1" aria-labelledby="addSavingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('goals.savings.store', $goal->id) }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="addSavingModalLabel">Add Saving</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Amount</label>
          <input type="number" name="amount" class="form-control" placeholder="Enter amount..." required>
        </div>
        <div class="mb-3">
          <label class="form-label">Date</label>
          <input type="date" name="saved_at" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Note (optional)</label>
          <textarea name="note" class="form-control" rows="2" placeholder="e.g. Tabungan minggu ini"></textarea>
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
    const statusEl = document.getElementById("goalStatus");

    if (amountCurrent >= amountTarget && amountTarget > 0) {
      statusEl.textContent = "Finished";
      statusEl.classList.add("border","border-success", "text-success", "rounded-pill");
    } else {
      statusEl.textContent = "Ongoing";
      statusEl.classList.add("border", "border-primary", "text-primary", "rounded-pill");
    }
  });
</script>
  {{-- ✅ Toastify.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
  @if (session('success'))
    Toastify({
      text: "{{ session('success') }}",
      duration: 3000,
      gravity: "top", // posisi atas
      position: "right", // pojok kanan
      backgroundColor: "#198754",
      stopOnFocus: true,
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
      stopOnFocus: true,
      close: true,
    }).showToast();
  @endif
</script>
@endsection
