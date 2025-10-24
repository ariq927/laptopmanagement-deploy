<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Login')</title>
    
    {{-- ===== CSS ===== --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/core.scss') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/theme-default.scss') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/_theme/_theme.scss') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/scss/custom-override.scss') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/css/demo.css') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.scss') }}">
    <link rel="stylesheet" href="{{ manifest_asset('resources/assets/vendor/fonts/boxicons.scss') }}">
    
    @yield('page-style')
  </head>
  <body>
    @yield('content')

    {{-- ===== JS - Tambahkan type="module" ===== --}}
    <script type="module" src="{{ manifest_asset('resources/assets/vendor/js/helpers.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/vendor/libs/popper/popper.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/vendor/js/bootstrap.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/vendor/js/menu.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/js/main.js') }}"></script>
    <script type="module" src="{{ manifest_asset('resources/assets/js/config.js') }}"></script>
    
    @yield('page-script')
  </body>
</html>