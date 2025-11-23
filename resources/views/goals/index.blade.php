@extends('layouts.app')

@section('title', 'Goals')

@section('content')
<div class="container mt-4">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold ff-sfBold text-dark mb-0 d-flex align-items-center">
      My Saving Goals
    </h3>

    <button class="btn btn-success rounded-pill px-4 py-2 fw-semibold"
            data-bs-toggle="modal" data-bs-target="#createGoalModal">
      + Add Goal
    </button>
  </div>

  {{-- ✅ Modal buat nambah goal --}}
  @include('goals.modalCreate')

  {{-- 📦 Daftar Goal --}}
  @if($goals->count() > 0)
  <div class="row g-4">
    @foreach($goals as $goal)
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0 rounded-4 bg-glass h-100">

          {{-- Gambar Goal --}}
          @if($goal->photo)
            <img src="{{ asset('storage/' . $goal->photo) }}" class="card-img-top rounded-top-4" alt="Goal Photo" style="height: 180px; object-fit: cover;">
          @else
            <img src="{{ asset('images/default_goal.jpg') }}" class="card-img-top rounded-top-4" alt="Goal Photo" style="height: 180px; object-fit: cover;">
          @endif

          <div class="card-body">
            {{-- Judul + Status --}}
            <div class="d-flex justify-content-between align-items-center mb-1">
              <h5 class="fw-bold text-dark mb-0">{{ $goal->title }}</h5>
              <span id="status-{{ $goal->id }}" class="badge rounded-pill px-3 py-2 fs-6"></span>
            </div>

            <p class="text-muted small mb-3">{{ $goal->description ?? 'No description provided.' }}</p>

            {{-- 💰 Progress Info --}}
            @php
              $current = $goal->amount_current;
              $target = $goal->amount_target;
              $progress = $target > 0 ? ($current / $target) * 100 : 0;
            @endphp

            <div class="d-flex justify-content-between align-items-center mb-2">
              <small class="text-success fw-bold">Rp {{ number_format($current, 0, ',', '.') }}</small>
              <small class="text-secondary">of Rp {{ number_format($target, 0, ',', '.') }}</small>
            </div>

            <div class="progress rounded-pill" style="height: 12px;">
              <div class="progress-bar bg-success" role="progressbar"
                style="width: {{ $progress }}%"
                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>

            {{-- ⚙️ Tombol Aksi --}}
            <div class="mt-3 d-flex justify-content-between">
              <a href="{{ route('goals.savings.index', $goal->id) }}" class="btn btn-outline-success btn-sm rounded-pill px-3">
                <i class="bi bi-wallet2 me-1"></i> View Savings
              </a>

              <div class="d-flex gap-2">
                <button class="btn btn-warning text-white btn-sm rounded-pill px-3"
                        data-bs-toggle="modal" data-bs-target="#editGoalModal{{ $goal->id }}">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-danger btn-sm rounded-pill px-3"
                        data-bs-toggle="modal" data-bs-target="#deleteGoalModal{{ $goal->id }}">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- ✏️ Modal Edit --}}
      @include('goals.modalEdit', ['goal' => $goal])

      {{-- 🗑️ Modal Delete --}}
      <div class="modal fade" id="deleteGoalModal{{ $goal->id }}" tabindex="-1" aria-labelledby="deleteGoalLabel{{ $goal->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content rounded-4 border-0">
            <div class="modal-header border-0">
              <h5 class="modal-title fw-bold text-danger">Delete Goal</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              Are you sure you want to delete <strong>{{ $goal->title }}</strong>?  
              This action cannot be undone.
            </div>
            <div class="modal-footer border-0">
              <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
              <form action="{{ route('goals.destroy', $goal->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger rounded-pill px-3">Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </div>
  @else
    <div class="text-center py-5 text-muted">
      <i class="bi bi-emoji-frown fs-1"></i>
      <p class="mt-3">You don’t have any goals yet.</p>
    </div>
  @endif

</div>

{{-- 💡 JavaScript Status --}}
<script>
  document.addEventListener("DOMContentLoaded", function() {
    @foreach($goals as $goal)
      const current{{ $goal->id }} = {{ $goal->amount_current }};
      const target{{ $goal->id }} = {{ $goal->amount_target }};
      const badge{{ $goal->id }} = document.getElementById("status-{{ $goal->id }}");

      if (target{{ $goal->id }} > 0 && current{{ $goal->id }} >= target{{ $goal->id }}) {
        badge{{ $goal->id }}.textContent = "Finished";
        badge{{ $goal->id }}.classList.add("border","border-success", "text-success", "rounded-pill");
      } else {
        badge{{ $goal->id }}.textContent = "Ongoing";
        badge{{ $goal->id }}.classList.add("border", "border-primary", "text-primary", "rounded-pill");
      }
    @endforeach
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
