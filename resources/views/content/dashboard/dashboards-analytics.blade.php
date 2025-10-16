@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
<div class="row g-4">

{{-- User Info --}}
<div class="card mt-4">
  <div class="card-body">
    <h2 class="card-title mb-3">Hi, {{ $user['name'] ?? '-' }}!</h2>
    <p><strong>Email:</strong> {{ $user['email'] ?? '-' }}</p>
    <p><strong>Departemen:</strong> {{ $user['department'] ?? '-' }}</p>
  </div>
</div>

  {{-- Card: Total Laptop --}}
  <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title mb-2">Total Laptop</h5>
        <h2 class="fw-bold mb-0">{{ $totalLaptop }}</h2>
      </div>
    </div>
  </div>

  {{-- Card: Tersedia --}}
  <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title mb-2">Laptop Tersedia</h5>
        <h2 class="fw-bold text-success mb-0">{{ $tersedia }}</h2>
      </div>
    </div>
  </div>

  {{-- Card: Diarsip --}}
  <div class="col-xl-4 col-md-6">
    <div class="card">
      <div class="card-body text-center">
        <h5 class="card-title mb-2">Laptop Diarsip</h5>
        <h2 class="fw-bold text-danger mb-0">{{ $diarsip }}</h2>
      </div>
    </div>
  </div>
</div>

{{-- Chart --}}
<div class="card mt-4">
  <div class="card-body">
    <h5 class="card-title mb-3">Statistik Laptop per Merek</h5>
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
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>
@endpush
