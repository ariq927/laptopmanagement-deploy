@extends('layouts.contentNavbarLayout')

@section('title', 'Detail Peminjaman Laptop')

@section('content')
<div class="container py-4">
  <div class="card shadow-sm border-0 rounded-4" style="background-color: rgba(255,255,255,0.85); backdrop-filter: blur(8px);">
    <div class="card-body">
      <h4 class="fw-bold mb-3">Detail Peminjaman</h4>
      <hr>

      {{-- Informasi Peminjam --}}
      <div class="row mb-4">
        <div class="col-md-6">
          <p><strong>Nama Pegawai:</strong> {{ $peminjam->nama }}</p>
          <p><strong>Kode Pegawai:</strong> {{ $peminjam->department }}</p>
          <p><strong>No. Telepon:</strong> {{ $peminjam->nomor_telepon ?? '-' }}</p>
        </div>
        <div class="col-md-6">
          <p><strong>Tanggal Mulai:</strong> {{ $peminjam->tanggal_mulai ? \Carbon\Carbon::parse($peminjam->tanggal_mulai)->format('d M Y') : '-' }}</p>
          <p><strong>Tanggal Selesai:</strong> {{ $peminjam->tanggal_selesai ? \Carbon\Carbon::parse($peminjam->tanggal_selesai)->format('d M Y') : '-' }}</p>
          <p><strong>Status:</strong>
            @if($peminjam->status === 'in use')
              <span class="badge bg-warning text-dark">Sedang Digunakan</span>
            @elseif($peminjam->status === 'in stock')
              <span class="badge bg-success">Tersedia</span>
            @elseif($peminjam->status === 'diarsip')
              <span class="badge bg-secondary">Diarsipkan</span>
            @else
              <span class="badge bg-light text-dark">{{ ucfirst($peminjam->status) }}</span>
            @endif
          </p>
        </div>
      </div>

      {{-- Informasi Laptop --}}
      <div class="card mb-4 border-0 shadow-sm" style="background-color: rgba(240,248,255,0.8);">
        <div class="card-body">
          <h5 class="fw-semibold mb-3">Informasi Laptop</h5>
          @if($peminjam->laptop)
            <p><strong>Merek:</strong> {{ $peminjam->laptop->merek }}</p>
            <p><strong>Model:</strong> {{ $peminjam->laptop->tipe ?? '-' }}</p>
            <p><strong>Kode Laptop:</strong> {{ $peminjam->laptop->kode ?? '-' }}</p>
            <p><strong>Status Laptop:</strong>
              @if($peminjam->laptop->status === 'in use')
                <span class="badge bg-warning text-dark">Sedang Digunakan</span>
              @elseif($peminjam->laptop->status === 'in stock')
                <span class="badge bg-success">Tersedia</span>
              @elseif($peminjam->laptop->status === 'diarsip')
                <span class="badge bg-secondary">Diarsipkan</span>
              @else
                <span class="badge bg-light text-dark">{{ ucfirst($peminjam->laptop->status) }}</span>
              @endif
            </p>
          @else
            <p class="text-muted fst-italic">Tidak ada data laptop terkait.</p>
          @endif
        </div>
      </div>

      {{-- Riwayat Peminjaman --}}
      <div class="card border-0 shadow-sm" style="background-color: rgba(248,249,250,0.9);">
        <div class="card-body">
          <h5 class="fw-semibold mb-3">🕓 Riwayat Peminjaman {{ $peminjam->nama }}  </h5>

          @if($riwayat->isEmpty())
            <p class="text-muted fst-italic">Belum ada riwayat peminjaman untuk user ini.</p>
          @else
            <div class="table-responsive">
              <table class="table table-striped align-middle">
                <thead class="table-light">
                  <tr>
                    <th>No</th>
                    <th>Laptop</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($riwayat as $index => $r)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $r->laptop->merek ?? '-' }} {{ $r->laptop->tipe ?? '-' }}</td>
                      <td>{{ \Carbon\Carbon::parse($r->tanggal_mulai)->format('d M Y') }}</td>
                      <td>{{ $r->tanggal_selesai ? \Carbon\Carbon::parse($r->tanggal_selesai)->format('d M Y') : '-' }}</td>
                      <td>
                        @if($r->status === 'aktif')
                          <span class="badge bg-warning text-dark">Aktif</span>
                        @elseif($r->status === 'selesai')
                          <span class="badge bg-success">Selesai</span>
                        @else
                          <span class="badge bg-secondary">{{ ucfirst($r->status) }}</span>
                        @endif
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- Tombol Kembali --}}
      <div class="mt-4 text-end">
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary px-4 rounded-pill">
          ← Kembali
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
