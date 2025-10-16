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

  {{-- ===== Early Theme Init ===== --}}
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

  {{-- ===== Sneat CSS ===== --}}
  @vite([
    'resources/assets/vendor/scss/core.scss',
    'resources/assets/vendor/scss/theme-default.scss',
    'resources/assets/vendor/scss/_theme/_theme.scss',
    'resources/assets/vendor/scss/custom-override.scss',
    'resources/assets/css/demo.css',
  ])

  @stack('styles')
</head>

<body class="layout-menu-fixed {{ (session('theme') ?? 'light') == 'dark' ? 'dark-layout' : '' }}" data-active-menu="{{ request()->path() }}">
  <div class="d-flex">

    {{-- Sidebar Blade --}}
    @include('layouts/sections/menu/verticalMenu')

    {{-- Konten utama --}}
    <main id="main-content" class="flex-grow-1">
      @yield('layoutContent')
    </main>
  </div>

  {{-- Default scripts --}}
  @include('layouts/sections/scripts')
  @stack('scripts')
</body>
</html>
