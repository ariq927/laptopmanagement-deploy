@extends('layouts.contentNavbarLayout')

@section('title', 'Daftar Laptop')

@section('content')
<div class="card" id="laptopTableContainer"
     style="background-color: rgba(20,162,186,0.5); backdrop-filter: blur(10px); border: 1px solid rgba(20,162,186,0.3);">
  <div class="card-header d-flex justify-content-between align-items-center"
       style="background-color: #14a2ba; border-bottom: 1px solid rgba(20,162,186,0.3);">
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
      <a href="/laptop/create" class="btn btn-primary" style="font-weight:bold; background-color:#14a2ba; border-color:#14a2ba;">
        + Tambah Laptop
      </a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered" id="laptopTable" style="background-color:transparent;">
      <thead style="background-color:#14a2ba;">
        <tr>
          <th style="color:#fff; font-weight:bold;">No</th>
          <th style="color:#fff; font-weight:bold;">ID Laptop</th>
          <th style="color:#fff; font-weight:bold;">Merek</th>
          <th style="color:#fff; font-weight:bold;">Tipe</th>
          <th style="color:#fff; font-weight:bold;">Spesifikasi</th>
          <th style="color:#fff; font-weight:bold;">Aksi</th>
        </tr>
      </thead>
      <tbody id="laptopTableBody">
        <tr><td colspan="6" class="text-center">Memuat data...</td></tr>
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

    const tableBody = document.getElementById('laptopTableBody');
    const tableInfo = document.getElementById('tableInfo');
    const paginationContainer = document.getElementById('paginationContainer');
    const searchInput = document.getElementById('searchInput');
    const perPageSelect = document.getElementById('perPageSelect');

    const fetchData = (page = 1) => {
      currentPage = page;
      fetch(`/api/laptop?search=${search}&page=${page}&per_page=${perPage}`)
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
          <td style="color:#fff;font-weight:bold;">${laptop.id}</td>
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
      fetch(`/api/laptop/${id}/archive`, { method: 'PATCH' })
        .then(res => {
          if (!res.ok) throw new Error();
          alert('Laptop berhasil diarsip');
          fetchData(currentPage);
        })
        .catch(() => alert('Gagal mengarsipkan laptop'));
    };

    window.restoreLaptop = (id) => {
      fetch(`/api/laptop/${id}/restore`, { method: 'PATCH' })
        .then(res => {
          if (!res.ok) throw new Error();
          alert('Laptop berhasil dikembalikan');
          fetchData(currentPage);
        })
        .catch(() => alert('Gagal mengembalikan laptop'));
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
