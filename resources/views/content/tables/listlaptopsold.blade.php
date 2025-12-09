@extends('layouts/contentNavbarLayout')

@section('title', 'Laptop Terjual')

@section('content')
<!-- Toast Notification -->
<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease;">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

<style>
  /* Card Styling */
  .sold-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
  }

  .sold-card-header {
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    padding: 20px 24px;
    border-bottom: 2px solid rgba(255,255,255,0.2);
  }

  .sold-card-header h5 {
    color: #fff;
    font-weight: 600;
    font-size: 1.25rem;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .sold-card-header h5 i {
    font-size: 26px;
  }

  /* Filter Controls */
  .filter-controls {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    align-items: center;
  }

  .filter-controls .form-control,
  .filter-controls .form-select {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 0.9rem;
    transition: all 0.2s ease;
    background: #fff;
  }

  .filter-controls .form-control:focus,
  .filter-controls .form-select:focus {
    border-color: #14a2ba;
    box-shadow: 0 0 0 3px rgba(20,162,186,0.1);
    outline: none;
  }

  .filter-controls .form-control {
    min-width: 200px;
  }

  .filter-controls .form-select {
    min-width: 140px;
  }

  /* Loading State */
  .table-loading {
    position: relative;
    opacity: 0.5;
    pointer-events: none;
  }

  .loading-spinner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 40px;
    height: 40px;
    border: 4px solid rgba(20,162,186,0.3);
    border-top-color: #14a2ba;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    z-index: 1000;
  }

  @keyframes spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
  }

  /* Table Styling */
  .table-container {
    padding: 24px;
  }

  .sold-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
  }

  .sold-table thead th {
    background: #f8fafc;
    color: #334155;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    padding: 14px 16px;
    border-bottom: 2px solid #e2e8f0;
    letter-spacing: 0.3px;
  }

  .sold-table tbody tr {
    background: #fff;
    transition: all 0.2s ease;
    cursor: pointer;
  }

  .sold-table tbody tr:hover {
    background: #f8fafc;
    transform: translateX(4px);
  }

  .sold-table tbody td {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    color: #475569;
    font-size: 0.9rem;
  }

  /* Badge Styling */
  .laptop-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    color: white;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
  }

  .laptop-badge i {
    font-size: 16px;
  }

  /* Price Styling */
  .price-display {
    color: #0d7a8e;
    font-weight: 700;
    font-size: 1rem;
  }

  /* Buyer Info */
  .buyer-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .buyer-name {
    color: #1e293b;
    font-weight: 600;
    font-size: 0.95rem;
  }

  .buyer-code {
    color: #64748b;
    font-size: 0.8rem;
  }

  /* Date Display */
  .date-display {
    color: #64748b;
    font-size: 0.85rem;
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
  }

  .empty-state i {
    font-size: 64px;
    color: #cbd5e1;
    margin-bottom: 16px;
  }

  .empty-state h5 {
    color: #64748b;
    font-weight: 600;
    margin-bottom: 8px;
  }

  .empty-state p {
    color: #94a3b8;
    font-size: 0.9rem;
  }

  /* Pagination */
  .pagination-wrapper {
    padding: 20px 24px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .pagination-info {
    color: #64748b;
    font-size: 0.9rem;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .sold-card-header {
      flex-direction: column !important;
      gap: 15px;
    }

    .filter-controls {
      flex-direction: column;
      width: 100%;
    }

    .filter-controls > * {
      width: 100% !important;
      min-width: 100% !important;
    }

    .table-container {
      overflow-x: auto;
    }

    .sold-table {
      min-width: 600px;
    }

    .pagination-wrapper {
      flex-direction: column;
      gap: 12px;
    }
  }

  /* Dark Mode Support */
  [data-theme='dark'] .sold-card {
    background: rgba(30, 41, 59, 0.95);
  }

  [data-theme='dark'] .sold-table thead th {
    background: #1e293b;
    color: #f1f5f9;
  }

  [data-theme='dark'] .sold-table tbody tr {
    background: #0f172a;
  }

  [data-theme='dark'] .sold-table tbody tr:hover {
    background: #1e293b;
  }

  [data-theme='dark'] .sold-table tbody td {
    border-color: #334155;
    color: #cbd5e1;
  }

  [data-theme='dark'] .filter-controls .form-control,
  [data-theme='dark'] .filter-controls .form-select {
    background: #0f172a;
    color: #f1f5f9;
    border-color: #334155;
  }
</style>

<div class="sold-card">
  <!-- Card Header -->
  <div class="sold-card-header d-flex justify-content-between align-items-center">
    <h5>
      <i class="bx bx-check-circle"></i>
      Laptop Terjual
    </h5>
    
    <!-- Filter Controls -->
    <form method="GET" action="{{ url()->current() }}" class="filter-controls" id="filterForm">
      <select name="per_page" class="form-select" id="perPageFilter">
        <option value="10" {{ ($perPage ?? 10)==10 ? 'selected' : '' }}>10 / halaman</option>
        <option value="20" {{ ($perPage ?? 10)==20 ? 'selected' : '' }}>20 / halaman</option>
        <option value="50" {{ ($perPage ?? 10)==50 ? 'selected' : '' }}>50 / halaman</option>
      </select>
      
      <input type="text" 
             name="search" 
             value="{{ $search ?? '' }}" 
             class="form-control" 
             id="searchInput" 
             placeholder="Cari pembeli atau laptop...">
    </form>
  </div>

  <!-- Table Container -->
  <div id="tableContainer" style="position: relative;">
    @include('content.peminjaman.table-soldlaptop')
  </div>
</div>

<script>
  let searchTimeout = null;

  // Apply filters with AJAX
  function applyFilters(page = 1) {
    const perPage = document.getElementById('perPageFilter').value;
    const search = document.getElementById('searchInput').value;

    const params = new URLSearchParams({
      per_page: perPage,
      search: search,
      page: page
    });

    // Show loading state
    const container = document.getElementById('tableContainer');
    container.classList.add('table-loading');
    
    // Create loading spinner
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.id = 'loadingSpinner';
    container.appendChild(spinner);

    // Fetch new data
    fetch(`{{ route('laptop.sold') }}?${params.toString()}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html'
      }
    })
    .then(response => {
      if (!response.ok) throw new Error('Network response was not ok');
      return response.text();
    })
    .then(html => {
      container.innerHTML = html;
      
      // Update URL without reload
      const newUrl = `${window.location.pathname}?${params.toString()}`;
      window.history.pushState({}, '', newUrl);
      
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Gagal memuat data', '#ef4444');
    })
    .finally(() => {
      container.classList.remove('table-loading');
      const spinnerEl = document.getElementById('loadingSpinner');
      if (spinnerEl) spinnerEl.remove();
    });
  }

  // Event listeners
  document.getElementById('perPageFilter').addEventListener('change', () => applyFilters());
  
  // Debounced search
  document.getElementById('searchInput').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => applyFilters(), 500);
  });

  // Prevent form submission
  document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();
    applyFilters();
  });

  // Handle pagination clicks (delegated event)
  document.addEventListener('click', function(e) {
    const paginationLink = e.target.closest('.pagination a');
    if (paginationLink) {
      e.preventDefault();
      const url = new URL(paginationLink.href);
      const page = url.searchParams.get('page') || 1;
      applyFilters(page);
    }
  });

 // Event listener khusus untuk sold table
const soldTableContainer = document.querySelector('.sold-table');

if (soldTableContainer) {
    soldTableContainer.addEventListener('click', function(e) {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        if (e.target.closest('.pagination')) return;
        
        const id = row.getAttribute('data-id');
        if (id) {
            window.location.href = '{{ url('/laptop/sold') }}/' + id + '/detail';
        }
    });
}

  // Toast notification
  function showToast(message, color = "#10b981") {
    const toast = document.getElementById('toastNotification');
    const msg = document.getElementById('toastMessage');
    msg.innerText = message;
    toast.style.background = color;
    toast.style.display = 'block';
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => toast.style.display = 'none', 300);
    }, 2500);
  }
</script>
@endsection