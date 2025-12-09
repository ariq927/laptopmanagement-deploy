@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = $navbarDetached ?? '';
$ldapUser = session('ldap_user');
$isLoggedIn = Auth::check() || $ldapUser;
$userName = $ldapUser['displayName'] ?? Auth::user()->name ?? 'User';
$userEmail = $ldapUser['mail'] ?? Auth::user()->email ?? '';
@endphp

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

@if($navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{ $containerNav }} navbar navbar-expand-xl {{ $navbarDetached }} align-items-center bg-navbar-theme navbar-card" 
     id="layout-navbar">
@else
<nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme navbar-card" 
     id="layout-navbar">
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
        <a class="nav-link dropdown-toggle hide-arrow p-0" href="#" role="button">
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

<!-- Theme Toggle & Dropdown Script -->
<!-- Theme Toggle & Dropdown Script -->
<script data-navigate-once="navbar-scripts">
// ✅ Function untuk initialize navbar functionality
function initializeNavbar() {
  console.log('🔧 Initializing navbar...');
  
  // ===== Theme Toggle =====
  const toggleBtn = document.getElementById('theme-toggle');
  const icon = document.getElementById('theme-icon');
  const html = document.documentElement;

  if (toggleBtn && icon) {
    // Remove old listener dengan clone
    const newToggleBtn = toggleBtn.cloneNode(true);
    toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
    
    const newIcon = document.getElementById('theme-icon');
    
    // Set initial theme
    const savedTheme = localStorage.getItem('theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    
    if (savedTheme === 'dark') {
      html.classList.remove('light-style');
      html.classList.add('dark-style');
      html.style.background = '#121212';
      html.style.colorScheme = 'dark';
      newIcon.className = 'bx bx-moon fs-4';
    } else {
      html.classList.remove('dark-style');
      html.classList.add('light-style');
      html.style.background = '#ffffff';
      html.style.colorScheme = 'light';
      newIcon.className = 'bx bx-sun fs-4';
    }
    
    // Add new listener
    document.getElementById('theme-toggle').addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const currentTheme = html.getAttribute('data-theme');
      const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
      
      html.setAttribute('data-theme', newTheme);
      localStorage.setItem('theme', newTheme);
      
      const themeIcon = document.getElementById('theme-icon');
      
      if (newTheme === 'dark') {
        html.classList.remove('light-style');
        html.classList.add('dark-style');
        html.style.background = '#121212';
        html.style.colorScheme = 'dark';
        themeIcon.className = 'bx bx-moon fs-4';
      } else {
        html.classList.remove('dark-style');
        html.classList.add('light-style');
        html.style.background = '#ffffff';
        html.style.colorScheme = 'light';
        themeIcon.className = 'bx bx-sun fs-4';
      }
      
      console.log('🌓 Theme changed to:', newTheme);
    });
  }

  // ===== User Dropdown Manual Toggle =====
  const dropdownToggle = document.querySelector('.dropdown-user .dropdown-toggle');
  const dropdownMenu = document.querySelector('.dropdown-user .dropdown-menu');

  if (dropdownToggle && dropdownMenu) {
    // Remove old listeners dengan clone
    const newDropdownToggle = dropdownToggle.cloneNode(true);
    dropdownToggle.parentNode.replaceChild(newDropdownToggle, dropdownToggle);
    
    const newDropdownMenu = document.querySelector('.dropdown-user .dropdown-menu');
    
    // Toggle dropdown on click
    document.querySelector('.dropdown-user .dropdown-toggle').addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const menu = document.querySelector('.dropdown-user .dropdown-menu');
      const isShown = menu.classList.contains('show');
      
      // Close all other dropdowns first
      document.querySelectorAll('.dropdown-menu.show').forEach(m => {
        m.classList.remove('show');
      });
      
      // Toggle current dropdown
      if (!isShown) {
        menu.classList.add('show');
      }
      
      console.log('👤 Dropdown toggled:', !isShown);
    });

    // Close dropdown when clicking outside
    const closeDropdownOutside = function(e) {
      const toggle = document.querySelector('.dropdown-user .dropdown-toggle');
      const menu = document.querySelector('.dropdown-user .dropdown-menu');
      
      if (toggle && menu && !toggle.contains(e.target) && !menu.contains(e.target)) {
        menu.classList.remove('show');
      }
    };
    
    // Remove old listener
    document.removeEventListener('click', closeDropdownOutside);
    // Add new listener
    document.addEventListener('click', closeDropdownOutside);

    // Prevent dropdown from closing when clicking inside
    newDropdownMenu.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  }
  
  console.log('✅ Navbar initialized!');
}

// ✅ Initialize on first load
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeNavbar);
} else {
  initializeNavbar();
}

// ✅ Reinitialize after Livewire navigation
document.addEventListener('livewire:navigated', function() {
  console.log('🔄 Livewire navigated - reinitializing navbar...');
  setTimeout(initializeNavbar, 50);
});
</script>

<!-- Theme Toggle & Dropdown Styling -->
<style>
/* Glass Toggle Button */
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

/* Dropdown Base Styling */
.dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  right: 0;
  z-index: 1000;
  min-width: 12rem;
  padding: 0.5rem 0;
  margin: 0.125rem 0 0;
  font-size: 0.9375rem;
  color: #697a8d;
  text-align: left;
  list-style: none;
  background-color: #fff;
  background-clip: padding-box;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 0.375rem;
  box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.45);
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity 0.15s ease, transform 0.15s ease;
  pointer-events: none;
}

.dropdown-menu.show {
  display: block;
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

/* Dropdown Items */
.dropdown-item {
  display: block;
  width: 100%;
  padding: 0.532rem 1.25rem;
  clear: both;
  font-weight: 400;
  color: #697a8d;
  text-align: inherit;
  text-decoration: none;
  white-space: nowrap;
  background-color: transparent;
  border: 0;
  cursor: pointer;
  transition: all 0.2s ease;
}

.dropdown-item:hover,
.dropdown-item:focus {
  color: #566a7f;
  background-color: rgba(67, 89, 113, 0.04);
}

.dropdown-item i {
  vertical-align: middle;
}

.dropdown-item span {
  line-height: 1;
}

.dropdown-divider {
  height: 0;
  margin: 0.5rem 0;
  overflow: hidden;
  border-top: 1px solid rgba(0, 0, 0, 0.06);
}

/* Dark Theme Dropdown */
[data-theme='dark'] .dropdown-menu {
  background-color: #2b2b3c !important;
  color: #e0e0e0 !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.5);
}

[data-theme='dark'] .dropdown-item {
  color: #e0e0e0 !important;
}

[data-theme='dark'] .dropdown-item:hover,
[data-theme='dark'] .dropdown-item:focus {
  background-color: rgba(255, 255, 255, 0.05);
  color: #ffffff !important;
}

[data-theme='dark'] .dropdown-divider {
  border-top-color: rgba(255, 255, 255, 0.1);
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

/* User Avatar Hover Effect */
.dropdown-user .dropdown-toggle {
  transition: transform 0.2s ease;
}

.dropdown-user .dropdown-toggle:hover {
  transform: scale(1.05);
}

.navbar-card {
  border-radius: 25px;         
  padding-left: 0rem;       
  padding-right: 1.3rem;      
  margin: 0 0.8rem;            
  width: auto;                
  max-width: 100%;             
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
}
</style>