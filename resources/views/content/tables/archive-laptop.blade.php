@extends('layouts/contentNavbarLayout')

@section('title', 'Laptop Arsip')

@section('content')
@php
    $isDarkMode = session('theme') === 'dark';
    $headerBgColor = $isDarkMode ? '#125d72' : '#14a2ba';
    $cardBgColor = $isDarkMode ? 'rgba(20,162,186,0.1)' : 'rgba(20,162,186,0.5)';
    $borderColor = $isDarkMode ? 'rgba(18,93,114,0.5)' : 'rgba(20,162,186,0.3)';
@endphp

<div id="restoreModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px);">
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:linear-gradient(135deg, #14a2ba 0%, #0d7a8e 100%); padding:30px; border-radius:15px; box-shadow:0 10px 40px rgba(0,0,0,0.5); min-width:450px; border:2px solid rgba(255,255,255,0.2);">
    <h4 style="color:#fff; margin-bottom:20px; text-align:center; font-weight:bold; text-shadow:2px 2px 4px rgba(0,0,0,0.3);">Kembalikan Laptop</h4>
    <div style="background:rgba(255,255,255,0.1); padding:15px; border-radius:8px; margin-bottom:20px;">
      <p style="color:#fff; margin:0; font-size:14px; line-height:1.6;">
        <strong>Kode:</strong> <span id="modalKode"></span><br>
        <strong>Laptop:</strong> <span id="modalLaptop"></span><br>
        <strong>Keterangan:</strong> <span id="modalKeterangan"></span>
      </p>
    </div>
    <p style="color:#fff; margin-bottom:20px; opacity:0.9; text-align:center;">Apakah Anda yakin ingin mengembalikan laptop ini dari arsip?</p>
    <div style="display:flex; gap:10px; justify-content:center;">
      <button onclick="closeRestoreModal()" class="btn btn-light" style="font-weight:bold; padding:8px 20px;">Batal</button>
      <button onclick="confirmRestore()" class="btn btn-success" style="font-weight:bold; padding:8px 20px;">Ya, Kembalikan</button>
    </div>
  </div>
</div>

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

<div class="card mb-4" style="background-color: {{ $cardBgColor }}; backdrop-filter: blur(10px); border:1px solid {{ $borderColor }};">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: rgba(20,162,186,0.5); border-bottom:1px solid {{ $borderColor }};">
        <h5 class="text-white fw-bold mb-0">Laptop Diarsip</h5>
        <form method="GET" class="d-flex gap-2">
            <select name="per_page" class="form-select" style="width:auto; background-color: rgba(255,255,255,0.9); border:1px solid {{ $headerBgColor }}; color:#000;">
                @foreach([10,25,50,100] as $size)
                    <option value="{{ $size }}" {{ request('per_page',10) == $size ? 'selected' : '' }}>{{ $size }} / halaman</option>
                @endforeach
            </select>
            <input type="text" name="search" class="form-control" placeholder="Cari Laptop.." value="{{ request('search') }}" style="background-color: rgba(255,255,255,0.9); border:1px solid {{ $headerBgColor }}; color:#000;">
            <button type="submit" class="btn btn-light">Cari</button>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table class="table table-bordered mb-0" style="background-color: transparent;">
            <thead style="background-color: rgba(20,162,186,0.5);">
                <tr>
                    <th class="text-white fw-bold">No</th>
                    <th class="text-white fw-bold">Kode Laptop</th>
                    <th class="text-white fw-bold">Merek - Tipe</th>
                    <th class="text-white fw-bold">Keterangan</th>
                    <th class="text-white fw-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laptops as $index => $laptop)
                    <tr style="background-color: rgba(20,162,186,0.1); transition: all 0.3s ease;"
                        onmouseover="this.style.backgroundColor='rgba(20,162,186,0.25)'"
                        onmouseout="this.style.backgroundColor='rgba(20,162,186,0.1)'">
                        <td class="fw-bold text-white">{{ $laptops->firstItem() + $index }}</td>
                        <td class="fw-bold text-white">{{ $laptop->kode }}</td>
                        <td class="fw-bold text-white">{{ $laptop->merek }} {{ $laptop->tipe }}</td>
                        <td class="fw-bold text-white">{{ $laptop->keterangan }}</td>
                        <td>
                            <form action="{{ route('laptop.restore', $laptop->id) }}" method="POST" class="restore-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" class="btn btn-success btn-sm"
                                    onclick="openRestoreModal(this, '{{ $laptop->kode }}', '{{ $laptop->merek }} {{ $laptop->tipe }}', '{{ $laptop->keterangan }}')">
                                    Kembalikan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="fw-bold text-white text-center">Belum ada data laptop</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if ($laptops->total() > 0)
    <div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">
        <span class="fw-bold text-white">
            Menampilkan {{ $laptops->firstItem() }} - {{ $laptops->lastItem() }} dari {{ $laptops->total() }} data
        </span>
        <div>
            {{ $laptops->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>

<script>
  let currentForm = null;

  function openRestoreModal(button, kode, laptop, keterangan) {
    currentForm = button.closest('form');
    document.getElementById('modalKode').innerText = kode;
    document.getElementById('modalLaptop').innerText = laptop;
    document.getElementById('modalKeterangan').innerText = keterangan;
    document.getElementById('restoreModal').style.display = 'block';
  }

  function closeRestoreModal() {
    document.getElementById('restoreModal').style.display = 'none';
    currentForm = null;
  }

  function confirmRestore() {
    if (currentForm) {
      const formToSubmit = currentForm;
      closeRestoreModal();
      formToSubmit.submit();
    }
  }

  function showToast(message, bgColor = "#10b981") {
    const toast = document.getElementById('toastNotification');
    const toastMsg = document.getElementById('toastMessage');
    
    toast.style.background = `linear-gradient(135deg, ${bgColor} 0%, ${bgColor}dd 100%)`;
    toastMsg.innerText = message;
    toast.style.display = 'block';
    
    setTimeout(() => {
      toast.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
      toast.style.transform = 'translateX(400px)';
      setTimeout(() => {
        toast.style.display = 'none';
      }, 300);
    }, 3000);
  }

  document.getElementById('restoreModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeRestoreModal();
    }
  });
</script>
@endsection