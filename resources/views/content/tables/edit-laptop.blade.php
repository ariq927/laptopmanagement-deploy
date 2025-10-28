@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laptop')

@section('content')
<div class="container py-4">
  <div class="p-4 shadow-sm" style="background-color: rgba(255,255,255,0.9); border-radius: 12px; position: relative;">
    <h4 class="mb-4">Detail Laptop</h4>

    @php
      $isReadOnly = ($laptop->status === 'dipinjam');
    @endphp

    @unless($isReadOnly)
    <!-- Tombol Edit / Batal Edit -->
    <button id="toggleEditBtn"
      class="btn position-absolute top-0 end-0 m-3 px-4 py-2 d-flex align-items-center justify-content-center gap-2"
      style="
        border-radius: 40px;
        font-size: 0.85rem;
        background-color: #6c757d;
        color: white;
        border: none;
        transition: all 0.25s ease;
      ">
      <i class='bx bx-edit-alt' style="font-size: 1rem;"></i>
      <span>Edit</span>
    </button>
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

      {{-- Kode Laptop --}}
      <div class="mb-3">
        <label class="form-label fw-semibold">Kode Laptop</label>
        <input type="text" class="form-control" value="{{ $laptop->kode }}" readonly>
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

        <div class="card shadow-sm border-0 position-relative" 
             style="width: 400px; height: 400px; background-color: rgba(245,245,245,0.6); border-radius: 12px;">

          <div class="d-flex justify-content-center align-items-center h-100 position-relative">
            @if($laptop->foto)
              <img src="{{ $laptop->foto }}" 
                   id="laptopImage"
                   class="img-fluid"
                   style="max-height: 100%; max-width: 100%; object-fit: contain; cursor: pointer; border-radius: 8px;">
            @else
              <img src="{{ asset('images/nophoto.png') }}" 
                   id="laptopImage"
                   style="width: 40px; height: 40px; object-fit: contain; opacity: 0.4;">
            @endif
          </div>

          @unless($isReadOnly)
          <div class="foto-btn-container d-none d-flex justify-content-center gap-3 py-3"
               style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(255,255,255,0.7); border-top: 1px solid #ddd; border-radius: 0 0 12px 12px;">
            <label class="foto-btn foto-edit mb-0">
              <i class="bx bx-upload me-1"></i> Edit Foto
              <input type="file" id="fotoInput" name="foto" class="d-none" accept="image/*">
            </label>

            @if($laptop->foto)
              <button type="button" class="foto-btn foto-hapus mb-0" id="hapusFotoBtn">
                <i class="bx bx-trash me-1"></i> Hapus Foto
              </button>
              <input type="hidden" name="hapus_foto" id="hapusFotoInput" value="0">
            @endif
          </div>
          @endunless
        </div>

        <small id="hapusFotoNotif" class="text-danger d-none ms-1">Foto akan dihapus.</small>
        <small class="text-muted d-block mt-1">Klik gambar untuk melihat fullscreen.</small>
      </div>

      <div class="mt-4 d-flex gap-3">
        <button type="submit" id="saveBtn" class="btn btn-primary px-4 py-2 d-none" style="border-radius: 6px;">Simpan Perubahan</button>
        <a href="{{ route('laptop.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 6px;">Kembali</a>
      </div>
    </form>
  </div>
</div>

{{-- Fullscreen Image --}}
<div id="imageModal" class="custom-modal" style="display: none;">
  <div class="custom-modal-overlay"></div>
  <div class="custom-modal-content">
    <img id="modalImage" src="" alt="Fullscreen Image">
    <button id="closeModal" class="custom-modal-close">&times;</button>
  </div>
</div>
@endsection

@section('page-script')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('toggleEditBtn');
  const form = document.getElementById('editForm');
  const fields = document.querySelectorAll('.editable-field');
  const saveBtn = document.getElementById('saveBtn');
  const fotoBtns = document.querySelector('.foto-btn-container');
  const hapusBtn = document.getElementById('hapusFotoBtn');
  const hapusInput = document.getElementById('hapusFotoInput');
  const hapusNotif = document.getElementById('hapusFotoNotif');
  const originalSrc = '{{ $laptop->foto }}';
  const laptopImage = document.getElementById('laptopImage');
  const imageModal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  const closeModal = document.getElementById('closeModal');
  const overlay = document.querySelector('.custom-modal-overlay');

  let editMode = false;

  // === TOGGLE EDIT MODE ===
  if (toggleBtn) {
    toggleBtn.addEventListener('click', function() {
      editMode = !editMode;
      const icon = this.querySelector('i');
      const text = this.querySelector('span');

      fields.forEach(f => f.readOnly = !editMode);
      if (fotoBtns) fotoBtns.classList.toggle('d-none', !editMode);

      if (editMode) {
        text.textContent = "Batal Edit";
        icon.className = "bx bx-x";
        this.style.backgroundColor = "#495057";
        saveBtn.classList.remove('d-none');
      } else {
        text.textContent = "Edit";
        icon.className = "bx bx-edit-alt";
        this.style.backgroundColor = "#6c757d";
        saveBtn.classList.add('d-none');
        form.reset();

        // Reset foto ke aslinya
        if (laptopImage && originalSrc) laptopImage.src = originalSrc;
        if (hapusInput) hapusInput.value = "0";
        if (hapusNotif) hapusNotif.classList.add('d-none');
      }
    });
  }

  // === HAPUS FOTO ===
  if (hapusBtn) {
    hapusBtn.addEventListener('click', function(event) {
      event.preventDefault();
      const isDeleting = hapusInput.value === "1";
      hapusInput.value = isDeleting ? "0" : "1";
      this.innerHTML = isDeleting ? '<i class="bx bx-trash me-1"></i> Hapus Foto' : '<i class="bx bx-x me-1"></i> Batal';
      hapusNotif.classList.toggle('d-none', isDeleting);
    });
  }

  // === PREVIEW FOTO BARU ===
  const fotoInput = document.getElementById('fotoInput');
  if (fotoInput) {
    fotoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
          laptopImage.src = event.target.result;
          laptopImage.style.cursor = 'pointer';
        }
        reader.readAsDataURL(file);
      }
    });
  }

  // === FULLSCREEN IMAGE MODAL ===
  if (laptopImage && imageModal && modalImage) {
    laptopImage.addEventListener('click', function() {
      const src = this.src;
      if (src && src !== "{{ asset('images/nophoto.png') }}") {
        modalImage.src = src;
        imageModal.style.display = 'flex';
      }
    });

    closeModal.addEventListener('click', () => imageModal.style.display = 'none');
    overlay.addEventListener('click', () => imageModal.style.display = 'none');

    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') imageModal.style.display = 'none';
    });
  }
});
</script>

<style>
/* Tombol Edit Mode */
#toggleEditBtn:hover {
  filter: brightness(1.1);
  transform: translateY(-1px);
  box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

/* Tombol Edit & Hapus Foto */
.foto-btn {
  font-size: 0.85rem;
  padding: 6px 16px;
  border: none;
  border-radius: 30px;
  transition: all 0.25s ease;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
}

.foto-edit {
  background-color: #007bff;
  color: white;
}
.foto-edit:hover {
  background-color: #0056b3;
}

.foto-hapus {
  background-color: #dc3545;
  color: white;
}
.foto-hapus:hover {
  background-color: #a71d2a;
}

/* Modal background & blur */
.custom-modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.custom-modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(15, 15, 15, 0.4);
  backdrop-filter: blur(8px);
}

.custom-modal-content {
  position: relative;
  border-radius: 8px;
  overflow: visible;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.6);
}

#modalImage {
  display: block;
  max-width: 90vw;
  max-height: 90vh;
  width: auto;
  height: auto;
  border-radius: 8px;
  object-fit: contain;
}

/* Tombol close */
#closeModal {
  position: fixed;
  top: 16px;
  right: 16px;
  background: rgba(255, 255, 255, 0.25);
  border: none;
  color: #fff;
  font-size: 1.8rem;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10000;
  transition: background 0.2s ease;
}

#closeModal:hover {
  background: rgba(255, 255, 255, 0.45);
}

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

[data-theme="dark"] .container .card {
  background-color: rgba(40, 40, 60, 0.9) !important;
  border: 1px solid #444 !important;
}

[data-theme="dark"] .text-muted {
  color: #aaa !important;
}   
</style>
@endsection