@extends('layouts/contentNavbarLayout')

@section('title', ' Horizontal Layouts - Forms')

@section('content')

<form action="{{ route('peminjaman.store') }}" method="POST">
  @csrf

<!-- Basic Layout & Basic with Icons -->
<div class="row">
  <!-- Basic Layout -->
  <div class="col-xxl">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Form Peminjaman Laptop</h5> 
      </div>

      <div class="card-body">
        <form>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-name">Nama</label>
            <div class="col-sm-10">
              <input type="text" name="name" class="form-control" id="basic-default-name" placeholder="John Doe" />
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-company">Departemen</label>
            <div class="col-sm-10">
              <select name="department" id="basic-default-company" class="form-control">
                <option value="">-- Pilih Departemen --</option>
                <option value="Keuangan">Keuangan</option>
                <option value="Administrasi">Administrasi</option>
                <option value="IT">IT</option>
                <option value="SDM">SDM</option>
                <option value="Lainnya">Lainnya</option>
              </select>
            </div>
          </div>


          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-phone">No Telepon</label>
            <div class="col-sm-10">
              <input type="number" 
                    id="basic-default-phone" 
                    name="phone"
                    class="form-control" 
                    placeholder="62xxxx"
                    aria-label="No Telepon"
                    aria-describedby="basic-default-phone" />
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-sm-2 col-form-label">Durasi Peminjaman</label>
            <div class="col-sm-10">
              <div class="input-group">
                <!-- From date -->
                <input type="date" id="start-date" name="start_date" class="form-control" />

                <span class="input-group-text">-</span>

                <!-- To date -->
                <input type="date" id="end-date" name="end_date" class="form-control" />
              </div>
            </div>
          </div>

          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-message">Catatan</label>
            <div class="col-sm-10">
              <textarea name="note" placeholder=" Opsional " id="basic-default-message" class="form-control"></textarea>
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Send</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</form>
  
@endsection
