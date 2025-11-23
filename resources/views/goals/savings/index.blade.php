@extends('layouts.app')

@section('content')
<div class="container mt-4">

  {{-- 🔙 Back --}}
  <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-4">
    ← Back to Goals
  </a>

  {{-- 🧠 Goal Info --}}
  <div class="card shadow-sm rounded-4 mb-4 border-0 position-relative">
    <div class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 position-relative">

      {{-- ✅ FOTO GOAL --}}
      @if($goal->photo)
        <img src="{{ asset('storage/' . $goal->photo) }}" alt="{{ $goal->title }}"
             class="goal-image rounded-4">
      @else
        <img src="{{ asset('images/default_goal.jpg') }}" alt="Goal Default"
             class="goal-image rounded-4">
      @endif

      {{-- 🧾 Detail --}}
      <div class="flex-fill w-100">

        {{-- Judul + Badge Mobile --}}
        <div class="d-flex justify-content-between align-items-center d-md-none mb-2">
          <h4 class="fw-bold text-dark mb-0">{{ $goal->title }}</h4>

          <span id="goalStatusMobile" 
                class="badge px-3 py-2 border fw-semibold"></span>
        </div>

        {{-- Judul Desktop --}}
        <h4 class="fw-bold text-dark mb-1 d-none d-md-block">
          {{ $goal->title }}
        </h4>

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

      {{-- 🏷 BADGE DESKTOP --}}
      <span id="goalStatus" 
            class="badge px-3 py-2 border position-absolute top-0 end-0 m-3 d-none d-md-inline-block fw-semibold">
      </span>

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
              <form action="{{ route('goals.savings.destroy', $saving->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-muted py-4 text-center">
              No savings added yet.
            </td>
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
          <label class="form-label">Note</label>
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

{{-- 🔥 Badge Status Logic --}}
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const amountCurrent = {{ $goal->amount_current }};
    const amountTarget = {{ $goal->amount_target }};

    const badgeDesktop = document.getElementById("goalStatus");
    const badgeMobile = document.getElementById("goalStatusMobile");

    let text = "";
    let className = "";

    if (amountCurrent >= amountTarget && amountTarget > 0) {
      text = "Finished";
      className = "text-success border-success";
    } else {
      text = "Ongoing";
      className = "text-primary border-primary";
    }

    if (badgeDesktop) {
      badgeDesktop.textContent = text;
      badgeDesktop.classList.add(...className.split(" "));
    }

    if (badgeMobile) {
      badgeMobile.textContent = text;
      badgeMobile.classList.add(...className.split(" "));
    }
  });
</script>

{{-- 🎯 Styling khusus responsif gambar --}}
<style>
  /* Desktop default */
  .goal-image {
    width: 150px;
    height: 100px;
    object-fit: cover;
  }

  /* Mobile full image */
  @media (max-width: 576px) {
    .goal-image {
      width: 100%;
      height: auto;
      aspect-ratio: 16 / 9;
    }

    .progress-mobile {
      width: 100%;
    }
  }
</style>

@endsection
