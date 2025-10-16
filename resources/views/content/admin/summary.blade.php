@extends('layouts/contentNavbarLayout')

@section('title', 'Admin Summary')

@section('content')
<div class="card">
  <div class="card-header">Ringkasan Peminjaman</div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>User</th>
          <th>Laptop</th>
          <th>Tanggal Pinjam</th>
          <th>Tanggal Kembali</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($peminjaman as $item)
        <tr>
          <td>{{ $item->user->name ?? '-' }}</td>
          <td>{{ $item->laptop->nama ?? '-' }}</td>
          <td>{{ $item->tanggal_pinjam }}</td>
          <td>{{ $item->tanggal_kembali ?? 'Belum kembali' }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="4" class="text-center">Belum ada data peminjaman</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
