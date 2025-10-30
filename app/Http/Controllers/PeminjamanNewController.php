<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LaptopData;
use App\Models\DataPeminjam;
use App\Models\HistoriPeminjaman;
use Illuminate\Support\Facades\DB;

class PeminjamanNewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $user = auth()->user();

        return view('content.peminjaman.form', compact('laptop', 'user'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'laptop_id' => 'required|exists:laptop_data,id',
            'nama' => 'required|string',
            'department' => 'required|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'nomor_telepon' => 'required|string',
        ]);

        DB::transaction(function () use ($validated, $user) {
            $laptop = LaptopData::findOrFail($validated['laptop_id']);

            DataPeminjam::create([
                'user_id' => $user->id,
                'laptop_id' => $laptop->id,
                'nama' => $validated['nama'],
                'department' => $validated['department'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'nomor_telepon' => $validated['nomor_telepon'],
            ]);

            HistoriPeminjaman::create([
                'user_id' => $user->id,
                'laptop_id' => $laptop->id,
                'nama' => $validated['nama'],
                'department' => $validated['department'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'nomor_telepon' => $validated['nomor_telepon'],
                'status' => 'aktif',
            ]);

            $laptop->update(['status' => 'in use']);
        });

        return redirect()->route('laptop.index')->with('success', 'Peminjaman berhasil disimpan!');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $peminjams = DataPeminjam::with('laptop')
            ->select('data_peminjam.*')
            ->join(
                DB::raw('(SELECT department, MAX(id) AS max_id FROM data_peminjam GROUP BY department) AS latest'),
                'data_peminjam.id',
                '=',
                'latest.max_id'
            )
            ->where(function ($query) use ($search) {
                $query->where('data_peminjam.nama', 'like', "%$search%")
                    ->orWhere('data_peminjam.department', 'like', "%$search%");
            })
            ->orderByDesc('data_peminjam.created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('content.tables.tables-basic', compact('peminjams', 'search', 'perPage'));
    }


    public function selesai($id)
    {
        $pinjam = DataPeminjam::findOrFail($id);

        DB::transaction(function () use ($pinjam) {
            if ($pinjam->laptop) {
                $pinjam->laptop->status = 'in stock';
                $pinjam->laptop->save();
            }

            HistoriPeminjaman::where('laptop_id', $pinjam->laptop_id)
                ->where('user_id', $pinjam->user_id)
                ->where('status', 'aktif')
                ->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);

            $pinjam->delete();
        });

        return redirect()->back()->with('success', 'Peminjaman selesai. Status laptop berubah menjadi in stock.');
    }

    public function cari(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $peminjams = DataPeminjam::when($search, function ($query, $search) {
                return $query->where('nama', 'like', "%{$search}%")
                            ->orWhere('laptop_id', 'like', "%{$search}%")
                            ->orWhere('department', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();

        $user = auth()->user();

        return view('content.tables.tables-basic', compact('peminjams', 'perPage', 'user'));
    }

    // API
    public function apiIndex(Request $request)
    {
        $query = DataPeminjam::with('laptop');

        if ($request->filled('search')) {
            $query->where('nama', 'like', "%{$request->search}%")
                ->orWhere('department', 'like', "%{$request->search}%");
        }

        $perPage = $request->per_page ?? 10;
        return $query->paginate($perPage);
    }

    public function apiSelesai($id)
    {
        $pinjam = DataPeminjam::findOrFail($id);

        DB::transaction(function () use ($pinjam) {
            if ($pinjam->laptop) {
                $pinjam->laptop->status = 'in stock';
                $pinjam->laptop->save();
            }

            HistoriPeminjaman::where('laptop_id', $pinjam->laptop_id)
                ->where('user_id', $pinjam->user_id)
                ->where('status', 'aktif')
                ->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);

            $pinjam->delete();
        });

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
{
    try {
        $pinjam = DataPeminjam::findOrFail($id);
        $statusBaru = $request->input('status');

        if (!in_array($statusBaru, ['in stock', 'diarsip'])) {
            return response()->json(['success' => false, 'error' => 'Status tidak valid.'], 400);
        }

        $hasilStatus = $statusBaru;
        $masihAktif = false;

        DB::transaction(function () use ($pinjam, $statusBaru, &$hasilStatus, &$masihAktif) {
            // ubah status laptop
            if ($pinjam->laptop) {
                $pinjam->laptop->update(['status' => $statusBaru]);
            }

            // ubah histori
            HistoriPeminjaman::where('laptop_id', $pinjam->laptop_id)
                ->where('department', $pinjam->department)
                ->where('status', 'aktif')
                ->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);

            // hapus data aktif
            $pinjam->delete();

            // cek apakah masih ada aktif lain di departemen sama
            $masihAktif = DataPeminjam::where('department', $pinjam->department)
                ->whereHas('laptop', function ($q) {
                    $q->where('status', 'in use');
                })
                ->exists();

            if (!$masihAktif) {
                $hasilStatus = 'expired';
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Status laptop berhasil diubah menjadi ' . $hasilStatus,
            'status' => $hasilStatus,
            'adaAktif' => $masihAktif,
        ]);
    } catch (\Throwable $e) {
        Log::error('updateStatus error: '.$e->getMessage(), [
            'id' => $id,
            'payload' => $request->all(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ], 500);
    }
}

    public function showDetail($id)
    {
        $peminjam = DataPeminjam::with('laptop')->findOrFail($id);

        $riwayat = HistoriPeminjaman::with('laptop')
            ->where('department', $peminjam->department)
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('content.tables.detail-peminjaman', compact('peminjam', 'riwayat'));
    }

}
