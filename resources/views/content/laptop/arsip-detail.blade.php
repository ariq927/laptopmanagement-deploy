@extends('layouts/contentNavbarLayout')

@section('title', 'Detail Laptop Arsip - ' . $laptop->kode)

{{-- HANYA CSS SAJA YANG PAKAI vendor-css --}}
@section('vendor-css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endsection

@section('content')
@php
    $isDarkMode = session('theme') === 'dark';
    $cardBg = $isDarkMode ? 'rgba(20,162,186,0.08)' : 'rgba(20,162,186,0.15)';
    $borderColor = $isDarkMode ? 'rgba(18,93,114,0.3)' : 'rgba(20,162,186,0.2)';
@endphp

<style>
    .btn-minimal {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 16px;
        font-weight: 500;
        font-size: 0.875rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: 1px solid transparent;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-back {
        background: transparent;
        color: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.3);
    }

    .btn-edit-minimal {
        background: transparent;
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.3);
        padding: 6px 14px;
        font-size: 0.813rem;
    }

    .btn-edit-minimal:hover {
        background: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.5);
    }

    .btn-cancel-minimal {
        background: rgba(255, 59, 48, 0.15);
        color: #ff6b6b;
        border: 1px solid rgba(255, 59, 48, 0.3);
    }

    .btn-cancel-minimal:hover {
        background: rgba(255, 59, 48, 0.25);
        border-color: rgba(255, 59, 48, 0.5);
    }

    .btn-save-minimal {
        background: #4caf50;
        color: white;
        border: 1px solid #4caf50;
    }

    .btn-save-minimal:hover {
        background: #43a047;
        border-color: #43a047;
    }

    .btn-restore-minimal {
        background: #00bcd4;
        color: white;
        border: 1px solid #00bcd4;
        padding: 10px 24px;
    }

    .btn-restore-minimal:hover {
        background: #00acc1;
        border-color: #00acc1;
    }

    .info-table {
        width: 100%;
    }

    .info-table tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table th {
        padding: 12px 0;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.875rem;
        width: 35%;
    }

    .info-table td {
        padding: 12px 0;
        color: rgba(255, 255, 255, 0.95);
        font-size: 0.875rem;
    }

    .keterangan-box {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 16px;
        min-height: 100px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .keterangan-edit-box textarea {
        background: rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.95);
        border-radius: 8px;
        resize: vertical;
        font-size: 0.875rem;
        padding: 12px;
        width: 100%;
    }

    .keterangan-edit-box textarea:focus {
        outline: none;
        border-color: #00bcd4;
        background: rgba(0, 0, 0, 0.2);
        box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
    }

    .section-divider {
        border: none;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin: 24px 0;
    }

    .section-title {
        font-size: 0.938rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.95);
        margin-bottom: 0;
    }

    .card-header-minimal {
        background: linear-gradient(135deg, rgba(20, 162, 186, 0.4), rgba(13, 110, 130, 0.5));
        padding: 16px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card-title-minimal {
        font-size: 1.125rem;
        font-weight: 600;
        color: white;
        margin: 0;
    }
</style>

<div class="card mb-4" style="background-color: {{ $cardBg }}; backdrop-filter: blur(10px); border: 1px solid {{ $borderColor }}; border-radius: 12px; overflow: hidden;">
    <div class="card-header-minimal d-flex justify-content-between align-items-center">
        <h5 class="card-title-minimal">Detail Laptop Arsip</h5>
        <a href="{{ url()->previous() }}" class="btn-minimal btn-back">
            ← Kembali
        </a>
    </div>

    <div class="card-body" style="padding: 24px;">
        <!-- Informasi Laptop -->
        <div class="mb-4">
            <table class="info-table">
                <tr>
                    <th>Kode Laptop</th>
                    <td><strong>{{ $laptop->kode }}</strong></td>
                </tr>
                <tr>
                    <th>Merek & Tipe</th>
                    <td><strong>{{ $laptop->merek }} {{ $laptop->tipe }}</strong></td>
                </tr>
                <tr>
                    <th>Spesifikasi</th>
                    <td>{{ $laptop->spesifikasi }}</td>
                </tr>
                <tr>
                    <th>Dibuat</th>
                    <td>{{ $laptop->created_at->format('d M Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Diarsipkan</th>
                    <td>{{ $laptop->updated_at->format('d M Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <hr class="section-divider">

        <!-- Keterangan Arsip -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="section-title">Keterangan Arsip</h6>
                <button id="btn-edit-ket" class="btn-minimal btn-edit-minimal">
                    ✏ Edit
                </button>
            </div>

            <div id="keterangan-display" class="keterangan-box">
                @if($laptop->keterangan)
                    {!! nl2br(e($laptop->keterangan)) !!}
                @else
                    <em style="color: rgba(255, 255, 255, 0.5);">Tidak ada keterangan</em>
                @endif
            </div>

            <div id="keterangan-edit" class="keterangan-edit-box" style="display: none;">
                <form action="{{ route('laptop.update-keterangan', $laptop->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="keterangan" rows="5" placeholder="Tulis keterangan arsip...">{{ old('keterangan', $laptop->keterangan) }}</textarea>
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="button" id="btn-cancel-edit" class="btn-minimal btn-cancel-minimal">
                            Batal
                        </button>
                        <button type="submit" class="btn-minimal btn-save-minimal">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <hr class="section-divider">

        <!-- Tombol Kembalikan -->
        <div class="text-center">
            <button type="button" id="btn-restore-confirm" class="btn-minimal btn-restore-minimal">
                ↻ Kembalikan ke In Stock
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT SWEETALERT2 + LOGIC DI SINI (PASTI TERMUAT) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('btn-edit-ket').addEventListener('click', function() {
        document.getElementById('keterangan-display').style.display = 'none';
        document.getElementById('keterangan-edit').style.display = 'block';
        this.style.display = 'none';
    });

    document.getElementById('btn-cancel-edit').addEventListener('click', function() {
        document.getElementById('keterangan-display').style.display = 'block';
        document.getElementById('keterangan-edit').style.display = 'none';
        document.getElementById('btn-edit-ket').style.display = 'inline-flex';
    });

    document.getElementById('btn-restore-confirm').addEventListener('click', function() {
        Swal.fire({
            title: 'Kembalikan Laptop?',
            text: "Status akan diubah menjadi 'In Stock'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kembalikan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                confirmButton: 'btn-minimal btn-restore-minimal',
                cancelButton: 'btn-minimal btn-cancel-minimal'
            },
            buttonsStyling: false,
            didOpen: () => {
                const actions = Swal.getActions();
                actions.style.gap = '12px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('laptop.restore', $laptop->id) }}';
                form.innerHTML = '@csrf @method("PATCH")';
                document.body.appendChild(form);
                form.submit();
            }
        });
    });
</script>
@endsection