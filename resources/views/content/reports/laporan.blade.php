@extends('layouts/contentNavbarLayout')

@section('title', 'Laporan Peminjaman Laptop')

@section('content')
@php
  // gunakan session('theme') jika ada; fallback ke prefers-color-scheme via CSS
  $isDarkMode = session('theme') === 'dark';
@endphp

<div id="glass-nova-root" data-theme="{{ $isDarkMode ? 'dark' : 'light' }}">
  <style>
    /* =========================
       Theme variables & reset
       ========================= */
    :root {
      --bg-0: #f6fbfc;
      --card-bg: rgba(255,255,255,0.84);
      --muted: #6b7c80;
      --accent-1: #14a2ba;
      --accent-2: #0d7a8e;
      --accent-3: #0b8699;
      --success: #10b981;
      --danger: #ef4444;
      --glass-border: rgba(255,255,255,0.28);
      --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
      --control-radius: 12px;
      --btn-radius: 12px;
      --text: #0c2a2f;
    }

    /* dark override */
    [data-theme="dark"] {
      --bg-0: linear-gradient(180deg,#0b1720 0%, #12242e 100%);
      --card-bg: rgba(20,28,34,0.5);
      --muted: #b9d7de;
      --accent-1: #0fa0b8;
      --accent-2: #0a6a74;
      --accent-3: #0b8399;
      --glass-border: rgba(255,255,255,0.06);
      --shadow: 0 12px 30px rgba(0,0,0,0.5);
      --text: #e6f7f8;
    }

    /* Respect user OS preference if session not set (handled by root wrapper) */
    @media (prefers-color-scheme: dark) {
      :root:not([data-theme]) {
        --bg-0: linear-gradient(180deg,#0b1720 0%, #12242e 100%);
        --card-bg: rgba(20,28,34,0.5);
        --muted: #b9d7de;
        --accent-1: #0fa0b8;
        --accent-2: #0a6a74;
        --accent-3: #0b8399;
        --glass-border: rgba(255,255,255,0.06);
        --shadow: 0 12px 30px rgba(0,0,0,0.5);
        --text: #e6f7f8;
      }
    }

    /* page background */
    #glass-nova-root {
      padding: 2.25rem 1rem;
      background: var(--bg-0);
      min-height: 100vh;
      color: var(--text);
      transition: background 0.4s ease, color 0.4s ease;
      font-family: Inter, "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }

    /* container center */
    .glass-card-wrap {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1.25rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    /* =========================
       Main Card - glass + gradient border (animated)
       ========================= */
    .glass-card {
      position: relative;
      background: linear-gradient(180deg, rgba(255,255,255,0.65), rgba(255,255,255,0.55));
      border-radius: 16px;
      padding: 0;
      overflow: visible;
      box-shadow: var(--shadow);
      backdrop-filter: blur(8px) saturate(120%);
      border: 1px solid var(--glass-border);
      transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    [data-theme="dark"] .glass-card {
      background: rgba(20,28,34,0.55);
      border: 1px solid rgba(255,255,255,0.04);
    }

    .glass-card:hover { transform: translateY(-6px); }

    /* animated gradient border */
    .glass-card::before {
      content: "";
      position: absolute;
      inset: -2px;
      z-index: 0;
      border-radius: 18px;
      padding: 2px;
      background: linear-gradient(90deg, rgba(20,162,186,0.95), rgba(11,134,153,0.9), rgba(15,160,184,0.95));
      -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
      -webkit-mask-composite: xor;
      mask-composite: exclude;
      animation: hueShift 6s linear infinite;
      opacity: 0.95;
      filter: blur(8px) saturate(120%);
      pointer-events: none;
    }

    @keyframes hueShift {
      0% { filter: hue-rotate(0deg) blur(6px); }
      50% { filter: hue-rotate(40deg) blur(8px); }
      100% { filter: hue-rotate(0deg) blur(6px); }
    }

    /* =========================
       Header area (with floating orb + icon)
       ========================= */
    .glass-header {
      position: relative;
      z-index: 2;
      padding: 1.25rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      border-radius: 14px 14px 0 0;
      background: linear-gradient(90deg, rgba(20,162,186,0.95), rgba(11,134,153,0.9));
      color: #fff;
      box-shadow: inset 0 -2px 6px rgba(0,0,0,0.06);
    }

    .glass-header .title {
      font-size: 1.375rem;
      font-weight: 700;
      letter-spacing: 0.2px;
      display:flex;
      flex-direction:column;
    }

    .glass-header .subtitle {
      font-size: 0.9rem;
      opacity: 0.95;
      font-weight: 500;
    }

    /* floating orb (decorative) */
    .header-orb {
      width: 56px;
      height: 56px;
      border-radius: 50%;
      background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.22), rgba(255,255,255,0.06));
      display:flex;
      align-items:center;
      justify-content:center;
      box-shadow: 0 6px 18px rgba(11,134,153,0.32);
      transform-origin: center;
      animation: orbFloat 6s ease-in-out infinite;
      margin-right: 8px;
    }

    @keyframes orbFloat {
      0% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-6px) rotate(6deg); }
      100% { transform: translateY(0) rotate(0deg); }
    }

    /* icon pulse */
    .orb-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
      display:flex;
      align-items:center;
      justify-content:center;
      color: var(--accent-2);
      font-weight:700;
      animation: pulseIcon 2.6s ease-in-out infinite;
    }

    @keyframes pulseIcon {
      0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(15,160,184,0.28); }
      70% { transform: scale(1.05); box-shadow: 0 0 0 12px rgba(15,160,184,0.04); }
      100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(15,160,184,0); }
    }

    /* =========================
       Form area
       ========================= */
    .glass-body {
      padding: 1.5rem;
      z-index: 2;
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
      display:flex;
      flex-direction:column;
      gap:0.5rem;
    }

    .label-with-icon {
      display:flex;
      gap:0.65rem;
      align-items:center;
      font-weight:700;
      color:var(--muted);
      font-size:0.95rem;
    }

    .label-with-icon .label-icon {
      width:32px;
      height:32px;
      border-radius:8px;
      display:flex;
      align-items:center;
      justify-content:center;
      background: linear-gradient(180deg, rgba(255,255,255,0.95), rgba(255,255,255,0.85));
      color: var(--accent-2);
      box-shadow: 0 6px 18px rgba(11,134,153,0.06);
      font-size:0.95rem;
    }

    [data-theme="dark"] .label-with-icon .label-icon {
      background: rgba(255,255,255,0.04);
      color: var(--accent-1);
      box-shadow: none;
    }

    input.form-control, select.form-select {
      border-radius: var(--control-radius);
      padding: 12px 14px;
      border: 1px solid rgba(15,23,29,0.06);
      background: rgba(255,255,255,0.95);
      transition: box-shadow 0.22s ease, transform 0.12s ease;
      font-size: 0.95rem;
    }

    [data-theme="dark"] input.form-control, [data-theme="dark"] select.form-select {
      background: rgba(255,255,255,0.03);
      color: var(--text);
      border: 1px solid rgba(255,255,255,0.04);
    }

    input.form-control:focus, select.form-select:focus {
      outline: none;
      box-shadow: 0 8px 24px rgba(13,148,136,0.12);
      transform: translateY(-2px);
      border-color: var(--accent-2);
    }

    /* subtle dotted separator */
    .separator {
      grid-column: 1 / -1;
      height: 1px;
      margin: 0.75rem 0;
      border-bottom: 1px dashed rgba(15,23,29,0.06);
    }

    [data-theme="dark"] .separator { border-color: rgba(255,255,255,0.04); }

    /* action area (buttons) */
    .actions {
      display:flex;
      gap: 0.75rem;
      justify-content:flex-end;
      grid-column: 1 / -1;
      padding-top: 0.5rem;
    }

    /* advanced button with shine sweep + ripple */
    .btn-nova {
      --bg: linear-gradient(90deg, var(--accent-1), var(--accent-3));
      background: var(--bg);
      color: white;
      padding: 10px 18px;
      border-radius: var(--btn-radius);
      font-weight: 700;
      border: none;
      position: relative;
      overflow: hidden;
      cursor: pointer;
      transition: transform 0.18s ease, box-shadow 0.18s ease;
      box-shadow: 0 8px 22px rgba(11,134,153,0.16);
    }

    .btn-nova:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(11,134,153,0.2); }

    /* shine sweep */
    .btn-nova::after {
      content: "";
      position: absolute;
      top: 0;
      left: -60%;
      width: 60%;
      height: 100%;
      background: linear-gradient(120deg, rgba(255,255,255,0.18), rgba(255,255,255,0.06));
      transform: skewX(-18deg);
      transition: left 0.6s cubic-bezier(.2,.9,.2,1);
    }

    .btn-nova:hover::after { left: 120%; }

    /* ripple */
    .btn-nova .ripple {
      position:absolute;
      border-radius:50%;
      transform: scale(0);
      background: rgba(255,255,255,0.36);
      animation: ripple 0.6s linear;
      pointer-events: none;
    }

    @keyframes ripple {
      to { transform: scale(6); opacity: 0; }
    }

    /* outline secondary */
    .btn-outline {
      background: transparent;
      border: 1px solid rgba(15,23,29,0.06);
      color: var(--muted);
      padding: 10px 14px;
      border-radius: 12px;
      font-weight:700;
    }

    /* loading state (on submit) */
    .btn-loading {
      position: relative;
      pointer-events: none;
      opacity: 0.85;
    }

    .spinner-inline {
      width: 18px;
      height: 18px;
      border-radius: 50%;
      border: 2px solid rgba(255,255,255,0.2);
      border-top-color: rgba(255,255,255,0.95);
      animation: spin 0.9s linear infinite;
      display:inline-block;
      vertical-align: middle;
      margin-right:8px;
    }

    @keyframes spin { to { transform: rotate(360deg); } }

    /* shimmer animated border around card body */
    .body-shimmer {
      position: absolute;
      inset: 72px 20px 20px 20px;
      border-radius: 12px;
      pointer-events: none;
      background: linear-gradient(90deg, rgba(255,255,255,0.02), rgba(255,255,255,0.04), rgba(255,255,255,0.02));
      mix-blend-mode: overlay;
      animation: shimmer 4s linear infinite;
    }

    @keyframes shimmer {
      0% { background-position: -400px 0; }
      100% { background-position: 400px 0; }
    }

    /* small helper text */
    .muted { color: var(--muted); font-size: 0.92rem; }

    /* accessible focus outlines */
    :focus { outline: none; box-shadow: 0 0 0 4px rgba(15,160,184,0.12); border-color: var(--accent-2); }

  </style>

  <div class="glass-card-wrap">
    <div class="glass-card" role="region" aria-labelledby="report-title">

      <!-- header -->
      <div class="glass-header">
        <div class="header-orb" aria-hidden="true">
          <div class="orb-icon"><i class="bx bx-bar-chart" style="font-size:18px"></i></div>
        </div>

        <div class="title" id="report-title">
          <span>Laporan Peminjaman Laptop</span>
          <span class="subtitle">Generate laporan berdasarkan filter yang dipilih</span>
        </div>
      </div>

      <!-- shimmer layer (decorative) -->
      <div class="body-shimmer" aria-hidden="true"></div>

      <!-- body -->
      <div class="glass-body">
        @if(session('error'))
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bx bx-error mr-2" style="font-size:20px; margin-right:8px;"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <form id="reportForm" action="{{ route('laporan.export') }}" method="get" class="form-grid" novalidate>
          <!-- Dari Tanggal -->
          <div class="field">
            <label class="label-with-icon" for="from">
              <span class="label-icon"><i class="bx bx-calendar"></i></span>
              Dari Tanggal
            </label>
            <input type="date" id="from" name="from" class="form-control" aria-label="Dari tanggal">
            <small class="muted">Pilih tanggal awal</small>
          </div>

          <!-- Sampai Tanggal -->
          <div class="field">
            <label class="label-with-icon" for="to">
              <span class="label-icon"><i class="bx bx-calendar-check"></i></span>
              Sampai Tanggal
            </label>
            <input type="date" id="to" name="to" class="form-control" aria-label="Sampai tanggal">
            <small class="muted">Pilih tanggal akhir</small>
          </div>

          <!-- Status -->
          <div class="field">
            <label class="label-with-icon" for="status">
              <span class="label-icon"><i class="bx bx-list-check"></i></span>
              Status
            </label>
            <select id="status" name="status" class="form-select" aria-label="Status peminjaman">
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
            <select id="format" name="format" class="form-select" aria-label="Format laporan">
              <option value="excel">Excel (.xlsx)</option>
              <option value="pdf">PDF (.pdf)</option>
            </select>
            <small class="muted">Pilih format export</small>
          </div>

          <div class="separator" aria-hidden="true"></div>

          <!-- Actions -->
          <div class="actions">
            <button type="reset" class="btn-outline" id="resetBtn" aria-label="Reset filter">
              <i class="bx bx-reset" style="vertical-align:middle;"></i> Reset Filter
            </button>

            <button type="submit" class="btn-nova" id="generateBtn" aria-label="Generate laporan">
              <span id="btnContent">
                <i class="bx bx-download" style="vertical-align:middle; margin-right:8px;"></i>
                Generate Laporan
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Toast -->
  <div id="novaToast" style="position:fixed; right:20px; bottom:22px; z-index:20000; display:none;">
    <div style="background:linear-gradient(135deg,#16a34a,#059669); color:white; padding:10px 14px; border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,0.2); font-weight:700;">
      <span id="novaToastMsg"></span>
    </div>
  </div>
</div>

<script>
  // =========================
  // Client-side enhancement JS
  // - 1) ripple effect on button click
  // - 2) loading state on submit with spinner
  // - 3) simple validation: from <= to (non-blocking)
  // - 4) toast helper
  // =========================

  (function () {
    const root = document.getElementById('glass-nova-root');
    // if session-driven theme not set, prefer OS setting
    if (!root.getAttribute('data-theme')) {
      const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      root.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
    }

    const form = document.getElementById('reportForm');
    const btn = document.getElementById('generateBtn');
    const btnContent = document.getElementById('btnContent');
    const toast = document.getElementById('novaToast');
    const toastMsg = document.getElementById('novaToastMsg');

    // ripple on click
    btn.addEventListener('click', function (e) {
      // create ripple element
      const rect = btn.getBoundingClientRect();
      const ripple = document.createElement('span');
      ripple.className = 'ripple';
      const size = Math.max(rect.width, rect.height) * 0.2;
      ripple.style.width = ripple.style.height = (size * 2) + 'px';
      ripple.style.left = (e.clientX - rect.left - size) + 'px';
      ripple.style.top = (e.clientY - rect.top - size) + 'px';
      btn.appendChild(ripple);
      setTimeout(() => ripple.remove(), 650);
    });

    // simple validation and loading
    form.addEventListener('submit', function (e) {
      // perform small client-side validation (non-blocking)
      const from = document.getElementById('from').value;
      const to = document.getElementById('to').value;
      if (from && to && (new Date(from) > new Date(to))) {
        e.preventDefault();
        showNovaToast('Tanggal awal tidak boleh lebih dari tanggal akhir', '#ef4444');
        return;
      }

      // set loading state
      btn.classList.add('btn-loading');
      btn.setAttribute('aria-busy', 'true');
      const spinner = document.createElement('span');
      spinner.className = 'spinner-inline';
      // replace content with spinner + text
      btnContent.prepend(spinner);
      // disable reset while loading
      document.getElementById('resetBtn').disabled = true;

      // allow normal form submit to proceed to backend
      // if this were AJAX we would handle promise then restore state
      // but since backend handles download, we still show loading
      // after 10s fallback remove (in case of navigation canceled)
      setTimeout(() => {
        if (btn.classList.contains('btn-loading')) {
          btn.classList.remove('btn-loading');
          btn.removeAttribute('aria-busy');
          if (spinner) spinner.remove();
          document.getElementById('resetBtn').disabled = false;
        }
      }, 10000);
    });

    // toast helper
    window.showNovaToast = function (message, bg) {
      toastMsg.innerText = message;
      toast.style.display = 'block';
      toast.firstElementChild.style.background = bg || 'linear-gradient(135deg,#16a34a,#059669)';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 3000);
    };

    // optional: restore toast from server flash messages (Laravel)
    @if(session('success'))
      showNovaToast("{!! addslashes(session('success')) !!}", 'linear-gradient(135deg,#10b981,#08915b)');
    @endif
    @if(session('error'))
      showNovaToast("{!! addslashes(session('error')) !!}", 'linear-gradient(135deg,#ef4444,#d43f3f)');
    @endif

    // progressive enhancement: keyboard shortcut (g) => focus generate
    document.addEventListener('keydown', function (e) {
      if (e.key.toLowerCase() === 'g' && (e.ctrlKey || e.metaKey)) {
        e.preventDefault();
        btn.focus();
      }
    });

  })();
</script>

<script>
// ✅ Cleanup saat leave page untuk performance
document.addEventListener('livewire:navigating', function() {
  console.log('🧹 Cleaning up laporan page...');
  
  // Stop semua animasi
  const root = document.getElementById('glass-nova-root');
  if (root) {
    root.style.animation = 'none';
    root.querySelectorAll('*').forEach(el => {
      el.style.animation = 'none';
      el.style.transition = 'none';
    });
  }
  
  // Remove event listeners
  const form = document.getElementById('reportForm');
  const btn = document.getElementById('generateBtn');
  
  if (form) {
    const newForm = form.cloneNode(true);
    form.parentNode.replaceChild(newForm, form);
  }
  
  if (btn) {
    const newBtn = btn.cloneNode(true);
    btn.parentNode.replaceChild(newBtn, btn);
  }
  
  // Clear toast
  const toast = document.getElementById('novaToast');
  if (toast) toast.style.display = 'none';
  
  console.log('✅ Laporan page cleaned!');
});
</script>
@endsection
