@extends('layouts/contentNavbarLayout')

@section('title', 'Form Peminjaman')

@section('content')
<div class="card">
  <h5 class="card-header">Gunakan Laptop {{ $laptop->merek }} {{ $laptop->tipe }}</h5>
  <div class="card-body">
    
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm">
      @csrf
      <input type="hidden" name="laptop_id" value="{{ $laptop->id }}">

      <div class="mb-3">
        <label class="form-label">Nama <span class="text-danger">*</span></label>
        <select id="nama_select" class="form-select">
          <option value="" disabled selected>Pilih Nama</option>
        </select>
        <!-- Hidden field untuk submit -->
        <input type="hidden" id="nama" name="nama" value="" required>
        <div class="invalid-feedback d-none" id="nama-error">
          Silakan pilih nama pegawai terlebih dahulu
        </div>
      </div>

      <input type="hidden" id="kode_pegawai" name="kode_pegawai">

      <div class="mb-3">
        <label class="form-label">Unit</label>
        <input type="text" id="unit_display" class="form-control" readonly>
        <input type="hidden" id="unit" name="unit">
      </div>

      <div class="mb-3">
        <label class="form-label">Posisi</label>
        <input type="text" id="department_display" class="form-control" readonly>
        <!-- Hidden field untuk submit ke controller -->
        <input type="hidden" id="department" name="department" value="">
      </div>
     
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_mulai" class="form-control" 
                 value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
          <input type="date" name="tanggal_selesai" class="form-control" 
                 value="{{ old('tanggal_selesai') }}" required>
        </div>
      </div>

      <div class="d-flex justify-content-between">
        <a href="{{ route('laptop.index') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary" id="submitBtn">
          <i class="bx bx-save"></i> Gunakan
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  $('#nama_select').select2({
    placeholder: "Pilih Nama",
    allowClear: true,
    width: '100%',
    ajax: {
      url: '/pegawai',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return { 
          q: params.term 
        };
      },
      processResults: function (data) {
        console.log('API Response:', data);
        
        if (!data.data || data.data.length === 0) {
          return { results: [] };
        }
        
        return {
          results: data.data.map(emp => ({
            id: emp.employeeCode,
            text: emp.employeeName,
            nama: emp.employeeName,
            kode: emp.employeeCode,  
            department: emp.jobPositionName,
            unit: emp.jobDivisionName
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

  $('#nama_select').on('select2:select', function (e) {
    const selected = e.params.data;
    
    console.log('Selected data:', selected);
    
    $('#nama').val(selected.nama);
    $('#kode_pegawai').val(selected.kode);           
    $('#department').val(selected.department);
    $('#department_display').val(selected.department);
    $('#unit').val(selected.unit);
    $('#unit_display').val(selected.unit);
    
    $('#nama-error').addClass('d-none');
    $('#nama_select').next('.select2-container').removeClass('is-invalid');
  });

  $('#nama_select').on('select2:clear', function () {
    $('#nama').val('');
    $('#kode_pegawai').val('');                      
    $('#department').val('');
    $('#department_display').val('');
    $('#unit').val('');
    $('#unit_display').val('');
  });

  $('#peminjamanForm').on('submit', function(e) {
    const nama = $('#nama').val();
    const kodePegawai = $('#kode_pegawai').val();
    const unit = $('#unit').val();
    const department = $('#department').val();
    
    console.log('Form submit check:', { nama, kodePegawai, department });
    
    if (!nama || nama === '' || nama === '-') {
      e.preventDefault();
      $('#nama-error').removeClass('d-none').addClass('d-block');
      $('#nama_select').next('.select2-container').addClass('is-invalid');
      $('html, body').animate({ scrollTop: $('#nama_select').offset().top - 100 }, 500);
      $('#nama_select').select2('open');
      return false;
    }
    
    if (!kodePegawai || kodePegawai.trim() === '' || kodePegawai === '-') {
      e.preventDefault();
      alert('Kode pegawai tidak valid. Silakan pilih pegawai yang benar.');
      return false;
    }
    
    if (!unit || unit === '-') {
      e.preventDefault();
      alert('Unit tidak valid.');
      return false;
    }

    if (!department || department === '' || department === '-') {
      e.preventDefault();
      alert('Posisi tidak valid. Silakan pilih pegawai lagi.');
      return false;
    }
    
    $('#submitBtn').prop('disabled', true)
                   .html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');
    
    return true;
  });
});
</script>

<style>
.select2-container.is-invalid .select2-selection {
  border-color: #dc3545 !important;
}
.select2-container.is-invalid + .invalid-feedback {
  display: block;
}
</style>
@endpush