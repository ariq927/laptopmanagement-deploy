@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laptop')

@section('content')
<div class="container py-4">
  <div class="p-4" style="background-color: rgba(255,255,255,0.85); border-radius: 8px; position: relative;">
    <h4 class="mb-4">Detail Laptop</h4>

    @php
      $isReadOnly = ($laptop->status === 'dipinjam');
    @endphp

    {{-- Tombol Edit di kanan atas (hanya muncul kalau tidak dipinjam) --}}
    @unless($isReadOnly)
      <button id="toggleEditBtn" class="btn btn-warning position-absolute top-0 end-0 m-3 px-4 py-2" style="border-radius: 0;">Edit</button>
    @endunless

    <form 
      action="{{ $isReadOnly ? '#' : route('laptop.update', $laptop->id) }}" 
      method="POST" 
      enctype="multipart/form-data"
      id="editForm"
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
        <input type="text" class="form-control editable-field" name="merek" value="{{ $laptop->merek }}" readonly required>
      </div>

      {{-- Tipe --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Tipe</label>
        <input type="text" class="form-control editable-field" name="tipe" value="{{ $laptop->tipe }}" readonly required>
      </div>

      {{-- Spesifikasi --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Spesifikasi</label>
        <textarea class="form-control editable-field" name="spesifikasi" rows="4" readonly>{{ $laptop->spesifikasi }}</textarea>
      </div>

      {{-- Status --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Status</label>
        <input type="text" class="form-control" value="{{ ucfirst($laptop->status) }}" readonly>
      </div>

      {{-- Foto Laptop --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Foto Laptop</label><br>

        <div class="card shadow-sm p-2 border border-2 position-relative" 
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
            @endif
          </div>

          {{-- Tombol Edit/Hapus Foto di dalam card --}}
          @unless($isReadOnly)
            <div class="foto-btn-container d-none d-flex justify-content-center gap-2 mt-2 pb-2" 
                 style="position: absolute; bottom: 0; left: 0; right: 0;">
              <label class="btn btn-primary px-3 py-1 mb-0" style="border-radius: 0; font-size: 0.8rem;" onclick="event.stopPropagation();">
                Edit Foto
                <input type="file" id="fotoInput" name="foto" class="d-none">
              </label>
              @if($laptop->foto)
                <button type="button" class="btn btn-danger px-3 py-1 mb-0" id="hapusFotoBtn" style="border-radius: 0; font-size: 0.8rem;" onclick="event.stopPropagation();">
                  Hapus Foto
                </button>
                <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
              @endif
            </div>
          @endunless
        </div>

        <small id="hapusFotoNotif" class="text-danger d-none ms-1">Foto akan dihapus.</small>
        <small class="text-muted d-block mt-1">Klik gambar untuk melihat fullscreen.</small>
      </div>

      {{-- Tombol Simpan / Kembali --}}
      <div class="mt-4 d-flex gap-3">
        <button type="submit" id="saveBtn" class="btn btn-primary px-4 py-2 d-none" style="border-radius: 0; font-size: 1rem;">Simpan Perubahan</button>
        <a href="{{ route('laptop.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 0; font-size: 1rem;">Kembali</a>
      </div>
    </form>
  </div>
</div>

{{-- Modal Fullscreen Gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-fullscreen p-0 m-0">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-body d-flex justify-content-center align-items-center p-0" 
           style="backdrop-filter: blur(4px); background-color: rgba(0,0,0,0.3);">
        <img id="modalImage" src="" style="width: 100%; height: 100%; object-fit: contain; cursor: zoom-out;">
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
  hapusBtn.addEventListener('click', (event) => {
    event.stopPropagation();
    const isDeleting = hapusInput.value === "1";
    hapusInput.value = isDeleting ? "0" : "1";
    hapusBtn.innerText = isDeleting ? "Hapus Foto" : "Batal";
    hapusNotif.classList.toggle('d-none', isDeleting);
  });
}

// Preview foto saat pilih file
const fotoInput = document.getElementById('fotoInput');
if(fotoInput){
  fotoInput.addEventListener('click', (event) => event.stopPropagation());
  fotoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if(file){
      const reader = new FileReader();
      reader.onload = function(event){
        const cardImg = document.querySelector('.card img');
        cardImg.src = event.target.result;
      }
      reader.readAsDataURL(file);
    }
  });
}

// Toggle Edit Mode
const toggleBtn = document.getElementById('toggleEditBtn');
if(toggleBtn){
  const form = document.getElementById('editForm');
  const fields = document.querySelectorAll('.editable-field');
  const saveBtn = document.getElementById('saveBtn');
  const fotoBtns = document.querySelector('.foto-btn-container');

  let editMode = false;

  toggleBtn.addEventListener('click', () => {
    editMode = !editMode;

    fields.forEach(f => f.readOnly = !editMode);

    if(fotoBtns){
      fotoBtns.classList.toggle('d-none', !editMode);
    }

    if(editMode){
      toggleBtn.innerText = "Batal Edit";
      toggleBtn.classList.replace('btn-warning', 'btn-danger');
      saveBtn.classList.remove('d-none');
    } else {
      toggleBtn.innerText = "Edit";
      toggleBtn.classList.replace('btn-danger', 'btn-warning');
      saveBtn.classList.add('d-none');
      form.reset();
    }
  });
}
</script>
@endsection
