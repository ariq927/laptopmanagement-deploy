@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laptop')

@section('content')
<div class="container py-4">
  <div class="p-4" style="background-color: rgba(255,255,255,0.85); border-radius: 8px; position: relative;">
    <h4 class="mb-4">Detail Laptop</h4>

    @php
      $isReadOnly = ($laptop->status === 'dipinjam');
    @endphp

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

      {{-- ID --}}
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

        <div class="card shadow-sm p-2 border border-2 position-relative" 
             style="width: 400px; height: 400px; background-color: rgba(255,255,255,0.5);">

          <div class="d-flex justify-content-center align-items-center" style="height: 100%; position: relative; z-index: 1;">
            @if($laptop->foto)
              <img src="{{ $laptop->foto }}" 
                   id="laptopImage"
                   class="img-fluid" 
                   style="max-height: 100%; max-width: 100%; object-fit: contain; cursor: pointer;">
            @else
              <img src="{{ asset('images/nophoto.png') }}" 
                   id="laptopImage"
                   style="width: 100px; height: 100px; object-fit: contain;">
            @endif
          </div>

          @unless($isReadOnly)
            <div class="foto-btn-container d-none d-flex justify-content-center gap-2 mt-2 pb-2" 
                 style="position: absolute; bottom: 0; left: 0; right: 0; z-index: 10;">
              <label class="btn btn-primary px-3 py-1 mb-0" style="border-radius: 0; font-size: 0.8rem;">
                Edit Foto
                <input type="file" id="fotoInput" name="foto" class="d-none" accept="image/*">
              </label>
              @if($laptop->foto)
                <button type="button" class="btn btn-danger px-3 py-1 mb-0" id="hapusFotoBtn" style="border-radius: 0; font-size: 0.8rem;">
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

      <div class="mt-4 d-flex gap-3">
        <button type="submit" id="saveBtn" class="btn btn-primary px-4 py-2 d-none" style="border-radius: 0; font-size: 1rem;">Simpan Perubahan</button>
        <a href="{{ route('laptop.index') }}" class="btn btn-secondary px-4 py-2" style="border-radius: 0; font-size: 1rem;">Kembali</a>
      </div>
    </form>
  </div>
</div>

{{-- Fullscreen Image  --}}
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
console.log('✅ Script loaded');

// Pindahkan fungsi ke level global
function showImageModal(src) {
  console.log('📸 Showing modal with image:', src);
  const modal = document.getElementById('imageModal');
  const modalImage = document.getElementById('modalImage');
  
  modalImage.src = src;
  modal.style.display = 'flex';
  document.body.style.overflow = 'hidden'; 
}

function closeImageModal() {
  console.log('❌ Closing modal');
  const modal = document.getElementById('imageModal');
  modal.style.display = 'none';
  document.body.style.overflow = 'auto'; 
}

document.addEventListener('DOMContentLoaded', function() {
  console.log('🚀 DOM Content Loaded');

  const closeBtn = document.getElementById('closeModal');
  const modalOverlay = document.querySelector('.custom-modal-overlay');
  const modalImage = document.getElementById('modalImage');
  
  if (closeBtn) {
    closeBtn.addEventListener('click', closeImageModal);
  }
  
  if (modalOverlay) {
    modalOverlay.addEventListener('click', closeImageModal);
  }
  
  if (modalImage) {
    modalImage.addEventListener('click', closeImageModal);
  }
  
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeImageModal();
    }
  });

  const laptopImage = document.getElementById('laptopImage');
  
  console.log('🖼️ Laptop image found:', laptopImage);
  console.log('📍 Image src:', laptopImage ? laptopImage.src : 'not found');
  
  if (laptopImage) {
    const imgSrc = laptopImage.src;
    
    if (imgSrc && imgSrc.indexOf('nophoto.png') === -1) {
      console.log('✅ Adding click listener to image');
      
      laptopImage.addEventListener('click', function(e) {
        console.log('🖱️ Image clicked!');
        e.preventDefault();
        e.stopPropagation();
        showImageModal(this.src);
      });
      
      laptopImage.style.cursor = 'pointer';
      laptopImage.title = 'Klik untuk melihat fullscreen';
    } else {
      console.log('⚠️ Image is nophoto.png, not adding click');
    }
  } else {
    console.error('❌ Laptop image element not found!');
  }

  const hapusBtn = document.getElementById('hapusFotoBtn');
  const hapusInput = document.getElementById('hapusFotoInput');
  const hapusNotif = document.getElementById('hapusFotoNotif');

  if(hapusBtn){
    hapusBtn.addEventListener('click', function(event) {
      event.stopPropagation();
      event.preventDefault();
      
      const isDeleting = hapusInput.value === "1";
      hapusInput.value = isDeleting ? "0" : "1";
      this.innerText = isDeleting ? "Hapus Foto" : "Batal";
      hapusNotif.classList.toggle('d-none', isDeleting);
      
      console.log('🗑️ Hapus foto clicked, value:', hapusInput.value);
    });
  }

  const fotoInput = document.getElementById('fotoInput');
  if(fotoInput){
    fotoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if(file){
        console.log('📁 File selected:', file.name);
        
        const reader = new FileReader();
        reader.onload = function(event){
          const cardImg = document.getElementById('laptopImage');
          if (cardImg) {
            cardImg.src = event.target.result;
            cardImg.style.cursor = 'pointer';
            
            const newImg = cardImg.cloneNode(true);
            cardImg.parentNode.replaceChild(newImg, cardImg);
            
            newImg.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              showImageModal(event.target.result);
            });
            
            console.log('✅ Image preview updated');
          }
        }
        reader.readAsDataURL(file);
      }
    });
  }

  const toggleBtn = document.getElementById('toggleEditBtn');
  if(toggleBtn){
    const form = document.getElementById('editForm');
    const fields = document.querySelectorAll('.editable-field');
    const saveBtn = document.getElementById('saveBtn');
    const fotoBtns = document.querySelector('.foto-btn-container');

    let editMode = false;

    toggleBtn.addEventListener('click', function() {
      editMode = !editMode;
      console.log('✏️ Edit mode:', editMode);

      fields.forEach(f => f.readOnly = !editMode);

      if(fotoBtns){
        fotoBtns.classList.toggle('d-none', !editMode);
      }

      if(editMode){
        this.innerText = "Batal Edit";
        this.classList.replace('btn-warning', 'btn-danger');
        saveBtn.classList.remove('d-none');
      } else {
        this.innerText = "Edit";
        this.classList.replace('btn-danger', 'btn-warning');
        saveBtn.classList.add('d-none');
        form.reset();
        
        const originalSrc = '{{ $laptop->foto }}';
        const cardImg = document.getElementById('laptopImage');
        if (cardImg && originalSrc) {
          cardImg.src = originalSrc;
        }
        
        if (hapusInput) hapusInput.value = "0";
        if (hapusBtn) hapusBtn.innerText = "Hapus Foto";
        if (hapusNotif) hapusNotif.classList.add('d-none');
      }
    });
  }
});
</script>

{{-- Dark Mode & Custom Styles --}}
<style>
/* Light Theme Base */
[data-theme="light"] .container .p-4 {
  background-color: rgba(255,255,255,0.85) !important;
  color: #000 !important;
}

/* Dark Theme */
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

#laptopImage[style*="cursor: pointer"] {
  transition: all 0.2s ease;
}

#laptopImage[style*="cursor: pointer"]:hover {
  opacity: 0.85;
  transform: scale(1.01);
}

[data-theme="dark"] .container .btn-warning {
  background-color: #ffb84d !important;
  border-color: #ffb84d !important;
  color: #000 !important;
}

[data-theme="dark"] .container .btn-danger {
  background-color: #ff5c5c !important;
  border-color: #ff5c5c !important;
  color: #fff !important;
}

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

.custom-modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 9999;
  align-items: center;
  justify-content: center;
}

.custom-modal-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  backdrop-filter: blur(4px);
  cursor: zoom-out;
}

.custom-modal-content {
  position: relative;
  max-width: 95vw;
  max-height: 95vh;
  z-index: 10000;
}

.custom-modal-content img {
  max-width: 100%;
  max-height: 95vh;
  object-fit: contain;
  cursor: zoom-out;
  animation: modalFadeIn 0.3s ease;
}

.custom-modal-close {
  position: fixed;
  top: 20px;
  right: 20px;
  background: rgba(255, 255, 255, 0.9);
  border: none;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  font-size: 30px;
  line-height: 1;
  cursor: pointer;
  color: #000;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  z-index: 10001;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.custom-modal-close:hover {
  background: rgba(255, 255, 255, 1);
  transform: rotate(90deg) scale(1.1);
}

[data-theme="dark"] .custom-modal-close {
  background: rgba(40, 40, 40, 0.9);
  color: #fff;
}

[data-theme="dark"] .custom-modal-close:hover {
  background: rgba(60, 60, 60, 0.95);
}

@keyframes modalFadeIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
@endsection