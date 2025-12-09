@extends('layouts/contentNavbarLayout')

@section('title', 'Dashboard - Analytics')

@section('content')
<style>
  /* hover */
  .dashboard-card {
    background-color: rgba(20,162,186,0.5);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(20,162,186,0.3);
    color: #fff;
    transition: all 0.3s ease;
  }

  .dashboard-card:hover {
    transform: translateY(-6px);
    background-color: rgba(20,162,186,0.7);
    box-shadow: 0 6px 15px rgba(20,162,186,0.4);
  }

  .dashboard-card .card-title, 
  .dashboard-card .fw-bold {
    color: #fff;
  }

  .apexcharts-text {
    fill: #fff !important;
  }
  
  .apexcharts-gridline {
    stroke: rgba(255,255,255,0.2) !important;
  }
  
  .apexcharts-legend-text {
    color: #fff !important;
  }

  #jabatanCard {
    animation: fadeIn 0.5s ease;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  #debugPanel {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: rgba(0,0,0,0.9);
    color: #0f0;
    padding: 15px;
    border-radius: 8px;
    font-family: monospace;
    font-size: 11px;
    max-width: 400px;
    max-height: 300px;
    overflow: auto;
    z-index: 9999;
    border: 2px solid #0f0;
  }
</style>

<div class="row g-4">

  {{-- User Info --}}
  <div class="dashboard-card card mt-4">
    <div class="card-body">
      <h2 class="card-title mb-3 fw-bold">Hi, {{ $user['name'] ?? '-' }}!</h2>
      <p><strong>Email:</strong> {{ $user['email'] ?? '-' }}</p>
      <p><strong>Departemen:</strong> {{ $user['department'] ?? '-' }}</p>
    </div>
  </div>

  {{-- Card: Total Laptop --}}
  <div class="col-xl-4 col-md-6">
    <a href="{{ route('laptop.index') }}" class="text-decoration-none">
      <div class="dashboard-card card text-center">
        <div class="card-body">
          <h5 class="card-title mb-2 fw-bold">Total Laptop</h5>
          <h2 class="fw-bold mb-0">{{ $totalLaptop }}</h2>
        </div>
      </div>
    </a>
  </div>

  {{-- Card: Tersedia --}}
  <div class="col-xl-4 col-md-6">
    <a href="{{ route('laptop.index', ['status' => 'in stock']) }}" class="text-decoration-none">
      <div class="dashboard-card card text-center">
        <div class="card-body">
          <h5 class="card-title mb-2 fw-bold">Laptop Tersedia</h5>
          <h2 class="fw-bold text-success mb-0">{{ $inStock }}</h2>
        </div>
      </div>
      </a>
    </div>

  {{-- Card: Diarsip --}}
  <div class="col-xl-4 col-md-6">
    <a href="{{ route('laptop.arsip') }}" class="text-decoration-none">
      <div class="dashboard-card card text-center">
        <div class="card-body">
          <h5 class="card-title mb-2 fw-bold">Laptop Diarsip</h5>
          <h2 class="fw-bold text-danger mb-0">{{ $diarsip }}</h2>
        </div>
      </div>
    </a>
  </div>

  {{-- Chart Tahun --}}
  <div class="dashboard-card card mt-4">
    <div class="card-body">
      <h5 class="card-title mb-3 fw-bold">Peminjaman Laptop per Tahun</h5>
      <div id="yearChart" style="min-height: 380px; background: rgba(255,255,255,0.1);"></div>
    </div>
  </div>

  {{-- Chart Jabatan (Drilldown) --}}
  <div class="dashboard-card card mt-4 d-none" id="jabatanCard">
    <div class="card-body">
      <h5 class="card-title mb-3 fw-bold">
        Kategori Peminjam Tahun <span id="selectedYear" class="text-info"></span>
        <button class="btn btn-sm btn-outline-light float-end" id="closeJabatan" title="Tutup">
          <i class="bx bx-x"></i>
        </button>
      </h5>
      <div id="jabatanChart" style="min-height: 350px;"></div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
console.log('🚀 DASHBOARD SCRIPT LOADED');

// Debug logger
const debugLogs = [];
function debugLog(msg) {
  const timestamp = new Date().toLocaleTimeString();
  const logMsg = `[${timestamp}] ${msg}`;
  console.log(logMsg);
  debugLogs.push(logMsg);
  
  const debugEl = document.getElementById('debugLog');
  if (debugEl) {
    debugEl.innerHTML = debugLogs.slice(-15).join('<br>');
  }
}

debugLog('Script started');
debugLog('jQuery available: ' + (typeof $ !== 'undefined'));
debugLog('ApexCharts available: ' + (typeof ApexCharts !== 'undefined'));

// Data check
const tahunData = @json($laptopStats);
const jabatanPerTahun = @json($jabatanPerTahun);

const jabatanLabels = {
  'Staf': 'Staf',
  'Staf Senior': 'Staf Senior',
  'Supervisor': 'Supervisor',
  'Supervisor Senior': 'Supervisor Senior',
  'Manager': 'Manager',
  'Senior Manager': 'Senior Manager',
  'General Manager': 'General Manager'
};

debugLog('Tahun data length: ' + (tahunData ? tahunData.length : 0));

function initDashboard() {
  debugLog('=== INIT DASHBOARD ===');
  
  // Check ApexCharts
  if (typeof ApexCharts === 'undefined') {
    debugLog('❌ ApexCharts NOT LOADED!');
    setTimeout(initDashboard, 200);
    return;
  }
  
  debugLog('✅ ApexCharts available');
  
  // Check DOM
  const yearChartEl = document.querySelector("#yearChart");
  const jabatanChartEl = document.querySelector("#jabatanChart");
  
  if (!yearChartEl) {
    debugLog('❌ #yearChart element NOT FOUND!');
    setTimeout(initDashboard, 200);
    return;
  }
  
  debugLog('✅ #yearChart element found');
  
  // Check data
  if (!tahunData || tahunData.length === 0) {
    debugLog('❌ No data available');
    yearChartEl.innerHTML = '<p class="text-center text-white p-4">Tidak ada data</p>';
    return;
  }
  
  debugLog('✅ Data OK, rendering chart...');
  
  try {
    // ✅ FIX: Properly check and destroy old chart
    if (window.dashboardYearChart) {
      debugLog('Checking yearChart type: ' + typeof window.dashboardYearChart);
      debugLog('Has destroy method: ' + (typeof window.dashboardYearChart.destroy === 'function'));
      
      if (typeof window.dashboardYearChart.destroy === 'function') {
        debugLog('Destroying old chart');
        window.dashboardYearChart.destroy();
      } else {
        debugLog('⚠️ Old chart exists but has no destroy method, clearing manually');
        yearChartEl.innerHTML = '';
      }
    }
    
    const yearOptions = {
      series: [{ 
        name: 'Peminjaman', 
        data: tahunData.map(d => d.total) 
      }],
      chart: {
        type: 'bar',
        height: 380,
        background: 'transparent',
        events: {
          dataPointSelection: function(event, chartContext, config) {
            const tahun = tahunData[config.dataPointIndex].tahun;
            debugLog('📊 Clicked year: ' + tahun);
            showJabatanChart(tahun);
          }
        },
        toolbar: { show: true },
        animations: { enabled: true, easing: 'easeinout', speed: 800 }
      },
      plotOptions: {
        bar: { 
          borderRadius: 8, 
          columnWidth: '60%',
          dataLabels: { position: 'top' }
        }
      },
      dataLabels: {
        enabled: true,
        offsetY: -20,
        style: { fontSize: '12px', colors: ['#fff'], fontWeight: 'bold' },
        formatter: val => val
      },
      xaxis: {
        categories: tahunData.map(d => d.tahun),
        labels: { style: { colors: '#fff', fontSize: '12px' } },
        axisBorder: { color: 'rgba(255,255,255,0.3)' },
        axisTicks: { color: 'rgba(255,255,255,0.3)' }
      },
      yaxis: {
        labels: { 
          style: { colors: '#fff' },
          formatter: val => Math.floor(val)
        }
      },
      grid: { 
        borderColor: 'rgba(255,255,255,0.2)',
        strokeDashArray: 4
      },
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'light',
          type: 'vertical',
          shadeIntensity: 0.5,
          gradientToColors: ['rgba(255,255,255,0.9)'],
          opacityFrom: 0.8,
          opacityTo: 0.6
        }
      },
      colors: ['rgba(255,255,255,0.8)'],
      tooltip: { 
        theme: 'dark',
        y: { formatter: val => val + ' peminjaman' }
      }
    };
    
    debugLog('Creating ApexCharts instance...');
    window.dashboardYearChart = new ApexCharts(yearChartEl, yearOptions);
    
    debugLog('Rendering chart...');
    window.dashboardYearChart.render();
    
    debugLog('✅✅✅ CHART RENDERED SUCCESSFULLY!');
    
    // Setup jabatan chart function
    window.showJabatanChart = function(tahun) {
      debugLog('=== SHOW JABATAN CHART: ' + tahun + ' ===');
      
      const rawData = jabatanPerTahun[tahun] || {};
      const labels = Object.keys(rawData).map(key => jabatanLabels[key] || key);
      const values = Object.values(rawData);

      if (values.length === 0 || values.reduce((a,b) => a+b, 0) === 0) {
        alert(`Tidak ada data peminjaman untuk tahun ${tahun}`);
        return;
      }

      document.getElementById('selectedYear').textContent = tahun;
      document.getElementById('jabatanCard').classList.remove('d-none');

      const total = values.reduce((a,b) => a+b, 0);

      const jabatanOptions = {
        series: values,
        chart: { type: 'donut', height: 350 },
        labels: labels,
        colors: ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57', '#DDA0DD', '#98D8C8', '#F06292'],
        legend: { position: 'bottom', labels: { colors: '#fff' } },
        dataLabels: { enabled: true, style: { colors: ['#000'], fontWeight: 'bold' } },
        tooltip: { theme: 'dark', y: { formatter: val => val + ' orang' } },
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Total',
                  color: '#fff',
                  fontSize: '16px',
                  fontWeight: 'bold',
                  formatter: () => total
                }
              }
            }
          }
        }
      };

      // Properly destroy jabatan chart
      if (window.dashboardJabatanChart && typeof window.dashboardJabatanChart.destroy === 'function') {
        window.dashboardJabatanChart.destroy();
        debugLog('🗑️ Jabatan chart destroyed');
      }
      
      try {
        window.dashboardJabatanChart = new ApexCharts(jabatanChartEl, jabatanOptions);
        window.dashboardJabatanChart.render();
        debugLog('✅ Jabatan chart rendered');
      } catch(e) {
        debugLog('❌ Jabatan chart error: ' + e.message);
      }

      setTimeout(() => {
        document.getElementById('jabatanCard').scrollIntoView({ behavior: 'smooth' });
      }, 100);
    };

    // Close button
    const closeBtn = document.getElementById('closeJabatan');
    if (closeBtn) {
      closeBtn.onclick = function() {
        debugLog('Closing jabatan chart');
        document.getElementById('jabatanCard').classList.add('d-none');
        if (window.dashboardJabatanChart && typeof window.dashboardJabatanChart.destroy === 'function') {
          window.dashboardJabatanChart.destroy();
          window.dashboardJabatanChart = null;
        }
      };
    }
    
  } catch(err) {
    debugLog('❌ ERROR: ' + err.message);
    debugLog('Stack: ' + err.stack);
  }
}

// Run immediately
debugLog('Calling initDashboard...');
setTimeout(initDashboard, 500);

// Also on Livewire navigation
document.addEventListener('livewire:navigated', function() {
  debugLog('🔄 Livewire navigated');
  setTimeout(initDashboard, 500);
});
</script>
@endpush