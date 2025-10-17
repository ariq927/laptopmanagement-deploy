@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
<style>
  /* Animasi hover untuk semua card */
  .card {
    background-color: rgba(20,162,186,0.5);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(20,162,186,0.3);
    color: #fff;
    transition: all 0.3s ease;
  }

  .card:hover {
    transform: translateY(-6px);
    background-color: rgba(20,162,186,0.7);
    box-shadow: 0 6px 15px rgba(20,162,186,0.4);
  }

  .card-title, .fw-bold {
    color: #fff;
  }
</style>

<div class="row g-4">

  {{-- User Info --}}
  <div class="card mt-4">
    <div class="card-body">
      <h2 class="card-title mb-3 fw-bold">Hi, {{ $user['name'] ?? '-' }}!</h2>
      <p><strong>Email:</strong> {{ $user['email'] ?? '-' }}</p>
      <p><strong>Departemen:</strong> {{ $user['department'] ?? '-' }}</p>
    </div>
  </div>

  {{-- Card: Total Laptop --}}
  <div class="col-xl-4 col-md-6">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title mb-2 fw-bold">Total Laptop</h5>
        <h2 class="fw-bold mb-0">{{ $totalLaptop }}</h2>
      </div>
    </div>
  </div>

  {{-- Card: Tersedia --}}
  <div class="col-xl-4 col-md-6">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title mb-2 fw-bold">Laptop Tersedia</h5>
        <h2 class="fw-bold text-success mb-0">{{ $tersedia }}</h2>
      </div>
    </div>
  </div>

  {{-- Card: Diarsip --}}
  <div class="col-xl-4 col-md-6">
    <div class="card text-center">
      <div class="card-body">
        <h5 class="card-title mb-2 fw-bold">Laptop Diarsip</h5>
        <h2 class="fw-bold text-danger mb-0">{{ $diarsip }}</h2>
      </div>
    </div>
  </div>
</div>

{{-- Chart --}}
<div class="card mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3 fw-bold">Statistik Laptop per Merek</h5>
    <canvas id="brandChart" style="max-height: 400px;"></canvas>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
  const ctx = document.getElementById('brandChart');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: {!! json_encode($laptopStats->pluck('merek')) !!},
      datasets: [{
        label: 'Jumlah Laptop',
        data: {!! json_encode($laptopStats->pluck('total')) !!},
        backgroundColor: 'rgba(255, 255, 255, 0.6)',
        borderColor: 'rgba(255, 255, 255, 1)',
        borderWidth: 1
      }]
    },
    options: {
      plugins: {
        legend: { labels: { color: 'white' } }
      },
      scales: {
        x: { ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.2)' } },
        y: { beginAtZero: true, ticks: { color: 'white' }, grid: { color: 'rgba(255,255,255,0.2)' } }
      }
    }
  });
});
</script>
@endpush
