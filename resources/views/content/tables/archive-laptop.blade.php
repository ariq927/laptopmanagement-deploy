{{-- resources/views/content/tables/archive-laptop.blade.php --}}
@extends('layouts/contentNavbarLayout')

@section('title', 'Laptop Arsip')

@section('content')
@php
    $isDarkMode = session('theme') === 'dark';
    $headerBgColor = $isDarkMode ? '#125d72' : '#14a2ba';
    $cardBgColor = $isDarkMode ? 'rgba(20,162,186,0.1)' : 'rgba(20,162,186,0.5)';
    $borderColor = $isDarkMode ? 'rgba(18,93,114,0.5)' : 'rgba(20,162,186,0.3)';
@endphp

<div class="card mb-4" style="background-color: {{ $cardBgColor }}; backdrop-filter: blur(10px); border:1px solid {{ $borderColor }};">
    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: {{ $headerBgColor }}; border-bottom:1px solid {{ $borderColor }};">
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
            <thead style="background-color: {{ $headerBgColor }};">
                <tr>
                    <th class="text-white fw-bold">No</th>
                    <th class="text-white fw-bold">Merek - Tipe</th>
                    <th class="text-white fw-bold">Serial Number</th>
                    <th class="text-white fw-bold">Spesifikasi</th>
                    <th class="text-white fw-bold">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($laptops as $index => $laptop)
                    <tr style="background-color: rgba(20,162,186,0.1); transition: all 0.3s ease;">
                        <td class="fw-bold text-white">{{ $laptops->firstItem() + $index }}</td>
                        <td class="fw-bold text-white">{{ $laptop->merek }} {{ $laptop->tipe }}</td>
                        <td class="fw-bold text-white">{{ $laptop->serial_number }}</td>
                        <td class="fw-bold text-white">{{ $laptop->spesifikasi }}</td>
                        <td>
                            <form action="{{ route('laptop.restore', $laptop->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm"
                                    style="background-color: {{ $headerBgColor }}; border-color: {{ $headerBgColor }};"
                                    onmouseover="this.style.backgroundColor='{{ $isDarkMode ? '#0d4a5c' : '#0f8399' }}'; this.style.transform='scale(1.05)';"
                                    onmouseout="this.style.backgroundColor='{{ $headerBgColor }}'; this.style.transform='scale(1)';"
                                >
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
    <div class="d-flex justify-content-between align-items-center mt-3 px-3">
        <span class="fw-bold text-white">
            Menampilkan {{ $laptops->firstItem() }} - {{ $laptops->lastItem() }} dari {{ $laptops->total() }} data
        </span>
        <div>
            {{ $laptops->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection
