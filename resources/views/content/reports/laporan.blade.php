@extends('layouts/contentNavbarLayout')

@section('title', 'Laporan Peminjaman Laptop')

@section('content')
@php
  $isDarkMode = session('theme') === 'dark';
@endphp

<div id="glass-nova-root" data-theme="{{ $isDarkMode ? 'dark' : 'light' }}">
  <style>
    /* Theme variables */
    :root {
      --bg-0: #f6fbfc;
      --card-bg: rgba(255,255,255,0.95);
      --muted: #6b7c80;
      --accent-1: #14a2ba;
      --accent-2: #0d7a8e;
      --text: #0c2a2f;
    }

    [data-theme="dark"] {
      --bg-0: #0b1720;
      --card-bg: rgba(20,28,34,0.85);
      --muted: #b9d7de;
      --accent-1: #0fa0b8;
      --accent-2: #0a6a74;
      --text: #e6f7f8;
    }

    #glass-nova-root {
      padding: 2.25rem 1rem;
      background: var(--bg-0);
      min-height: 100vh;
      color: var(--text);
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto;
    }

    .glass-card-wrap {
      max-width: 1200px;
      margin: 0 auto;
    }

    /* Card - TANPA animated border & blur */
    .glass-card {
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      border: 1px solid rgba(20,162,186,0.2);
      overflow: hidden;
    }

    /* Header - simplified */
    .glass-header {
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      background: linear-gradient(90deg, var(--accent-1), var(--accent-2));
      color: #fff;
    }

    .header-orb {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: rgba(255,255,255,0.15);
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .orb-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: rgba(255,255,255,0.95);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--accent-2);
      font-weight: 700;
    }

    .glass-header .title {
      font-size: 1.375rem;
      font-weight: 700;
      display: flex;
      flex-direction: column;
    }

    .glass-header .subtitle {
      font-size: 0.9rem;
      opacity: 0.95;
      font-weight: 500;
    }

    /* Body */
    .glass-body {
      padding: 1.5rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem 1.25rem;
      align-items: end;
    }

    @media (max-width: 880px) {
      .form-grid { grid-template-columns: 1fr; }
    }

    .field {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .label-with-icon {
      display: flex;
      gap: 0.65rem;
      align-items: center;
      font-weight: 700;
      color: var(--muted);
      font-size: 0.95rem;
    }

    .label-icon {
      width: 32px;
      height: 32px;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: rgba(20,162,186,0.1);
      color: var(--accent-2);
    }

    input.form-control, select.form-select {
      border-radius: 12px;
      padding: 12px 14px;
      border: 1px solid rgba(0,0,0,0.1);
      background: rgba(255,255,255,0.95);
      font-size: 0.95rem;
    }

    [data-theme="dark"] input.form-control, 
    [data-theme="dark"] select.form-select {
      background: rgba(255,255,255,0.05);
      color: var(--text);
      border-color: rgba(255,255,255,0.1);
    }

    input.form-control:focus, select.form-select:focus {
      outline: none;
      border-color: var(--accent-2);
      box-shadow: 0 0 0 3px rgba(20,162,186,0.15);
    }

    .separator {
      grid-column: 1 / -1;
      height: 1px;
      margin: 0.75rem 0;
      border-bottom: 1px dashed rgba(0,0,0,0.1);
    }

    .actions {
      display: flex;
      gap: 0.75rem;
      justify-content: flex-end;
      grid-column: 1 / -1;
      padding-top: 0.5rem;
    }

    /* Button - TANPA shine effect */
    .btn-nova {
      background: linear-gradient(90deg, var(--accent-1), var(--accent-2));
      color: white;
      padding: 10px 18px;
      border-radius: 12px;
      font-weight: 700;
      border: none;
      cursor: pointer;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-nova:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(20,162,186,0.3);
    }

    .btn-outline {
      background: transparent;
      border: 1px solid rgba(0,0,0,0.1);
      color: var(--muted);
      padding: 10px 14px;
      border-radius: 12px;
      font-weight: 700;
      cursor: pointer;
    }

    .btn-loading {
      opacity: 0.7;
      pointer-events: none;
    }

    .spinner-inline {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,0.3);
      border-top-color: white;
      animation: spin 0.8s linear infinite;
      display: inline-block;
      vertical-align: middle;
      margin-right: 8px;
    }

    @keyframes spin { 
      to { transform: rotate(360deg); } 
    }

    .muted { 
      color: var(--muted); 
      font-size: 0.92rem; 
    }
  </style>

  <div class="glass-card-wrap">
    <div class="glass-card">
      <!-- Header -->
      <div class="glass-header">
        <div class="header-orb">
          <div class="orb-icon">
            <i class="bx bx-bar-chart" style="font-size:18px"></i>
          </div>
        </div>
        <div class="title">
          <span>Laporan Peminjaman Laptop</span>
          <span class="subtitle">Generate laporan berdasarkan filter yang dipilih</span>
        </div>
      </div>

      <!-- Body -->
      <div class="glass-body">
        @if(session('error'))
          <div class="alert alert-warning d-flex align-items-center mb-3">
            <i class="bx bx-error" style="font-size:20px; margin-right:8px;"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form id="reportForm" action="{{ route('laporan.export') }}" method="get" class="form-grid">
          <!-- Dari Tanggal -->
          <div class="field">
            <label class="label-with-icon" for="from">
              <span class="label-icon"><i class="bx bx-calendar"></i></span>
              Dari Tanggal
            </label>
            <input type="date" id="from" name="from" class="form-control">
            <small class="muted">Pilih tanggal awal</small>
          </div>

          <!-- Sampai Tanggal -->
          <div class="field">
            <label class="label-with-icon" for="to">
              <span class="label-icon"><i class="bx bx-calendar-check"></i></span>
              Sampai Tanggal
            </label>
            <input type="date" id="to" name="to" class="form-control">
            <small class="muted">Pilih tanggal akhir</small>
          </div>

          <!-- Status -->
          <div class="field">
            <label class="label-with-icon" for="status">
              <span class="label-icon"><i class="bx bx-list-check"></i></span>
              Status
            </label>
            <select id="status" name="status" class="form-select">
              <option value="">Semua</option>
              <option value="aktif">Aktif</option>
              <option value="selesai">Selesai</option>
            </select>
            <small class="muted">Filter status peminjaman</small>
          </div>

          <!-- Format -->
          <div class="field">
            <label class="label-with-icon" for="format">
              <span class="label-icon"><i class="bx bx-file"></i></span>
              Format Laporan
            </label>
            <select id="format" name="format" class="form-select">
              <option value="excel">Excel (.xlsx)</option>
              <option value="pdf">PDF (.pdf)</option>
            </select>
            <small class="muted">Pilih format export</small>
          </div>

          <div class="separator"></div>

          <!-- Actions -->
          <div class="actions">
            <button type="reset" class="btn-outline" id="resetBtn">
              <i class="bx bx-reset"></i> Reset Filter
            </button>
            <button type="submit" class="btn-nova" id="generateBtn">
              <span id="btnContent">
                <i class="bx bx-download"></i> Generate Laporan
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div id="novaToast" style="position:fixed; right:20px; bottom:22px; z-index:9999; display:none;">
    <div style="background:linear-gradient(135deg,#16a34a,#059669); color:white; padding:12px 16px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.2); font-weight:700;">
      <span id="novaToastMsg"></span>
    </div>
  </div>
</div>

<script>
(function() {
  const form = document.getElementById('reportForm');
  const btn = document.getElementById('generateBtn');
  const btnContent = document.getElementById('btnContent');

  form.addEventListener('submit', function(e) {
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    
    if (from && to && new Date(from) > new Date(to)) {
      e.preventDefault();
      alert('Tanggal awal tidak boleh lebih dari tanggal akhir');
      return;
    }

    btn.classList.add('btn-loading');
    const spinner = document.createElement('span');
    spinner.className = 'spinner-inline';
    btnContent.prepend(spinner);
    
    setTimeout(() => {
      btn.classList.remove('btn-loading');
      if(spinner.parentNode) spinner.remove();
    }, 10000);
  });

  @if(session('success'))
    setTimeout(() => alert("{{ session('success') }}"), 100);
  @endif
})();
</script>

@endsection