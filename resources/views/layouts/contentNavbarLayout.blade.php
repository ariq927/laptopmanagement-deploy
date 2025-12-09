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

{{-- Preloader --}}
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

<div id="preloader">
  <div class="loader">
    <div class="spinner"></div>
    <p class="text">Memuat data...</p>
  </div>
</div>

<div class="layout-wrapper layout-content-navbar {{ $isMenu ? '' : 'layout-without-menu' }}">
  <div class="layout-container">

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
  /* Preloader styles */
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

  body.loaded #preloader {
    opacity: 0;
    visibility: hidden;
  }
</style>

<script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      document.body.classList.add('loaded');
      sessionStorage.setItem('app-loaded', 'true');
    }, 100);
  });

  if (sessionStorage.getItem('app-loaded') === 'true') {
    document.body.classList.add('loaded');
  }

  document.addEventListener('livewire:navigated', () => {
    document.body.classList.add('loaded');
  });
</script>

{{-- ❌ HAPUS SEMUA BARIS INI (sudah ada di commonMaster) --}}
{{-- 
<link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
...semua CSS dan JS...
--}}

{{-- ✅ HANYA PUSH CSS/JS yang SPESIFIK untuk halaman ini --}}
@push('styles')
  {{-- Custom styles for this layout only --}}
@endpush

@push('scripts')
  {{-- ✅ Reinitialize plugins setelah Livewire navigation --}}
  <script>
    document.addEventListener('livewire:navigated', () => {
      
      // Select2 (if available via CDN in specific pages)
      if (typeof $.fn.select2 !== 'undefined') {
        $('.select2').each(function() {
          if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
              theme: 'bootstrap-5',
              width: '100%'
            });
          }
        });
      }
      
      // DataTables (if available via CDN in specific pages)
      if (typeof $.fn.DataTable !== 'undefined' && $('.datatable').length) {
        $('.datatable').each(function() {
          if ($.fn.DataTable.isDataTable(this)) {
            $(this).DataTable().destroy();
          }
          
          $(this).DataTable({
            responsive: true,
            pageLength: 25,
            language: {
              url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            }
          });
        });
      }
      
      // ApexCharts (if available)
      if (typeof ApexCharts !== 'undefined') {
        document.querySelectorAll('[data-chart]').forEach(el => {
          if (!el.hasAttribute('data-chart-initialized')) {
            const chartData = JSON.parse(el.getAttribute('data-chart'));
            new ApexCharts(el, chartData).render();
            el.setAttribute('data-chart-initialized', 'true');
          }
        });
      }
      
    });
  </script>
@endpush

@endsection