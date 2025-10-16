<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Login')</title>
    @vite(['resources/assets/vendor/scss/core.scss', 'resources/assets/vendor/scss/theme-default.scss', 'resources/assets/css/demo.css', 'resources/assets/vendor/js/helpers.js'])
    @yield('page-style')
  </head>
  <body>
    @yield('content')

    @vite(['resources/assets/vendor/js/bootstrap.js'])
    @yield('page-script')

    {{-- ===== Production CSS ===== --}}
  <link rel="stylesheet" href="{{ asset('build/assets/core-BnqA3ef1.css') }}">
  <link rel="stylesheet" href="{{ asset('build/assets/theme-default-D5KFm6jZ.css') }}">
  <link rel="stylesheet" href="{{ asset('build/assets/_theme-Ruhj2bpQ.css') }}">
  <link rel="stylesheet" href="{{ asset('build/assets/custom-override-B_FnKQlU.css') }}">
  <link rel="stylesheet" href="{{ asset('build/assets/demo-ISkCbL8g.css') }}">

  {{-- ===== Production JS ===== --}}
  <script src="{{ asset('build/assets/_commonjsHelpers-Cpj98o6Y.js') }}"></script>
  <script src="{{ asset('build/assets/popper-CgINJS0r.js') }}"></script>
  <script src="{{ asset('build/assets/bootstrap-BqFZZLXP.js') }}"></script>
  <script src="{{ asset('build/assets/perfect-scrollbar-DPYX2UL_.js') }}"></script>
  <script src="{{ asset('build/assets/menu-Bldkajpn.js') }}"></script>
  <script src="{{ asset('build/assets/main-CWila6Zz.js') }}"></script>
  </body>
</html>
