@extends('layouts.contentNavbarLayout')

@section('title', 'Detail Penggunaan User')

@section('content')
<!-- Toast Notification -->
<div id="toastNotification" role="status" aria-live="polite" aria-atomic="true"
     style="position:fixed; top:20px; right:20px; min-width:300px; max-width:420px; z-index:10000; display:none;">
  <div class="toast-inner">
    <div class="toast-icon" aria-hidden="true">✓</div>
    <div id="toastMessage" class="toast-text"></div>
  </div>
</div>

<!-- Modal Arsip -->
<div id="archiveModal" aria-hidden="true" class="modal-overlay">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <button class="modal-close" onclick="closeArchiveModal()" aria-label="Tutup modal">✕</button>
    <h4 id="modalTitle" class="modal-title">Arsipkan Laptop</h4>
    <p class="modal-sub"><strong id="modalLaptopName"></strong></p>
    <p class="modal-desc">Masukkan keterangan pengarsipan:</p>
    <textarea id="keteranganInput" class="form-control modal-textarea" rows="3"
              placeholder="Contoh: Rusak pada bagian keyboard"></textarea>
    <div class="modal-actions">
      <button onclick="closeArchiveModal()" class="btn btn-light btn-pill">Batal</button>
      <button onclick="confirmArchive()" class="btn btn-danger btn-pill">Arsipkan</button>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi In Stock -->
<div id="instockModal" aria-hidden="true" class="modal-overlay">
  <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="instockModalTitle">
    <button class="modal-close" onclick="closeInstockModal()" aria-label="Tutup modal">✕</button>
    <h4 id="instockModalTitle" class="modal-title">Konfirmasi Ubah Status</h4>
    <p class="modal-sub"><strong id="instockLaptopName"></strong></p>
    <p class="modal-desc">Apakah Anda yakin ingin mengubah status laptop ini menjadi <strong>In Stock</strong>?</p>
    <div class="modal-actions">
      <button onclick="closeInstockModal()" class="btn btn-light btn-pill">Batal</button>
      <button onclick="confirmInstock()" class="btn btn-success btn-pill">Ya, Ubah Status</button>
    </div>
  </div>
</div>

<style>
  /* ========================
     Theme variables (light & dark)
     ======================== */
  :root{
    --bg: #f6f8fa;
    --card: rgba(255,255,255,0.85);
    --glass-border: rgba(255,255,255,0.6);
    --text: #0f172a;
    --muted: #6b7280;
    --accent-1: linear-gradient(135deg,#10b981 0%,#059669 100%);
    --accent-2: linear-gradient(135deg,#6ee7b7 0%,#3b82f6 100%);
    --danger:#ef4444;
    --success:#10b981;
    --shadow: 0 8px 30px rgba(16,24,40,0.08);
    --glass-blur: blur(8px);
    --radius-lg: 14px;
    --radius-sm: 8px;
  }

  /* dark-mode support (also supports [data-theme="dark"]) */
  [data-theme="dark"], body.dark-mode {
    --bg: #0b1220;
    --card: rgba(18,21,33,0.6);
    --glass-border: rgba(255,255,255,0.04);
    --text: #e6eef8;
    --muted: #9aa6bf;
    --accent-1: linear-gradient(135deg,#059669 0%,#065f46 100%);
    --accent-2: linear-gradient(135deg,#0ea5e9 0%,#6366f1 100%);
    --shadow: 0 8px 30px rgba(2,6,23,0.7);
  }

  body {
    background: var(--bg);
    color: var(--text);
  }

  /* container card */
  .container.py-4 { padding-top: 1.5rem; padding-bottom: 1.5rem; }

  .glass-card {
    background: var(--card);
    border-radius: var(--radius-lg);
    padding: 1.25rem;
    box-shadow: var(--shadow);
    backdrop-filter: var(--glass-blur);
    border: 1px solid var(--glass-border);
    transition: transform .18s ease, box-shadow .18s ease;
  }

  .glass-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(2,6,23,0.06); }

  /* Hero header */
  .hero {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:1rem;
  }

  .hero-left {
    display:flex;
    align-items:center;
    gap:14px;
  }

  .avatar {
    width:64px; height:64px; border-radius:12px;
    background: var(--accent-2);
    display:flex; align-items:center; justify-content:center;
    color:white; font-weight:700; font-size:20px;
    box-shadow: 0 6px 22px rgba(59,130,246,0.18);
  }

  .hero-title h4 { margin:0; font-size:1.125rem; }
  .hero-title p { margin:0; color:var(--muted); font-size:0.9rem; }

  /* Cards inside */
  .info-grid {
    display:grid;
    grid-template-columns: repeat(2, 1fr);
    gap:1rem;
  }
  @media (max-width: 768px){
    .info-grid { grid-template-columns: 1fr; }
  }

  .mini-card {
    background: linear-gradient(180deg, rgba(255,255,255,0.5), rgba(255,255,255,0.35));
    border-radius: 12px; padding: 1rem;
    border: 1px solid rgba(255,255,255,0.35);
    height:100%;
  }
  [data-theme="dark"] .mini-card, body.dark-mode .mini-card {
    background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border: 1px solid rgba(255,255,255,0.04);
  }

  .mini-card p { margin: 0.2rem 0; }
  .muted { color: var(--muted); font-size:0.9rem; }

  /* Laptop info card */
  .laptop-card {
    background: linear-gradient(90deg, rgba(240,248,255,0.9), rgba(255,255,255,0.6));
    border-radius:12px; padding:1rem;
    border:1px solid rgba(0,0,0,0.04);
  }
  [data-theme="dark"] .laptop-card, body.dark-mode .laptop-card {
    background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.015));
    border:1px solid rgba(255,255,255,0.04);
  }

  /* History area - table modernized */
  .history-wrapper { margin-top:0.6rem; }

  .table {
    width:100%;
    border-collapse:collapse;
    font-size:0.95rem;
    overflow: visible;
  }
  
  .table-responsive {
    overflow: visible !important;
  }

  /* Desktop table */
  @media (min-width: 769px) {
    .table thead th { text-align:left; padding: .75rem 1rem; color:var(--muted); font-weight:700; font-size:0.9rem; }
    .table tbody td { padding: .85rem 1rem; border-top: 1px solid rgba(0,0,0,0.04); vertical-align:middle; position: relative; }
    .table tbody tr:hover td { background: rgba(0,0,0,0.01); }
  }

  /* Mobile: convert rows into cards for readability */
  @media (max-width: 768px) {
    .table thead { display:none; }
    .table tbody, .table tr, .table td { display:block; width:100%; }
    .table tbody tr { margin-bottom: 0.8rem; border-radius:12px; background: var(--card); padding: .8rem; border:1px solid var(--glass-border); box-shadow: var(--shadow); }
    .table tbody td { padding: .2rem 0; border: none; }
    .row-label { display:block; color:var(--muted); font-size:0.85rem; }
    .row-value { display:block; font-weight:600; font-size:0.98rem; margin-top:4px; }
  }

  /* status dropdown */
  .status-dropdown {
    display: none; 
    flex-direction: column; 
    position: absolute; 
    top: calc(100% + 4px); 
    right: 0;
    margin-top: 0px; 
    background: rgba(255,255,255,0.98); 
    border-radius: 10px; 
    box-shadow: 0 12px 40px rgba(2,6,23,0.15);
    z-index: 10000; 
    overflow: visible;
    padding:6px; 
    min-width:140px;
    transition: opacity .12s ease, transform .12s ease;
    backdrop-filter: blur(8px);
  }
  .status-dropdown.show { display:flex; opacity:1; transform: translateY(0); }
  .status-dropdown button { background:none; border:none; padding:8px 12px; text-align:left; width:100%; cursor:pointer; font-weight:600; color: #0d9488; border-radius:8px; }
  .status-dropdown button:hover { background: rgba(20,162,186,0.08); }

  [data-theme="dark"] .status-dropdown, body.dark-mode .status-dropdown {
    background: rgba(18,21,33,0.95);
  }
  [data-theme="dark"] .status-dropdown button, body.dark-mode .status-dropdown button { color: #e6eef8; }

  /* buttons */
  .btn { padding: 8px 14px; border-radius: 99px; border:1px solid transparent; cursor:pointer; font-weight:700; }
  .btn-pill { border-radius: 999px; }
  .btn-light { background:transparent; color:var(--text); border:1px solid rgba(0,0,0,0.06); }
  .btn-danger { background: linear-gradient(90deg,#ff7a7a,#ef4444); color:white; border:none; }
  .btn-success { background: linear-gradient(90deg,#10b981,#059669); color:white; border:none; }
  .btn-outline-primary { background:transparent; border:1px solid rgba(59,130,246,0.18); color:var(--text); }

  /* modal */
  .modal-overlay { display:none; position:fixed; inset:0; background: rgba(2,6,23,0.55); z-index:9999; align-items:center; justify-content:center; }
  .modal-overlay.show { display:flex; }
  .modal-card { width: min(540px, 94%); background: var(--card); border-radius:14px; padding:1.25rem; position:relative; box-shadow: var(--shadow); border:1px solid var(--glass-border); }
  .modal-title { margin:0 0 .25rem 0; font-weight:800; }
  .modal-sub { margin:0 0 .5rem 0; color:var(--muted); font-weight:700; }
  .modal-desc { margin:0 0 .6rem 0; color:var(--muted); }
  .modal-textarea { width:100%; padding:.6rem; border-radius:10px; border:1px solid rgba(0,0,0,0.06); }
  .modal-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:1rem; }
  .modal-close { position:absolute; top:10px; right:10px; background:transparent; border:none; font-size:16px; cursor:pointer; }

  /* toast */
  #toastNotification .toast-inner {
    display:flex; align-items:center; gap:12px;
    background:var(--accent-1);
    color:white; padding:12px 16px; border-radius:12px; box-shadow:0 12px 40px rgba(2,6,23,0.12);
    transform: translateX(20px) translateY(-6px) scale(.98);
    opacity:0; transition: transform .22s cubic-bezier(.2,.9,.3,1), opacity .22s ease;
    border: 1px solid rgba(255,255,255,0.12);
  }
  #toastNotification.show .toast-inner { transform: translateX(0) translateY(0) scale(1); opacity:1; }

  .toast-icon { width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:800; background: rgba(255,255,255,0.12); }
  .toast-text { font-weight:700; font-size:0.95rem; }

  /* small helpers */
  .text-end { text-align:right; }
  .fst-italic { font-style:italic; color:var(--muted); }
  .badge { display:inline-block; padding:.32rem .55rem; border-radius:8px; font-weight:700; }
  .badge.bg-warning { background: linear-gradient(90deg,#fef3c7,#f59e0b); color:#000; }
  .badge.bg-success { background: linear-gradient(90deg,#bbf7d0,#10b981); color:#000; }
  .badge.bg-secondary { background: #e6e9ef; color:#000; }
  [data-theme="dark"] .badge.bg-secondary { background: rgba(255,255,255,0.06); color:var(--text); }

  /* small responsive tweaks */
  @media (max-width:420px){
    .avatar{ width:52px; height:52px; font-size:18px;}
    .hero-title h4{ font-size:1rem;}
  }
</style>

<div class="container py-4">
  <div class="glass-card">
    <div class="hero">
      <div class="hero-left">
        <div class="avatar" aria-hidden="true">
          {{ strtoupper(substr($peminjam->nama ?? 'U', 0, 2)) }}
        </div>
        <div class="hero-title">
          <h4 class="fw-bold mb-1">Detail Peminjaman</h4>
          <p class="muted">Informasi & riwayat penggunaan {{ $peminjam->nama }}</p>
        </div>
      </div>

      <div class="hero-actions">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary px-4 rounded-pill">
          ← Kembali
        </a>
      </div>
    </div>

    <hr style="opacity:.06; margin: .25rem 0 1rem 0;">

    <div class="info-grid mb-3">
      {{-- Informasi Peminjam --}}
      <div class="mini-card">
        <h6 class="fw-semibold">Informasi Pegawai</h6>
        <p class="muted">Nama Pegawai</p>
        <p class="row-value">{{ $peminjam->nama }}</p>
        
        <p class="muted" style="margin-top:.6rem;">Kode Pegawai</p>
        <p class="row-value">{{ $peminjam->nomor_telepon ?? '-' }}</p>

        <p class="muted" style="margin-top:.6rem;">Jabatan</p>
        <p class="row-value">{{ $peminjam->department }}</p>

        <p class="muted" style="margin-top:.6rem;">Status</p>
        <p class="row-value">
          @if($peminjam->status_peminjaman === 'active')
            <span class="badge bg-warning text-dark">Aktif</span>
          @elseif($peminjam->status_peminjaman === 'expired')
            <span class="badge bg-secondary">Expired</span>
          @else
            <span class="badge bg-secondary">{{ ucfirst($peminjam->status_peminjaman ?? 'N/A') }}</span>
          @endif
        </p>
      </div>

      {{-- Informasi Laptop --}}
      <div class="laptop-card">
        <h6 class="fw-semibold">Informasi Laptop Terakhir</h6>
        @if($peminjam->laptop)
          <p class="muted">Merek</p>
          <p class="row-value">{{ $peminjam->laptop->merek }}</p>

          <p class="muted" style="margin-top:.5rem;">Model</p>
          <p class="row-value">{{ $peminjam->laptop->tipe ?? '-' }}</p>

          <p class="muted" style="margin-top:.5rem;">Kode Laptop</p>
          <p class="row-value">{{ $peminjam->laptop->kode ?? '-' }}</p>

          <p class="muted" style="margin-top:.5rem;">Status Laptop</p>
          <p class="row-value">
            @if($peminjam->laptop->status === 'in use')
              <span class="badge bg-warning text-dark">Sedang Digunakan</span>
            @elseif($peminjam->laptop->status === 'in stock')
              <span class="badge bg-success">Tersedia</span>
            @elseif($peminjam->laptop->status === 'diarsip')
              <span class="badge bg-secondary">Diarsipkan</span>
            @else
              <span class="badge bg-secondary">{{ ucfirst($peminjam->laptop->status) }}</span>
            @endif
          </p>
        @else
          <p class="fst-italic">Tidak ada data laptop terkait.</p>
        @endif
      </div>
    </div>

    {{-- Riwayat Peminjaman --}}
    <div class="card border-0 shadow-sm" style="background:transparent; padding:0;">
      <div class="card-body history-wrapper">
        <h5 class="fw-semibold mb-3">Riwayat Peminjaman {{ $peminjam->nama }}</h5>

        @if($riwayat->isEmpty())
          <p class="text-muted fst-italic">Belum ada riwayat peminjaman untuk user ini.</p>
        @else
          <div class="table-responsive">
            <table class="table" role="table" aria-label="Riwayat peminjaman">
              <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Laptop</th>
                  <th>Tanggal Mulai</th>
                  <th>Tanggal Selesai</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($riwayat as $index => $r)
                  <tr>
                    <td data-label="No">
                      <span class="row-value">{{ $index + 1 }}</span>
                    </td>

                    <td data-label="Laptop">
                      <span class="row-value">{{ $r->laptop->merek ?? '-' }} {{ $r->laptop->tipe ?? '-' }} ({{ $r->laptop->kode ?? '-' }})</span>
                    </td>

                    <td data-label="Tanggal Mulai">
                      <span class="row-value">{{ \Carbon\Carbon::parse($r->tanggal_mulai)->format('d M Y') }}</span>
                    </td>

                    <td data-label="Tanggal Selesai">
                      <span class="row-value">{{ $r->tanggal_selesai ? \Carbon\Carbon::parse($r->tanggal_selesai)->format('d M Y') : '-' }}</span>
                    </td>

                    <td data-label="Status" style="position:relative;">
                      @if($r->status === 'aktif')
                        <button type="button" class="btn btn-sm btn-warning no-nav" onclick="event.stopPropagation(); toggleDropdown(this, '{{ $r->id }}')">
                          Aktif
                        </button>

                        <!-- dropdown -->
                        <div class="status-dropdown" data-owner="{{ $r->id }}">
                          <button type="button" class="no-nav" onclick="event.stopPropagation(); openInstockModal('{{ $r->id }}', '{{ $r->laptop->merek ?? '' }} {{ $r->laptop->tipe ?? '' }}')">
                            In Stock
                          </button>
                          <button type="button" class="no-nav" onclick="event.stopPropagation(); openArchiveModal('{{ $r->id }}', '{{ $r->laptop->merek ?? '' }} {{ $r->laptop->tipe ?? '' }}')">
                            Arsip
                          </button>
                        </div>
                      @elseif($r->status === 'selesai')
                        <span class="badge bg-success">Selesai</span>
                      @else
                        <span class="badge bg-secondary">{{ ucfirst($r->status) }}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif

      </div>
    </div>

  </div>
</div>

<script>
  let pendingArchiveHistoryId = null;
  let pendingInstockHistoryId = null;

  function closeDropdowns() {
    document.querySelectorAll('.status-dropdown').forEach(el => {
      el.classList.remove('show');
      el.style.right = '0';
    });
  }

  function toggleDropdown(btn, id) {
    // keep existing signature and behavior
    if (event) event.stopPropagation();

    const dropdown = btn.nextElementSibling;
    if (!dropdown) return;

    // toggle
    const isShown = dropdown.classList.contains('show');
    closeDropdowns();

    if (isShown) return;

    // make visible
    dropdown.classList.add('show');

    // ensure width fits the button for nicer UI
    const btnWidth = btn.getBoundingClientRect().width;
    dropdown.style.minWidth = Math.max(btnWidth + 20, 140) + 'px';

    // reposition to avoid overflow
    const rect = dropdown.getBoundingClientRect();
    const overflowRight = rect.right - window.innerWidth;
    if (overflowRight > 0) {
      dropdown.style.right = (overflowRight + 12) + 'px';
    } else {
      dropdown.style.right = '0';
    }
  }

  // Modal In Stock
  function openInstockModal(historyId, laptopName) {
    closeDropdowns();
    pendingInstockHistoryId = historyId;
    document.getElementById('instockLaptopName').innerText = laptopName;
    const overlay = document.getElementById('instockModal');
    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden','false');
  }

  function closeInstockModal() {
    const overlay = document.getElementById('instockModal');
    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden','true');
    pendingInstockHistoryId = null;
  }

  function confirmInstock() {
    if (!pendingInstockHistoryId) return;
    updateStatusHistori(pendingInstockHistoryId, 'in stock');
    closeInstockModal();
  }

  // Modal Archive
  function openArchiveModal(historyId, laptopName) {
    closeDropdowns();
    pendingArchiveHistoryId = historyId;
    document.getElementById('modalLaptopName').innerText = laptopName;
    const overlay = document.getElementById('archiveModal');
    overlay.classList.add('show');
    overlay.setAttribute('aria-hidden','false');
    document.getElementById('keteranganInput').value = '';
    setTimeout(() => document.getElementById('keteranganInput').focus(), 120);
  }

  function closeArchiveModal() {
    const overlay = document.getElementById('archiveModal');
    overlay.classList.remove('show');
    overlay.setAttribute('aria-hidden','true');
    pendingArchiveHistoryId = null;
  }

  function confirmArchive() {
    const keterangan = document.getElementById('keteranganInput').value.trim();
    if (keterangan === "") {
      showToast("⚠️ Keterangan wajib diisi!", "#f59e0b");
      return;
    }
    updateStatusHistori(pendingArchiveHistoryId, 'diarsip', '', keterangan);
    closeArchiveModal();
  }

  function updateStatusHistori(historyId, status, laptopName = '', keterangan = null) {
    closeDropdowns();

    const body = { status };
    if (keterangan) {
      body.keterangan = keterangan;
    }

    fetch(`/peminjaman/riwayat/${historyId}/update-status`, {
      method: 'POST',
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
        showToast('✗ Gagal memperbarui status', '#ef4444');
        return;
      }
      showToast('✓ ' + (data.message || 'Status berhasil diperbarui'), '#10b981');
      setTimeout(() => location.reload(), 1500);
    })
    .catch(() => showToast('✗ Terjadi kesalahan jaringan', '#ef4444'));
  }

  function showToast(message, color = "#10b981") {
    const toastWrap = document.getElementById('toastNotification');
    const toastInner = toastWrap.querySelector('.toast-inner');
    const msg = document.getElementById('toastMessage');
    msg.innerText = message;

    // set color: support both solid color and gradient hex
    if (color.startsWith('linear-gradient') || color.includes('gradient')) {
      toastInner.style.background = color;
    } else {
      toastInner.style.background = color;
    }

    toastWrap.classList.add('show');
    toastWrap.style.display = 'block';
    // small timeout to allow CSS transition
    setTimeout(() => toastWrap.classList.add('visible'), 20);

    // animate inner
    toastWrap.classList.add('show');

    setTimeout(() => {
      toastWrap.classList.remove('show');
      setTimeout(() => {
        toastWrap.style.display = 'none';
      }, 260);
    }, 2500);
  }

  // Close dropdowns when clicking outside (preserve existing behavior)
  document.addEventListener('click', e => {
    const noNav = e.target.closest('.no-nav') || e.target.closest('.status-dropdown') || e.target.closest('.btn');
    if (!noNav) closeDropdowns();
  });

  // close modal when clicking overlay background
  document.getElementById('archiveModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeArchiveModal();
    }
  });

  document.getElementById('instockModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeInstockModal();
    }
  });

  // accessibility: close modal with Esc
  document.addEventListener('keydown', function(e){
    if(e.key === 'Escape'){
      closeDropdowns();
      closeArchiveModal();
      closeInstockModal();
    }
  });
</script>
@endsection