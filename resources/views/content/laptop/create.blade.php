@extends('layouts/contentNavbarLayout')

@section('title', 'Tambah Laptop')

@section('content')
<div class="container" style="padding: 20px;">
    <div class="p-4" style="background-color: rgba(255,255,255,0.85); border-radius: 8px;">
        <h4 class="mb-4 fw-bold">Tambah Laptop Baru</h4>

        {{-- Tampilkan semua error --}}
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

            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Seri</label>
                <input type="text" name="serial_number" 
                       class="form-control @error('serial_number') is-invalid @enderror" 
                       value="{{ old('serial_number') }}" required>
                @error('serial_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <input type="text" class="form-control" value="Tersedia" readonly>
                <input type="hidden" name="status" value="tersedia">
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Laptop</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
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
@endsection
