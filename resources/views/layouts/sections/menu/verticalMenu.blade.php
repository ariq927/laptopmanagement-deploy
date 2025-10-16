<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme" style="background-color: #005bac;">
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
        <i class="bx bx-monitor"></i>
        <div>Daftar Laptop</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('tables/basic') ? 'active' : '' }}">
      <a href="{{ url('tables/basic') }}" class="menu-link">
        <i class="bx bx-user"></i>
        <div>Daftar Peminjam</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('arsip/laptop') ? 'active' : '' }}">
      <a href="{{ url('arsip/laptop') }}" class="menu-link">
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

{{-- CSS Menu Aktif --}}
<style>
  #layout-menu .menu-item.active > .menu-link {
    background-color: #ffffff !important;
    color: #14a2ba !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
  }

  #layout-menu .menu-item.active > .menu-link i {
    color: #14a2ba !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link {
    color: #ffffff !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link i {
    color: #ffffff !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link:hover {
    background-color: rgba(255, 255, 255, 0.15) !important;
  }
</style>
