<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <!-- Logo -->
  <div class="app-brand demo p-3">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <span class="app-brand-logo demo">
        <img src="{{ asset('assets/img/white-pln2.png') }}" alt="PLN Logo" class="app-brand-logo">
      </span>
      <span class="font-bold text-white">Laptop Management</span>
    </a>

    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
    </a>
  </div>

  <div class="menu-inner-shadow"></div>

  <!-- Menu Items -->
  <ul class="menu-inner py-1">
    <li class="menu-header small text-uppercase">
      <span class="menu-header-text">Dashboard</span>
    </li>
    <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ url('dashboard') }}" class="menu-link">
        <i class="bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase"><span class="menu-header-text">Daftar Laptop & Peminjam</span></li>
    <li class="menu-item {{ request()->is('tables/laptop*') ? 'active' : '' }}">
      <a href="{{ url('tables/laptop') }}" class="menu-link">
        <i class="bx bx-monitor"></i>
        <div>Daftar Laptop</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('tables/peminjam*') ? 'active' : '' }}">
      <a href="{{ url('tables/peminjam') }}" class="menu-link">
        <i class="bx bx-user"></i>
        <div>Daftar Peminjam</div>
      </a>
    </li>

    <li class="menu-header small text-uppercase"><span class="menu-header-text">Misc</span></li>
    <li class="menu-item {{ request()->is('support') ? 'active' : '' }}">
      <a href="{{ url('support') }}" class="menu-link">
        <i class="bx bx-help-circle"></i>
        <div>Support</div>
      </a>
    </li>
    <li class="menu-item {{ request()->is('docs') ? 'active' : '' }}">
      <a href="{{ url('docs') }}" class="menu-link">
        <i class="bx bx-file"></i>
        <div>Documentation</div>
      </a>
    </li>
  </ul>
</aside>

<style>
  #layout-menu .menu-item.active > .menu-link {
    background-color: #ffffff !important;
    color: #14a2ba !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
  }

  #layout-menu .menu-item.active > .menu-link i { color: #14a2ba !important; }
  #layout-menu .menu-item:not(.active) > .menu-link { color: #ffffff !important; }
  #layout-menu .menu-item:not(.active) > .menu-link i { color: #ffffff !important; }
  #layout-menu .menu-item:not(.active) > .menu-link:hover { background-color: rgba(255,255,255,0.15) !important; }
</style>
