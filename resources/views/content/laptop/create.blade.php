@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Laptop')

@section('content')
<div class="card">
  <h5 class="card-header">Tambah Laptop Baru</h5>
  <div class="card-body">
    
    {{-- TAMBAHKAN INI: Tampilkan semua error --}}
    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('laptop.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Merek</label>
        <input type="text" name="merek" class="form-control @error('merek') is-invalid @enderror" 
               value="{{ old('merek') }}" required>
        @error('merek')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Tipe</label>
        <input type="text" name="tipe" class="form-control @error('tipe') is-invalid @enderror" 
               value="{{ old('tipe') }}" required>
        @error('tipe')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Spesifikasi</label>
        <textarea name="spesifikasi" class="form-control @error('spesifikasi') is-invalid @enderror" 
                  rows="3" required>{{ old('spesifikasi') }}</textarea>
        @error('spesifikasi')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Nomor Seri</label>
        <input type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" 
               value="{{ old('serial_number') }}" required>
        @error('serial_number')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Status</label>
        <input type="text" class="form-control" value="Tersedia" readonly>
        <input type="hidden" name="status" value="tersedia">
      </div>

      <div class="mb-3">
        <label class="form-label">Foto Laptop</label>
        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
        <small class="text-muted">Opsional. Format: jpg, jpeg, png. Maks 2MB</small>
        @error('foto')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('laptop.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection