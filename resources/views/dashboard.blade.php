@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4 ff-sfBold fs-2">Hello, <span class="me-1"> {{ Auth::user()->name }}</span> <img src="{{ asset('images/wave.png') }}" alt="" width="35" class="mb-1"></h4>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card rounded-2 shadow border-0 text-white bg-success w-100 p-3">
                <span class="finance-title">Your Balance</span>
                <span class="amount-balance">Rp.{{ number_format($balance, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card rounded-2 shadow border-0 text-white bg-primary w-100 p-3">
                <span class="finance-title">Income</span>
                <span class="amount-balance">Rp.{{ number_format($totalIncome, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card rounded-2 shadow border-0 text-white bg-warning w-100 p-3">
                <span class="finance-title">Expense</span>
                <span class="amount-balance">Rp.{{ number_format($totalExpense, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Grafik --}}
    <div class="mt-4" style="height: 330px; width:auto;">
        <canvas id="yearlyChart"></canvas>
    </div>
</div>

{{-- Script Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let yearlyChart = null;

    function renderChart() {
        const ctx = document.getElementById('yearlyChart').getContext('2d');

        // Hapus chart lama kalau ada (biar gak dobel pas resize)
        if (yearlyChart) {
            yearlyChart.destroy();
        }

        yearlyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($months), // contoh: ['Jan', 'Feb', 'Mar', ...]
                datasets: [
                    {
                        label: 'Income',
                        data: @json($incomes), // array data pemasukan
                        backgroundColor: 'rgba(54, 162, 235, 0.7)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    },
                    {
                        label: 'Expense',
                        data: @json($expenses), // array data pengeluaran
                        backgroundColor: 'rgba(255, 99, 132, 0.7)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: { family: "'Segoe UI', sans-serif", size: 13 }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Grafik Pemasukan & Pengeluaran Tahunan',
                        font: { size: 16, weight: 'bold' },
                        color: '#000'
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#000' },
                        grid: { display: false },
                        title: {
                            display: true,
                            text: 'Bulan',
                            color: '#000',
                            font: { weight: 'bold' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#000',
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        },
                        grid: { color: 'rgba(0,0,0,0.1)' },
                        title: {
                            display: true,
                            text: 'Jumlah (Rupiah)',
                            color: '#000',
                            font: { weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Render pertama
    renderChart();

    // Supaya grafik auto re-render pas ukuran layar berubah (tanpa refresh)
    const chartContainer = document.getElementById('yearlyChart').parentElement;
    const resizeObserver = new ResizeObserver(() => {
        renderChart();
    });
    resizeObserver.observe(chartContainer);
</script>

@endsection
