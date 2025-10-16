@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Peminjam')

@section('content')
<div class="card" id="peminjamCard" 
  style="background-color: rgba(20, 162, 186, 0,5); backdrop-filter: blur(10px); border: 1px solid rgba(20, 162, 186, 0.3);">
  <div class="card-header d-flex justify-content-between align-items-center"
    style="background-color: #14a2ba; border-bottom: 1px solid rgba(20, 162, 186, 0.3);">
    <h5 style="color: #fff; font-weight: bold; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
      Daftar Peminjam
    </h5>
    <div class="d-flex gap-2">
      <select id="perPage" class="form-select" style="width: auto; background-color: rgba(255,255,255,0.9); border: 1px solid #14a2ba;">
        <option value="10">10 / halaman</option>
        <option value="20">20 / halaman</option>
        <option value="50">50 / halaman</option>
      </select>
      <input id="search" type="text" class="form-control" placeholder="Cari nama, departemen..."
        style="background-color: rgba(255,255,255,0.9); border: 1px solid #14a2ba;">
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered mb-0">
      <thead style="background-color: #14a2ba;">
        <tr>
          <th class="text-white fw-bold">No</th>
          <th class="text-white fw-bold">Nama</th>
          <th class="text-white fw-bold">Departemen</th>
          <th class="text-white fw-bold">Laptop</th>
          <th class="text-white fw-bold">Tanggal Pinjam</th>
          <th class="text-white fw-bold">Tanggal Selesai</th>
          <th class="text-white fw-bold">Aksi</th>
        </tr>
      </thead>
      <tbody id="peminjamTableBody">
        <tr>
          <td colspan="7" class="text-center text-white fw-bold">Memuat data...</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
    <span id="infoText" style="color: #fff; font-weight: bold;"></span>
    <div id="pagination" class="d-flex gap-2 align-items-center"></div>
  </div>
</div>

{{-- Modal konfirmasi --}}
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="background-color: rgba(255,255,255,0.95);">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Konfirmasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body fw-semibold">
        Yakin ingin menyelesaikan peminjaman ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="btnConfirm" class="btn btn-primary">Ya, Selesai</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('page-script')
<script>
document.addEventListener("DOMContentLoaded", () => {
  const tableBody = document.getElementById("peminjamTableBody");
  const searchInput = document.getElementById("search");
  const perPageSelect = document.getElementById("perPage");
  const paginationDiv = document.getElementById("pagination");
  const infoText = document.getElementById("infoText");
  const modal = new bootstrap.Modal(document.getElementById("confirmModal"));
  const confirmBtn = document.getElementById("btnConfirm");

  let selectedId = null;
  let pagination = {};
  let search = "";
  let perPage = 10;

  async function fetchData(page = 1) {
    try {
      const res = await fetch(`/api/peminjam?search=${search}&page=${page}&per_page=${perPage}`);
      const data = await res.json();
      renderTable(data.data || []);
      renderPagination(data);
      pagination = data;
      infoText.textContent = `Menampilkan ${data.from || 0} - ${data.to || 0} dari ${data.total || 0} data`;
    } catch (e) {
      tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-white fw-bold">Gagal memuat data</td></tr>`;
    }
  }

  function renderTable(items) {
    if (items.length === 0) {
      tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-white fw-bold">Belum ada data peminjam</td></tr>`;
      return;
    }

    tableBody.innerHTML = items.map((p, i) => `
      <tr style="background-color: rgba(20,162,186,1)">
        <td class="text-white fw-bold">${(pagination.from || 0) + i}</td>
        <td class="text-white fw-bold">${p.nama}</td>
        <td class="text-white fw-bold">${p.department ?? '-'}</td>
        <td class="text-white fw-bold">${p.laptop ? `${p.laptop.merek} ${p.laptop.tipe}` : '-'}</td>
        <td class="text-white fw-bold">${p.tanggal_mulai}</td>
        <td class="text-white fw-bold">${p.tanggal_selesai}</td>
        <td><button class="btn btn-sm text-white fw-bold" style="background-color:#14a2ba;" onclick="showModal(${p.id})">Selesai</button></td>
      </tr>
    `).join('');
  }

  function renderPagination(data) {
    const current = data.current_page || 1;
    const last = data.last_page || 1;
    let buttons = "";

    const makeBtn = (label, page, disabled = false, active = false) => `
      <button class="btn ${active ? 'btn-light text-dark' : 'btn-outline-light'}"
        ${disabled ? 'disabled' : ''} onclick="fetchData(${page})"
        style="width:40px; height:40px; border-radius:50%; font-weight:bold;">
        ${label}
      </button>
    `;

    buttons += makeBtn('‹', current - 1, !data.prev_page_url);
    for (let i = 1; i <= last; i++) {
      if (i === 1 || i === last || (i >= current - 2 && i <= current + 2)) {
        buttons += makeBtn(i, i, false, i === current);
      } else if (i === current - 3 || i === current + 3) {
        buttons += `<span class="text-white px-2">...</span>`;
      }
    }
    buttons += makeBtn('›', current + 1, !data.next_page_url);

    paginationDiv.innerHTML = buttons;
  }

  window.showModal = (id) => {
    selectedId = id;
    modal.show();
  };

  confirmBtn.addEventListener("click", async () => {
    if (!selectedId) return;
    try {
      const res = await fetch(`/api/peminjam/${selectedId}/selesai`, { method: "DELETE" });
      if (!res.ok) throw new Error("Gagal menyelesaikan peminjaman");
      modal.hide();
      fetchData(pagination.current_page);
    } catch (e) {
      alert(e.message);
    }
  });

  searchInput.addEventListener("input", (e) => {
    search = e.target.value;
    fetchData();
  });

  perPageSelect.addEventListener("change", (e) => {
    perPage = e.target.value;
    fetchData();
  });

  fetchData();
});
</script>
@endsection
