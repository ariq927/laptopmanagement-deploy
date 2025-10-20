@extends('layouts/contentNavbarLayout')

@section('title', 'Daftar Peminjam')

@section('content')
<div class="card" id="peminjamCard" 
  style="background-color: rgba(20, 162, 186, 0.5); backdrop-filter: blur(10px); border: 1px solid rgba(20, 162, 186, 0.3);">

  <div class="card-header d-flex justify-content-between align-items-center"
    style="background-color: #14a2ba; border-bottom: 1px solid rgba(20, 162, 186, 0.3);">
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
      <thead style="background-color: #14a2ba;">
        <tr>
          <th class="text-white fw-bold">No</th>
          <th class="text-white fw-bold">Nama</th>
          <th class="text-white fw-bold">Departemen</th>
          <th class="text-white fw-bold">Laptop</th>
          <th class="text-white fw-bold">Tanggal Pinjam</th>
          <th class="text-white fw-bold">Tanggal Selesai</th>
          <th class="text-white fw-bold">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($peminjams as $i => $p)
        <tr style="background-color: rgba(20,162,186,0.1)">
          <td class="text-white fw-bold">{{ $peminjams->firstItem() + $i }}</td>
          <td class="text-white fw-bold">{{ $p->nama }}</td>
          <td class="text-white fw-bold">{{ $p->department ?? '-' }}</td>
          <td class="text-white fw-bold">{{ $p->laptop ? $p->laptop->merek.' '.$p->laptop->tipe : '-' }}</td>
          <td class="text-white fw-bold">{{ $p->tanggal_mulai }}</td>
          <td class="text-white fw-bold">{{ $p->tanggal_selesai }}</td>
          <td>
            <form action="{{ route('peminjaman.selesai', $p->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menyelesaikan peminjaman ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm text-white fw-bold" style="background-color:#14a2ba;">Selesai</button>
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
@endsection
