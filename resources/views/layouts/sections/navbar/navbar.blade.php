@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = $navbarDetached ?? '';
$ldapUser = session('ldap_user');
$isLoggedIn = Auth::check() || $ldapUser;
$userName = $ldapUser['displayName'] ?? Auth::user()->name ?? 'User';
$userEmail = $ldapUser['mail'] ?? Auth::user()->email ?? '';
@endphp

<!-- Navbar -->
@if($navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme" id="layout-navbar">
@else
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
  <div class="{{ $containerNav }}">
@endif

  @if(isset($navbarFull))
  <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
    <a href="{{ url('/') }}" class="app-brand-link gap-2">
      <img src="{{ asset('assets/img/logo_plnips.png') }}" alt="PLN Logo" class="app-brand-logo">
      <span class="app-brand-text demo menu-text fw-bold text-heading">PLN</span>
    </a>
  </div>
  @endif

  <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <div class="navbar-nav align-items-center me-3">
      <div class="nav-item d-flex align-items-center">
        <span class="fw-bold fs-5">Laptop Management</span>
      </div>
    </div>

    <div class="navbar-nav align-items-center"></div>

    <ul class="navbar-nav flex-row align-items-center ms-auto">
      <!-- Theme Toggle -->
      <li class="nav-item me-3 d-flex align-items-center">
        <span class="me-1">🌞</span>
        <label class="switch mb-0">
          <input type="checkbox" id="theme-toggle-checkbox">
          <span class="slider round"></span>
        </label>
        <span class="ms-1">🌙</span>
      </li>

      <!-- User Dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar">
            <img src="{{ asset('assets/img/avatars/blnk.png') }}" alt class="w-px-40 h-auto rounded-circle">
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          @if($isLoggedIn)
            <li>
              <a class="dropdown-item">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar">
                      <img src="{{ asset('assets/img/avatars/blnk.png') }}" alt class="w-px-40 h-auto rounded-circle">
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="mb-0">{{ $userName }}</h6>
                    <small class="text-muted">{{ $userEmail }}</small>
                  </div>
                </div>
              </a>
            </li>
            <li><div class="dropdown-divider my-1"></div></li>
            <li>
              <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item">
                  <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                </button>
              </form>
            </li>
          @else
            <li>
              <a class="dropdown-item" href="{{ route('auth-login-basic') }}">
                <i class="bx bx-log-in bx-md me-3"></i><span>Login</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('register') }}">
                <i class="bx bx-user-plus bx-md me-3"></i><span>Register</span>
              </a>
            </li>
          @endif
        </ul>
      </li>
      <!-- /User Dropdown -->
    </ul>
  </div>

  @if($navbarDetached == '')
  </div>
  @endif
</nav>

<!-- Theme toggle script & styling sama seperti sebelumnya -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const checkbox = document.getElementById('theme-toggle-checkbox');
  const html = document.documentElement;
  const savedTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', savedTheme);
  checkbox.checked = savedTheme === 'dark';
  checkbox.addEventListener('change', function () {
    const newTheme = this.checked ? 'dark' : 'light';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
  });
});
</script>

<style>
.switch { position: relative; display: inline-block; width: 48px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #14a2ba; }
input:checked + .slider:before { transform: translateX(24px); }
</style>
