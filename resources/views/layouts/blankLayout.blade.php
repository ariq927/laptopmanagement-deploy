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
  </body>
</html>
