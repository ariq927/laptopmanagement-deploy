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

{{-- Prevent White Flash & Preloader --}}
<script>
  (function() {
    const isDark =
      localStorage.getItem('theme') === 'dark' ||
      (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);

    document.documentElement.style.backgroundColor = isDark ? '#0e141b' : '#fff';
    document.body.style.backgroundColor = isDark ? '#0e141b' : '#fff';
    document.documentElement.classList.toggle('dark-mode', isDark);

    const preloader = document.getElementById('preloader');
    if(preloader){
      preloader.classList.add(isDark ? 'dark-theme' : 'light-theme');
    }
  })();
</script>

{{-- Preloader overlay --}}
<div id="preloader">
  <div class="loader">
    <div class="spinner"></div>
    <p class="text">Memuat data...</p>
  </div>
</div>

<div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
  <div class="layout-container">

    {{-- Sidebar Menu --}}
    @if ($isMenu)
      @include('layouts/sections/menu/verticalMenu')
    @endif

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
</div>

<style>
  /* Preloader */
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
    background-color: rgba(255,255,255,0.35);
  }
  #preloader.dark-theme { background-color: rgba(0,0,0,0.35); }

  #preloader .spinner {
    width: 50px;
    height: 50px;
    border: 4px solid rgba(255, 255, 255, 0.15);
    border-top-color: #14a2ba;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }
  #preloader.light-theme .spinner { border-top-color: #6c63ff; }
  #preloader.dark-theme .spinner { border-top-color: #14a2ba; }

  @keyframes spin { to { transform: rotate(360deg); } }

  #preloader .text {
    font-family: 'Poppins', sans-serif;
    font-size: 1.1rem;
    margin-top: 15px;
    letter-spacing: 0.5px;
    opacity: 0.8;
    animation: fadeBlink 1.6s ease-in-out infinite;
  }
  #preloader.light-theme .text { color: #000; }
  #preloader.dark-theme .text { color: #fff; }

  @keyframes fadeBlink {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
  }

  /* Hide after loaded */
  body.loaded #preloader {
    opacity: 0;
    visibility: hidden;
  }
</style>

<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.body.classList.add('loaded');
    }, 0);
  });
</script>

{{-- CSS JS --}}
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}">

<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/js/main.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@endsection
