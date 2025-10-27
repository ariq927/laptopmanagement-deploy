<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Logo / App Brand -->
  <div class="app-brand demo">
    <a href="{{ Auth::check() 
                ? (Auth::user()->role === 'admin' 
                    ? route('admin.dashboard') 
                    : route('dashboard-analytics')) 
                : url('/') }}" 
       class="app-brand-link gap-2">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/white-pln2.png') }}" alt="PLN Logo" class="app-brand-logo">
      </span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    {{-- Dashboard --}}
    <li class="menu-item {{ request()->is('/') ? 'active' : '' }}">
      <a href="{{ url('/') }}" class="menu-link">
        <i class="bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- DAFTAR LAPTOP & PEMINJAM --}}
    <li class="menu-header small text-uppercase mt-3">
      <span class="menu-header-text">DAFTAR LAPTOP & PEMINJAM</span>
    </li>

    <li class="menu-item {{ request()->is('tables/laptop') ? 'active' : '' }}">
      <a href="{{ url('tables/laptop') }}" class="menu-link">
        <i class="bx bx-laptop"></i>
        <div>Daftar Laptop</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('tables/basic') ? 'active' : '' }}">
      <a href="{{ url('tables/basic') }}" class="menu-link">
        <i class="bx bx-user"></i>
        <div>Daftar Peminjam</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('laptop/arsip') ? 'active' : '' }}">
      <a href="{{ url('laptop/arsip') }}" class="menu-link">
        <i class="bx bx-archive"></i>
        <div>Laptop Diarsip</div>
      </a>
    </li>

    {{-- LAPORAN --}}
    <li class="menu-header small text-uppercase mt-3">
      <span class="menu-header-text">Laporan</span>
    </li>

    <li class="menu-item {{ request()->is('laporan') ? 'active' : '' }}">
      <a href="{{ url('laporan') }}" class="menu-link">
        <i class="bx bx-file"></i>
        <div>Laporan</div>
      </a>
    </li>
  </ul>
</aside>

{{-- inline style --}}
<style>
  /* Light Mode - Default */
  #layout-menu {
    background: #14a2ba !important;
  }

  /* Dark Mode */
  [data-theme="dark"] #layout-menu,
  body.dark-mode #layout-menu {
    background: #2b2b3c !important;
  }

  #layout-menu .menu-item.active > .menu-link {
    background-color: rgba(255, 255, 255, 0.95) !important;
    color: #14a2ba !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
  }

  #layout-menu .menu-item.active > .menu-link i {
    color: #14a2ba !important;
  }

  [data-theme="dark"] #layout-menu .menu-item.active > .menu-link,
  body.dark-mode #layout-menu .menu-item.active > .menu-link {
    background-color: rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 6px rgba(255, 255, 255, 0.2) !important;
    border-left: 3px solid #ffffff !important;
    padding-left: calc(1rem - 3px) !important;
  }

  [data-theme="dark"] #layout-menu .menu-item.active > .menu-link i,
  body.dark-mode #layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link {
    color: #ffffff !important;
    font-weight: 600 !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link i {
    color: #ffffff !important;
  }

  [data-theme="dark"] #layout-menu .menu-item:not(.active) > .menu-link,
  body.dark-mode #layout-menu .menu-item:not(.active) > .menu-link {
    color: #ffffff !important;
  }

  [data-theme="dark"] #layout-menu .menu-item:not(.active) > .menu-link i,
  body.dark-mode #layout-menu .menu-item:not(.active) > .menu-link i {
    color: #ffffff !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
  }

  [data-theme="dark"] #layout-menu .menu-item:not(.active) > .menu-link:hover,
  body.dark-mode #layout-menu .menu-item:not(.active) > .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important;
  }

  #layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.85) !important;
    font-weight: 600 !important;
    letter-spacing: 0.5px;
  }

  [data-theme="dark"] #layout-menu .menu-header-text,
  body.dark-mode #layout-menu .menu-header-text {
    color: rgba(255, 255, 255, 0.7) !important;
  }

  #layout-menu .menu-link {
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
  }

  #layout-menu .menu-link i {
    font-size: 22px; 
    margin-right: 10px;
  }

  [data-theme="dark"] #layout-menu .menu-inner,
  body.dark-mode #layout-menu .menu-inner {
    background: transparent !important;
  }

  [data-theme="dark"] .app-brand-logo,
  body.dark-mode .app-brand-logo {
    filter: brightness(0.95);
  }
</style>