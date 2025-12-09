<!-- Slider Tab for Mobile -->
<div id="mobile-slider-tab" style="display: none; position: fixed; top: 50%; left: 0; transform: translateY(-50%); z-index: 999999; pointer-events: auto; width: 45px; height: 100px; background: #14a2ba; border-radius: 0 20px 20px 0; cursor: pointer; box-shadow: 3px 0 15px rgba(0,0,0,0.4); align-items: center; justify-content: center;">
  <i class="bx bx-chevron-right" style="color: white; font-size: 28px; pointer-events: none;"></i>
</div>

<!-- Overlay for mobile -->
<div id="mobile-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.6); z-index: 999998; opacity: 0; transition: opacity 0.3s;"></div>

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
      <a wire:navigate href="{{ url('/') }}" class="menu-link">
        <i class="bx bx-home"></i>
        <div>Dashboard</div>
      </a>
    </li>

    {{-- DAFTAR LAPTOP & PEMINJAM --}}
    <li class="menu-header small text-uppercase mt-3">
      <span class="menu-header-text">DAFTAR LAPTOP & PEMINJAM</span>
    </li>

    <li class="menu-item {{ request()->is('tables/laptop') ? 'active' : '' }}">
      <a wire:navigate href="{{ url('tables/laptop') }}" class="menu-link">
        <i class="bx bx-laptop"></i>
        <div>Daftar Laptop</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('tables/basic') ? 'active' : '' }}">
      <a wire:navigate href="{{ url('tables/basic') }}" class="menu-link">
        <i class="bx bx-user"></i>
        <div>Daftar Pengguna</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('laptop/arsip') ? 'active' : '' }}">
      <a wire:navigate href="{{ url('laptop/arsip') }}" class="menu-link">
        <i class="bx bx-archive"></i>
        <div>Laptop Diarsip</div>
      </a>
    </li>

    <li class="menu-item {{ request()->is('laptop/sold') ? 'active' : '' }}">
      <a wire:navigate href="{{ url('laptop/sold') }}" class="menu-link">
        <i class="bx bx-money"></i>
        <div>Laptop Terjual</div>
      </a>
    </li>

    {{-- LAPORAN --}}
    <li class="menu-header small text-uppercase mt-3">
      <span class="menu-header-text">Laporan</span>
    </li>

    <li class="menu-item {{ request()->is('laporan') ? 'active' : '' }}">
      <a wire:navigate href="{{ url('laporan') }}" class="menu-link">
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

  #mobile-slider-tab {
    background: #14a2ba !important;
  }

  #mobile-slider-tab:hover {
    background: #1299b0 !important;
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
    font-size: 1.05rem !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 6px rgba(0,0,0,0.15) !important;
    padding: 0.75rem 1rem !important;
  }

  #layout-menu .menu-item.active > .menu-link i {
    color: #14a2ba !important;
  }

  [data-theme="dark"] #layout-menu .menu-item.active > .menu-link,
  body.dark-mode #layout-menu .menu-item.active > .menu-link {
    background-color: rgba(255, 255, 255, 0.15) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    border-radius: 8px !important;
    margin: 0 8px !important;
    box-shadow: 0 2px 6px rgba(255, 255, 255, 0.2) !important;
    border-left: 3px solid #ffffff !important;
    padding: 0.75rem 1rem !important;
    padding-left: calc(1rem - 3px) !important;
  }

  [data-theme="dark"] #layout-menu .menu-item.active > .menu-link i,
  body.dark-mode #layout-menu .menu-item.active > .menu-link i {
    color: #ffffff !important;
  }

  #layout-menu .menu-item:not(.active) > .menu-link {
    color: #ffffff !important;
    font-weight: 600 !important;
    font-size: 1.05rem !important;
    padding: 0.75rem 1rem !important;
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
    font-size: 0.85rem !important;
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
    font-size: 26px !important;
    margin-right: 12px !important;
    min-width: 26px;
  }

  #layout-menu .menu-link div {
    line-height: 1.4;
  }

  [data-theme="dark"] #layout-menu .menu-inner,
  body.dark-mode #layout-menu .menu-inner {
    background: transparent !important;
  }

  [data-theme="dark"] .app-brand-logo,
  body.dark-mode .app-brand-logo {
    filter: brightness(0.95);
  }

  /* Slider styles */
  #mobile-slider-tab {
    transition: left 0.3s ease !important;
  }

  #mobile-slider-tab:hover {
    width: 50px !important;
    background: #1299b0 !important;
  }

  #mobile-slider-tab:active {
    transform: translateY(-50%) scale(0.95) !important;
  }

  #mobile-slider-tab i {
    transition: transform 0.3s ease !important;
  }

  [data-theme="dark"] #mobile-slider-tab,
  body.dark-mode #mobile-slider-tab {
    background: #2b2b3c !important;
  }

  [data-theme="dark"] #mobile-slider-tab:hover,
  body.dark-mode #mobile-slider-tab:hover {
    background: #3a3a4f !important;
  }

  /* Desktop */
  @media (min-width: 1200px) {
    #layout-menu {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100vh;
    }

    .layout-page {
      margin-left: 0px;
    }
  }
</style>

<script data-navigate-once="vertical-menu-scripts">
// ==========================================
// KILL TEMPLATE SCRIPTS FIRST
// ==========================================
(function() {
  'use strict';
  
  console.clear();
  console.log('🔥 KILLING TEMPLATE SCRIPTS...');
  
  window.Menu = function() {};
  window.Menu.prototype.init = function() { return false; };
  window.Menu.prototype.destroy = function() { return false; };
  window.Menu.prototype.manageScroll = function() { return false; };
  
  const oldAddEventListener = window.addEventListener;
  window.addEventListener = function(type, listener, options) {
    if (type === 'resize' && listener.toString().includes('manageScroll')) {
      console.log('❌ Blocked template resize listener');
      return;
    }
    return oldAddEventListener.call(this, type, listener, options);
  };
  
  console.log('✅ Template scripts blocked!');
})();

// ==========================================
// SIMPLE SLIDER - PURE VANILLA JS
// ==========================================
(function() {
  'use strict';
  
  console.log('🚀 Initializing Simple Slider...');
  
  let slider, sidebar, overlay, icon;
  let isOpen = false;
  
  function init() {
    slider = document.getElementById('mobile-slider-tab');
    sidebar = document.getElementById('layout-menu');
    overlay = document.getElementById('mobile-overlay');
    icon = slider ? slider.querySelector('i') : null;
    
    if (!slider || !sidebar || !overlay) {
      setTimeout(init, 50);
      return;
    }
    
    console.log('✅ Elements loaded');
    
    sidebar.removeAttribute('data-menu');
    sidebar.removeAttribute('data-scroll');
    sidebar.className = 'layout-menu menu-vertical menu bg-menu-theme';
    
    setupMobile();
    attachEvents();
    
    console.log('✅ Slider ready!');
  }
  
  function setupMobile() {
    if (window.innerWidth < 1200) {
      slider.setAttribute('style', 'display: flex !important; position: fixed !important; top: 50% !important; left: 0 !important; transform: translateY(-50%) !important; z-index: 9999999 !important; pointer-events: auto !important; width: 45px !important; height: 100px !important; border-radius: 0 20px 20px 0 !important; cursor: pointer !important; box-shadow: 3px 0 15px rgba(0,0,0,0.4) !important; align-items: center !important; justify-content: center !important;');

      sidebar.setAttribute('style', 'position: fixed !important; top: 0 !important; left: -280px !important; width: 260px !important; height: 100vh !important; z-index: 9999999 !important; transition: left 0.3s ease !important; overflow-y: auto !important; display: block !important; visibility: visible !important; transform: translateX(0) !important;');
      
      console.log('📱 Mobile setup done');
    } else {
      slider.style.display = 'none';
      sidebar.setAttribute('style', 'position: fixed !important; top: 0 !important; left: 0 !important; width: 260px !important; height: 100vh !important;');
    }
  }
  
  function open() {
    console.log('🟢 OPENING');
    
    sidebar.setAttribute('style', 'position: fixed !important; top: 0 !important; left: 0px !important; width: 260px !important; height: 100vh !important; z-index: 9999999 !important; transition: left 0.3s ease !important; overflow-y: auto !important; display: block !important; visibility: visible !important; transform: translateX(0) !important;');
    
    slider.setAttribute('style', slider.getAttribute('style').replace('left: 0', 'left: 260px'));
    
    overlay.style.display = 'block';
    setTimeout(() => overlay.style.opacity = '1', 10);
    
    if (icon) icon.style.transform = 'rotate(180deg)';
    
    document.body.style.overflow = 'hidden';
    isOpen = true;
  }
  
  function close() {
    console.log('🔴 CLOSING');
    
    sidebar.setAttribute('style', 'position: fixed !important; top: 0 !important; left: -280px !important; width: 260px !important; height: 100vh !important; z-index: 9999999 !important; transition: left 0.3s ease !important; overflow-y: auto !important; display: block !important; visibility: visible !important; transform: translateX(0) !important;');
    
    slider.setAttribute('style', slider.getAttribute('style').replace('left: 260px', 'left: 0'));
    
    overlay.style.opacity = '0';
    setTimeout(() => overlay.style.display = 'none', 300);
    
    if (icon) icon.style.transform = 'rotate(0deg)';
    
    document.body.style.overflow = '';
    isOpen = false;
  }
  
  function toggle() {
    console.log('🔄 TOGGLE clicked!');
    isOpen ? close() : open();
  }
  
  function attachEvents() {
    slider.onclick = function(e) {
      e.preventDefault();
      e.stopPropagation();
      console.log('👆 SLIDER CLICKED!');
      toggle();
    };
    
    slider.ontouchstart = function(e) {
      e.preventDefault();
      console.log('📱 SLIDER TOUCHED!');
      toggle();
    };
    
    overlay.onclick = function() {
      if (isOpen) close();
    };
    
    const chevron = sidebar.querySelector('.layout-menu-toggle');
    if (chevron) {
      chevron.onclick = function(e) {
        e.preventDefault();
        if (isOpen) close();
      };
    }
    
    sidebar.querySelectorAll('.menu-link:not(.layout-menu-toggle)').forEach(link => {
      link.onclick = function() {
        if (window.innerWidth < 1200 && isOpen) {
          setTimeout(close, 100);
        }
      };
    });
    
    window.addEventListener('resize', function() {
      setupMobile();
      if (window.innerWidth >= 1200 && isOpen) {
        close();
      }
    });
    
    console.log('✅ Events attached!');
  }
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
  
  // Reinit after Livewire navigation
  document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navigated - reinitializing slider...');
    setTimeout(init, 50);
  });
})();

// ==========================================
// UPDATE ACTIVE MENU AFTER NAVIGATION
// ==========================================
(function() {
  'use strict';
  
  function updateActiveMenu() {
    console.log('🎯 Updating active menu...');
    
    const currentPath = window.location.pathname;
    console.log('Current path:', currentPath);
    
    // Remove all active classes first
    document.querySelectorAll('#layout-menu .menu-item').forEach(item => {
      item.classList.remove('active');
    });
    
    // Add active class to matching menu item
    document.querySelectorAll('#layout-menu .menu-link').forEach(link => {
      const href = link.getAttribute('href');
      
      if (!href) return;
      
      // Extract path from href
      let linkPath;
      try {
        linkPath = new URL(href, window.location.origin).pathname;
      } catch (e) {
        linkPath = href;
      }
      
      // Exact match
      if (linkPath === currentPath) {
        link.closest('.menu-item').classList.add('active');
        console.log('✅ Active menu set for:', linkPath);
      }
      // Special case for root/dashboard
      else if (currentPath === '/' && linkPath === '/') {
        link.closest('.menu-item').classList.add('active');
        console.log('✅ Active menu set for dashboard');
      }
      // Partial match (for nested routes)
      else if (currentPath.startsWith(linkPath) && linkPath !== '/') {
        link.closest('.menu-item').classList.add('active');
        console.log('✅ Active menu set for:', linkPath);
      }
    });
  }
  
  // Initial load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateActiveMenu);
  } else {
    updateActiveMenu();
  }
  
  // After Livewire navigation
  document.addEventListener('livewire:navigated', function() {
    console.log('🔄 Livewire navigated - updating menu...');
    setTimeout(updateActiveMenu, 50);
  });
  
  console.log('✅ Active menu updater initialized!');
})();
</script>