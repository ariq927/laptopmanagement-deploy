@extends('layouts/contentNavbarLayout')

@section('title', 'Laporan Peminjaman Laptop')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0" style="--card-bg: #fff; --header-bg: #14a2ba; --text-color: #333;">
        <style>
            :root[data-theme="dark"] .card {
                --card-bg: #2b2b3c !important;
                --header-bg: #125d72 !important;
                --text-color: #f1f1f1 !important;
            }

            body.dark-mode .card {
                --card-bg: #2b2b3c !important;
                --header-bg: #125d72 !important;
                --text-color: #f1f1f1 !important;
            }

            .card {
                background-color: var(--card-bg);
                color: var(--text-color);
                transition: all 0.3s ease;
            }

            .card-header {
                background-color: var(--header-bg) !important;
                color: #fff !important;
                border-bottom: 2px solid rgba(20, 162, 186, 0.3);
            }

            .card-header h5 {
                color: #fff !important;
                text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            }

            .form-label {
                color: var(--text-color) !important;
                font-weight: 600 !important;
            }

            .form-control,
            .form-select {
                background-color: var(--card-bg) !important;
                color: var(--text-color) !important;
                border: 1px solid rgba(20, 162, 186, 0.3) !important;
                transition: all 0.3s ease;
            }

            .form-control:focus,
            .form-select:focus {
                background-color: var(--card-bg) !important;
                color: var(--text-color) !important;
                border-color: var(--header-bg) !important;
                box-shadow: 0 0 0 0.2rem rgba(20, 162, 186, 0.25);
            }

            .form-control::placeholder {
                color: rgba(100, 100, 100, 0.7) !important;
            }

            .btn-primary {
                background-color: var(--header-bg) !important;
                border-color: var(--header-bg) !important;
                color: #fff !important;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #0f8399 !important;
                border-color: #0f8399 !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(20, 162, 186, 0.4);
            }

            .btn-outline-secondary {
                color: var(--text-color) !important;
                border-color: rgba(20, 162, 186, 0.4) !important;
                transition: all 0.3s ease;
            }

            .btn-outline-secondary:hover {
                background-color: rgba(20, 162, 186, 0.1) !important;
                border-color: var(--header-bg) !important;
                color: var(--header-bg) !important;
            }

            .alert {
                background-color: rgba(20, 162, 186, 0.1) !important;
                border-color: rgba(20, 162, 186, 0.3) !important;
                color: var(--text-color) !important;
            }

            .alert-warning {
                background-color: rgba(255, 193, 7, 0.1) !important;
                border-color: rgba(255, 193, 7, 0.3) !important;
            }

            /* Dark mode specific */
            :root[data-theme="dark"] .form-control,
            :root[data-theme="dark"] .form-select,
            body.dark-mode .form-control,
            body.dark-mode .form-select {
                background-color: #33334d !important;
                color: #f1f1f1 !important;
                border-color: #444 !important;
            }

            :root[data-theme="dark"] .form-control::placeholder,
            body.dark-mode .form-control::placeholder {
                color: #aaa !important;
            }

            :root[data-theme="dark"] .form-control:focus,
            :root[data-theme="dark"] .form-select:focus,
            body.dark-mode .form-control:focus,
            body.dark-mode .form-select:focus {
                background-color: #3a3a50 !important;
                border-color: #125d72 !important;
                box-shadow: 0 0 0 0.2rem rgba(18, 93, 114, 0.25);
            }
        </style>

        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">📄 Laporan Peminjaman Laptop</h5>
        </div>

        <div class="card-body">
            @if(session('error'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bx bx-info-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('laporan.export') }}" method="get" class="row g-4 mt-4">
                <div class="col-md-6">
                    <label for="department" class="form-label fw-semibold">Department</label>
                    <input type="text" name="department" id="department" class="form-control py-2" placeholder="Masukkan nama departemen">
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status</label>
                    <select name="status" id="status" class="form-select py-2">
                        <option value="">Semua</option>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="from" class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control py-2">
                </div>

                <div class="col-md-6">
                    <label for="to" class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control py-2">
                </div>

                <div class="col-md-6">
                    <label for="format" class="form-label fw-semibold">Format Laporan</label>
                    <select name="format" id="format" class="form-select py-2">
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="pdf">PDF (.pdf)</option>
                    </select>
                </div>

                <div class="col-md-12 text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bx bx-download"></i> Generate Laporan
                    </button>
                    <button type="reset" class="btn btn-outline-secondary ms-2 px-4 py-2">
                        <i class="bx bx-reset"></i> Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection