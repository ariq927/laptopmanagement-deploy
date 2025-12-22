@extends('layouts.blankLayout')

@section('title', 'Login - Laptop Management PLN IPS')

@section('page-style')
<style>
.toggle-password-span {
  cursor: pointer !important;
  user-select: none !important;
  pointer-events: auto !important;
  display: inline-flex !important;
  align-items: center !important;
}
</style>
@endsection

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
                <span class="input-group-text toggle-password-span" id="togglePasswordBtn">
                  <i class="bx bx-hide" id="toggleIcon"></i>
                </span>
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

@section('page-script')
<script>
(function() {
  'use strict';
  
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initToggle);
  } else {
    initToggle();
  }
  
  function initToggle() {
    const toggleBtn = document.getElementById('togglePasswordBtn');
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('toggleIcon');
    
    if (!toggleBtn || !passwordInput || !icon) {
      console.error('Element tidak ditemukan');
      return;
    }
    
    const newToggleBtn = toggleBtn.cloneNode(true);
    toggleBtn.parentNode.replaceChild(newToggleBtn, toggleBtn);
    
    newToggleBtn.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      const newIcon = document.getElementById('toggleIcon');
      const newPasswordInput = document.getElementById('password');
      
      if (newPasswordInput.type === 'password') {
        newPasswordInput.type = 'text';
        newIcon.classList.remove('bx-hide');
        newIcon.classList.add('bx-show');
      } else {
        newPasswordInput.type = 'password';
        newIcon.classList.remove('bx-show');
        newIcon.classList.add('bx-hide');
      }
    });
  }
})();
</script>
@endsection