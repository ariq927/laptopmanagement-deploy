@extends('layouts/commonMaster')

@php
  $contentNavbar = $contentNavbar ?? true;
  $containerNav = $containerNav ?? 'container-xxl';
  $isNavbar = $isNavbar ?? true;
  $isMenu = $isMenu ?? true;
  $isFlex = $isFlex ?? false;
  $isFooter = $isFooter ?? true;
  $container = $container ?? 'container-xxl';
@endphp

@section('layoutContent')
  {{-- Prevent White Flash --}}
  <script>
    (function() {
      const isDark =
        localStorage.getItem('theme') === 'dark' ||
        (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

      document.documentElement.style.backgroundColor = isDark ? '#0e141b' : '#fff';
      document.body.style.backgroundColor = isDark ? '#0e141b' : '#fff';
      document.documentElement.classList.toggle('dark-mode', isDark);

      // Set the preloader theme based on current theme
      const preloader = document.getElementById('preloader');
      if (isDark) {
        preloader.classList.add('dark-theme');
      } else {
        preloader.classList.add('light-theme');
      }
    })();
  </script>

  <div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
    <div class="layout-container">

      {{-- Sidebar Menu --}}
      {{-- @if ($isMenu)
        @include('layouts/sections/menu/verticalMenu')
      @endif --}}

      <div class="layout-page">
        @if ($isNavbar)
          @include('layouts/sections/navbar/navbar')
        @endif

        <div class="content-wrapper">
          <div class="{{ $container }} flex-grow-1 container-p-y">
            @yield('content')
          </div>

          @if ($isFooter)
            @include('layouts/sections/footer/footer')
          @endif
        </div>
      </div>
    </div>

    @if ($isMenu)
      <div class="layout-overlay layout-menu-toggle"></div>
    @endif
    <div class="drag-target"></div>

    <div id="preloader">
      <div class="loader">
        <div class="spinner"></div>
        <p class="text">Memuat data...</p>
      </div>
    </div>
  </div>

  <style>
    #preloader {
      position: fixed;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      z-index: 9999;
      opacity: 1;
      visibility: visible;
      transition: opacity 0.4s ease, visibility 0.4s ease;
    }

    /* spinner */
    #preloader .spinner {
      width: 50px;
      height: 50px;
      border: 4px solid rgba(255, 255, 255, 0.15);
      border-top-color: #14a2ba;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    /* teks */
    #preloader .text {
      font-family: 'Poppins', sans-serif;
      font-size: 1.1rem;
      margin-top: 15px;
      letter-spacing: 0.5px;
      opacity: 0.8;
      animation: fadeBlink 1.6s ease-in-out infinite;
    }

    @keyframes fadeBlink {
      0%, 100% { opacity: 0.6; }
      50% { opacity: 1; }
    }

    /* Light mode */
    #preloader.light-theme .spinner {
      border-top-color: #6c63ff; /* Light theme color */
    }
    #preloader.light-theme .text {
      color: #000; /* Light mode text */
    }

    /* Dark mode */
    #preloader.dark-theme .spinner {
      border-top-color: #14a2ba; /* Dark theme color */
    }
    #preloader.dark-theme .text {
      color: #fff; /* Dark mode text */
    }

    /* ilangin setelah loaded */
    body.loaded #preloader {
      opacity: 0;
      visibility: hidden;
    }
  </style>

  {{-- Preloader Script --}}
  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.body.classList.add('loaded');
      }, 0);
    });
  </script>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush
