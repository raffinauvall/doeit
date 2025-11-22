@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <div class="transaction-box p-4 shadow-sm rounded-4 border-0 bg-glass">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold ff-sfBold text-dark mb-0 d-flex align-items-center"><span><img
                            src="{{ asset('images/wallet_green.png') }}" width="35" class="me-2" alt=""></span>
                    Transaction List</h4>
                <button class="btn btn-success rounded-pill px-4 py-2 fw-semibold btn-add" data-bs-toggle="modal"
                    data-bs-target="#createModal">
                    + Add Transaction
                </button>
            </div>

            {{-- 🔍 Filter Bar --}}
            <div class="d-flex flex-wrap gap-3 align-items-center mb-4">
                <div class="input-group shadow-sm" style="max-width: 300px;">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control border-start-0 rounded-end-pill"
                        placeholder="Search Transaction...">
                </div>

                <select id="filterType" class="form-select rounded-pill shadow-sm" style="max-width: 180px;">
                    <option value="">All Type</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>

                <button type="button"
                    class="btn btn-outline-secondary rounded-pill shadow-sm d-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#filterDateModal">
                    <i class="bi bi-calendar-event"></i> Date Filter
                </button>

                @include('transactions.modalFilterDate')
            </div>

            @include('transactions.modalCreate')

            {{-- 📋 Transaction Table --}}
            <div class="table-responsive shadow-sm rounded-4">
                <table class="table table-hover align-middle mb-0" id="transactionTable">
                    <thead class="table-light text-uppercase small text-secondary">
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $t)
                            <tr class="align-middle">
                                <td>{{ \Carbon\Carbon::parse($t->date)->format('d F Y') }}</td>
                                <td class="text-dark">{{ $t->description }}</td>
                                <td class="text-capitalize fw-semibold text-secondary">{{ $t->type }}</td>
                                <td class="{{ $t->type == 'income' ? 'text-success' : 'text-danger' }} fw-bold">
                                    {{ $t->type == 'income' ? '+' : '-' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                </td>
                                <td>
                                    <button class="btn btn-warning text-white me-2" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $t->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('transactions.destroy', $t->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger text-white btn-delete"
                                            data-id="{{ $t->id }}" data-bs-toggle="modal"
                                            data-bs-target="#deleteModal">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        @include('transactions.modalDelete')
                                    </form>
                                </td>
                            </tr>
                            @include('transactions.modalEdit', ['transaction' => $t])
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">There are no transactions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" id="export-box">
  <div class="card-body">
    <h5 class="card-title mb-3">
      <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
      <span class="ff-sfBold">Export Financial Report</span>
    </h5>

    <form action="{{ route('transactions.exportPdf') }}" method="GET" class="row g-3 align-items-end">
      <!-- Pilih Bulan -->
      <div class="col-md-4">
        <label for="month" class="form-label fw-semibold">Month</label>
        <select name="month" id="month" class="form-select">
          <option value="">Choose month</option>
          @for ($m = 1; $m <= 12; $m++)
            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
              {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
            </option>
          @endfor
        </select>
      </div>

      <div class="col-md-4">
        <label for="year" class="form-label fw-semibold">Year</label>
        <select name="year" id="year" class="form-select">
          <option value="">Choose year</option>
          @for ($y = now()->year; $y >= 2020; $y--)
            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
              {{ $y }}
            </option>
          @endfor
        </select>
      </div>

      
      <div class="col-md-4 d-grid">
        <button type="submit" class="btn btn-danger fw-semibold">
          <i class="bi bi-filetype-pdf me-2"></i> Export PDF
        </button>
      </div>
    </form>
  </div>
</div>


    </div>

    {{-- 🧠 JS Filter --}}
    <script>
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('filterType');
        const rows = document.querySelectorAll('#transactionTable tbody tr');

        function filterRows() {
            const searchValue = searchInput.value.toLowerCase();
            const typeValue = typeFilter.value;
            const startDate = document.getElementById('startDate')?.value;
            const endDate = document.getElementById('endDate')?.value;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matchSearch = text.includes(searchValue);
                const matchType = !typeValue || row.cells[2].textContent.toLowerCase() === typeValue;

                // Ambil tanggal di kolom pertama (format "12 Oktober 2025")
                const dateText = row.cells[0].textContent.trim();
                const rowDate = new Date(dateText);

                let matchDate = true;
                if (startDate && endDate) {
                    const start = new Date(startDate);
                    start.setHours(0, 0, 0, 0);
                    const end = new Date(endDate);
                    end.setHours(23, 59, 59, 999);
                    matchDate = rowDate >= start && rowDate <= end;
                }

                row.style.display = matchSearch && matchType && matchDate ? '' : 'none';
            });
        }

        // 🔍 filter teks dan tipe
        searchInput.addEventListener('input', filterRows);
        typeFilter.addEventListener('change', filterRows);

        // 📅 filter tanggal via modal
        document.getElementById('applyDateFilter').addEventListener('click', function() {
            filterRows();
            const modal = bootstrap.Modal.getInstance(document.getElementById('filterDateModal'));
            modal.hide();
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
