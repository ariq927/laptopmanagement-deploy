@extends('layouts/blankLayout')

@section('title', 'Register Basic - Pages')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register Card -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center mb-6">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-heading fw-bold">{{config('variables.templateName')}}</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Adventure starts here 🚀</h4>
          <p class="mb-6">Kelola peminjaman dan pengembalian laptop dengan mudah!</p>

          <form id="formAuthentication" class="mb-6" action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="name" class="form-label">Name</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required autofocus>
            </div>
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" required>
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-6">
              <label class="form-label" for="password_confirmation">Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-primary d-grid w-100">
              Sign up
            </button>

            {{-- Error messages --}}
            @if ($errors->any())
              <div class="alert alert-danger mt-3">
                  <ul class="mb-0">
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{url('auth/login-basic')}}">
              <span>Sign in instead</span>
            </a>
          </p>

          {{-- Tambahan untuk admin --}}
          <p class="text-center mt-2">
            <span>Want to register an admin account?</span>
            <a href="{{ route('admin.register') }}">
              <span>Register as Admin</span>
            </a>
          </p>

        </div>
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>
@endsection
