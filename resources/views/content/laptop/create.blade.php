@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Laptop')

@section('content')
<div class="container py-4">
  <div class="p-4" style="background-color: rgba(255,255,255,0.85); border-radius: 8px; position: relative;">
    <h4 class="mb-4 fw-bold">Tambah Laptop Baru</h4>

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
        <label class="form-label fw-semibold">Merek</label>
        <input type="text" name="merek" 
               class="form-control @error('merek') is-invalid @enderror" 
               value="{{ old('merek') }}" required>
        @error('merek')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Tipe</label>
        <input type="text" name="tipe" 
               class="form-control @error('tipe') is-invalid @enderror" 
               value="{{ old('tipe') }}" required>
        @error('tipe')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Spesifikasi</label>
        <textarea name="spesifikasi" 
                  class="form-control @error('spesifikasi') is-invalid @enderror" 
                  rows="3" required>{{ old('spesifikasi') }}</textarea>
        @error('spesifikasi')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      {{-- Status Laptop --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <input type="text" class="form-control" value="Tersedia" readonly>
        <input type="hidden" name="status" value="tersedia">
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Foto Laptop</label>
        <input type="file" name="foto" 
               class="form-control @error('foto') is-invalid @enderror" 
               accept="image/*">
        <small class="text-muted">Opsional. Format: jpg, jpeg, png. Maks 2MB</small>
        @error('foto')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('laptop.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>
  </div>
</div>

{{-- dark mode style --}}
<style>
[data-theme="dark"] .container .p-4 {
  background-color: rgba(30, 30, 47, 0.9) !important;
  color: #fff !important;
}

[data-theme="dark"] .container .form-label {
  color: #fff !important;
}

[data-theme="dark"] .container .form-control,
[data-theme="dark"] .container textarea {
  background-color: rgba(50, 50, 70, 0.8) !important;
  color: #fff !important;
  border-color: #555 !important;
}

[data-theme="dark"] .container .form-control:focus,
[data-theme="dark"] .container textarea:focus {
  background-color: rgba(60, 60, 80, 0.9) !important;
  color: #fff !important;
  border-color: #777 !important;
  box-shadow: none !important;
}

/* Tombol */
[data-theme="dark"] .container .btn-primary {
  background-color: #6c63ff !important;
  border-color: #6c63ff !important;
  color: #fff !important;
}

[data-theme="dark"] .container .btn-secondary {
  background-color: #3b3b52 !important;
  border-color: #3b3b52 !important;
  color: #fff !important;
}

[data-theme="dark"] .text-muted {
  color: #aaa !important;
}
</style>
@endsection
