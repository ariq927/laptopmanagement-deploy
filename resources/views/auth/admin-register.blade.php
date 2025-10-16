@extends('layouts/blankLayout')

@section('title', 'Admin Register')

@section('page-style')
@vite(['resources/assets/vendor/scss/pages/page-auth.scss'])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
            </a>
          </div>
          <h4 class="mb-1">Create Admin Account 🔑</h4>
          <p class="mb-6">Register a new admin for the system</p>

          <form id="formAuthentication" class="mb-6" action="{{ route('admin.register.post') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="name" class="form-label">Full Name</label>
              <input 
                type="text"
                class="form-control"
                id="name"
                name="name"
                placeholder="Enter full name"
                value="{{ old('name') }}"
                required
                autofocus>
            </div>

            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input 
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your admin email"
                value="{{ old('email') }}"
                required>
            </div>

            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input 
                  type="password"
                  id="password"
                  class="form-control"
                  name="password"
                  required
                  placeholder="••••••••••••">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password_confirmation">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input 
                  type="password"
                  id="password_confirmation"
                  class="form-control"
                  name="password_confirmation"
                  required
                  placeholder="••••••••••••">
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>

            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Register</button>
            </div>
          </form>

          <p class="text-center">
            <span>Already have an admin account?</span>
            <a href="{{ route('admin.login') }}">
              <span>Login here</span>
            </a>
          </p>

          <p class="text-center mt-2">
            <a href="{{ route('login') }}">Back to User Login</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
