@extends('layouts/contentNavbarLayout')

@section('title', 'Laptop Arsip')

@section('content')
@php
    $isDarkMode = session('theme') === 'dark';
    $headerBgColor = $isDarkMode ? '#125d72' : '#14a2ba';
    $cardBgColor = $isDarkMode ? 'rgba(20,162,186,0.1)' : 'rgba(20,162,186,0.5)';
    $borderColor = $isDarkMode ? 'rgba(18,93,114,0.5)' : 'rgba(20,162,186,0.3)';
@endphp

<style>
  .btn-modern {
    border: none;
    border-radius: 8px;
    font-weight: 600;
    padding: 8px 18px;
    transition: all 0.25s ease;
  }
  .btn-cancel {
    background-color: rgba(255,255,255,0.85);
    color: #333;
  }
  .btn-cancel:hover {
    background-color: rgba(255,255,255,0.95);
    transform: translateY(-1px);
  }
  .btn-restore {
    background-color: #0d9488;
    color: #fff;
  }
  .btn-restore:hover {
    background-color: #0b7d73;
    transform: translateY(-1px);
  }
  .btn-search {
    background-color: rgba(255,255,255,0.85);
    color: #333;
  }
  .btn-search:hover {
    background-color: rgba(255,255,255,0.95);
    transform: translateY(-1px);
  }
  .btn-table {
    background-color: #0d9488;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.25s ease;
  }
  .btn-table:hover {
    background-color: #0b7d73;
    transform: translateY(-1px);
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

<div id="restoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:450px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan Laptop</h4>
    <div style="background:rgba(255,255,255,0.1); padding:15px; border-radius:8px; margin-bottom:20px;">
      <p style="color:#fff; margin:0; font-size:14px; line-height:1.6;">
        <strong>Kode:</strong> <span id="modalKode"></span><br>
        <strong>Laptop:</strong> <span id="modalLaptop"></span><br>
        <strong>Keterangan:</strong> <span id="modalKeterangan"></span>
      </p>
    </div>
    <p style="color:#fff; margin-bottom:20px; opacity:0.9; text-align:center;">Apakah kamu yakin ingin mengembalikan laptop ini dari arsip?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button id="btnCancelRestore" type="button" style="padding:10px 25px; border:none; border-radius:8px; font-weight:600; cursor:pointer; background:rgba(255,255,255,0.2); color:#fff; border:1px solid rgba(255,255,255,0.3);">Batal</button>
      <button id="btnConfirmRestore" type="button" style="padding:10px 25px; border:none; border-radius:8px; font-weight:600; cursor:pointer; background:linear-gradient(135deg, #22c55e 0%, #16a34a 100%); color:#fff;">Ya, Kembalikan</button>
    </div>
  </div>
</div>

<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease; border:2px solid rgba(255,255,255,0.2);">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast("✓ {{ session('success') }}", "#10b981");
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast("✗ {{ session('error') }}", "#ef4444");
  });
</script>
@endif

<div class="card mb-4" style="background-color: {{ $cardBgColor }}; backdrop-filter: blur(10px); border:1px solid {{ $borderColor }};">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: rgba(20,162,186,0.5); border-bottom:1px solid {{ $borderColor }};">
    <h5 class="text-white fw-bold mb-0">Laptop Diarsip</h5>
    <div class="d-flex align-items-center gap-2">
        <form method="GET" class="header-controls-form d-flex gap-2" id="filterForm">
            <select name="per_page" class="form-select" id="perPageFilter" style="width:auto; background-color: rgba(255,255,255,0.9); border:1px solid {{ $headerBgColor }}; color:#000;">
                @foreach([10,25,50,100] as $size)
                    <option value="{{ $size }}" {{ request('per_page',10) == $size ? 'selected' : '' }}>{{ $size }} / halaman</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" id="searchInput" placeholder="Cari Laptop.." value="{{ request('search') }}" style="background-color: rgba(255,255,255,0.9); border:1px solid {{ $headerBgColor }}; color:#000;">
        </form>
        <button 
            id="selectionModeBtn"
            class="btn btn-outline-light d-flex align-items-center justify-content-center gap-2 px-3 py-2" 
            style="border-radius: 8px; white-space: nowrap; font-size: 14px; line-height: 1; border: 2px solid rgba(255,255,255,0.5);">
            <i class="bx bx-checkbox-square" style="font-size: 18px;"></i>
            <span>Pilih</span>
        </button>
    </div>
</div>

    {{-- Table Container --}}
    <div id="tableContainer" style="position: relative;">
        <div id="tableContent">
            @include('content.peminjaman.table-arsip')
        </div>
    </div>
</div>

<script>
(function() {
  'use strict';
  
  console.log('📄 Arsip page script loaded');

  let searchTimeout = null;

  // ✅ Apply filters with AJAX
  function applyFilters(page = 1) {
    const perPage = document.getElementById('perPageFilter')?.value || 10;
    const search = document.getElementById('searchInput')?.value || '';

    const params = new URLSearchParams({
      per_page: perPage,
      search: search,
      page: page
    });

    const container = document.getElementById('tableContainer');
    if (!container) return;

    container.classList.add('table-loading');
    
    const spinner = document.createElement('div');
    spinner.className = 'loading-spinner';
    spinner.id = 'loadingSpinner';
    container.appendChild(spinner);

    fetch(`{{ url()->current() }}?${params.toString()}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.text())
    .then(html => {
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const newContent = doc.getElementById('tableContent');
      
      if (newContent) {
        document.getElementById('tableContent').innerHTML = newContent.innerHTML;
        
        const newUrl = `${window.location.pathname}?${params.toString()}`;
        window.history.pushState({}, '', newUrl);
        
        console.log('✅ Table content updated');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      if (typeof window.showToast === 'function') {
        window.showToast('Gagal memuat data', '#ef4444');
      }
    })
    .finally(() => {
      container.classList.remove('table-loading');
      const spinnerEl = document.getElementById('loadingSpinner');
      if (spinnerEl) spinnerEl.remove();
    });
  }

  // ✅ Toast notification
  function showToast(message, bgColor = '#10b981') {
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');
    
    if (!toast || !toastMsg) return;
    
    toastMsg.textContent = message;
    toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${adjustColor(bgColor, -20)} 100%)`;
    toast.style.display = 'block';
    
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    
    setTimeout(() => {
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => toast.style.display = 'none', 300);
    }, 3000);
  }

  function adjustColor(color, percent) {
    const num = parseInt(color.replace('#', ''), 16);
    const amt = Math.round(2.55 * percent);
    const R = (num >> 16) + amt;
    const G = (num >> 8 & 0x00FF) + amt;
    const B = (num & 0x0000FF) + amt;
    return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 +
      (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 +
      (B < 255 ? B < 1 ? 0 : B : 255))
      .toString(16).slice(1);
  }

  // ✅ Initialize page controls (filters, pagination, modals)
  function initPageControls() {
    console.log('🔧 Initializing page controls...');

    // Filter listeners
    const perPageFilter = document.getElementById('perPageFilter');
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');

    if (perPageFilter && !perPageFilter.hasAttribute('data-listener-attached')) {
      perPageFilter.addEventListener('change', () => applyFilters());
      perPageFilter.setAttribute('data-listener-attached', 'true');
    }
    
    if (searchInput && !searchInput.hasAttribute('data-listener-attached')) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => applyFilters(), 500);
      });
      searchInput.setAttribute('data-listener-attached', 'true');
    }

    if (filterForm && !filterForm.hasAttribute('data-listener-attached')) {
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
      });
      filterForm.setAttribute('data-listener-attached', 'true');
    }

    console.log('✅ Page controls initialized');
  }

  // ✅ GLOBAL EVENT DELEGATION - Persistent across Livewire navigation
  if (!window.arsipPageInitialized) {
    console.log('🎯 Setting up global event delegation...');

    // Handle pagination
    document.addEventListener('click', function(e) {
      if (e.target.matches('.pagination a')) {
        e.preventDefault();
        const url = new URL(e.target.href);
        const page = url.searchParams.get('page') || 1;
        applyFilters(page);
      }
    });

    // Handle modal buttons
    document.addEventListener('click', function(e) {
      const modal = document.getElementById('restoreModal');
      if (!modal) return;

      // Batal button
      if (e.target.id === 'btnCancelRestore' || e.target.closest('#btnCancelRestore')) {
        e.preventDefault();
        e.stopPropagation();
        console.log('❌ Cancel restore');
        modal.style.display = 'none';
        return;
      }
      
      // Confirm button
      if (e.target.id === 'btnConfirmRestore' || e.target.closest('#btnConfirmRestore')) {
        e.preventDefault();
        e.stopPropagation();
        console.log('✅ Confirm restore');
        
        const btn = document.getElementById('btnConfirmRestore');
        const laptopId = btn.getAttribute('data-laptop-id');
        
        if (!laptopId) {
          console.error('❌ Laptop ID not found');
          return;
        }
        
        btn.disabled = true;
        btn.textContent = 'Memproses...';
        
        fetch(`/laptop/restore/${laptopId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
          }
        })
        .then(response => response.json())
        .then(data => {
          modal.style.display = 'none';
          
          if (data.success) {
            showToast(data.message || 'Laptop berhasil dikembalikan!', '#10b981');
            setTimeout(() => applyFilters(), 1000);
          } else {
            showToast(data.message || 'Gagal mengembalikan laptop', '#ef4444');
          }
        })
        .catch(error => {
          console.error('Error:', error);
          modal.style.display = 'none';
          showToast('Terjadi kesalahan', '#ef4444');
        })
        .finally(() => {
          btn.disabled = false;
          btn.textContent = 'Ya, Kembalikan';
        });
        
        return;
      }
      
      // Close on backdrop click
      if (e.target.id === 'restoreModal') {
        e.target.style.display = 'none';
      }
    });

    window.arsipPageInitialized = true;
    console.log('✅ Global event delegation setup complete');
  }

  // Make functions globally available
  window.applyFilters = applyFilters;
  window.showToast = showToast;

  // ✅ Initialize on first load
  initPageControls();

  // ✅ Re-initialize after Livewire navigation
  document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navigated - reinitializing controls...');
    initPageControls();
  });

  // ✅ Show session toasts
  @if(session('success'))
    showToast("✓ {{ session('success') }}", "#10b981");
  @endif

  @if(session('error'))
    showToast("✗ {{ session('error') }}", "#ef4444");
  @endif

})();
</script>
@endsection