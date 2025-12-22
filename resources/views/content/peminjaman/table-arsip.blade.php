<div id="tableContent">
{{-- Restore Modal --}}
<div id="restoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:450px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan Laptop</h4>
    <div style="background:rgba(255,255,255,0.15); padding:15px; border-radius:8px; margin-bottom:20px;">
      <p style="color:#fff; margin:0; font-size:14px; line-height:1.8;">
        <strong>Kode:</strong> <span id="modalKode"></span><br>
        <strong>Laptop:</strong> <span id="modalLaptop"></span><br>
        <strong>Keterangan:</strong> <span id="modalKeterangan"></span>
      </p>
    </div>
    <p style="color:#fff; margin-bottom:20px; opacity:0.9; text-align:center; font-weight: 500;">Apakah Anda yakin ingin mengembalikan laptop ini dari arsip?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeRestoreModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmRestore()" class="btn" style="font-weight:bold; padding:8px 20px; background:#10b981; color:white; border:none;">Ya, Kembalikan</button>
    </div>
  </div>
</div>

{{-- Bulk Restore Modal --}}
<div id="bulkRestoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:fixed; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:400px; max-width:500px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan <span id="bulkRestoreCount">0</span> Laptop</h4>
    <p style="color:#fff; margin-bottom:15px; opacity:0.9; text-align:center; font-weight: 500;">Apakah Anda yakin ingin mengembalikan semua laptop terpilih dari arsip?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeBulkRestoreModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmBulkRestore()" class="btn" style="font-weight:bold; padding:8px 20px; background:#10b981; color:white; border:none;">Ya, Kembalikan Semua</button>
    </div>
  </div>
</div>

<style>
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
    display: none;
    align-items: center;
    gap: 20px;
    z-index: 9998;
    transition: transform 0.3s ease;
    border: 2px solid rgba(255,255,255,0.3);
  }

  .bulk-action-bar.active {
    display: flex;
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

  .table-responsive {
    padding-bottom: 100px !important;
  }

  .laptop-checkbox {
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #14a2ba;
  }

  .btn-table {
    font-weight: 600;
    border-radius: 8px;
    padding: 6px 14px;
    font-size: 13px;
    transition: all 0.25s ease;
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
  }

  .btn-table:hover {
    background: #6ee7b7;
  }
</style>

{{-- Table --}}
<div class="table-responsive">
    <table class="table table-bordered mb-0" id="arsipTable" style="background-color: transparent; transition: all 0.3s ease;">
        <thead style="background-color: rgba(20,162,186,0.5);">
            <tr>
                <th class="text-white fw-bold" style="width: 50px; display: none;" id="checkboxHeader">
                    <input type="checkbox" id="selectAllCheckbox" class="laptop-checkbox" style="margin: 0;">
                </th>
                <th class="text-white fw-bold">No</th>
                <th class="text-white fw-bold">Kode Laptop</th>
                <th class="text-white fw-bold">Laptop</th>
                <th class="text-white fw-bold">Keterangan</th>
                <th class="text-white fw-bold">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laptops as $index => $laptop)
            <tr class="table-row-clickable" 
                style="cursor: pointer; background-color: rgba(20,162,186,0.1); transition: all 0.2s ease;"
                data-row-id="{{ $laptop->id }}"
                data-row-url="{{ route('laptop.arsip.show', $laptop->id) }}"
                onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'"
                onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'">
                
                <td class="text-center" style="display: none;" data-checkbox-cell>
                    <input type="checkbox" 
                        class="laptop-item-checkbox" 
                        data-laptop-id="{{ $laptop->id }}"
                        data-laptop-kode="{{ $laptop->kode }}"
                        data-laptop-name="{{ $laptop->merek }} {{ $laptop->tipe }}">
                </td>
                
                <td class="fw-bold text-white">{{ $laptops->firstItem() + $index }}</td>
                <td class="fw-bold text-white">{{ $laptop->kode }}</td>
                <td class="fw-bold text-white">{{ $laptop->merek }} {{ $laptop->tipe }}</td>
                <td class="fw-bold text-white text-truncate" style="max-width: 150px;" title="{{ $laptop->keterangan }}">
                    {{ Str::limit($laptop->keterangan, 50) }}
                </td>
                <td>
                    <form action="{{ route('laptop.restore', $laptop->id) }}" method="POST" class="restore-form d-inline" data-laptop-id="{{ $laptop->id }}">
                        @csrf
                        @method('PATCH')
                        <button type="button" class="btn-table restore-btn" 
                            data-laptop-id="{{ $laptop->id }}"
                            data-kode="{{ $laptop->kode }}" 
                            data-name="{{ $laptop->merek }} {{ $laptop->tipe }}" 
                            data-keterangan="{{ $laptop->keterangan ?? 'Tidak ada keterangan' }}">
                            Kembalikan
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="fw-bold text-white text-center">Belum ada data laptop</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if ($laptops->total() > 0)
<div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
    <span class="fw-bold text-white">
        Menampilkan {{ $laptops->firstItem() }} - {{ $laptops->lastItem() }} dari {{ $laptops->total() }} data
    </span>
    <div>
        {{ $laptops->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endif

{{-- Bulk Action Bar --}}
<div class="bulk-action-bar" id="bulkActionBar">
  <span class="bulk-action-bar-text">
    <span id="selectedCount">0</span> laptop dipilih
  </span>
  <button class="bulk-action-btn" id="bulkRestoreBtn" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
    <i class="bx bx-revision" style="font-size: 16px; margin-right: 4px;"></i>
    Kembalikan
  </button>
  <button class="bulk-action-btn" id="cancelSelectionBtn" style="background: rgba(255,255,255,0.25); color: white; border: 1px solid rgba(255,255,255,0.4);">
    Batal
  </button>
</div>

<script>
(function() {
    console.log('🔄 Table-arsip script loaded');
    
    if (window.arsipTableInitialized) {
        console.log('♻️ Re-initializing table...');
        
        selectionMode = window.arsipTableSelectionMode || false;
        
        if (window.selectedLaptopsGlobal && window.selectedLaptopsGlobal.size > 0) {
            selectedLaptops = new Set(Array.from(window.selectedLaptopsGlobal));
            console.log('📦 Restored from global:', {
                globalSize: window.selectedLaptopsGlobal.size,
                localSize: selectedLaptops.size,
                ids: Array.from(selectedLaptops)
            });
        } else {
            selectedLaptops = new Set();
            console.log('⚠️ No global state, starting fresh');
        }
        
        console.log('♻️ Using existing state:', {
            selectionMode: selectionMode,
            selectedCount: selectedLaptops.size,
            selectedIds: Array.from(selectedLaptops)
        });
        
        setupEventListeners();
        
        if (selectionMode && typeof activateSelectionMode === 'function') {
            console.log('🔄 Re-activating selection mode...');
            activateSelectionMode();
        }
        
        return;
    }
    window.arsipTableInitialized = true;

    let selectionMode = window.arsipTableSelectionMode || false;
    let selectedLaptops = window.selectedLaptopsGlobal ? new Set(Array.from(window.selectedLaptopsGlobal)) : new Set();
    let currentRestoreForm = null;
    
    console.log('📦 Initial state:', {
        selectionMode: selectionMode,
        selectedCount: selectedLaptops.size,
        selectedIds: Array.from(selectedLaptops),
        globalSize: window.selectedLaptopsGlobal?.size,
        globalIds: window.selectedLaptopsGlobal ? Array.from(window.selectedLaptopsGlobal) : []
    });
    
    if (!window.selectedLaptopsGlobal) {
        window.selectedLaptopsGlobal = new Set();
    }
    
    Object.defineProperty(window, 'arsipTableSelectionMode', {
        get: () => selectionMode,
        set: (val) => { 
            selectionMode = val;
            console.log('🔧 Selection mode changed to:', val);
        }
    });
    
    window.initializeTable = initializeTable;

    function initializeTable() {
        console.log('⚙️ Initializing table elements...');
        
        const selectionModeBtn = document.getElementById('selectionModeBtn');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const checkboxHeader = document.getElementById('checkboxHeader');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const arsipTable = document.getElementById('arsipTable');
        
        if (window.selectedLaptopsGlobal && window.selectedLaptopsGlobal.size > 0) {
            console.log('📦 Restoring selections from global:', {
                globalSize: window.selectedLaptopsGlobal.size,
                globalIds: Array.from(window.selectedLaptopsGlobal)
            });
            selectedLaptops = new Set(Array.from(window.selectedLaptopsGlobal));
            console.log('📦 Local selectedLaptops after restore:', {
                localSize: selectedLaptops.size,
                localIds: Array.from(selectedLaptops)
            });
        }
        
        if (window.arsipTableSelectionMode) {
            selectionMode = window.arsipTableSelectionMode;
        }

        if (selectionMode) {
            console.log('✓ Restoring selection mode with', selectedLaptops.size, 'selections');
            activateSelectionMode();
        }

        setupEventListeners();
        
        console.log(' Table initialized with selections:', Array.from(selectedLaptops));
    }

    function setupEventListeners() {
        const selectionModeBtn = document.getElementById('selectionModeBtn');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const cancelBtn = document.getElementById('cancelSelectionBtn');
        const bulkRestoreBtn = document.getElementById('bulkRestoreBtn');
        
        console.log('🔧 Setting up event listeners...', {
            selectionModeBtn: !!selectionModeBtn,
            selectAllCheckbox: !!selectAllCheckbox,
            cancelBtn: !!cancelBtn,
            bulkRestoreBtn: !!bulkRestoreBtn
        });
        
        if (selectionModeBtn) {
            const hasListener = selectionModeBtn.hasAttribute('data-initialized');
            if (hasListener) {
                console.log('⚠️ Removing old listener from selection mode button');
                const newBtn = selectionModeBtn.cloneNode(true);
                selectionModeBtn.parentNode.replaceChild(newBtn, selectionModeBtn);
            }
            const btn = document.getElementById('selectionModeBtn');
            btn.addEventListener('click', toggleSelectionMode);
            btn.setAttribute('data-initialized', 'true');
            console.log(' Selection mode button listener attached');
        }
        
        // Select All Checkbox
        if (selectAllCheckbox && !selectAllCheckbox.hasAttribute('data-initialized')) {
            selectAllCheckbox.addEventListener('change', handleSelectAll);
            selectAllCheckbox.setAttribute('data-initialized', 'true');
            console.log(' Select all checkbox listener attached');
        }
        
        // Cancel selection button
        if (cancelBtn && !cancelBtn.hasAttribute('data-initialized')) {
            cancelBtn.addEventListener('click', cancelSelection);
            cancelBtn.setAttribute('data-initialized', 'true');
            console.log(' Cancel button listener attached');
        }
        
        // Bulk restore button
        if (bulkRestoreBtn && !bulkRestoreBtn.hasAttribute('data-initialized')) {
            bulkRestoreBtn.addEventListener('click', handleBulkRestore);
            bulkRestoreBtn.setAttribute('data-initialized', 'true');
            console.log(' Bulk restore button listener attached');
        }
    }

    function activateSelectionMode() {
        selectionMode = true;
        window.arsipTableSelectionMode = true;
        
        console.log('✅ Activating selection mode');
        
        const selectionModeBtn = document.getElementById('selectionModeBtn');
        const checkboxHeader = document.getElementById('checkboxHeader');
        const arsipTable = document.getElementById('arsipTable');
        
        if (selectionModeBtn) {
            selectionModeBtn.innerHTML = '<i class="bx bx-x" style="font-size: 18px;"></i><span>Batal Pilih</span>';
            selectionModeBtn.style.background = '#ef4444';
            selectionModeBtn.style.borderColor = '#ef4444';
            selectionModeBtn.style.color = 'white';
        }
        
        if (checkboxHeader) checkboxHeader.style.display = 'table-cell';
        if (arsipTable) arsipTable.classList.add('selection-mode-active');
        
        document.querySelectorAll('[data-checkbox-cell]').forEach(cell => {
            cell.style.display = 'table-cell';
        });
        
        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.style.display = 'none';
        });
        
        restoreCheckboxStates();
    }
    
    window.activateSelectionMode = activateSelectionMode;
    
    function restoreCheckboxStates() {
        console.log('🔄 Restoring checkbox states...', {
            selectionsToRestore: Array.from(selectedLaptops),
            selectionCount: selectedLaptops.size
        });
        
        let restoredCount = 0;
        const allCheckboxes = document.querySelectorAll('.laptop-item-checkbox');
        
        console.log('📋 Available checkboxes on page:', 
            Array.from(allCheckboxes).map(cb => parseInt(cb.dataset.laptopId))
        );
        
        allCheckboxes.forEach(cb => {
            const laptopId = parseInt(cb.dataset.laptopId);
            console.log(`🔍 Checking laptop ${laptopId}: selected=${selectedLaptops.has(laptopId)}`);
            
            if (selectedLaptops.has(laptopId)) {
                cb.checked = true;
                restoredCount++;
                console.log('✅ Restored checkbox for laptop:', laptopId);
            } else {
                cb.checked = false;
            }
        });
        
        console.log('✅ Restored', restoredCount, 'of', selectedLaptops.size, 'checkboxes');
        updateBulkActionBar();
    }

    function deactivateSelectionMode() {
        selectionMode = false;
        window.arsipTableSelectionMode = false;
        
        const selectionModeBtn = document.getElementById('selectionModeBtn');
        const checkboxHeader = document.getElementById('checkboxHeader');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const arsipTable = document.getElementById('arsipTable');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        
        if (selectionModeBtn) {
            selectionModeBtn.innerHTML = '<i class="bx bx-checkbox-square" style="font-size: 18px;"></i><span>Pilih</span>';
            selectionModeBtn.style.background = '';
            selectionModeBtn.style.borderColor = 'rgba(255,255,255,0.5)';
            selectionModeBtn.style.color = '';
        }
        
        if (checkboxHeader) checkboxHeader.style.display = 'none';
        if (bulkActionBar) bulkActionBar.classList.remove('active');
        if (arsipTable) arsipTable.classList.remove('selection-mode-active');
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        
        console.log('🧹 Clearing all selections');
        selectedLaptops.clear();
        window.selectedLaptopsGlobal = new Set();
        
        document.querySelectorAll('[data-checkbox-cell]').forEach(cell => {
            cell.style.display = 'none';
        });
        
        document.querySelectorAll('.laptop-item-checkbox').forEach(cb => {
            cb.checked = false;
        });
        
        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.style.display = 'inline-block';
        });
    }
    
    window.deactivateSelectionMode = deactivateSelectionMode;

    function toggleSelectionMode(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('🎯 Toggle selection mode clicked', {
            currentMode: selectionMode,
            willBecome: !selectionMode
        });
        
        if (selectionMode) {
            deactivateSelectionMode();
        } else {
            activateSelectionMode();
        }
    }

    document.addEventListener('click', function(e) {
        if (e.target.closest('#restoreModal') || e.target.closest('#bulkRestoreModal')) {
            return;
        }
        
        if (e.target.classList.contains('restore-btn') || e.target.closest('.restore-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const btn = e.target.classList.contains('restore-btn') ? e.target : e.target.closest('.restore-btn');
            const kode = btn.dataset.kode;
            const name = btn.dataset.name;
            const keterangan = btn.dataset.keterangan;
            currentRestoreForm = btn.closest('form');
            
            const modalKode = document.getElementById('modalKode');
            const modalLaptop = document.getElementById('modalLaptop');
            const modalKeterangan = document.getElementById('modalKeterangan');
            
            if (modalKode) modalKode.textContent = kode;
            if (modalLaptop) modalLaptop.textContent = name;
            if (modalKeterangan) modalKeterangan.textContent = keterangan;
            
            const modal = document.getElementById('restoreModal');
            if (modal) modal.style.display = 'block';
            return;
        }
        
        // Handle row clicks
        const row = e.target.closest('.table-row-clickable');
        if (row) {
            if (e.target.closest('[data-checkbox-cell]') || 
                e.target.closest('.restore-btn') || 
                e.target.closest('form')) {
                return;
            }

            if (selectionMode) {
                const checkbox = row.querySelector('.laptop-item-checkbox');
                if (checkbox) {
                    e.preventDefault();
                    checkbox.checked = !checkbox.checked;
                    handleCheckboxChange(checkbox);
                }
            } else {
                const url = row.dataset.rowUrl;
                if (url) {
                    window.location.href = url;
                }
            }
        }
    });

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('laptop-item-checkbox')) {
            handleCheckboxChange(e.target);
        }
    });

    function handleSelectAll(e) {
        const checkboxes = document.querySelectorAll('.laptop-item-checkbox');
        
        console.log('📋 Select All triggered:', {
            checked: e.target.checked,
            checkboxCount: checkboxes.length,
            currentSelections: Array.from(selectedLaptops)
        });
        
        checkboxes.forEach(cb => {
            cb.checked = e.target.checked;
            const laptopId = parseInt(cb.dataset.laptopId);
            
            if (e.target.checked) {
                selectedLaptops.add(laptopId);
                console.log('➕ Added:', laptopId);
            } else {
                selectedLaptops.delete(laptopId);
                console.log('➖ Removed:', laptopId);
            }
        });
        
        window.selectedLaptopsGlobal = new Set(selectedLaptops);
        
        console.log('✅ Select All done:', {
            totalSelected: selectedLaptops.size,
            selectedIds: Array.from(selectedLaptops),
            globalSize: window.selectedLaptopsGlobal.size
        });
        
        updateBulkActionBar();
    }

    function handleCheckboxChange(checkbox) {
        const laptopId = parseInt(checkbox.dataset.laptopId);
        
        console.log('🔄 Checkbox changed:', {
            laptopId: laptopId,
            checked: checkbox.checked,
            currentLocalSelections: Array.from(selectedLaptops),
            currentGlobalSelections: window.selectedLaptopsGlobal ? Array.from(window.selectedLaptopsGlobal) : []
        });
        
        if (checkbox.checked) {
            selectedLaptops.add(laptopId);
        } else {
            selectedLaptops.delete(laptopId);
        }
        
        window.selectedLaptopsGlobal = new Set(selectedLaptops);
        
        console.log('✅ After checkbox change:', {
            action: checkbox.checked ? 'ADDED' : 'REMOVED',
            laptopId: laptopId,
            localCount: selectedLaptops.size,
            localIds: Array.from(selectedLaptops),
            globalCount: window.selectedLaptopsGlobal.size,
            globalIds: Array.from(window.selectedLaptopsGlobal),
            synced: selectedLaptops.size === window.selectedLaptopsGlobal.size
        });
        
        updateBulkActionBar();
    }

    function updateBulkActionBar() {
        const count = selectedLaptops.size;
        const countEl = document.getElementById('selectedCount');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        
        if (countEl) countEl.textContent = count;
        
        if (bulkActionBar) {
            if (count > 0) {
                bulkActionBar.classList.add('active');
            } else {
                bulkActionBar.classList.remove('active');
            }
        }
        
        const checkboxes = document.querySelectorAll('.laptop-item-checkbox');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = allChecked;
        }
    }
    
    window.updateBulkActionBar = updateBulkActionBar; 

    function cancelSelection() {
        deactivateSelectionMode();
    }

    function handleBulkRestore() {
        const currentSelections = Array.from(selectedLaptops);
        const globalSelections = window.selectedLaptopsGlobal ? Array.from(window.selectedLaptopsGlobal) : [];
        
        console.log('📋 Bulk Restore initiated:', {
            localCount: selectedLaptops.size,
            localIds: currentSelections,
            globalCount: window.selectedLaptopsGlobal?.size || 0,
            globalIds: globalSelections
        });
        
        if (selectedLaptops.size === 0 && (!window.selectedLaptopsGlobal || window.selectedLaptopsGlobal.size === 0)) {
            if (typeof window.showToast === 'function') {
                window.showToast("⚠️ Pilih minimal 1 laptop!", "#f59e0b");
            }
            return;
        }
        
        // Use global state if local is empty
        const countToUse = selectedLaptops.size > 0 ? selectedLaptops.size : (window.selectedLaptopsGlobal?.size || 0);
        
        const countEl = document.getElementById('bulkRestoreCount');
        if (countEl) countEl.textContent = countToUse;
        
        const modal = document.getElementById('bulkRestoreModal');
        if (modal) modal.style.display = 'block';
    }

    // Global functions for modal
    window.closeRestoreModal = function() {
        const modal = document.getElementById('restoreModal');
        if (modal) modal.style.display = 'none';
        currentRestoreForm = null;
    };

    window.confirmRestore = function() {
        if (currentRestoreForm) {
            currentRestoreForm.submit();
        }
    };

    window.closeBulkRestoreModal = function() {
        const modal = document.getElementById('bulkRestoreModal');
        if (modal) modal.style.display = 'none';
    };

    window.confirmBulkRestore = async function() {
        // CRITICAL: Use global state as source of truth
        const laptopIds = Array.from(window.selectedLaptopsGlobal || selectedLaptops);
        
        console.log('🚀 Starting bulk restore:', {
            totalToRestore: laptopIds.length,
            ids: laptopIds
        });
        
        if (laptopIds.length === 0) {
            if (typeof window.showToast === 'function') {
                window.showToast("⚠️ Tidak ada laptop yang dipilih!", "#f59e0b");
            }
            window.closeBulkRestoreModal();
            return;
        }
        
        let successCount = 0;
        let failCount = 0;

        for (const laptopId of laptopIds) {
            console.log(`🔄 Restoring laptop ID: ${laptopId}`);
            
            const form = document.querySelector(`.restore-form[data-laptop-id="${laptopId}"]`);
            
            if (!form) {
                console.warn(`⚠️ Form not found for laptop ${laptopId} (might be on different page)`);
                
                try {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                                     document.querySelector('[name="_token"]')?.value;
                    
                    const response = await fetch(`/laptop/${laptopId}/restore`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'Accept': 'application/json'
                        },
                        body: '_method=PATCH'
                    });
                    
                    if (response.ok) {
                        successCount++;
                        console.log(`✅ Restored laptop ${laptopId} via API`);
                    } else {
                        failCount++;
                        console.error(`❌ Failed to restore laptop ${laptopId}:`, response.status);
                    }
                } catch (error) {
                    failCount++;
                    console.error(`❌ Error restoring laptop ${laptopId}:`, error);
                }
                continue;
            }
            
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json'
                    },
                    body: '_method=PATCH'
                });
                
                if (response.ok) {
                    successCount++;
                    console.log(`✅ Restored laptop ${laptopId}`);
                } else {
                    failCount++;
                    console.error(`❌ Failed to restore laptop ${laptopId}:`, response.status);
                }
            } catch (error) {
                failCount++;
                console.error(`❌ Error restoring laptop ${laptopId}:`, error);
            }
        }

        console.log('✅ Bulk restore complete:', {
            total: laptopIds.length,
            success: successCount,
            failed: failCount
        });

        window.closeBulkRestoreModal();
        
        if (successCount > 0) {
            if (typeof window.showToast === 'function') {
                const message = failCount > 0 
                    ? `✓ ${successCount} laptop dikembalikan, ${failCount} gagal` 
                    : `✓ ${successCount} laptop berhasil dikembalikan!`;
                window.showToast(message, failCount > 0 ? "#f59e0b" : "#10b981");
            }
            
            // Clear selections
            selectedLaptops.clear();
            window.selectedLaptopsGlobal = new Set();
            
            setTimeout(() => window.location.reload(), 1500);
        } else {
            if (typeof window.showToast === 'function') {
                window.showToast("❌ Gagal mengembalikan laptop", "#ef4444");
            }
        }
    };
    
    console.log('🚀 Running initial table initialization...');
    initializeTable();
    
})();
</script>
</div>