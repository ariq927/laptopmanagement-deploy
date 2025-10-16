@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings')

@section('page-script')
@vite(['resources/assets/js/pages-account-settings-account.js'])
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <!-- <div class="nav-align-top">
      <ul class="nav nav-pills flex-column flex-md-row mb-6">
        <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i class="bx bx-sm bx-user me-1_5"></i> Account</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-notifications')}}"><i class="bx bx-sm bx-bell me-1_5"></i> Notifications</a></li>
        <li class="nav-item"><a class="nav-link" href="{{url('pages/account-settings-connections')}}"><i class="bx bx-sm bx-link-alt me-1_5"></i> Connections</a></li>
      </ul> 
    </div> -->
    <div class="card mb-6">
      <!-- Account -->
      <div class="card-body">
        <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
          <img src="{{asset('assets/img/avatars/1.png')}}" alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />
          <div class="button-wrapper">
            <label for="upload" class="btn btn-primary me-3 mb-4" tabindex="0">
              <span class="d-none d-sm-block">Upload new photo</span>
              <i class="bx bx-upload d-block d-sm-none"></i>
              <input type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
            </label>
            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
              <i class="bx bx-reset d-block d-sm-none"></i>
              <span class="d-none d-sm-block">Reset</span>
            </button>

            <div>Allowed JPG, GIF or PNG. Max size of 800K</div>
          </div>
        </div>
      </div>
     <div class="card-body pt-4">
  <form id="formAccountSettings" method="POST" action="{{ route('account.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-6">
      <div class="col-md-6">
        <label for="nama" class="form-label">Nama</label>
        <input class="form-control" type="text" id="nama" name="nama" value="{{ old('nama', $UsersDetail->nama ?? '') }}" autofocus />
      </div>
      <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input class="form-control" type="text" name="email" id="email" value="" />
      </div>
      <div class="col-md-6">
        <label for="phone" class="form-label">No Telepon</label>
        <input class="form-control" type="text" id="phone" name="phone" value=""/>
      </div>
      <div class="col-md-6">
        <label for="department" class="form-label">Departemen</label>
        <input type="text" class="form-control" id="department" name="department"/>
      </div>
      <div class="col-md-6">
        <label class="form-label" for="address">Alamat</label>
        <input type="text" id="address" name="address" class="form-control" />
      </div>
    </div>

    <div class="mt-6">
      <button type="submit" class="btn btn-primary me-3">Save changes</button>
      <button type="reset" class="btn btn-outline-secondary">Cancel</button>
    </div>
  </form>
</div>

      <!-- /Account -->
    </div>
    <div class="card">
      <h5 class="card-header">Delete Account</h5>
      <div class="card-body">
        <div class="mb-6 col-12 mb-0">
          <div class="alert alert-warning">
            <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
            <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
          </div>
        </div>
          <div class="form-check my-8 ms-2">
            <input class="form-check-input" type="checkbox" name="accountActivation" id="accountActivation" />
            <label class="form-check-label" for="accountActivation">I confirm my account deactivation</label>
          </div>
          <button type="submit" class="btn btn-danger deactivate-account" disabled>Deactivate Account</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
