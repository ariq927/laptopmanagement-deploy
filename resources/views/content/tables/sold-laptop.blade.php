@extends('layouts.contentNavbarLayout')

@section('title', 'Jual Laptop')

@section('content')
<style>
  /* === MINIMAL ANIMATIONS === */
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* === GLOBAL STYLES === */
  body {
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background: #f8fafc;
  }

  .sold-container {
    max-width: 1150px;
    margin: 0 auto;
    padding: 30px 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    animation: fadeInUp 0.5s ease;
  }

  @media (max-width: 992px) {
    .sold-container {
      grid-template-columns: 1fr;
    }
  }

  /* === CARD BASE === */
  .info-card, .form-card {
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: box-shadow 0.3s ease;
  }

  .info-card:hover, .form-card:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
  }

  /* === INFO CARD === */
  .info-card {
    background: linear-gradient(160deg, #14a2ba 0%, #0d7a8e 100%);
    color: #fff;
    padding: 28px;
    border-left: 5px solid #fcd116;
  }

  .info-header {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    font-size: 1.25rem;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.2);
  }

  .info-header i {
    font-size: 26px;
  }

  /* === INFO ROWS === */
  .info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    margin-bottom: 10px;
    background: rgba(255, 255, 255, 0.08);
    border-radius: 10px;
    transition: all 0.2s ease;
  }

  .info-row:hover {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(4px);
  }

  .info-row:last-child {
    margin-bottom: 0;
  }

  .info-label {
    color: rgba(255,255,255,0.85);
    font-size: 0.9rem;
    font-weight: 500;
  }

  .info-value {
    font-weight: 600;
    color: #fff;
    font-size: 0.95rem;
    background: rgba(0,0,0,0.2);
    padding: 5px 12px;
    border-radius: 6px;
  }

  /* === LAPTOP IMAGE === */
  .laptop-image-wrapper {
    margin-top: 20px;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
  }

  .laptop-image {
    width: 100%;
    display: block;
    transition: transform 0.3s ease;
  }

  .laptop-image-wrapper:hover .laptop-image {
    transform: scale(1.05);
  }

  .image-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .laptop-image-wrapper:hover .image-overlay {
    opacity: 1;
  }

  .zoom-text {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(20, 162, 186, 0.95);
    padding: 10px 20px;
    border-radius: 8px;
  }

  .zoom-text i {
    font-size: 20px;
  }

  /* === FORM CARD === */
  .form-card {
    background: #fff;
    padding: 32px;
  }

  .form-header {
    font-weight: 600;
    color: #0d7a8e;
    margin-bottom: 28px;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
  }

  .form-header i {
    font-size: 26px;
    color: #14a2ba;
  }

  /* === FORM ELEMENTS === */
  .form-group {
    margin-bottom: 20px;
  }

  .form-label {
    font-weight: 600;
    color: #334155;
    margin-bottom: 8px;
    font-size: 0.9rem;
    display: block;
  }

  .form-control, .form-select {
    width: 100%;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    background: #fff;
    transition: all 0.2s ease;
    font-size: 0.9rem;
    color: #1e293b;
  }

  .form-control:focus, .form-select:focus {
    outline: none;
    border-color: #14a2ba;
    box-shadow: 0 0 0 3px rgba(20,162,186,0.1);
  }

  .form-control::placeholder {
    color: #94a3b8;
  }

  .form-control:read-only {
    background: #f8fafc;
    color: #64748b;
    cursor: not-allowed;
  }

  textarea.form-control {
    resize: vertical;
    min-height: 90px;
  }

  /* === SELECT2 STYLING === */
  .select2-container--default .select2-selection--single {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 14px;
    height: auto;
    min-height: 46px;
    transition: all 0.2s ease;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 1.5;
    padding: 0;
    color: #1e293b;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 100%;
    right: 10px;
  }

  .select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #14a2ba;
    box-shadow: 0 0 0 3px rgba(20,162,186,0.1);
  }

  .select2-container.is-invalid .select2-selection {
    border-color: #ef4444 !important;
  }

  .select2-container {
    width: 100% !important;
  }

  /* === HELPER TEXT === */
  .form-text {
    color: #64748b;
    font-size: 0.8rem;
    margin-top: 5px;
    display: block;
  }

  .invalid-feedback {
    color: #ef4444;
    font-size: 0.85rem;
    margin-top: 6px;
    font-weight: 500;
  }

  /* === PRICE DISPLAY === */
  .price-preview {
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    margin-top: 10px;
    padding: 12px 18px;
    border-radius: 10px;
    text-align: center;
    display: none;
  }

  .price-preview.show {
    display: block;
  }

  /* === BUTTONS === */
  .form-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 28px;
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
  }

  .btn i {
    font-size: 18px;
  }

  /* Button Back/Cancel */
  .btn-back {
    background: #f1f5f9;
    color: #475569;
    border: 1.5px solid #e2e8f0;
  }

  .btn-back:hover {
    background: #e2e8f0;
    color: #334155;
    transform: translateX(-2px);
  }

  /* Button Submit */
  .btn-submit {
    background: linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(20, 162, 186, 0.25);
  }

  .btn-submit:hover {
    box-shadow: 0 4px 12px rgba(20, 162, 186, 0.35);
    transform: translateY(-1px);
  }

  .btn-submit:active {
    transform: translateY(0);
  }

  .btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }

  /* === FULLSCREEN MODAL === */
  .fullscreen-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.9);
  }

  .fullscreen-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .fullscreen-modal img {
    max-width: 90%;
    max-height: 90vh;
    object-fit: contain;
    border-radius: 8px;
  }

  .close-modal {
    position: absolute;
    top: 25px;
    right: 35px;
    color: white;
    font-size: 40px;
    cursor: pointer;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    background: rgba(255, 255, 255, 0.1);
  }

  .close-modal:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: rotate(90deg);
  }

  /* === DARK MODE === */
  [data-theme='dark'] body {
    background: #0f172a;
  }

  [data-theme='dark'] .info-card {
    background: linear-gradient(160deg, #0d5a6e 0%, #083845 100%);
  }

  [data-theme='dark'] .form-card {
    background: #1e293b;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  }

  [data-theme='dark'] .form-header,
  [data-theme='dark'] .form-label {
    color: #f1f5f9;
  }

  [data-theme='dark'] .form-control,
  [data-theme='dark'] .form-select {
    background: #0f172a;
    color: #f1f5f9;
    border-color: #334155;
  }

  [data-theme='dark'] .form-control:focus,
  [data-theme='dark'] .form-select:focus {
    background: #1e293b;
    border-color: #14a2ba;
  }

  [data-theme='dark'] .form-control:read-only {
    background: #0f172a;
    color: #64748b;
  }

  [data-theme='dark'] .btn-back {
    background: #334155;
    color: #e2e8f0;
    border-color: #475569;
  }

  [data-theme='dark'] .btn-back:hover {
    background: #475569;
    color: #f1f5f9;
  }

  .spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
  }
</style>

<div class="sold-container">
  <!-- INFO CARD -->
  <div class="info-card">
    <div class="info-header">
      <i class="bx bx-laptop"></i>
      <span>Informasi Laptop</span>
    </div>

    <div class="info-row">
      <span class="info-label">Kode Laptop</span>
      <span class="info-value">{{ $laptop->kode }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Merek</span>
      <span class="info-value">{{ $laptop->merek }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Tipe</span>
      <span class="info-value">{{ $laptop->tipe }}</span>
    </div>
    <div class="info-row">
      <span class="info-label">Spesifikasi</span>
      <span class="info-value">{{ $laptop->spesifikasi }}</span>
    </div>

    @if($laptop->foto)
    <div class="laptop-image-wrapper" id="imageWrapper">
      <img src="{{ $laptop->foto }}" alt="Foto Laptop" class="laptop-image">
      <div class="image-overlay">
        <span class="zoom-text">
          <i class="bx bx-zoom-in"></i>
          <span>Lihat Fullscreen</span>
        </span>
      </div>
    </div>
    @endif
  </div>

  <!-- FORM CARD -->
  <div class="form-card">
    <div class="form-header">
      <i class="bx bx-dollar-circle"></i>
      <span>Form Penjualan</span>
    </div>

    <form action="{{ route('laptop.processSold', $laptop->id) }}" method="POST" id="soldForm">
      @csrf

      <div class="form-group">
        <label class="form-label">
          Nama Pembeli <span class="text-danger">*</span>
        </label>
        <select id="buyer_select" class="form-select">
          <option value="" disabled selected>Pilih Nama</option>
        </select>
        <input type="hidden" id="buyer_name" name="buyer_name" required>
        <input type="hidden" id="buyer_id" name="buyer_id">
        <div class="invalid-feedback d-none" id="buyer-error">
          Silakan pilih pembeli terlebih dahulu
        </div>
        <small class="form-text">Data pembeli dari i-Morning</small>
      </div>

      <div class="form-group">
        <label class="form-label">Kode Pegawai</label>
        <input type="text" id="employee_code" class="form-control" readonly placeholder="Otomatis terisi">
      </div>

      <div class="form-group">
        <label class="form-label">Posisi</label>
        <input type="text" id="position" class="form-control" readonly placeholder="Otomatis terisi">
      </div>

      <div class="form-group">
        <label class="form-label">
          Harga Jual (Rp) <span class="text-danger">*</span>
        </label>
        <input type="number" 
               class="form-control" 
               name="price" 
               id="priceInput"
               placeholder="Contoh: 5000000" 
               min="0" 
               step="1000" 
               required>
        <div id="priceFormatted" class="price-preview"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Catatan (Opsional)</label>
        <textarea class="form-control" 
                  name="notes" 
                  rows="3" 
                  placeholder="Catatan tambahan tentang penjualan..."></textarea>
      </div>

      <div class="form-actions">
        <a href="{{ route('laptop.index') }}" class="btn btn-back">
          <i class="bx bx-arrow-back"></i>
          <span>Kembali</span>
        </a>
        <button type="submit" class="btn btn-submit" id="submitBtn">
          <i class="bx bx-check-circle"></i>
          <span>Konfirmasi Penjualan</span>
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Fullscreen Modal -->
<div class="fullscreen-modal" id="imageModal">
  <span class="close-modal" id="closeModal">&times;</span>
  <img src="" alt="Laptop Fullscreen" id="modalImage">
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Price formatter
  $('#priceInput').on('input', function() {
    const value = parseInt(this.value) || 0;
    const display = $('#priceFormatted');
    
    if (value > 0) {
      display.text('Rp ' + value.toLocaleString('id-ID')).addClass('show');
    } else {
      display.removeClass('show');
    }
  });

  // Select2 initialization
  $('#buyer_select').select2({
    placeholder: "Pilih Nama",
    allowClear: true,
    width: '100%',
    ajax: {
      url: '/pegawai',
      dataType: 'json',
      delay: 250,
      data: function (params) { 
        return { q: params.term }; 
      },
      processResults: function (data) {
        if (!data.data || data.data.length === 0) return { results: [] };
        return {
          results: data.data.map(emp => ({
            id: emp.employeeCode || '-',
            text: emp.employeeName || '-',
            nama: emp.employeeName || '-',
            kode: emp.employeeCode || '-',
            department: emp.jobPositionName || '-'
          }))
        };
      },
      cache: true
    },
    templateResult: function (data) {
      if (data.loading) return 'Mencari...';
      if (!data.id) return data.text;
      return $('<span>' + data.text + ' - ' + data.kode + '</span>');
    },
    templateSelection: function (data) {
      return data.text || data.nama;
    },
    minimumInputLength: 0
  });

  // Select2 events
  $('#buyer_select').on('select2:select', function (e) {
    const selected = e.params.data;
    $('#buyer_name').val(selected.nama);
    $('#buyer_id').val(selected.kode);
    $('#employee_code').val(selected.kode);
    $('#position').val(selected.department);
    $('#buyer-error').addClass('d-none');
    $('#buyer_select').next('.select2-container').removeClass('is-invalid');
  });

  $('#buyer_select').on('select2:clear', function () {
    $('#buyer_name, #buyer_id, #employee_code, #position').val('');
  });

  // Form validation
  $('#soldForm').on('submit', function(e) {
    const buyerName = $('#buyer_name').val();
    const buyerId = $('#buyer_id').val();
    const position = $('#position').val();

    if (!buyerName || buyerName === '-' || !buyerId || buyerId === '-' || !position || position === '-') {
      e.preventDefault();
      $('#buyer-error').removeClass('d-none').addClass('d-block');
      $('#buyer_select').next('.select2-container').addClass('is-invalid');
      $('html, body').animate({ scrollTop: $('#buyer_select').offset().top - 100 }, 500);
      $('#buyer_select').select2('open');
      return false;
    }

    $('#submitBtn').prop('disabled', true)
                   .html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
    return true;
  });

  // Fullscreen image modal
  const modal = $('#imageModal');
  const modalImg = $('#modalImage');
  const imageWrapper = $('#imageWrapper');
  const laptopImg = imageWrapper.find('.laptop-image');
  const closeModal = $('#closeModal');

  imageWrapper.on('click', function() {
    modal.addClass('active');
    modalImg.attr('src', laptopImg.attr('src'));
  });

  closeModal.on('click', function() {
    modal.removeClass('active');
  });

  modal.on('click', function(e) {
    if (e.target.id === 'imageModal') {
      modal.removeClass('active');
    }
  });

  // ESC key to close modal
  $(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
      modal.removeClass('active');
    }
  });
});
</script>
@endpush