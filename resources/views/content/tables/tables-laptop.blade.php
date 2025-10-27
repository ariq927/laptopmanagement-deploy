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

<!-- Restore -->
<div id="restoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #10b981 0%, #059669 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:400px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan Laptop</h4>
    <p style="color:#fff; margin-bottom:20px; opacity:0.9; text-align:center;">Apakah Anda yakin ingin mengembalikan laptop ini dari arsip?</p>
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

<div class="card" id="laptopTableContainer"
     style="background-color: rgba(20,162,186,0.5); backdrop-filter: blur(10px); border: 1px solid rgba(20,162,186,0.3);">
  <div class="card-header d-flex justify-content-between align-items-center"
       style="background-color: rgba(20,162,186,0.5);; border-bottom: 1px solid rgba(20,162,186,0.3);">
    <h5 style="color: #fff; font-weight: bold; text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
      Daftar Laptop
    </h5>
    <div class="d-flex gap-2">
      <select id="perPageSelect" class="form-select"
              style="width:auto; background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba; color:#000;">
        <option value="10">10 / halaman</option>
        <option value="25">25 / halaman</option>
        <option value="50">50 / halaman</option>
        <option value="100">100 / halaman</option>
      </select>
      <input type="text" id="searchInput" class="form-control"
             placeholder="Cari Laptop..."
             style="background-color:rgba(255,255,255,0.9); border:1px solid #14a2ba; color:#000;">
      <a href="/laptop/create" class="btn btn-success fw-bold" style="border-radius:0; font-size:14px; height:36px; line-height:36px; padding:0 12px; white-space: nowrap;">+ TAMBAH LAPTOP</a>
      </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered" id="laptopTable" style="background-color:transparent;">
      <thead style="background-color: rgba(20,162,186,0.5);">
        <tr>
          <th style="color:#fff; font-weight:bold;">No</th>
          <th style="color:#fff; font-weight:bold;">Kode Laptop</th>
          <th style="color:#fff; font-weight:bold;">Merek</th>
          <th style="color:#fff; font-weight:bold;">Tipe</th>
          <th style="color:#fff; font-weight:bold;">Spesifikasi</th>
          <th style="color:#fff; font-weight:bold;">Aksi</th>
        </tr>
      </thead>
      <tbody id="laptopTableBody">
        <tr style="background-color: rgba(20,162,186,0.1)"><td colspan="6" class="text-center">Memuat data...</td></tr>
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

<script>
  document.addEventListener("DOMContentLoaded", function() {
    let search = '';
    let perPage = 10;
    let currentPage = 1;
    let laptopIdToArchive = null;
    let laptopIdToRestore = null;

    const tableBody = document.getElementById('laptopTableBody');
    const tableInfo = document.getElementById('tableInfo');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchInput = document.getElementById('searchInput');
    const perPageSelect = document.getElementById('perPageSelect');

    const fetchData = (page = 1) => {
      currentPage = page;
      fetch(`/laptop/data?search=${search}&page=${page}&per_page=${perPage}`)
        .then(res => res.json())
        .then(json => {
          renderTable(json.data, json.from, json);
          renderPagination(json);
          tableInfo.innerText = `Menampilkan ${json.from || 0} - ${json.to || 0} dari ${json.total || 0} data`;
        })
        .catch(() => {
          tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Gagal memuat data</td></tr>';
        });
    };

    const renderTable = (data, start, pagination) => {
      if (!data || data.length === 0) {
        tableBody.innerHTML = `
          <tr>
            <td colspan="6" class="text-center" style="color:#fff;font-weight:bold;">Belum ada data laptop</td>
          </tr>`;
        return;
      }
      tableBody.innerHTML = data.map((laptop, index) => `
        <tr style="background-color:rgba(20,162,186,0.1); transition:all 0.3s ease;"
            onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'; this.style.transform='scale(1.02)'"
            onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'; this.style.transform='scale(1)'"
            onclick="window.location.href='/laptop/${laptop.id}/edit'">
          <td style="color:#fff;font-weight:bold;">${(pagination.from || 0) + index}</td>
          <td style="color:#fff;font-weight:bold;">${laptop.kode}</td>
          <td style="color:#fff;font-weight:bold;">${laptop.merek}</td>
          <td style="color:#fff;font-weight:bold;">${laptop.tipe}</td>
          <td style="color:#fff;font-weight:bold;">${laptop.spesifikasi}</td>
          <td>
            ${renderActions(laptop)}
          </td>
        </tr>`).join('');
    };

    const renderActions = (laptop) => {
      if (laptop.status === 'tersedia') {
        return `
          <a href="/peminjaman/create/${laptop.id}" class="btn btn-primary btn-sm">Pinjam</a>
          <a href="/laptop/${laptop.id}/edit" class="btn btn-warning btn-sm">Detail</a>
          <button class="btn btn-danger btn-sm" onclick="event.stopPropagation(); archiveLaptop(${laptop.id})">Arsip</button>`;
      } else if (laptop.status === 'dipinjam') {
        return `<button class="btn btn-secondary btn-sm" disabled>Dipinjam</button>`;
      } else if (laptop.status === 'diarsip') {
        return `<button class="btn btn-success btn-sm" onclick="event.stopPropagation(); restoreLaptop(${laptop.id})">Kembalikan</button>`;
      }
      return '';
    };

    const renderPagination = (pagination) => {
      const { current_page, last_page } = pagination;
      let html = '';

      const makeButton = (page, text, disabled = false) =>
        `<button ${disabled ? 'disabled' : ''} onclick="fetchData(${page})"
           class="btn btn-outline-light"
           style="font-weight:bold;width:40px;height:40px;border-radius:50%;padding:0;">
           ${text}
         </button>`;

      html += makeButton(current_page - 1, '‹', !pagination.prev_page_url);

      for (let i = 1; i <= last_page; i++) {
        html += `<button onclick="fetchData(${i})" class="btn ${i === current_page ? 'btn-light' : 'btn-outline-light'}"
          style="font-weight:bold;width:40px;height:40px;border-radius:50%;padding:0;">
          ${i}
        </button>`;
      }

      html += makeButton(current_page + 1, '›', !pagination.next_page_url);
      paginationContainer.innerHTML = html;
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
          closeArchiveModal();
          showToast("✓ Laptop berhasil diarsip!", "#10b981");
          fetchData(currentPage);
        })
        .catch(() => {
          showToast("✗ Gagal mengarsipkan laptop", "#ef4444");
        });
    };

    window.showToast = (message, bgColor = "#10b981") => {
      const toast = document.getElementById('toastNotification');
      const toastMsg = document.getElementById('toastMessage');
      
      toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)`;
      toastMsg.innerText = message;
      toast.style.display = 'block';
      
      setTimeout(() => {
        toast.style.transform = 'translateX(0)';
      }, 10);
      
      setTimeout(() => {
        toast.style.transform = 'translateX(400px)';
        setTimeout(() => {
          toast.style.display = 'none';
        }, 300);
      }, 3000);
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
          closeRestoreModal();
          showToast("✓ Laptop berhasil dikembalikan!", "#10b981");
          fetchData(currentPage);
        })
        .catch(() => {
          showToast("✗ Gagal mengembalikan laptop", "#ef4444");
        });
    };

    searchInput.addEventListener('input', (e) => {
      search = e.target.value;
      fetchData(1);
    });

    perPageSelect.addEventListener('change', (e) => {
      perPage = e.target.value;
      fetchData(1);
    });

    fetchData();
  });
</script>
@endsection