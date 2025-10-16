@extends('layouts/blankLayout')

@section('title', 'Login - Laptop Management PLN IPS')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <h4 class="mb-1">Selamat Datang 👋</h4>
          <p class="mb-6">Silakan login menggunakan akun LDAP Anda.</p>

          @if ($errors->any())
            <div class="alert alert-danger mt-3">
              {{ $errors->first() }}
            </div>
          @endif

          <form action="{{ route('auth-login-basic-post') }}" method="POST">
            @csrf
            <div class="mb-6">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required autofocus>
            </div>

            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" required>
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
