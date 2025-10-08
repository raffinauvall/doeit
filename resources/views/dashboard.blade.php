@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row">
        <div class="col-md-4">
            <div class="card rounded-5 border-0 pb-0 text-white w-100 p-2 ps-3 pt-3">
        <span class="finance-title ">Total</span>
        <span class="mb-3">pemasukan dan pengeluaran</span>

        <div class="row">
            <div class="col-2"><div class="circle"></div></div>
            <div class="col">
                <span>Balance</span>
                <span class="ff-sfBold amount-balance">Rp.300.000</span>
            </div>
            <div class="col"></div>
        </div>
       
        <p class="pb-0">Anda lebih hemat 15% dari bulan lalu!</p>
    </div></div>
        <div class="col-md-8"><div class="card w-100 p-2 ps-3 pt-2">
        <p class="finance-title mb-0">Pemasukan</p>
        <h2 class="ff-sfBold">Rp.120.000</h2>
    </div>
</div>
        
    </div>
    
    <div class="container mt-4">
  <canvas id="yearlyChart" height="70"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('yearlyChart');

  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($months),
      datasets: [
        {
          label: 'Pemasukan',
          data: @json($incomes),
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          fill: false,
          tension: 0.3
        },
        {
          label: 'Pengeluaran',
          data: @json($expenses),
          borderColor: 'rgba(255, 99, 132, 1)',
          borderWidth: 2,
          fill: false,
          tension: 0.3,
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'top' },
        title: { display: false, text: 'Laporan Keuangan Bulanan' }
      },
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
</script>
</div>
@endsection
