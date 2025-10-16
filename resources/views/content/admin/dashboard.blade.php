@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row">
  <div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Total Laptop</h5>
        <p class="card-text display-6">{{ $totalLaptop ?? 0 }}</p>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">Dipinjam</h5>
        <p class="card-text display-6">{{ $laptopDipinjam ?? 0 }}</p>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-4 mb-4">
    <div class="card h-100 shadow-sm">
      <div class="card-body">
        <h5 class="card-title">User Terdaftar</h5>
        <p class="card-text display-6">{{ $totalUser ?? 0 }}</p>
      </div>
    </div>
  </div>
</div>

{{-- Button Tambah Laptop --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addLaptopModal">
        <i class="bx bx-plus"></i> Tambah Laptop
    </button>

    {{-- Modal Form Tambah Laptop --}}
    <div class="modal fade" id="addLaptopModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.laptop.store') }}" class="modal-content">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Tambah Laptop</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Merek</label>
              <input type="text" name="merek" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tipe</label>
              <input type="text" name="tipe" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Spesifikasi</label>
              <textarea name="spesifikasi" class="form-control"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Serial Number</label>
              <input type="text" name="serial_number" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Stok</label>
              <input type="number" name="stok" class="form-control" min="1" value="1">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Tambahkan</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Flash message --}}
    @if(session('success'))
      <div class="alert alert-success mt-3">
        {{ session('success') }}
      </div>
    @endif


@endsection
