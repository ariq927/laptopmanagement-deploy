<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class LaptopsExport implements FromView, WithTitle
{
    protected $peminjaman;

    public function __construct($peminjaman)
    {
        $this->peminjaman = $peminjaman;
    }

    public function view(): View
    {
        return view('content.reports.laptops_excel', [
            'laptops' => $this->peminjaman
        ]);
    }

    public function title(): string
    {
        return 'Laporan Peminjaman Laptop';
    }
}
