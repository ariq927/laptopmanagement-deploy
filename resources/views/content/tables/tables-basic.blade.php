@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Peminjam')

@section('content')
<!-- Toast -->
<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg,#10b981 0%,#059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease;">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

<!-- Modal Arsip -->
<div id="archiveModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:400px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Arsipkan Laptop</h4>
    <p style="color:#fff; margin-bottom:15px; opacity:0.9;">Masukkan keterangan pengarsipan:</p>
    <textarea id="keteranganInput" class="form-control" rows="3" placeholder="Contoh: Rusak pada bagian keyboard" style="margin-bottom:20px; border:2px solid rgba(255,255,255,0.3); background:rgba(255,255,255,0.95);"></textarea>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button onclick="closeArchiveModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmArchive()" class="btn btn-danger" style="font-weight:bold; padding:8px 20px;">Arsipkan</button>
    </div>
  </div>
</div>

<style>
.btn-table {
  background-color: #0d9488;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 6px 14px;
  font-size: 13px;
  font-weight: 600;
  transition: all 0.25s ease;
  position: relative;
  z-index: 10;
}
.btn-table:hover {
  background-color: #0b7d73;
  transform: translateY(-1px);
}

.btn-expired {
  background-color: #94a3b8;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 6px 14px;
  font-size: 13px;
  font-weight: 600;
  cursor: not-allowed;
  opacity: 0.7;
}

.status-dropdown {
  display: none;
  flex-direction: column;
  position: absolute;
  top: 35px;
  right: 0;
  background: rgba(255,255,255,0.95);
  border-radius: 8px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.3);
  z-index: 10000;
  overflow: visible;
  padding: 6px 6px;
  min-width: 120px;
}

.status-dropdown.show {
  display: flex;
}

.status-dropdown button {
  background: none;
  border: none;
  padding: 8px 14px;
  text-align: left;
  width: 100%;
  color: #0d9488;
  font-weight: 600;
  transition: background 0.2s;
  cursor: pointer;
  white-space: nowrap;
}
.status-dropdown button:hover {
  background: rgba(20,162,186,0.15);
}

/* Loading overlay */
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

/* Responsive styles for mobile */
.header-controls-form {
  display: flex;
  gap: 10px;
}

@media (max-width: 768px) {
  .card-header {
    flex-direction: column !important;
    gap: 15px;
  }

  .card-header h5 {
    width: 100%;
    text-align: center;
  }

  .header-controls-form {
    flex-direction: column;
    width: 100%;
  }

  .header-controls-form > * {
    width: 100% !important;
  }

  .header-controls-form select,
  .header-controls-form input,
  .header-controls-form button {
    width: 100% !important;
  }
}
</style>

<div class="card" style="background-color:rgba(20,162,186,0.5); backdrop-filter:blur(10px); border:1px solid rgba(20,162,186,0.3);">
  <div class="card-header d-flex justify-content-between align-items-center" style="background-color:rgba(20,162,186,0.5); border-bottom:1px solid rgba(20,162,186,0.3);">
    <h5 style="color:#fff; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.8);">Daftar Peminjam</h5>
    <form method="GET" action="{{ url()->current() }}" class="header-controls-form" id="filterForm">
      <select name="status_filter" class="form-select" id="statusFilter" style="width:auto; background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba;">
        <option value="all" {{ request('status_filter', 'all') == 'all' ? 'selected' : '' }}>Semua Status</option>
        <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>In Use</option>
        <option value="expired" {{ request('status_filter') == 'expired' ? 'selected' : '' }}>Expired</option>
      </select>
      <select name="per_page" class="form-select" id="perPageFilter" style="width:auto; background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba;">
        <option value="10" {{ ($perPage ?? 10)==10 ? 'selected' : '' }}>10 / halaman</option>
        <option value="20" {{ ($perPage ?? 10)==20 ? 'selected' : '' }}>20 / halaman</option>
        <option value="50" {{ ($perPage ?? 10)==50 ? 'selected' : '' }}>50 / halaman</option>
      </select>
      <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" id="searchInput" placeholder="Cari data..." style="background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba;">
    </form>
  </div>

  <div id="tableContainer" style="position: relative;">
    <div id="tableContent">
      @include('content.peminjaman.table-peminjam')
    </div>
  </div>
</div>

<script>
(function() {
  'use strict';

  let pendingArchiveId = null;
  let searchTimeout = null;

  // Prevent double initialization
  if (window.tableBasicInitialized) return;
  window.tableBasicInitialized = true;

  // Handle filter changes
  function applyFilters(page = 1) {
    const statusFilter = document.getElementById('statusFilter').value;
    const perPage = document.getElementById('perPageFilter').value;
    const search = document.getElementById('searchInput').value;

    const params = new URLSearchParams({
      status_filter: statusFilter,
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
    fetch(`{{ route('peminjaman.index') }}?${params.toString()}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => response.text())
    .then(html => {
      // Parse the response and extract table content
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newContent = doc.getElementById('tableContent');
      
      if (newContent) {
        document.getElementById('tableContent').innerHTML = newContent.innerHTML;
        
        // Update URL without reload
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showToast('Gagal memuat data', '#ef4444');
    })
    .finally(() => {
      // Remove loading state
      container.classList.remove('table-loading');
      const spinnerEl = document.getElementById('loadingSpinner');
      if (spinnerEl) spinnerEl.remove();
    });
  }

  // Event listeners for filters
  document.getElementById('statusFilter').addEventListener('change', () => applyFilters());
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

  // Handle pagination clicks
  document.addEventListener('click', function(e) {
    if (e.target.matches('.pagination a')) {
      e.preventDefault();
      const url = new URL(e.target.href);
      const page = url.searchParams.get('page') || 1;
      applyFilters(page);
    }
  });

  // Toggle dropdown function (global)
  window.toggleDropdown = function(btn, id) {
    if (event) event.stopPropagation();

    const dropdown = btn.nextElementSibling;
    if (!dropdown) return;

    if (dropdown.classList.contains('show')) {
      dropdown.classList.remove('show');
      return;
    }

    closeDropdowns();

    const btnWidth = btn.getBoundingClientRect().width;
    dropdown.style.minWidth = btnWidth + 'px';

    dropdown.classList.add('show');

    const rect = dropdown.getBoundingClientRect();
    const overflowRight = rect.right - window.innerWidth;
    if (overflowRight > 0) {
      dropdown.style.right = (overflowRight + 8) + 'px';
    } else {
      dropdown.style.right = '0';
    }
  };

  function closeDropdowns() {
    document.querySelectorAll('.status-dropdown').forEach(el => el.classList.remove('show'));
  }

  window.openArchiveModal = function(id) {
    closeDropdowns();
    pendingArchiveId = id;
    document.getElementById('archiveModal').style.display = 'block';
    document.getElementById('keteranganInput').value = '';
    document.getElementById('keteranganInput').focus();
  };

  window.closeArchiveModal = function() {
    document.getElementById('archiveModal').style.display = 'none';
    pendingArchiveId = null;
  };

  window.confirmArchive = function() {
    const keterangan = document.getElementById('keteranganInput').value.trim();
    if (keterangan === "") {
      showToast("⚠️ Keterangan wajib diisi!", "#f59e0b");
      return;
    }
    updateStatus(pendingArchiveId, 'diarsip', keterangan);
    closeArchiveModal();
  };

  function updateStatus(id, status, keterangan = null) {
    closeDropdowns();
    
    const body = { status };
    if (keterangan) {
      body.keterangan = keterangan;
    }

    fetch(`/peminjaman/update-status/${id}`, {
      method: 'PUT',
      headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify(body)
    })
    .then(async res => {
      const data = await res.json().catch(() => ({}));
      if (!res.ok) {
        showToast('Gagal memperbarui status', '#ef4444');
        return;
      }
      showToast(data.message || 'Status berhasil diperbarui');
      // Refresh table instead of full reload
      applyFilters();
    })
    .catch(() => showToast('Terjadi kesalahan jaringan', '#ef4444'));
  }

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

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(e) {
    const noNav = e.target.closest('.no-nav') || e.target.closest('.status-dropdown');
    if (!noNav) closeDropdowns();
  });

  // Handle row clicks for detail view - ONLY for peminjaman table
  document.addEventListener('click', function(e) {
    const row = e.target.closest('tr[data-id]');
    if (!row) return;
    
    // Skip if clicking on action buttons or dropdowns
    if (e.target.closest('.no-nav') || e.target.closest('.status-dropdown')) return;
    
    // Skip if this is a sold laptop table
    if (row.closest('.sold-table')) return;
    
    const id = row.getAttribute('data-id');
    if (id) {
      window.location.href = '{{ url('/') }}/peminjaman/' + id; 
    }
  });

  // Close modal when clicking outside
  document.getElementById('archiveModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeArchiveModal();
    }
  });

  // Re-initialize after Livewire navigation (if using Livewire)
  document.addEventListener('livewire:navigated', function() {
    console.log('Livewire navigated - table events ready');
  });
})();
</script>
@endsection