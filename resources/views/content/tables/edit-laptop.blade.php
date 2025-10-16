@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Laptop')

@section('content')
<div class="container">
    <h4 class="mb-4">Data Laptop</h4>

    @php
        $isReadOnly = ($laptop->status === 'dipinjam');
    @endphp

    <form 
        action="{{ $isReadOnly ? '#' : route('laptop.update', $laptop->id) }}" 
        method="POST" 
        enctype="multipart/form-data"
    >
        @csrf
        @if(!$isReadOnly)
            @method('PUT')
        @endif

        {{-- ID Laptop --}}
        <div class="mb-3">
            <label class="form-label">ID Laptop</label>
            <input type="text" 
                   class="form-control" 
                   value="{{ $laptop->id }}" 
                   readonly>
        </div>

        <div class="mb-3">
            <label class="form-label">Merek</label>
            <input type="text" 
                   class="form-control" 
                   name="merek" 
                   value="{{ $laptop->merek }}" 
                   {{ $isReadOnly ? 'readonly' : 'required' }}>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipe</label>
            <input type="text" 
                   class="form-control" 
                   name="tipe" 
                   value="{{ $laptop->tipe }}" 
                   {{ $isReadOnly ? 'readonly' : 'required' }}>
        </div>

        <div class="mb-3">
            <label class="form-label">Spesifikasi</label>
            <textarea class="form-control" 
                      name="spesifikasi" 
                      {{ $isReadOnly ? 'readonly' : '' }}>{{ $laptop->spesifikasi }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="text" 
                   class="form-control" 
                   value="{{ ucfirst($laptop->status) }}" 
                   readonly>
        </div>

        {{-- Foto Laptop --}}
        <div class="mb-3">
            <label class="form-label">Foto Laptop</label><br>
            <div class="card shadow-sm p-2 border border-2" style="width: 400px; height: 400px;">
                @if($laptop->foto)
                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                        <img src="{{ $laptop->foto }}" 
                             class="img-fluid" 
                             style="max-height: 100%; max-width: 100%; object-fit: contain;">
                    </div>

                    @unless($isReadOnly)
                        <div class="card-body p-3">
                            <input type="checkbox" name="hapus_foto" value="1"> Hapus foto
                        </div>
                    @endunless
                @else
                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                        <img src="{{ asset('images/nophoto.png') }}" 
                             style="height: 50px; width: 50px; object-fit: contain; opacity: 0.3;">
                    </div>
                    <div class="card-body text-center text-muted p-2">
                        <small>Tidak ada foto</small>
                    </div>
                @endif
            </div>
        </div>

        @unless($isReadOnly)
            <input type="file" class="form-control mt-3" name="foto">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
        @endunless

        <div class="mt-4">
            @if(!$isReadOnly)
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            @else
                <div class="alert alert-warning mt-3">
                    Laptop sedang <strong>dipinjam</strong>, data tidak dapat diedit.
                </div>
            @endif
            <a href="{{ route('laptop.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
