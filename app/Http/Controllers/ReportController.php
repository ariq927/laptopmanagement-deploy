<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriPeminjaman;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaptopsExport;
use Carbon\Carbon; 

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $filters = $request->only(['department', 'status', 'from', 'to', 'format']);

        $query = HistoriPeminjaman::query()
            ->with('laptop')
            ->whereColumn('tanggal_mulai', '<=', 'tanggal_selesai'); 

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (!empty($filters['status']) && $filters['status'] !== 'semua') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from']) && !empty($filters['to'])) {
            $from = $filters['from'];
            $to = $filters['to'];

            if (strtotime($from) > strtotime($to)) {
                return response('<h3 style="text-align:center; color:red">Tanggal mulai tidak boleh lebih besar dari tanggal selesai</h3>');
            }

            $query->where(function ($q) use ($from, $to) {
                $q->whereBetween('tanggal_mulai', [$from, $to])
                  ->orWhereBetween('tanggal_selesai', [$from, $to]);
            });
        }

        $peminjaman = $query->get();

        $peminjaman->map(function ($item) {
            if ($item->tanggal_mulai && $item->tanggal_selesai) {
                $mulai = Carbon::parse($item->tanggal_mulai);
                $selesai = Carbon::parse($item->tanggal_selesai);
                $item->durasi_peminjaman = $mulai->diffInDays($selesai);
            } else {
                $item->durasi_peminjaman = '-';
            }
            return $item;
        });

        if ($peminjaman->isEmpty()) {
            return response('<h3 style="text-align:center; color:red">Tidak ada data untuk periode tersebut</h3>');
        }

        if (($filters['format'] ?? '') === 'pdf') {
            $pdf = Pdf::loadView('content.reports.laptops_pdf', ['laptops' => $peminjaman]);
            return $pdf->stream('laporan_peminjaman.pdf');
        }

        return Excel::download(new LaptopsExport($peminjaman), 'laporan_peminjaman.xlsx');
    }
}
