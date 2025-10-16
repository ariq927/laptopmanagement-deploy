@extends('layouts/contentNavbarLayout')

@section('title', 'Loan Analytics')

@section('content')
<div class="row">
  <div class="col-12 col-lg-8 mb-4">
    <div class="card">
      <div class="card-header">Grafik Peminjaman</div>
      <div class="card-body">
        <canvas id="loanChart" height="120"></canvas>
      </div>
    </div>
  </div>
  <div class="col-12 col-lg-4 mb-4">
    <div class="card">
      <div class="card-header">Statistik</div>
      <div class="card-body">
        <p>Total Laptop: {{ $totalLaptop ?? 0 }}</p>
        <p>Total Dipinjam: {{ $laptopDipinjam ?? 0 }}</p>
        <p>User Aktif: {{ $totalUser ?? 0 }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('loanChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: @json($labels ?? []),
      datasets: [{
        label: 'Jumlah Peminjaman',
        data: @json($values ?? []),
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        fill: true,
        tension: 0.3
      }]
    }
  });
</script>
@endsection
