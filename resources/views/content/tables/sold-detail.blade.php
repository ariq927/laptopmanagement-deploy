@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laptop Terjual')

@section('content')
<style>
  .detail-card {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 24px;
  }

  .detail-header {
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    padding: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .detail-header h4 {
    color: #fff;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .detail-header h4 i {
    font-size: 28px;
  }

  .back-btn {
    background: rgba(255,255,255,0.2);
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    text-decoration: none;
  }

  .back-btn:hover {
    background: rgba(255,255,255,0.3);
    color: #fff;
    transform: translateX(-4px);
  }

  .detail-body {
    padding: 32px;
  }

  .laptop-photo {
    width: 100%;
    max-width: 500px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    margin-bottom: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .laptop-photo:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
  }

  /* Fullscreen Modal */
  .photo-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.95);
    animation: fadeIn 0.3s ease;
  }

  .photo-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .photo-modal img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
    border-radius: 8px;
    animation: zoomIn 0.3s ease;
  }

  .photo-modal-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 10000;
  }

  .photo-modal-close:hover {
    transform: rotate(90deg);
    color: #ef4444;
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }

  @keyframes zoomIn {
    from { 
      transform: scale(0.8);
      opacity: 0;
    }
    to { 
      transform: scale(1);
      opacity: 1;
    }
  }

  .info-section {
    margin-bottom: 32px;
  }

  .info-section h5 {
    color: #0d7a8e;
    font-weight: 600;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
  }

  .info-section h5 i {
    font-size: 22px;
  }

  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }

  .info-item {
    background: #f8fafc;
    padding: 16px;
    border-radius: 10px;
    border-left: 4px solid #14a2ba;
  }

  .info-label {
    color: #64748b;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
  }

  .info-value {
    color: #1e293b;
    font-size: 1rem;
    font-weight: 600;
  }

  .price-highlight {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    font-size: 1.5rem;
    font-weight: 700;
  }

  .status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #10b981;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
  }

  .notes-box {
    background: #fff3cd;
    border: 1px solid #ffc107;
    padding: 16px;
    border-radius: 10px;
    color: #856404;
  }

  .notes-box strong {
    display: block;
    margin-bottom: 8px;
    color: #856404;
  }

  .divider {
    height: 1px;
    background: #e2e8f0;
    margin: 32px 0;
  }

  /* Dark Mode */
  [data-theme='dark'] .detail-card {
    background: rgba(30, 41, 59, 0.95);
  }

  [data-theme='dark'] .info-item {
    background: #1e293b;
  }

  [data-theme='dark'] .info-value {
    color: #f1f5f9;
  }

  [data-theme='dark'] .notes-box {
    background: #3a2f1f;
    border-color: #ffc107;
    color: #ffc107;
  }

  [data-theme='dark'] .notes-box strong {
    color: #ffc107;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .detail-header {
      flex-direction: column;
      gap: 16px;
      align-items: flex-start;
    }

    .detail-body {
      padding: 20px;
    }

    .info-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="detail-card">
  <!-- Header -->
  <div class="detail-header">
    <h4>
      <i class="bx bx-laptop"></i>
      Detail Laptop Terjual
    </h4>
    <a href="{{ route('laptop.sold') }}" class="back-btn">
      <i class="bx bx-arrow-back"></i>
      Kembali
    </a>
  </div>

  <!-- Body -->
  <div class="detail-body">
    <div class="row">
      <!-- Foto Laptop -->
      <div class="col-md-5 mb-4">
        @if($laptop->foto)
          <img src="{{ $laptop->foto }}" 
               alt="{{ $laptop->merek }} {{ $laptop->tipe }}" 
               class="laptop-photo"
               id="laptopPhoto">
        @else
          <div style="width: 100%; max-width: 500px; height: 300px; background: #f1f5f9; display: flex; align-items: center; justify-content: center; border-radius: 12px;">
            <i class="bx bx-laptop" style="font-size: 80px; color: #cbd5e1;"></i>
          </div>
        @endif

        <!-- Status Badge -->
        <div class="mt-3">
          <span class="status-badge">
            <i class="bx bx-check-circle"></i>
            Terjual
          </span>
        </div>
      </div>

      <!-- Info Laptop -->
      <div class="col-md-7">
        <!-- Informasi Laptop -->
        <div class="info-section">
          <h5>
            <i class="bx bx-info-circle"></i>
            Informasi Laptop
          </h5>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Kode Laptop</div>
              <div class="info-value">{{ $laptop->kode }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Merek</div>
              <div class="info-value">{{ $laptop->merek }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Tipe</div>
              <div class="info-value">{{ $laptop->tipe }}</div>
            </div>
            <div class="info-item">
              <div class="info-label">Spesifikasi</div>
              <div class="info-value">{{ $laptop->spesifikasi ?? '-' }}</div>
            </div>
          </div>
        </div>

        <div class="divider"></div>

        <!-- Informasi Pembeli -->
        <div class="info-section">
          <h5>
            <i class="bx bx-user"></i>
            Informasi Pembeli
          </h5>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Nama Pembeli</div>
              <div class="info-value">{{ $laptop->buyer_name }}</div>
            </div>
            @if($laptop->buyer_id)
            <div class="info-item">
              <div class="info-label">ID Pembeli</div>
              <div class="info-value">{{ $laptop->buyer_id }}</div>
            </div>
            @endif
          </div>
        </div>

        <div class="divider"></div>

        <!-- Informasi Penjualan -->
        <div class="info-section">
          <h5>
            <i class="bx bx-dollar-circle"></i>
            Informasi Penjualan
          </h5>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Tanggal Terjual</div>
              <div class="info-value">
                {{ $laptop->sold_at ? \Carbon\Carbon::parse($laptop->sold_at)->format('d M Y, H:i') : '-' }} WIB
              </div>
            </div>
            <div class="info-item price-highlight">
              <div style="font-size: 0.9rem; opacity: 0.9; margin-bottom: 4px;">Harga Jual</div>
              Rp {{ number_format($laptop->sold_price ?? 0, 0, ',', '.') }}
            </div>
          </div>
        </div>

        <!-- Catatan -->
        @if($laptop->notes)
        <div class="divider"></div>
        <div class="info-section">
          <div class="notes-box">
            <strong>
              <i class="bx bx-note"></i>
              Catatan Penjualan
            </strong>
            {{ $laptop->notes }}
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Fullscreen Photo Modal -->
<div id="photoModal" class="photo-modal">
  <span class="photo-modal-close" id="closeModal">&times;</span>
  @if($laptop->foto)
  <img src="{{ $laptop->foto }}" alt="{{ $laptop->merek }} {{ $laptop->tipe }}" id="modalImage">
  @endif
</div>

<script>
// Get elements
const laptopPhoto = document.getElementById('laptopPhoto');
const photoModal = document.getElementById('photoModal');
const closeModal = document.getElementById('closeModal');
const modalImage = document.getElementById('modalImage');

// Open modal when photo clicked
if (laptopPhoto) {
  laptopPhoto.addEventListener('click', function() {
    console.log('Photo clicked!'); // Debug
    photoModal.classList.add('active');
    document.body.style.overflow = 'hidden';
  });
}

// Close modal when X clicked
if (closeModal) {
  closeModal.addEventListener('click', function(e) {
    e.stopPropagation();
    photoModal.classList.remove('active');
    document.body.style.overflow = 'auto';
  });
}

// Close modal when background clicked
photoModal.addEventListener('click', function(e) {
  if (e.target === photoModal) {
    photoModal.classList.remove('active');
    document.body.style.overflow = 'auto';
  }
});

// Prevent closing when image clicked
if (modalImage) {
  modalImage.addEventListener('click', function(e) {
    e.stopPropagation();
  });
}

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape' && photoModal.classList.contains('active')) {
    photoModal.classList.remove('active');
    document.body.style.overflow = 'auto';
  }
});
</script>
@endsection