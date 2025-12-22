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

<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease; border:2px solid rgba(255,255,255,0.2);">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

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
  
  console.log('🛑 BLOCKING initLaptopTable infinite loop...');
  
  window.initLaptopTable = function() {
    console.log('❌ initLaptopTable BLOCKED on arsip page');
    return false;
  };
  
  const highestTimeoutId = setTimeout(';');
  for (let i = 0; i < highestTimeoutId; i++) {
    clearTimeout(i);
  }
  clearTimeout(highestTimeoutId);
  
  console.log('✅ Cleared all pending timeouts');
  // ===== END KILL INFINITE LOOP =====

  let searchTimeout = null;

  function applyFilters(page = 1) {
    const perPage = document.getElementById('perPageFilter')?.value || 10;
    const search = document.getElementById('searchInput')?.value || '';

    const params = new URLSearchParams({
      per_page: perPage,
      search: search,
      page: page
    });

    const container = document.getElementById('tableContainer');
    if (!container) {
      console.log('❌ Table container not found');
      return;
    }

     🔑 SAVE selection mode state before reload
    const wasInSelectionMode = window.arsipTableSelectionMode || false;
    const currentSelectionsArray = window.selectedLaptopsGlobal ? Array.from(window.selectedLaptopsGlobal) : [];
    
    console.log('💾 Saving state before AJAX:', { 
      selectionMode: wasInSelectionMode, 
      selectedCount: currentSelectionsArray.length,
      selectedIds: currentSelectionsArray
    });

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
      
      const currentContent = document.getElementById('tableContent');
      
      if (newContent && currentContent) {
        currentContent.innerHTML = newContent.innerHTML;
        console.log('✅ Table content updated');
      } else {
        console.log('⚠️ tableContent not found, trying full container');
        const newContainer = doc.getElementById('tableContainer');
        if (newContainer) {
          container.innerHTML = newContainer.innerHTML;
          console.log('✅ Full container updated');
        } else {
          console.error('❌ Could not find content to update');
          throw new Error('Content not found in response');
        }
      }
      
      if (wasInSelectionMode) {
        console.log('🔄 Restoring selection mode...');
        console.log('📦 Saved selections to restore:', currentSelectionsArray);
        
        setTimeout(() => {
          window.selectedLaptopsGlobal = new Set(currentSelectionsArray);
          window.arsipTableSelectionMode = true;
          
          console.log('🔧 Re-initialized global state:', {
            selectedCount: window.selectedLaptopsGlobal.size,
            selectedIds: Array.from(window.selectedLaptopsGlobal)
          });
          
          window.arsipTableInitialized = false;
          
          if (typeof window.initializeTable === 'function') {
            console.log('🔄 Calling initializeTable...');
            window.initializeTable();
            console.log('✅ Selection mode restored with', window.selectedLaptopsGlobal.size, 'selections');
          } else {
            console.error('❌ initializeTable function not found!');
          }
          
          if (typeof window.updateBulkActionBar === 'function') {
            console.log('🔄 Updating bulk action bar...');
            window.updateBulkActionBar();
          }
        }, 150);
      }
      
      const newUrl = `${window.location.pathname}?${params.toString()}`;
      window.history.pushState({}, '', newUrl);
      
      console.log('🎯 AJAX Complete - Final Check:', {
        globalSize: window.selectedLaptopsGlobal?.size,
        globalIds: window.selectedLaptopsGlobal ? Array.from(window.selectedLaptopsGlobal) : [],
        selectionModeActive: window.arsipTableSelectionMode
      });
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

  function initPageControls() {
    console.log('🔧 Initializing page controls...');

    const perPageFilter = document.getElementById('perPageFilter');
    const searchInput = document.getElementById('searchInput');
    const filterForm = document.getElementById('filterForm');
    
    if (!perPageFilter || !searchInput || !filterForm) {
      console.log('❌ Filter elements not found');
      return;
    }

    if (!perPageFilter.hasAttribute('data-listener-attached')) {
      perPageFilter.addEventListener('change', () => applyFilters());
      perPageFilter.setAttribute('data-listener-attached', 'true');
      console.log('✅ Per page filter attached');
    }
    
    if (!searchInput.hasAttribute('data-listener-attached')) {
      searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => applyFilters(), 500);
      });
      searchInput.setAttribute('data-listener-attached', 'true');
      console.log('✅ Search input attached');
    }

    if (!filterForm.hasAttribute('data-listener-attached')) {
      filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
      });
      filterForm.setAttribute('data-listener-attached', 'true');
      console.log('Filter form attached');
    }

    console.log('Page controls initialized');
  }

  if (!window.arsipPageInitialized) {
    console.log('🎯 Setting up global event delegation...');

    document.addEventListener('click', function(e) {
      if (e.target.matches('.pagination a')) {
        e.preventDefault();
        const url = new URL(e.target.href);
        const page = url.searchParams.get('page') || 1;
        applyFilters(page);
      }
    });

    window.arsipPageInitialized = true;
    console.log('✅ Global event delegation setup complete');
  }

  window.applyFilters = applyFilters;
  window.showToast = showToast;

  initPageControls();
  
  function setupSelectionButton() {
    const selectionModeBtn = document.getElementById('selectionModeBtn');
    
    if (!selectionModeBtn) {
      console.log('❌ Selection mode button not found');
      return;
    }
    
    if (selectionModeBtn.hasAttribute('data-listener-attached')) {
      console.log('🔄 Re-attaching selection button listener');
      const newBtn = selectionModeBtn.cloneNode(true);
      selectionModeBtn.parentNode.replaceChild(newBtn, selectionModeBtn);
    }
    
    // Get fresh reference
    const btn = document.getElementById('selectionModeBtn');
    
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      console.log('🎯 Selection mode button clicked!');
      m
      if (typeof window.initializeTable === 'function') {
        window.initializeTable();
      }
      
      const currentMode = window.arsipTableSelectionMode || false;
      console.log('Current mode:', currentMode, '→ Will become:', !currentMode);
      
      if (currentMode) {
        // Deactivate
        if (typeof window.deactivateSelectionMode === 'function') {
          window.deactivateSelectionMode();
        }
      } else {
        // Activate
        if (typeof window.activateSelectionMode === 'function') {
          window.activateSelectionMode();
        }
      }
    });
    
    btn.setAttribute('data-listener-attached', 'true');
    console.log('✅ Selection mode button listener attached');
  }
  
  setupSelectionButton();

  document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navigated - reinitializing controls...');
    
    window.initLaptopTable = function() {
      console.log('❌ initLaptopTable BLOCKED on arsip page');
      return false;
    };
    
    setTimeout(function() {
      const perPageFilter = document.getElementById('perPageFilter');
      const searchInput = document.getElementById('searchInput');
      
      if (perPageFilter && searchInput) {
        console.log('✅ Elements found, removing old listeners');
        perPageFilter.removeAttribute('data-listener-attached');
        searchInput.removeAttribute('data-listener-attached');
        document.getElementById('filterForm')?.removeAttribute('data-listener-attached');
        
        initPageControls();
      } else {
        console.log('❌ Elements not found after navigation');
      }
    }, 100);
  });

  @if(session('success'))
    showToast("✓ {{ session('success') }}", "#10b981");
  @endif

  @if(session('error'))
    showToast("✗ {{ session('error') }}", "#ef4444");
  @endif

})();
</script>
@endsection