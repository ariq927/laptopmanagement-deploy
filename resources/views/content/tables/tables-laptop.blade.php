@extends('layouts.contentNavbarLayout')

@section('title', 'Daftar Laptop')

@section('content')
<!-- Arsip -->
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

<!-- Bulk Archive Modal -->
<div id="bulkArchiveModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:400px; max-width:500px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Arsipkan <span id="bulkArchiveCount">0</span> Laptop</h4>
    <p style="color:#fff; margin-bottom:15px; opacity:0.9;">Masukkan keterangan pengarsipan:</p>
    <textarea id="bulkKeteranganInput" class="form-control" rows="3" placeholder="Contoh: Rusak pada bagian keyboard" style="margin-bottom:20px; border:2px solid rgba(255,255,255,0.3); background:rgba(255,255,255,0.95);"></textarea>
    <div style="display:flex; gap:10px; justify-content:flex-end;">
      <button onclick="closeBulkArchiveModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmBulkArchive()" class="btn btn-danger" style="font-weight:bold; padding:8px 20px;">Arsipkan Semua</button>
    </div>
  </div>
</div>

<!-- Restore -->
<div id="restoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #10b981 0%, #059669 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:400px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan Laptop</h4>
    <p style="color:#fff; margin-bottom:15px; opacity:0.9; text-align:center;">Apakah Anda yakin ingin mengembalikan laptop ini dari arsip?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeRestoreModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmRestore()" class="btn btn-success" style="font-weight:bold; padding:8px 20px;">Ya, Kembalikan</button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease; border:2px solid rgba(255,255,255,0.2);">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

<style>
  .header-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
  }

  .header-row {
    display: grid;
    grid-template-columns: auto minmax(200px, 300px) auto;
    gap: 12px;
    align-items: center;
  }

  .filter-group {
    display: flex;
    gap: 8px;
    align-items: center;
  }

  .filter-dropdown {
    position: relative;
    display: inline-block;
    z-index: 99998 !important;
  }

  .filter-button {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(255,255,255,0.9);
    border: 1px solid #14a2ba;
    border-radius: 8px;
    color: #000;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
  }

  .filter-button:hover {
    background: rgba(255,255,255,1);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }

  .filter-menu {
    display: none;
    position: absolute;
    top: calc(100% + 4px);
    right: 0;
    background: white;
    border: 1px solid #14a2ba;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 200px;
    max-height: 70vh;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 99999 !important;
    pointer-events: auto !important; 
  }

  .filter-menu::-webkit-scrollbar {
    width: 6px;
  }

  .filter-menu::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 8px;
  }

  .filter-menu::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 8px;
  }

  .filter-menu::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
  }

  .filter-menu.active {
    display: block !important;
  }

  .filter-section {
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
  }

  .filter-section:last-child {
    border-bottom: none;
  }

  .filter-section-title {
    font-size: 11px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
  }

  .filter-option {
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 6px;
    font-size: 14px;
    color: #374151;
    transition: all 0.2s ease;
  }

  .filter-option:hover {
    background: #f3f4f6;
  }

  .filter-option.active {
    background: #dbeafe;
    color: #1e40af;
    font-weight: 500;
  }

  /* Selection Mode Styles */
  .selection-mode-active {
    background-color: rgba(20,162,186,0.5) !important;
    border: 2px solid rgba(20,162,186,0.6) !important;
    box-shadow: 0 0 20px rgba(20,162,186,0.3);
  }

  .bulk-action-bar {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    padding: 15px 25px;
    border-radius: 50px;
    box-shadow: 0 10px 40px rgba(20, 162, 186, 0.5);
    display: flex;
    align-items: center;
    gap: 20px;
    z-index: 9998;
    transition: transform 0.3s ease;
    border: 2px solid rgba(255,255,255,0.3);
  }

  .bulk-action-bar.active {
    transform: translateX(-50%) translateY(0);
  }

  .bulk-action-bar-text {
    color: white;
    font-weight: bold;
    font-size: 15px;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
  }

  .bulk-action-btn {
    padding: 10px 24px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 14px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.2);
  }

  .laptop-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #14a2ba;
  }

  @media (max-width: 768px) {
    .header-wrapper {
      flex-direction: column;
      align-items: flex-start;
    }

    .header-row {
      grid-template-columns: 1fr;
      gap: 10px;
      width: 100%;
    }

    .filter-group {
      width: 100%;
    }

    .filter-group input {
      flex: 1;
    }

    .filter-button {
      width: auto;
    }

    .filter-menu {
      right: 0;
      left: auto;
    }

    .bulk-action-bar {
      bottom: 10px;
      padding: 12px 20px;
      flex-wrap: wrap;
      max-width: 90%;
    }
  }
</style>

<div class="card" id="laptopTableContainer"
    style="background-color: rgba(20,162,186,0.5); backdrop-filter: blur(10px); border: 1px solid rgba(20,162,186,0.3); transition: all 0.3s ease;">
  <div class="card-header"
      style="background-color: rgba(20,162,186,0.5); border-bottom: 1px solid rgba(20,162,186,0.3); padding: 16px 20px;">
    <div class="header-wrapper">
      <h5 style="color: #fff; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); margin: 0;">
        Daftar Laptop
      </h5>
      <div class="header-row">
        <!-- Tambah Laptop / Selection Mode Toggle -->
        <div style="display: flex; gap: 8px;">
          <a href="/laptop/create" 
            id="addLaptopBtn"
            class="btn btn-success d-flex align-items-center justify-content-center gap-2 px-3 py-2" 
            style="border-radius: 8px; white-space: nowrap; font-size: 14px; line-height: 1; width: fit-content;">
            <i class="bx bx-plus" style="font-size: 18px;"></i>
            <span>Tambah Laptop</span>
          </a>
          
          <button 
            id="selectionModeBtn"
            class="btn btn-outline-light d-flex align-items-center justify-content-center gap-2 px-3 py-2" 
            style="border-radius: 8px; white-space: nowrap; font-size: 14px; line-height: 1; width: fit-content; border: 2px solid rgba(255,255,255,0.5);">
            <i class="bx bx-checkbox-square" style="font-size: 18px;"></i>
            <span>Pilih</span>
          </button>
        </div>

        <!-- Search Input -->
        <input type="text" id="searchInput" class="form-control"
              placeholder="Cari laptop..."
              style="background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba; color:#000;">

        <!-- Filter Dropdown -->
        <div class="filter-dropdown">
          <button class="filter-button" id="filterButton">
            <i class="bx bx-filter-alt" style="font-size: 18px;"></i>
            <span>Filter</span>
            <i class="bx bx-chevron-down" style="font-size: 16px;"></i>
          </button>
          
          <div class="filter-menu" id="filterMenu">
            <!-- Filter Status -->
            <div class="filter-section">
              <div class="filter-section-title">Status Laptop</div>
              <div class="filter-option active" data-status="">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                Semua Status
              </div>
              <div class="filter-option" data-status="in stock">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                In Stock
              </div>
              <div class="filter-option" data-status="in use">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                In Use
              </div>
              <div class="filter-option" data-status="diarsip">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                Diarsip
              </div>
              <div class="filter-option" data-status="sold">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                Terjual
              </div>
            </div>

            <!-- Items per Page -->
            <div class="filter-section">
              <div class="filter-section-title">Item per Halaman</div>
              <div class="filter-option active" data-perpage="10">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                10 per halaman
              </div>
              <div class="filter-option" data-perpage="25">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                25 per halaman
              </div>
              <div class="filter-option" data-perpage="50">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                50 per halaman
              </div>
              <div class="filter-option" data-perpage="100">
                <i class="bx bx-check" style="font-size: 16px; margin-right: 4px; visibility: hidden;"></i>
                100 per halaman
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="filter-section" style="padding: 10px 12px;">
              <div style="display: flex; gap: 8px;">
                <button id="cancelFilterBtn" style="flex: 1; padding: 8px; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;">
                  Batal
                </button>
                <button id="applyFilterBtn" style="flex: 1; padding: 8px; background: #14a2ba; color: white; border: 1px solid #14a2ba; border-radius: 6px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s ease;">
                  Terapkan Filter
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered" id="laptopTable" style="background-color:transparent;">
      <thead style="background-color: rgba(20,162,186,0.5);">
        <tr>
          <th style="color:#fff; font-weight:bold; width: 50px; display: none;" id="checkboxHeader">
            <input type="checkbox" id="selectAllCheckbox" class="laptop-checkbox" style="margin: 0;">
          </th>
          <th style="color:#fff; font-weight:bold;">No</th>
          <th style="color:#fff; font-weight:bold;">Kode Laptop</th>
          <th style="color:#fff; font-weight:bold;">Merek</th>
          <th style="color:#fff; font-weight:bold;">Tipe</th>
          <th style="color:#fff; font-weight:bold;">Spesifikasi</th>
          <th style="color:#fff; font-weight:bold;">Status</th>
          <th style="color:#fff; font-weight:bold;">Aksi</th>
        </tr>
      </thead>
      <tbody id="laptopTableBody">
        <tr style="background-color: rgba(20,162,186,0.1)"><td colspan="8" class="text-center">Memuat data...</td></tr>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3" style="padding:10px 20px;">
    <span id="tableInfo" style="color:#fff; font-weight:bold;">
      Menampilkan 0 - 0 dari 0 data
    </span>
    <div class="d-flex gap-2 align-items-center" id="paginationContainer"></div>
  </div>
</div>

<!-- Bulk Action Bar -->
<div class="bulk-action-bar" id="bulkActionBar">
  <span class="bulk-action-bar-text">
    <span id="selectedCount">0</span> laptop dipilih
  </span>
  <button class="bulk-action-btn" onclick="bulkArchive()" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); color: white; box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);">
    <i class="bx bx-archive" style="font-size: 16px; margin-right: 4px;"></i>
    Arsipkan
  </button>
  <button class="bulk-action-btn" onclick="cancelSelection()" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.4);">
    Batal
  </button>
</div>



<script>
  // ✅ SINGLE SCRIPT - Scoped initialization
  (function() {
    let initialized = false;
    
    function initLaptopTable() {
      if (initialized) {
        console.log('⚠️ Already initialized, skipping...');
        return;
      }
      
      console.log('🔄 Initializing laptop table...');
      
      // ============================================
      // STATE VARIABLES
      // ============================================
      let search = '';
      let perPage = 10;
      let currentPage = 1;
      let statusFilter = '';
      let laptopIdToArchive = null;
      let laptopIdToRestore = null;
      let selectionMode = false;
      let selectedLaptops = new Set();
      let tempStatusFilter = '';
      let tempPerPage = 10;

      // ============================================
      // DOM ELEMENTS
      // ============================================
      const tableBody = document.getElementById('laptopTableBody');
      const tableInfo = document.getElementById('tableInfo');
      const paginationContainer = document.getElementById('paginationContainer');
      const searchInput = document.getElementById('searchInput');
      const filterButton = document.getElementById('filterButton');
      const filterMenu = document.getElementById('filterMenu');
      const selectionModeBtn = document.getElementById('selectionModeBtn');
      const bulkActionBar = document.getElementById('bulkActionBar');
      const checkboxHeader = document.getElementById('checkboxHeader');
      const selectAllCheckbox = document.getElementById('selectAllCheckbox');
      const laptopTableContainer = document.getElementById('laptopTableContainer');
      const applyFilterBtn = document.getElementById('applyFilterBtn');
      const cancelFilterBtn = document.getElementById('cancelFilterBtn');

      // Check if required elements exist
      if (!tableBody || !searchInput || !filterButton) {
        console.error('❌ Required elements not found, retrying in 100ms...');
        setTimeout(initLaptopTable, 100);
        return;
      }

      console.log('✅ All elements found, setting up...');
      initialized = true;

      // ============================================
      // RENDER FUNCTIONS
      // ============================================

      const renderStatusBadge = (status) => {
        const statusStyles = {
          'in stock': 'background:#d1fae5; color:#065f46; border:1px solid #6ee7b7;',
          'in use': 'background:#dbeafe; color:#1e3a8a; border:1px solid #93c5fd;',
          'diarsip': 'background:#f3f4f6; color:#374151; border:1px solid #d1d5db;',
          'sold': 'background:#fef3c7; color:#78350f; border:1px solid #fde68a;'
        };
        
        const statusText = {
          'in stock': 'In Stock',
          'in use': 'In Use',
          'diarsip': 'Diarsip',
          'sold': 'Terjual'
        };

        return `<span style="display:inline-block; padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600; ${statusStyles[status] || ''}">${statusText[status] || status}</span>`;
      };

      const renderActions = (laptop) => {
        if (selectionMode) {
          return '<span style="color:#fff; font-size:12px; opacity:0.7;">Mode Pilih Aktif</span>';
        }

        const baseBtnStyle = `font-weight:600; border-radius:8px; padding:6px 14px; font-size:13px; transition:all 0.25s ease;`;

        if (laptop.status === 'in stock') {
          return `
            <div style="display:flex; gap:8px; flex-wrap:wrap;">
              <a href="/peminjaman/create/${laptop.id}" class="btn" style="${baseBtnStyle} background:#e0e7ff; color:#1e3a8a; border:1px solid #c7d2fe;" onmouseover="this.style.background='#c7d2fe';" onmouseout="this.style.background='#e0e7ff';">Pakai</a>
              <button class="btn" onclick="event.stopPropagation(); window.location.href='/laptop/${laptop.id}/sold'" style="${baseBtnStyle} background:#fef3c7; color:#78350f; border:1px solid #fde68a;" onmouseover="this.style.background='#fde68a';" onmouseout="this.style.background='#fef3c7';">Jual</button>
              <button class="btn" onclick="event.stopPropagation(); archiveLaptop(${laptop.id})" style="${baseBtnStyle} background:#fee2e2; color:#7f1d1d; border:1px solid #fecaca;" onmouseover="this.style.background='#fecaca';" onmouseout="this.style.background='#fee2e2';">Arsip</button>
            </div>`;
        } else if (laptop.status === 'in use') {
          return `<button class="btn" disabled style="${baseBtnStyle} background:#f1f5f9; color:#475569; border:1px solid #e2e8f0;">In Use</button>`;
        } else if (laptop.status === 'diarsip') {
          return `<button class="btn" disabled style="${baseBtnStyle} background:#f9fafb; color:#9ca3af; border:1px solid #e5e7eb;">Diarsip</button>`;
        } else if (laptop.status === 'sold') {
          return `<button class="btn" disabled style="${baseBtnStyle} background:#fef3c7; color:#78350f; border:1px solid #fde68a;">Terjual</button>`;
        }
        return '';
      };

      const renderTable = (data, start, pagination) => {
        if (!data || data.length === 0) {
          const colspan = selectionMode ? '8' : '7';
          tableBody.innerHTML = `<tr style="background-color:rgba(20,162,186,0.1);"><td colspan="${colspan}" class="text-center" style="color:#fff;font-weight:bold;">Belum ada data laptop</td></tr>`;
          return;
        }
        
        tableBody.innerHTML = data.map((laptop, index) => {
          const canArchive = laptop.status === 'in stock';
          const isSelected = selectedLaptops.has(laptop.id);
          
          let checkboxCell = '';
          if (selectionMode) {
            if (canArchive) {
              checkboxCell = `<td style="text-align: center;" onclick="event.stopPropagation();"><input type="checkbox" class="laptop-item-checkbox" data-laptop-id="${laptop.id}" data-can-archive="true" ${isSelected ? 'checked' : ''} onchange="handleCheckboxChange(${laptop.id}, this.checked)"></td>`;
            } else {
              checkboxCell = `<td style="text-align: center;"><input type="checkbox" class="laptop-item-checkbox" disabled style="opacity: 0.3; cursor: not-allowed;"></td>`;
            }
          }
          
          const rowClick = selectionMode && canArchive ? `onclick="toggleLaptopSelection(${laptop.id})"` : `onclick="window.location.href='/laptop/${laptop.id}/edit'"`;
          
          return `
          <tr style="background-color:rgba(20,162,186,0.1); transition:all 0.3s ease; cursor: pointer;" onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'; this.style.transform='scale(1.02)'" onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'; this.style.transform='scale(1)'" ${rowClick}>
            ${checkboxCell}
            <td style="color:#fff;font-weight:bold;">${(pagination.from || 0) + index}</td>
            <td style="color:#fff;font-weight:bold;">${laptop.kode}</td>
            <td style="color:#fff;font-weight:bold;">${laptop.merek}</td>
            <td style="color:#fff;font-weight:bold;">${laptop.tipe}</td>
            <td style="color:#fff;font-weight:bold;">${laptop.spesifikasi}</td>
            <td>${renderStatusBadge(laptop.status)}</td>
            <td onclick="event.stopPropagation();">${renderActions(laptop)}</td>
          </tr>`;
        }).join('');
      };

      const renderPagination = (pagination) => {
        const { current_page, last_page } = pagination;
        let html = '';
        
        const makeButton = (page, text, disabled = false) => {
          if (disabled) {
            return `<button disabled class="btn btn-outline-light" style="font-weight:bold;width:40px;height:40px;border-radius:50%;padding:0;opacity:0.5;cursor:not-allowed;">${text}</button>`;
          }
          return `<button class="btn btn-outline-light page-btn" data-page="${page}" style="font-weight:bold;width:40px;height:40px;border-radius:50%;padding:0;">${text}</button>`;
        };
        
        html += makeButton(current_page - 1, '‹', !pagination.prev_page_url);
        for (let i = 1; i <= last_page; i++) {
          const isActive = i === current_page;
          html += `<button class="btn ${isActive ? 'btn-light' : 'btn-outline-light'} page-btn" data-page="${i}" style="font-weight:bold;width:40px;height:40px;border-radius:50%;padding:0;">${i}</button>`;
        }
        html += makeButton(current_page + 1, '›', !pagination.next_page_url);
        
        paginationContainer.innerHTML = html;
        
        document.querySelectorAll('.page-btn').forEach(btn => {
          btn.addEventListener('click', function() {
            fetchData(parseInt(this.dataset.page));
          });
        });
      };

      // ============================================
      // DATA FUNCTIONS
      // ============================================

      const fetchData = (page = 1) => {
        currentPage = page;
        console.log(`📡 Fetching: page=${page}, search="${search}", perPage=${perPage}, status="${statusFilter}"`);
        
        tableBody.innerHTML = '<tr style="background-color:rgba(20,162,186,0.1);"><td colspan="8" class="text-center" style="color:#fff;">Memuat data...</td></tr>';
        
        fetch(`/laptop/data?search=${search}&page=${page}&per_page=${perPage}&status=${statusFilter}`)
          .then(res => {
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            return res.json();
          })
          .then(json => {
            console.log('✅ Data received:', json.total, 'items');
            renderTable(json.data, json.from, json);
            renderPagination(json);
            tableInfo.innerText = `Menampilkan ${json.from || 0} - ${json.to || 0} dari ${json.total || 0} data`;
          })
          .catch(err => {
            console.error('❌ Fetch error:', err);
            tableBody.innerHTML = `<tr style="background-color:rgba(20,162,186,0.1);"><td colspan="8" class="text-center" style="color:#fff;">Error: ${err.message}</td></tr>`;
          });
      };

      const updateBulkActionBar = () => {
        const count = selectedLaptops.size;
        document.getElementById('selectedCount').textContent = count;
        
        if (count > 0) {
          bulkActionBar.classList.add('active');
        } else {
          bulkActionBar.classList.remove('active');
        }
        
        const checkboxes = document.querySelectorAll('.laptop-item-checkbox:not([disabled])');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        selectAllCheckbox.checked = allChecked;
      };

      const showToast = (message, bgColor = "#10b981") => {
        const toast = document.getElementById('toastNotification');
        const toastMsg = document.getElementById('toastMessage');
        toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)`;
        toastMsg.innerText = message;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.transform = 'translateX(0)'; }, 10);
        setTimeout(() => {
          toast.style.transform = 'translateX(400px)';
          setTimeout(() => { toast.style.display = 'none'; }, 300);
        }, 3000);
      };

      // ============================================
      // GLOBAL WINDOW FUNCTIONS (untuk onclick HTML)
      // ============================================

      window.handleCheckboxChange = (laptopId, checked) => {
        if (checked) {
          selectedLaptops.add(laptopId);
        } else {
          selectedLaptops.delete(laptopId);
        }
        updateBulkActionBar();
      };

      window.toggleLaptopSelection = (laptopId) => {
        const checkbox = document.querySelector(`.laptop-item-checkbox[data-laptop-id="${laptopId}"]`);
        if (checkbox && !checkbox.disabled) {
          checkbox.checked = !checkbox.checked;
          handleCheckboxChange(laptopId, checkbox.checked);
        }
      };

      window.archiveLaptop = (id) => {
        laptopIdToArchive = id;
        document.getElementById('archiveModal').style.display = 'block';
        document.getElementById('keteranganInput').value = '';
        document.getElementById('keteranganInput').focus();
      };

      window.closeArchiveModal = () => {
        document.getElementById('archiveModal').style.display = 'none';
        laptopIdToArchive = null;
      };

      window.confirmArchive = () => {
        const keterangan = document.getElementById('keteranganInput').value.trim();
        if (keterangan === "") {
          showToast("⚠️ Keterangan wajib diisi!", "#f59e0b");
          return;
        }
        fetch(`/api/laptop/${laptopIdToArchive}/archive`, {
          method: 'PATCH',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ keterangan })
        })
          .then(res => {
            if (!res.ok) throw new Error();
            window.closeArchiveModal();
            showToast("✓ Laptop berhasil diarsip!", "#10b981");
            fetchData(currentPage);
          })
          .catch(() => showToast("✗ Gagal mengarsipkan laptop", "#ef4444"));
      };

      window.cancelSelection = () => {
        selectionMode = false;
        selectedLaptops.clear();
        selectionModeBtn.click();
      };

      window.bulkArchive = () => {
        if (selectedLaptops.size === 0) {
          showToast("⚠️ Pilih minimal 1 laptop!", "#f59e0b");
          return;
        }
        document.getElementById('bulkArchiveCount').textContent = selectedLaptops.size;
        document.getElementById('bulkArchiveModal').style.display = 'block';
        document.getElementById('bulkKeteranganInput').value = '';
        document.getElementById('bulkKeteranganInput').focus();
      };

      window.closeBulkArchiveModal = () => {
        document.getElementById('bulkArchiveModal').style.display = 'none';
      };

      window.confirmBulkArchive = async () => {
        const keterangan = document.getElementById('bulkKeteranganInput').value.trim();
        if (keterangan === "") {
          showToast("⚠️ Keterangan wajib diisi!", "#f59e0b");
          return;
        }

        const laptopIds = Array.from(selectedLaptops);
        let successCount = 0;
        let failCount = 0;

        for (const id of laptopIds) {
          try {
            const response = await fetch(`/api/laptop/${id}/archive`, {
              method: 'PATCH',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ keterangan })
            });
            if (response.ok) successCount++;
            else failCount++;
          } catch (error) {
            failCount++;
          }
        }

        window.closeBulkArchiveModal();
        if (successCount > 0) showToast(`✓ ${successCount} laptop berhasil diarsipkan!`, "#10b981");
        if (failCount > 0) showToast(`⚠️ ${failCount} laptop gagal diarsipkan`, "#f59e0b");
        
        selectedLaptops.clear();
        selectionMode = false;
        selectionModeBtn.click();
        fetchData(currentPage);
      };

      window.restoreLaptop = (id) => {
        laptopIdToRestore = id;
        document.getElementById('restoreModal').style.display = 'block';
      };

      window.closeRestoreModal = () => {
        document.getElementById('restoreModal').style.display = 'none';
        laptopIdToRestore = null;
      };

      window.confirmRestore = () => {
        fetch(`/api/laptop/${laptopIdToRestore}/restore`, { method: 'PATCH' })
          .then(res => {
            if (!res.ok) throw new Error();
            window.closeRestoreModal();
            showToast("✓ Laptop berhasil dikembalikan!", "#10b981");
            fetchData(currentPage);
          })
          .catch(() => showToast("✗ Gagal mengembalikan laptop", "#ef4444"));
      };

      // ============================================
      // EVENT LISTENERS
      // ============================================

      // Selection Mode Toggle
      selectionModeBtn.addEventListener('click', () => {
        selectionMode = !selectionMode;
        selectedLaptops.clear();
        
        if (selectionMode) {
          selectionModeBtn.innerHTML = '<i class="bx bx-x" style="font-size: 18px;"></i><span>Batal Pilih</span>';
          selectionModeBtn.style.background = '#ef4444';
          selectionModeBtn.style.borderColor = '#ef4444';
          selectionModeBtn.style.color = 'white';
          checkboxHeader.style.display = 'table-cell';
          laptopTableContainer.classList.add('selection-mode-active');
        } else {
          selectionModeBtn.innerHTML = '<i class="bx bx-checkbox-square" style="font-size: 18px;"></i><span>Pilih</span>';
          selectionModeBtn.style.background = '';
          selectionModeBtn.style.borderColor = 'rgba(255,255,255,0.5)';
          selectionModeBtn.style.color = '';
          checkboxHeader.style.display = 'none';
          bulkActionBar.classList.remove('active');
          laptopTableContainer.classList.remove('selection-mode-active');
          selectAllCheckbox.checked = false;
        }
        fetchData(currentPage);
      });

      // Select All Checkbox
      selectAllCheckbox.addEventListener('change', (e) => {
        const checkboxes = document.querySelectorAll('.laptop-item-checkbox');
        checkboxes.forEach(cb => {
          const laptopId = parseInt(cb.dataset.laptopId);
          const canArchive = cb.dataset.canArchive === 'true';
          if (canArchive) {
            cb.checked = e.target.checked;
            if (e.target.checked) selectedLaptops.add(laptopId);
            else selectedLaptops.delete(laptopId);
          }
        });
        updateBulkActionBar();
      });

      // Search Input
      searchInput.addEventListener('input', (e) => {
        search = e.target.value;
        fetchData(1);
      });

// ============================================
// FILTER FUNCTIONALITY - ULTIMATE FIX
// ============================================

let isFilterOpen = false;

filterMenu.addEventListener('click', (e) => {
  e.stopPropagation();
  console.log('📋 Clicked inside filter menu');
});

filterButton.addEventListener('click', function(e) {
  e.preventDefault();
  e.stopPropagation();
  
  isFilterOpen = !isFilterOpen;
  console.log('🔘 Filter toggle clicked, new state:', isFilterOpen);
  
  if (isFilterOpen) {
    filterMenu.style.display = 'block';
    filterMenu.classList.add('active');
    console.log('✅ Filter OPENED');
  } else {
    filterMenu.style.display = 'none';
    filterMenu.classList.remove('active');
    console.log('❌ Filter CLOSED');
  }
});

document.addEventListener('click', function(e) {
  if (isFilterOpen) {
    const dropdown = e.target.closest('.filter-dropdown');
    if (!dropdown) {
      console.log('🚪 Clicked outside, closing filter');
      isFilterOpen = false;
      filterMenu.style.display = 'none';
      filterMenu.classList.remove('active');
    }
  }
});

// Status Filter Options
const statusOptions = filterMenu.querySelectorAll('[data-status]');
statusOptions.forEach(option => {
  option.addEventListener('click', function(e) {
    e.stopPropagation();
    console.log('📌 Status selected:', this.dataset.status);
    
    statusOptions.forEach(opt => {
      opt.classList.remove('active');
      opt.querySelector('i').style.visibility = 'hidden';
    });
    this.classList.add('active');
    this.querySelector('i').style.visibility = 'visible';
    tempStatusFilter = this.dataset.status;
  });
});

// Per Page Filter Options
const perPageOptions = filterMenu.querySelectorAll('[data-perpage]');
perPageOptions.forEach(option => {
  option.addEventListener('click', function(e) {
    e.stopPropagation();
    console.log('📊 Per page selected:', this.dataset.perpage);
    
    perPageOptions.forEach(opt => {
      opt.classList.remove('active');
      opt.querySelector('i').style.visibility = 'hidden';
    });
    this.classList.add('active');
    this.querySelector('i').style.visibility = 'visible';
    tempPerPage = this.dataset.perpage;
  });
});

// Apply Filter Button
applyFilterBtn.addEventListener('click', function(e) {
  e.stopPropagation();
  console.log('✅ Applying filter...');
  
  statusFilter = tempStatusFilter;
  perPage = parseInt(tempPerPage);
  
  isFilterOpen = false;
  filterMenu.style.display = 'none';
  filterMenu.classList.remove('active');
  
  console.log('📊 Applied:', { statusFilter, perPage });
  fetchData(1);
});

cancelFilterBtn.addEventListener('click', function(e) {
  e.stopPropagation();
  console.log('❌ Cancelling filter...');
  
  tempStatusFilter = statusFilter;
  tempPerPage = perPage;
  
  statusOptions.forEach(opt => {
    opt.classList.remove('active');
    opt.querySelector('i').style.visibility = 'hidden';
    if (opt.dataset.status === statusFilter) {
      opt.classList.add('active');
      opt.querySelector('i').style.visibility = 'visible';
    }
  });
  
  perPageOptions.forEach(opt => {
    opt.classList.remove('active');
    opt.querySelector('i').style.visibility = 'hidden';
    if (opt.dataset.perpage == perPage) {
      opt.classList.add('active');
      opt.querySelector('i').style.visibility = 'visible';
    }
  });
  
  isFilterOpen = false;
  filterMenu.style.display = 'none';
  filterMenu.classList.remove('active');
});

// Hover effects
applyFilterBtn.addEventListener('mouseenter', function() { this.style.background = '#0d7a8e'; });
applyFilterBtn.addEventListener('mouseleave', function() { this.style.background = '#14a2ba'; });
cancelFilterBtn.addEventListener('mouseenter', function() { this.style.background = '#e5e7eb'; });
cancelFilterBtn.addEventListener('mouseleave', function() { this.style.background = '#f3f4f6'; });

if (statusOptions[0]) statusOptions[0].querySelector('i').style.visibility = 'visible';
if (perPageOptions[0]) perPageOptions[0].querySelector('i').style.visibility = 'visible';

      // ============================================
      // INITIAL FETCH
      // ============================================
      console.log('🚀 Fetching initial data...');
      fetchData();
    }

    // ============================================
    // INITIALIZATION
    // ============================================
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initLaptopTable);
    } else {
      initLaptopTable();
    }

    // Re-initialize on Livewire navigation
    document.addEventListener('livewire:navigated', () => {
      console.log('🔄 Livewire navigated, re-initializing...');
      initialized = false;
      setTimeout(initLaptopTable, 50);
    });
  })();
</script>

@endsection