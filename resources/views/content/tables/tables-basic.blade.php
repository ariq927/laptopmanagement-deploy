@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Peminjam')

@section('content')
<div id="selesaiModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:450px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Selesaikan Peminjaman</h4>
    <div style="background:rgba(255,255,255,0.1); padding:15px; border-radius:8px; margin-bottom:20px;">
      <p style="color:#fff; margin:0; font-size:14px; line-height:1.6;">
        <strong>Nama:</strong> <span id="modalNama"></span><br>
        <strong>Laptop:</strong> <span id="modalLaptop"></span><br>
        <strong>Tanggal Pinjam:</strong> <span id="modalTanggal"></span>
      </p>
    </div>
    <p style="color:#fff; margin-bottom:20px; opacity:0.9; text-align:center;">Apakah Anda yakin ingin menyelesaikan peminjaman ini?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeSelesaiModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmSelesai()" class="btn btn-success" style="font-weight:bold; padding:8px 20px;">Ya, Selesaikan</button>
    </div>
  </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" style="position:fixed; top:20px; right:20px; min-width:300px; background:linear-gradient(135deg, #10b981 0%, #059669 100%); color:#fff; padding:15px 20px; border-radius:10px; box-shadow:0 8px 25px rgba(0,0,0,0.3); z-index:10000; display:none; transform:translateX(400px); transition:transform 0.3s ease; border:2px solid rgba(255,255,255,0.2);">
  <div style="display:flex; align-items:center; gap:12px;">
    <span style="font-size:24px;">✓</span>
    <span id="toastMessage" style="font-weight:bold; font-size:14px;"></span>
  </div>
</div>

@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast("✓ {{ session('success') }}", "#10b981");
  });
</script>
@endif

@if(session('error'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
    showToast("✗ {{ session('error') }}", "#ef4444");
  });
</script>
@endif

<div class="card" id="peminjamCard" 
  style="background-color: rgba(20, 162, 186, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(20, 162, 186, 0.3);">

  <div class="card-header d-flex justify-content-between align-items-center"
    style="background-color: rgba(20,162,186,0.5); border-bottom: 1px solid rgba(20, 162, 186, 0.3);">
    <h5 style="color: #fff; font-weight: bold; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);">
      Daftar Peminjam
    </h5>

    <form method="GET" class="d-flex gap-2">
       <select name="per_page" class="form-select" style="width: auto; background-color: rgba(255,255,255,0.9); border: 1px solid #14a2ba;">
        <option value="10" {{ ($perPage ?? 10)==10 ? 'selected' : '' }}>10 / halaman</option>
        <option value="20" {{ ($perPage ?? 10)==20 ? 'selected' : '' }}>20 / halaman</option>
        <option value="50" {{ ($perPage ?? 10)==50 ? 'selected' : '' }}>50 / halaman</option>
      </select>
      <input type="text" name="search" value="{{ $search ?? '' }}" class="form-control" placeholder="Cari nama/departemen..." 
        style="background-color: rgba(255,255,255,0.9); border: 1px solid #14a2ba;">
           <button type="submit" class="btn btn-light fw-bold">Cari</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered mb-0">
      <thead style="background-color: rgba(20,162,186,0.5);">
        <tr>
          <th class="text-white fw-bold">No</th>
          <th class="text-white fw-bold">Nama</th>
          <th class="text-white fw-bold">Kode Pegawai</th>
          <th class="text-white fw-bold">Laptop</th>
          <th class="text-white fw-bold">Tanggal Pinjam</th>
          <th class="text-white fw-bold">Tanggal Selesai</th>
          <th class="text-white fw-bold">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peminjams as $i => $p)
        <tr style="background-color: rgba(20,162,186,0.1); transition:all 0.3s ease;"
            onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'"
            onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'">
          <td class="text-white fw-bold">{{ $peminjams->firstItem() + $i }}</td>
          <td class="text-white fw-bold">{{ $p->nama }}</td>
          <td class="text-white fw-bold">{{ $p->department ?? '-' }}</td>
          <td class="text-white fw-bold">{{ $p->laptop ? $p->laptop->merek.' '.$p->laptop->tipe : '-' }}</td>
          <td class="text-white fw-bold">{{ $p->tanggal_mulai }}</td>
          <td class="text-white fw-bold">{{ $p->tanggal_selesai }}</td>
          <td>
            <form action="{{ route('peminjaman.selesai', $p->id) }}" method="POST" class="selesai-form">
              @csrf
              @method('DELETE')
              <button type="button" class="btn-table" 
                onclick="openSelesaiModal(this, '{{ $p->nama }}', '{{ $p->laptop ? $p->laptop->merek.' '.$p->laptop->tipe : '-' }}', '{{ $p->tanggal_mulai }}')">
                Selesai
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-white fw-bold">Belum ada data peminjam</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
    <div style="color: #fff; font-weight: bold;">
      Menampilkan {{ $peminjams->firstItem() ?? 0 }} - {{ $peminjams->lastItem() ?? 0 }} dari {{ $peminjams->total() }}
    </div>
    <div>
      {{ $peminjams->appends(['search'=>$search ?? '', 'per_page'=>$perPage ?? 10])->links() }}
    </div>
  </div>
</div>

<script>
  let currentForm = null;

  function openSelesaiModal(button, nama, laptop, tanggal) {
    currentForm = button.closest('form');
    document.getElementById('modalNama').innerText = nama;
    document.getElementById('modalLaptop').innerText = laptop;
    document.getElementById('modalTanggal').innerText = tanggal;
    document.getElementById('selesaiModal').style.display = 'block';
  }

  function closeSelesaiModal() {
    document.getElementById('selesaiModal').style.display = 'none';
    currentForm = null;
  }

  function confirmSelesai() {
    if (currentForm) {
      const formToSubmit = currentForm;
      closeSelesaiModal();
      formToSubmit.submit();
    }
  }

  function showToast(message, bgColor = "#10b981") {
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');
    toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)`;
    toastMsg.innerText = message;
    toast.style.display = 'block';
    setTimeout(() => toast.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => toast.style.display = 'none', 300);
    }, 3000);
  }

  document.getElementById('selesaiModal').addEventListener('click', function(e) {
    if (e.target === this) closeSelesaiModal();
  });
</script>

<style>
.btn-table {
    background-color: #0d9488;
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.25s ease;
  }
  .btn-table:hover {
    background-color: #0b7d73;
    transform: translateY(-1px);
  }
</style>
@endsection
