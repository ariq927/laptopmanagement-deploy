@extends('layouts/contentNavbarLayout')

@section('title', 'Form Peminjaman')

@section('content')
<div class="card">
  <h5 class="card-header">Pinjam Laptop {{ $laptop->merek }} {{ $laptop->tipe }}</h5>
  <div class="card-body">
    <form action="{{ route('peminjaman.store') }}" method="POST">
      @csrf
      <input type="hidden" name="laptop_id" value="{{ $laptop->id }}">

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <select id="nama" name="nama" class="form-select" required>
          <option value="" disabled selected>Pilih Nama</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Kode Pegawai</label>
        <input type="text" id="department" name="department" class="form-control" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="text" id="nomor_telepon" name="nomor_telepon" class="form-control" readonly>
      </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="form-control" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="form-control" required>
          </div>
        </div>


      <div class="d-flex justify-content-between">
        <a href="{{ route('laptop.index') }}" class="btn btn-outline-secondary">
          <i class="bx bx-arrow-back"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-save"></i> Pinjam
        </button>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$('#nama').select2({
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
      return {
        results: data.data.map(emp => ({
          id: emp.employeeCode || '-',
          text: emp.employeeName || '-',
          dept: emp.employeeCode || '-',
          phone: emp.employeeEmail || '-' 
        }))
      };
    }
  },
  templateResult: function (data) {
    if (!data.id) return data.text;
    return `${data.text} (${data.id})`;
  },
  templateSelection: function (data) {
    return data.text;
  }
});

$('#nama').on('select2:select', function (e) {
  const selected = e.params.data;
  $('#department').val(selected.dept);
  $('#nomor_telepon').val(selected.phone);
});

$('#nama').on('select2:clear', function () {
  $('#department').val('');
  $('#nomor_telepon').val('');
});
</script>
@endpush
