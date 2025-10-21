@extends('layouts.blankLayout')

@section('title', 'Login - Laptop Management PLN IPS')

@section('content')
<div class="container">
  <div class="authentication-wrapper authentication-basic d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="authentication-inner" style="max-width:420px; width:100%; margin:auto;">
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

<!-- Toggle Password Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const togglePassword = document.querySelector('.form-password-toggle .input-group-text');
  const passwordInput = document.querySelector('#password');
  const icon = togglePassword.querySelector('i');

  togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    
    if(type === 'password'){
      icon.classList.remove('bx-show');
      icon.classList.add('bx-hide');
    } else {
      icon.classList.remove('bx-hide');
      icon.classList.add('bx-show');
    }
  });
});
</script>
@endsection
