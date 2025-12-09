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
    // Prevent multiple initializations
    if (window.arsipTableInitialized) {
        // Re-run initialization for new table content
        initializeTable();
        return;
    }
    window.arsipTableInitialized = true;

    let selectionMode = false;
    let selectedLaptops = window.selectedLaptopsGlobal || new Set();
    let currentRestoreForm = null;
    
    // Expose selection mode to global scope for AJAX handling
    Object.defineProperty(window, 'arsipTableSelectionMode', {
        get: () => selectionMode,
        set: (val) => { selectionMode = val; }
    });
    
    // Expose selectedLaptops to global scope
    window.initializeTable = initializeTable;

    function initializeTable() {
        const selectionModeBtn = document.getElementById('selectionModeBtn');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const checkboxHeader = document.getElementById('checkboxHeader');
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const arsipTable = document.getElementById('arsipTable');
        
        // Restore from global state
        if (window.arsipTableSelectionMode) {
            selectionMode = window.arsipTableSelectionMode;
            selectedLaptops = window.selectedLaptopsGlobal || new Set();
        }

        // If already in selection mode, reapply styles
        if (selectionMode) {
            activateSelectionMode();
        }

    function activateSelectionMode() {
        selectionMode = true;
        window.arsipTableSelectionMode = true;
        
        if (!selectionModeBtn) return;
        
        selectionModeBtn.innerHTML = '<i class="bx bx-x" style="font-size: 18px;"></i><span>Batal Pilih</span>';
        selectionModeBtn.style.background = '#ef4444';
        selectionModeBtn.style.borderColor = '#ef4444';
        selectionModeBtn.style.color = 'white';
        
        if (checkboxHeader) checkboxHeader.style.display = 'table-cell';
        if (arsipTable) arsipTable.classList.add('selection-mode-active');
        
        document.querySelectorAll('[data-checkbox-cell]').forEach(cell => {
            cell.style.display = 'table-cell';
        });
        
        document.querySelectorAll('.restore-btn').forEach(btn => {
            btn.style.display = 'none';
        });
        
        // Restore checkbox states after filter/search
        restoreCheckboxStates();
    }
    
    function restoreCheckboxStates() {
        document.querySelectorAll('.laptop-item-checkbox').forEach(cb => {
            const laptopId = parseInt(cb.dataset.laptopId);
            if (selectedLaptops.has(laptopId)) {
                cb.checked = true;
            }
        });
        updateBulkActionBar();
    }

    function deactivateSelectionMode() {
        selectionMode = false;
        window.arsipTableSelectionMode = false;
        
        if (!selectionModeBtn) return;
        
        selectionModeBtn.innerHTML = '<i class="bx bx-checkbox-square" style="font-size: 18px;"></i><span>Pilih</span>';
        selectionModeBtn.style.background = '';
        selectionModeBtn.style.borderColor = 'rgba(255,255,255,0.5)';
        selectionModeBtn.style.color = '';
        
        if (checkboxHeader) checkboxHeader.style.display = 'none';
        if (bulkActionBar) bulkActionBar.classList.remove('active');
        if (arsipTable) arsipTable.classList.remove('selection-mode-active');
        
        if (selectAllCheckbox) selectAllCheckbox.checked = false;
        
        // Clear selections
        selectedLaptops.clear();
        window.selectedLaptopsGlobal = selectedLaptops;
        
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

    // Toggle Selection Mode
    if (selectionModeBtn) {
        selectionModeBtn.removeEventListener('click', toggleSelectionMode);
        selectionModeBtn.addEventListener('click', toggleSelectionMode);
    }
    
    function toggleSelectionMode(e) {
        e.preventDefault();
        e.stopPropagation();
        
        if (selectionMode) {
            deactivateSelectionMode();
        } else {
            activateSelectionMode();
        }
    }

    // Handle row clicks
    document.addEventListener('click', handleRowClick);
    
    function handleRowClick(e) {
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
    }

    // Select All Checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.removeEventListener('change', handleSelectAll);
        selectAllCheckbox.addEventListener('change', handleSelectAll);
    }
    
    function handleSelectAll(e) {
        const checkboxes = document.querySelectorAll('.laptop-item-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = e.target.checked;
            const laptopId = parseInt(cb.dataset.laptopId);
            if (e.target.checked) {
                selectedLaptops.add(laptopId);
            } else {
                selectedLaptops.delete(laptopId);
            }
        });
        window.selectedLaptopsGlobal = selectedLaptops;
        updateBulkActionBar();
    }

    // Handle checkbox changes
    document.addEventListener('change', handleCheckboxEvent);
    
    function handleCheckboxEvent(e) {
        if (e.target.classList.contains('laptop-item-checkbox')) {
            handleCheckboxChange(e.target);
        }
    }

    function handleCheckboxChange(checkbox) {
        const laptopId = parseInt(checkbox.dataset.laptopId);
        if (checkbox.checked) {
            selectedLaptops.add(laptopId);
        } else {
            selectedLaptops.delete(laptopId);
        }
        window.selectedLaptopsGlobal = selectedLaptops;
        updateBulkActionBar();
    }

    function updateBulkActionBar() {
        const count = selectedLaptops.size;
        const countEl = document.getElementById('selectedCount');
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

    // Cancel selection button
    const cancelBtn = document.getElementById('cancelSelectionBtn');
    if (cancelBtn) {
        cancelBtn.removeEventListener('click', cancelSelection);
        cancelBtn.addEventListener('click', cancelSelection);
    }
    
    function cancelSelection() {
        deactivateSelectionMode();
    }

    // Bulk restore button
    const bulkRestoreBtn = document.getElementById('bulkRestoreBtn');
    if (bulkRestoreBtn) {
        bulkRestoreBtn.removeEventListener('click', handleBulkRestore);
        bulkRestoreBtn.addEventListener('click', handleBulkRestore);
    }
    
    function handleBulkRestore() {
        if (selectedLaptops.size === 0) {
            showToast("⚠️ Pilih minimal 1 laptop!", "#f59e0b");
            return;
        }
        const countEl = document.getElementById('bulkRestoreCount');
        if (countEl) countEl.textContent = selectedLaptops.size;
        const modal = document.getElementById('bulkRestoreModal');
        if (modal) modal.style.display = 'block';
    }

    // Single restore buttons
    document.addEventListener('click', handleRestoreClick);
    
    function handleRestoreClick(e) {
        if (e.target.classList.contains('restore-btn')) {
            e.preventDefault();
            e.stopPropagation();
            const kode = e.target.dataset.kode;
            const name = e.target.dataset.name;
            const keterangan = e.target.dataset.keterangan;
            currentRestoreForm = e.target.closest('form');
            
            const modalKode = document.getElementById('modalKode');
            const modalLaptop = document.getElementById('modalLaptop');
            const modalKeterangan = document.getElementById('modalKeterangan');
            
            if (modalKode) modalKode.textContent = kode;
            if (modalLaptop) modalLaptop.textContent = name;
            if (modalKeterangan) modalKeterangan.textContent = keterangan;
            
            const modal = document.getElementById('restoreModal');
            if (modal) modal.style.display = 'block';
        }
    }

    // Global functions
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
        const laptopIds = Array.from(selectedLaptops);
        let successCount = 0;

        for (const laptopId of laptopIds) {
            const form = document.querySelector(`.restore-form[data-laptop-id="${laptopId}"]`);
            if (form) {
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value,
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: '_method=PATCH'
                    });
                    
                    if (response.ok) {
                        successCount++;
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }

        window.closeBulkRestoreModal();
        
        if (successCount > 0) {
            showToast(`✓ ${successCount} laptop berhasil dikembalikan!`, "#10b981");
            setTimeout(() => window.location.reload(), 1500);
        }
    };

    window.showToast = function(message, bgColor = "#10b981") {
        const toast = document.getElementById('toastNotification');
        const toastMsg = document.getElementById('toastMessage');
        if (toast && toastMsg) {
            toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)`;
            toastMsg.innerText = message;
            toast.style.display = 'block';
            setTimeout(() => { toast.style.transform = 'translateX(0)'; }, 10);
            setTimeout(() => {
                toast.style.transform = 'translateX(400px)';
                setTimeout(() => { toast.style.display = 'none'; }, 300);
            }, 3000);
        }
    };
    
    } // End of initializeTable
    
    // Initial run
    initializeTable();
    
})();
</script>