@extends('layouts/blankLayout')

@section('title', 'Admin Login')

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
          <h4 class="mb-1">Admin Login 🔑</h4>
          <p class="mb-6">Please sign-in to your admin account</p>

          <form id="formAuthentication" class="mb-6" action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input 
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your admin email"
                required
                autofocus>
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
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
            </div>
          </form>

          <p class="text-center">
            <span>Don't have an admin account?</span>
            <a href="{{ route('admin.register') }}">
              <span>Register as Admin</span>
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
