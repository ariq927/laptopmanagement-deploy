<!DOCTYPE html>
<html
  class="light-style layout-menu-fixed"
  data-theme="theme-default"
  data-assets-path="{{ asset('/assets') }}/"
  data-base-url="{{ url('/') }}"
  style="background:#121212; color-scheme: dark;">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
  <title>@yield('title')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/plnfavicon.ico') }}" />

  {{-- Early Theme Init --}}
  <script>
    (function() {
      const html = document.documentElement;
      const savedTheme = localStorage.getItem('theme') || 'light';
      document.documentElement.style.visibility = 'hidden';
      document.body && (document.body.style.display = 'none');
      if (savedTheme === 'dark') {
        html.classList.remove('light-style');
        html.classList.add('dark-style');
        html.setAttribute('data-theme', 'theme-dark');
        html.style.background = '#121212';
        html.style.colorScheme = 'dark';
      } else {
        html.classList.remove('dark-style');
        html.classList.add('light-style');
        html.setAttribute('data-theme', 'theme-default');
        html.style.background = '#ffffff';
        html.style.colorScheme = 'light';
      }
      window.addEventListener('DOMContentLoaded', () => {
        document.body.style.display = '';
        html.style.visibility = 'visible';
      });
    })();
  </script>

  {{-- Livewire Styles --}}
  @livewireStyles

  {{-- Livewire Loading Bar Style --}}
  <style>
    .livewire-loading-bar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: linear-gradient(90deg, #14a2ba, #0b8699);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform 0.3s ease;
      z-index: 99999;
      box-shadow: 0 2px 8px rgba(20, 162, 186, 0.4);
    }
    
    .livewire-loading-bar.loading {
      animation: loadingBar 1.5s ease-in-out infinite;
    }
    
    @keyframes loadingBar {
      0% { transform: scaleX(0); }
      50% { transform: scaleX(0.7); }
      100% { transform: scaleX(1); }
    }
    
    #main-content.loading-content {
      opacity: 0.7;
      pointer-events: none;
      transition: opacity 0.2s ease;
    }

    #main-content {
      transition: opacity 0.2s ease;
    }
  </style>

  {{-- Production CSS --}}
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/core.scss') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/theme-default.scss') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/_theme/_theme.scss') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/custom-override.scss') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/css/demo.css') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss') }}">
  <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/fonts/boxicons.scss') }}">

  {{-- CDN Libraries --}}
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

  @stack('styles')
</head>

<body class="layout-menu-fixed {{ (session('theme') ?? 'light') == 'dark' ? 'dark-layout' : '' }}" data-active-menu="{{ request()->path() }}">
  
  {{-- Livewire Loading Bar --}}
  <div class="livewire-loading-bar" wire:loading.class="loading"></div>

  <div class="d-flex">
    {{-- Sidebar --}}
    @include('layouts/sections/menu/verticalMenu')

    {{-- Main Content --}}
    <main id="main-content" class="flex-grow-1" wire:loading.class="loading-content">
      @yield('layoutContent')
    </main>
  </div>

  {{-- ✅ PENTING: Wrap scripts dengan data-navigate-once untuk prevent reload --}}
  <div data-navigate-once="scripts">
    
    {{-- jQuery FIRST --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    {{-- ✅ Production JS (Vite/Mix) - Load ONCE --}}
    <script src="{{ manifest_asset('resources/assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/js/main.js') }}"></script>
    <script src="{{ manifest_asset('resources/assets/js/config.js') }}"></script>

    {{-- CDN Libraries --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

  </div>

  {{-- Livewire Scripts --}}
  @livewireScripts

  {{-- ✅ OPTIMIZED: Livewire Navigation Handler (NO DUPLICATE LOGS) --}}
  <script data-navigate-once="livewire-handler">
    let navigationCount = 0;
    
    document.addEventListener('livewire:init', () => {
      console.log('✅ Livewire initialized (once)');
    });

    document.addEventListener('livewire:navigating', () => {
      navigationCount++;
      console.log(`🔄 Navigating... (${navigationCount})`);
    });

    document.addEventListener('livewire:navigated', () => {
      console.log(`✅ Navigation complete! (${navigationCount})`);
      
      // Scroll to top
      window.scrollTo({ top: 0, behavior: 'smooth' });
      
      // ✅ OPTIMIZE: Only reinitialize what's needed
      requestAnimationFrame(() => {
        initializePlugins();
      });
    });

    // ✅ Centralized plugin initialization
    function initializePlugins() {
      console.log('🔧 Initializing plugins...');
      
      // Perfect Scrollbar
      if (typeof PerfectScrollbar !== 'undefined') {
        document.querySelectorAll('.ps:not(.ps-initialized)').forEach(container => {
          new PerfectScrollbar(container);
          container.classList.add('ps-initialized');
        });
      }
      
      // Bootstrap Tooltips
      if (typeof bootstrap !== 'undefined') {
        // Dispose old tooltips first
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
          const tooltip = bootstrap.Tooltip.getInstance(el);
          if (tooltip) tooltip.dispose();
        });
        
        // Initialize new tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
          new bootstrap.Tooltip(el);
        });
      }
      
      // Bootstrap Popovers
      if (typeof bootstrap !== 'undefined') {
        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
          const popover = bootstrap.Popover.getInstance(el);
          if (popover) popover.dispose();
        });
        
        document.querySelectorAll('[data-bs-toggle="popover"]').forEach(el => {
          new bootstrap.Popover(el);
        });
      }
      
      // Menu active state
      const currentPath = window.location.pathname;
      document.querySelectorAll('.menu-item').forEach(item => {
        item.classList.remove('active');
      });
      
      document.querySelectorAll('.menu-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/') {
          link.closest('.menu-item')?.classList.add('active');
        }
      });
    }

    // Initialize on first load
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializePlugins);
    } else {
      initializePlugins();
    }
  </script>

  {{-- Default scripts --}}
  @include('layouts/sections/scripts')
  
  {{-- ✅ Page-specific scripts (akan di-reinit setiap navigasi) --}}
  @stack('scripts')
</body>
</html>