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
// PREVENT MULTIPLE INITIALIZATION
if (!window.peminjamTableInitialized) {
  window.peminjamTableInitialized = true;

(function() {
  'use strict';

  let pendingArchiveId = null;
  let searchTimeout = null;

  console.log('✅ Peminjam table script loaded ONCE');

  // ===== FUNGSI INISIALISASI FILTERS =====
  function initializeFilters() {
    console.log('🔄 Initializing peminjam filters...');
    
    const statusFilter = document.getElementById('statusFilter');
    const perPageFilter = document.getElementById('perPageFilter');
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    if (!statusFilter || !perPageFilter || !searchInput || !filterForm) {
      console.log('⚠️ Filter elements not found, skipping...');
      return;
    }

    const newStatus = statusFilter.cloneNode(true);
    const newPerPage = perPageFilter.cloneNode(true);
    const newSearch = searchInput.cloneNode(true);
    const newForm = filterForm.cloneNode(true);
    
    statusFilter.parentNode.replaceChild(newStatus, statusFilter);
    perPageFilter.parentNode.replaceChild(newPerPage, perPageFilter);
    searchInput.parentNode.replaceChild(newSearch, searchInput);
    filterForm.parentNode.replaceChild(newForm, filterForm);

    // Attach fresh listeners
    document.getElementById('statusFilter').addEventListener('change', function() {
      console.log('📊 Status filter changed:', this.value);
      applyFilters();
    });
    
    document.getElementById('perPageFilter').addEventListener('change', function() {
      console.log('📊 Per page changed:', this.value);
      applyFilters();
    });
    
    document.getElementById('searchInput').addEventListener('input', function() {
      console.log('🔍 Search input:', this.value);
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => applyFilters(), 500);
    });

    document.getElementById('filterForm').addEventListener('submit', function(e) {
      e.preventDefault();
      console.log('📝 Form submitted');
      applyFilters();
    });

    console.log('✅ Peminjam filters initialized');
  }

  // ===== APPLY FILTERS DENGAN AJAX =====
  function applyFilters(page = 1) {
    console.log('🔄 Applying filters, page:', page);
    
    const statusFilter = document.getElementById('statusFilter');
    const perPageFilter = document.getElementById('perPageFilter');
    const searchInput = document.getElementById('searchInput');
    
    if (!statusFilter || !perPageFilter || !searchInput) {
      console.log('❌ Filter elements not found');
      return;
    }

    const params = new URLSearchParams({
      status_filter: statusFilter.value,
      per_page: perPageFilter.value,
      search: searchInput.value,
      page: page
    });

    const requestUrl = `{{ route('peminjaman.index') }}?${params.toString()}`;
    console.log('📦 Request URL:', requestUrl);

    // Show loading
    const container = document.getElementById('tableContainer');
    container.classList.add('table-loading');
    
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.id = 'loadingSpinner';
    container.appendChild(spinner);

    // Fetch data
    fetch(requestUrl, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'text/html',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      }
    })
    .then(response => {
      console.log('📡 Response status:', response.status);
      
      if (!response.ok) {
        return response.text().then(text => {
          console.error('❌ Server error response:', text.substring(0, 500));
          throw new Error(`Server Error ${response.status}`);
        });
      }
      return response.text();
    })
    .then(html => {
      console.log('✅ Data loaded, length:', html.length);
      
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newContent = doc.getElementById('tableContent');
      
      if (newContent) {
        console.log('✅ Table content found and updated');
        document.getElementById('tableContent').innerHTML = newContent.innerHTML;
        
        // Update URL
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
      } else {
        console.error('❌ tableContent not found in response');
        console.log('📄 Response preview:', html.substring(0, 500));
        showToast('Format response tidak valid', '#ef4444');
      }
    })
    .catch(error => {
      console.error('❌ Fetch Error:', error);
      showToast('Gagal memuat data: ' + error.message, '#ef4444');
    })
    .finally(() => {
      container.classList.remove('table-loading');
      const spinnerEl = document.getElementById('loadingSpinner');
      if (spinnerEl) spinnerEl.remove();
    });
  }

  // ===== PAGINATION (DELEGATED) =====
  document.addEventListener('click', function(e) {
    const paginationLink = e.target.closest('.pagination a');
    if (paginationLink) {
      e.preventDefault();
      const url = new URL(paginationLink.href);
      const page = url.searchParams.get('page') || 1;
      console.log('📄 Pagination clicked:', page);
      applyFilters(page);
    }
  });

  // ===== DROPDOWN FUNCTIONS =====
  window.toggleDropdown = function(btn, id) {
    const e = event || window.event;
    if (e) e.stopPropagation();

    const dropdown = document.querySelector(`.status-dropdown[data-owner="${id}"]`);
    
    if (!dropdown) {
      console.error('Dropdown not found for id:', id);
      return;
    }

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

  // ===== MODAL FUNCTIONS =====
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

  // ===== UPDATE STATUS =====
  window.updateStatus = function(id, status, keterangan = null) {
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
      applyFilters();
    })
    .catch(() => showToast('Terjadi kesalahan jaringan', '#ef4444'));
  };

  // ===== TOAST =====
  function showToast(message, color = "#10b981") {
    const toast = document.getElementById('toastNotification');
    const msg = document.getElementById('toastMessage');
    
    if (!toast || !msg) return;
    
    msg.innerText = message;
    toast.style.background = color;
    toast.style.display = 'block';
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => toast.style.display = 'none', 300);
    }, 2500);
  }

  // ===== CLOSE DROPDOWNS ON CLICK =====
  document.addEventListener('click', function(e) {
    const noNav = e.target.closest('.no-nav') || e.target.closest('.status-dropdown');
    if (!noNav) closeDropdowns();
  });

  // ===== ROW CLICK (DELEGATED) =====
  document.addEventListener('click', function(e) {
    const row = e.target.closest('tr[data-id]');
    if (!row) return;
    
    if (e.target.closest('.no-nav') || e.target.closest('.status-dropdown')) return;
    if (row.closest('.sold-table')) return;
    
    const id = row.getAttribute('data-id');
    if (id) {
      console.log('✅ Row clicked:', id);
      window.location.href = '{{ url('/') }}/peminjaman/' + id; 
    }
  });

  // ===== MODAL OUTSIDE CLICK =====
  const archiveModal = document.getElementById('archiveModal');
  if (archiveModal) {
    archiveModal.addEventListener('click', function(e) {
      if (e.target === this) closeArchiveModal();
    });
  }

  // ===== INITIALIZE =====
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeFilters);
  } else {
    initializeFilters();
  }

  // ===== LIVEWIRE SUPPORT =====
  document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navigated');
    setTimeout(initializeFilters, 100);
  });

  document.addEventListener('livewire:load', function() {
    console.log('🔄 Livewire loaded');
    setTimeout(initializeFilters, 100);
  });

})();

} // END if (!window.peminjamTableInitialized)
</script>
@endsection