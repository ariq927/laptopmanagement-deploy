@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Laptop')

@section('content')
<div class="container py-4">
    <div class="p-4" style="background-color: rgba(255,255,255,0.85); border-radius: 8px;">
        <h4 class="mb-4">Edit Laptop</h4>

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
                <label class="form-label fw-semibold">ID Laptop</label>
                <input type="text" class="form-control" value="{{ $laptop->id }}" readonly>
            </div>

            {{-- Merek --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Merek</label>
                <input type="text" class="form-control" name="merek" value="{{ $laptop->merek }}" {{ $isReadOnly ? 'readonly' : 'required' }}>
            </div>

            {{-- Tipe --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Tipe</label>
                <input type="text" class="form-control" name="tipe" value="{{ $laptop->tipe }}" {{ $isReadOnly ? 'readonly' : 'required' }}>
            </div>

            {{-- Spesifikasi --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Spesifikasi</label>
                <textarea class="form-control" name="spesifikasi" {{ $isReadOnly ? 'readonly' : '' }}>{{ $laptop->spesifikasi }}</textarea>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <input type="text" class="form-control" value="{{ ucfirst($laptop->status) }}" readonly>
            </div>

            {{-- Foto Laptop --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Laptop</label><br>

                {{-- Card Gambar --}}
                <div class="card shadow-sm p-2 border border-2 mb-2 position-relative" 
                     style="width: 400px; height: 400px; background-color: rgba(255,255,255,0.5); 
                            {{ $laptop->foto ? 'cursor: pointer;' : 'cursor: default;' }}"
                     @if($laptop->foto) onclick="showImageModal('{{ $laptop->foto }}')" @endif>
                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                        @if($laptop->foto)
                            <img src="{{ $laptop->foto }}" 
                                 class="img-fluid" 
                                 style="max-height: 100%; max-width: 100%; object-fit: contain;">
                        @else
                            <img src="{{ asset('images/nophoto.png') }}" 
                                 style="width: 100px; height: 100px; object-fit: contain;">
                            
                            {{-- Tombol Tambah Foto di kiri bawah --}}
                            @unless($isReadOnly)
                                <label class="btn btn-primary position-absolute mb-2 ms-2" 
                                       style="bottom: 0; left: 0; cursor: pointer;">
                                    Tambah Foto
                                    <input type="file" id="fotoInput" name="foto" class="d-none">
                                </label>
                            @endunless
                        @endif
                    </div>
                </div>

                {{-- Pesan kecil kalau foto akan dihapus --}}
                <small id="hapusFotoNotif" class="text-danger d-none ms-1">Foto akan dihapus.</small>

                {{-- Tombol Edit/Hapus Foto jika ada foto --}}
                @unless($isReadOnly || !$laptop->foto)
                    <div class="d-flex gap-2 mb-2">
                        <label class="btn btn-primary mb-0" style="cursor: pointer;">
                            Edit Foto
                            <input type="file" id="fotoInput" name="foto" class="d-none">
                        </label>
                        <button type="button" class="btn btn-danger mb-0" id="hapusFotoBtn">Hapus Foto</button>
                        <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
                    </div>
                @endunless

                <small class="text-muted d-block">Klik gambar untuk melihat fullscreen.</small>
            </div>

            {{-- Tombol Submit / Kembali --}}
            <div class="mt-4 d-flex gap-2">
                @if(!$isReadOnly)
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                @else
                    <div class="alert alert-warning">
                        Laptop sedang <strong>dipinjam</strong>, data tidak dapat diedit.
                    </div>
                @endif
                <a href="{{ route('laptop.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

{{-- Modal Fullscreen Gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen">
    <div class="modal-content bg-transparent border-0">
      <div class="modal-body d-flex justify-content-center align-items-center p-0">
        <img id="modalImage" src="" style="max-height: 100%; max-width: 100%; object-fit: contain;">
      </div>
      <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"></button>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
function showImageModal(src) {
    const modalImage = document.getElementById('modalImage');
    modalImage.src = src;
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    modal.show();
}

// Tombol Hapus/Batal Foto
const hapusBtn = document.getElementById('hapusFotoBtn');
const hapusInput = document.getElementById('hapusFotoInput');
const hapusNotif = document.getElementById('hapusFotoNotif');

if(hapusBtn){
    hapusBtn.addEventListener('click', () => {
        const isDeleting = hapusInput.value === "1";

        if(isDeleting){
            // Batal hapus
            hapusInput.value = 0;
            hapusBtn.innerText = "Hapus Foto";
            hapusNotif.classList.add('d-none');
        } else {
            // Konfirmasi hapus
            hapusInput.value = 1;
            hapusBtn.innerText = "Batal";
            hapusNotif.classList.remove('d-none');
        }
    });
}

// Preview foto saat pilih file (Tambah atau Edit)
const fotoInputs = document.querySelectorAll('#fotoInput');
fotoInputs.forEach(fotoInput => {
    fotoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = function(event){
                const cardImg = fotoInput.closest('.card').querySelector('img');
                cardImg.src = event.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection
