@extends('layouts.app')

@section('title', 'Savings for ' . $goal->title)

@section('content')
    <div class="container mt-4">

        {{-- 🔙 Back --}}
        <a href="{{ route('goals.index') }}" class="btn btn-outline-secondary mb-3 rounded-pill px-4">
            ← Back to Goals
        </a>

        {{-- 🧠 Goal Info --}}
        <div class="card shadow-sm rounded-4 mb-4 border-0 position-relative">
            <div
                class="card-body d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3 position-relative">

                {{-- 🖼️ Goal Image --}}
                @if ($goal->photo)
                    <img src="{{ asset('storage/' . $goal->photo) }}" alt="{{ $goal->title }}" class="goal-image rounded-4">
                @else
                    <img src="{{ asset('images/default_goal.jpg') }}" alt="Goal Default" class="goal-image rounded-4">
                @endif

                {{-- 🧾 Goal Detail --}}
                <div class="flex-fill w-100">

                    {{-- Title + Mobile Badge --}}
                    <div class="d-flex justify-content-between align-items-center d-md-none mb-2">
                        <h4 class="fw-bold text-dark mb-0">{{ $goal->title }}</h4>
                        <span id="goalStatusMobile" class="badge px-3 py-2 border fw-semibold"></span>
                    </div>

                    {{-- Desktop Title --}}
                    <h4 class="fw-bold text-dark mb-1 d-none d-md-block">
                        {{ $goal->title }}
                    </h4>

                    <p class="text-muted mb-2">{{ $goal->description ?? '-' }}</p>

                    @php
                        $progress = $goal->amount_target > 0 ? ($goal->amount_current / $goal->amount_target) * 100 : 0;
                    @endphp

                    {{-- Progress Bar --}}
                    <div class="progress rounded-pill progress-mobile">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;"></div>
                    </div>

                    <small class="text-secondary d-block mt-1">
                        Rp {{ number_format($goal->amount_current, 0, ',', '.') }} /
                        Rp {{ number_format($goal->amount_target, 0, ',', '.') }}
                    </small>
                </div>

                {{-- Badge Desktop --}}
                <span id="goalStatus"
                    class="badge px-3 py-2 border position-absolute top-0 end-0 m-3 d-none d-md-inline-block fw-semibold">
                </span>

            </div>
        </div>

        {{-- 💰 Add Saving Button --}}
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <h5 class="fw-bold m-0">Savings History</h5>
            <button class="btn btn-success rounded-pill px-4 py-2 fw-semibold" data-bs-toggle="modal"
                data-bs-target="#addSavingModal">
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
                            <td class="d-flex gap-3">

                                {{-- Edit Button --}}
                                <button class="btn btn-warning text-white btn-sm rounded-pill px-3 edit-btn"
                                    data-id="{{ $saving->id }}" data-amount="{{ $saving->amount }}"
                                    data-date="{{ $saving->saved_at }}" data-note="{{ $saving->note }}"
                                    data-bs-toggle="modal" data-bs-target="#updateSavingModal">
                                    Edit
                                </button>

                                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $saving->id }}"
                                    data-bs-toggle="modal" data-bs-target="#deleteSavingModal">
                                    Delete
                                </button>

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

    {{-- ➕ Add Saving Modal --}}
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
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4">Save</button>
                </div>

            </form>
        </div>
    </div>

    {{-- ✏ Update Modal --}}
    <div class="modal fade" id="updateSavingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="updateSavingForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Update Saving</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" id="editAmount" name="amount" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" id="editDate" name="saved_at" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Note</label>
                        <textarea id="editNote" name="note" class="form-control" rows="2"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update</button>
                </div>

            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteSavingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Delete Saving</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" id="deleteSavingForm">
                    @csrf
                    @method('DELETE')

                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete:
                            <strong id="savingName" class="text-danger"></strong>?
                        </p>
                        <p class="text-muted">
                            This action cannot be undone.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill"
                            data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger rounded-pill">Delete</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll(".btnDelete");
            const form = document.getElementById("deleteSavingForm");
            const savingName = document.getElementById("savingName");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const id = this.dataset.id;
                    const name = this.dataset.name;

                    // Set nama tampilan ke modal
                    savingName.textContent = name;

                    // Set action form ke route yang benar
                    form.action = `/goals/savings/${id}`;
                });
            });
        });
    </script>


    {{-- 🧠 Status Badge Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const current = {{ $goal->amount_current }};
            const target = {{ $goal->amount_target }};
            const statusDesktop = document.getElementById("goalStatus");
            const statusMobile = document.getElementById("goalStatusMobile");

            const text = current >= target ? "Finished" : "Ongoing";
            const style = current >= target ? "text-success border-success" : "text-primary border-primary";

            statusDesktop.textContent = statusMobile.textContent = text;
            statusDesktop.classList.add(...style.split(" "));
            statusMobile.classList.add(...style.split(" "));
        });
    </script>

    {{-- ✨ Edit Form Fill Script --}}
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".edit-btn").forEach(button => {
                button.addEventListener("click", () => {

                    document.getElementById("editAmount").value = button.dataset.amount;
                    document.getElementById("editDate").value = button.dataset.date;
                    document.getElementById("editNote").value = button.dataset.note;

                    document.getElementById("updateSavingForm").action =
                        `/goals/savings/${button.dataset.id}`;
                });
            });
        });
    </script>

    {{-- 📱 Responsive UI --}}
    <style>
        .goal-image {
            width: 150px;
            height: 100px;
            object-fit: cover;
        }

        @media (max-width: 576px) {
            .goal-image {
                width: 100%;
                height: auto;
                aspect-ratio: 16 / 9;
            }
        }
    </style>
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
