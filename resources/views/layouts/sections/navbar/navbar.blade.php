@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = $navbarDetached ?? '';
$ldapUser = session('ldap_user');
$isLoggedIn = Auth::check() || $ldapUser;
$userName = $ldapUser['displayName'] ?? Auth::user()->name ?? 'User';
$userEmail = $ldapUser['mail'] ?? Auth::user()->email ?? '';
@endphp

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

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
        <button id="theme-toggle" class="glass-toggle-btn">
          <i id="theme-icon" class="bx bx-sun fs-4"></i>
        </button>
      </li>

      <!-- User Dropdown -->
      <li class="nav-item navbar-dropdown dropdown-user dropdown">
        <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
          <div class="avatar">
            <div class="avatar d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 40px; height: 40px;">
              <i class="bx bx-user fs-3 text-primary"></i>
            </div>
          </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-end">
          @if($isLoggedIn)
            <li>
              <a class="dropdown-item">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar">
                      <div class="avatar d-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 40px; height: 40px;">
                        <i class="bx bx-user fs-3 text-primary"></i>
                      </div>
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
                  <i class="bx bx-power-off fs-5 me-2"></i><span class="fw-medium">Log Out</span>
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
    </ul>
  </div>

  @if($navbarDetached == '')
  </div>
  @endif
</nav>

<!-- Theme Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const toggleBtn = document.getElementById('theme-toggle');
  const icon = document.getElementById('theme-icon');
  const html = document.documentElement;

  const savedTheme = localStorage.getItem('theme') || 'light';
  html.setAttribute('data-theme', savedTheme);
  icon.className = savedTheme === 'dark' ? 'bx bx-moon fs-4' : 'bx bx-sun fs-4';

  toggleBtn.addEventListener('click', function () {
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    icon.className = newTheme === 'dark' ? 'bx bx-moon fs-4' : 'bx bx-sun fs-4';
  });
});
</script>

<!-- Theme Toggle Styling -->
<style>
.glass-toggle-btn {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  backdrop-filter: blur(8px);
  background: rgba(255, 255, 255, 0.25);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease;
  cursor: pointer;
}

.glass-toggle-btn:hover {
  transform: scale(1.1);
  background: rgba(20, 162, 186, 0.25);
  box-shadow: 0 6px 14px rgba(20, 162, 186, 0.2);
}

#theme-icon {
  color: #14a2ba;
  transition: color 0.3s ease;
}

[data-theme='dark'] #theme-icon {
  color: #fdd835;
}

.dropdown-item i {
  vertical-align: middle;
}
.dropdown-item span {
  line-height: 1;
}

[data-theme='dark'] .dropdown-menu {
  background-color: #2b2b3c !important;
  color: #e0e0e0 !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

[data-theme='dark'] .dropdown-menu .dropdown-item {
  color: #e0e0e0 !important;
}

[data-theme='dark'] .dropdown-menu .dropdown-item:hover {
  background-color: rgba(255, 255, 255, 0.05);
}

[data-theme='dark'] .dropdown-menu i {
  color: #fdd835 !important; 
  transition: color 0.3s ease;
}

[data-theme='dark'] .navbar .bx-user {
  color: #fdd835 !important; 
  transition: color 0.3s ease;
}

[data-theme='light'] .navbar .bx-user {
  color: #14a2ba !important; 
}
</style>
