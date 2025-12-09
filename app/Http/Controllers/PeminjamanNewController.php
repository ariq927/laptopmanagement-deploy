<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\LaptopData;
use App\Models\DataPeminjam;
use App\Models\HistoriPeminjaman;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                'status_peminjaman' => 'active', 
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
        $statusFilter = $request->input('status_filter', 'all');

        // PERBAIKAN: Group by laptop_id, bukan department
        // Karena yang penting adalah siapa yang sedang meminjam laptop tersebut saat ini
        $subquery = DB::table('data_peminjam as dp')
            ->leftJoin('laptop_data as ld', 'dp.laptop_id', '=', 'ld.id')
            ->select('dp.laptop_id'); // Ubah dari department ke laptop_id

        if ($statusFilter === 'active') {
            $subquery->where('dp.status_peminjaman', 'active')
                ->where('ld.status', 'in use');
        } elseif ($statusFilter === 'expired') {
            $subquery->where(function($q) {
                $q->where('dp.status_peminjaman', 'expired');
            });
        }

        $subquery = $subquery
            ->selectRaw('MAX(CASE 
                WHEN dp.status_peminjaman = "active" AND ld.status = "in use" 
                THEN dp.id 
            END) as active_id')
            ->selectRaw('MAX(dp.id) as max_id')
            ->groupBy('dp.laptop_id'); // Ubah dari department ke laptop_id

        $query = DataPeminjam::with('laptop')
            ->joinSub($subquery, 'latest', function($join) {
                $join->on('data_peminjam.laptop_id', '=', 'latest.laptop_id') // Ubah dari department ke laptop_id
                    ->on('data_peminjam.id', '=', DB::raw('COALESCE(latest.active_id, latest.max_id)'));
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('data_peminjam.nama', 'like', "%$search%")
                ->orWhere('data_peminjam.department', 'like', "%$search%")
                ->orWhereHas('laptop', function($laptopQuery) use ($search) {
                    $laptopQuery->where('merek', 'like', "%$search%")
                                ->orWhere('tipe', 'like', "%$search%")
                                ->orWhere('kode', 'like', "%$search%");
                });
            });
        }

        $peminjams = $query->orderByDesc('data_peminjam.created_at')
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

            $pinjam->update(['status_peminjaman' => 'expired']);
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

            $pinjam->update(['status_peminjaman' => 'expired']);
        });

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $pinjam = DataPeminjam::findOrFail($id);
            $statusBaru = $request->input('status');
            $keterangan = $request->input('keterangan'); 

            if (!in_array($statusBaru, ['in stock', 'diarsip'])) {
                return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
            }

            if ($statusBaru === 'diarsip' && empty($keterangan)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Keterangan wajib diisi untuk pengarsipan.'
                ], 400);
            }

            DB::transaction(function () use ($pinjam, $statusBaru, $keterangan) {
                if ($pinjam->laptop) {
                    $updateData = ['status' => $statusBaru];
                    
                    if ($statusBaru === 'diarsip' && $keterangan) {
                        $updateData['keterangan'] = $keterangan;
                    }
                    
                    $pinjam->laptop->update($updateData);
                }

                HistoriPeminjaman::where('laptop_id', $pinjam->laptop_id)
                    ->where('department', $pinjam->department)
                    ->where('status', 'aktif')
                    ->update([
                        'status' => 'selesai',
                        'tanggal_selesai' => now(),
                    ]);

                $pinjam->update([
                    'status_peminjaman' => 'expired',
                    'tanggal_selesai' => $pinjam->tanggal_selesai ?? now()
                ]);

                // PERBAIKAN: Cek berdasarkan laptop_id, bukan department
                $masihAdaAktif = DataPeminjam::where('laptop_id', $pinjam->laptop_id)
                    ->where('status_peminjaman', 'active')
                    ->whereHas('laptop', function($q) {
                        $q->where('status', 'in use');
                    })
                    ->exists();

                if (!$masihAdaAktif) {
                    DataPeminjam::where('laptop_id', $pinjam->laptop_id)
                        ->where('status_peminjaman', 'active')
                        ->update(['status_peminjaman' => 'expired']);
                }
            });

            $message = $statusBaru === 'diarsip' 
                ? 'Laptop berhasil diarsipkan' 
                : 'Status laptop berhasil diubah menjadi ' . $statusBaru;

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $statusBaru,
            ]);

        } catch (\Throwable $e) {
            Log::error('updateStatus error: ' . $e->getMessage(), [
                'id' => $id,
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showDetail($id)
    {
        $peminjam = DataPeminjam::with('laptop')->findOrFail($id);

        // PERBAIKAN: Ambil riwayat berdasarkan laptop_id, bukan department
        // Karena yang ingin dilihat adalah riwayat peminjaman laptop tersebut
        $riwayat = HistoriPeminjaman::with('laptop')
            ->where('laptop_id', $peminjam->laptop_id) // Ubah dari department ke laptop_id
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('content.tables.detail-peminjaman', compact('peminjam', 'riwayat'));
    }

    public function updateStatusHistori(Request $request, $historyId)
    {
        try {
            $riwayat = HistoriPeminjaman::findOrFail($historyId);
            $statusBaru = $request->input('status');
            $keterangan = $request->input('keterangan');

            if (!in_array($statusBaru, ['in stock', 'diarsip'])) {
                return response()->json(['success' => false, 'message' => 'Status tidak valid.'], 400);
            }

            if ($statusBaru === 'diarsip' && empty($keterangan)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keterangan wajib diisi untuk pengarsipan.'
                ], 400);
            }

            DB::transaction(function () use ($riwayat, $statusBaru, $keterangan) {
                if ($riwayat->laptop) {
                    $updateData = ['status' => $statusBaru];

                    if ($statusBaru === 'diarsip' && $keterangan) {
                        $updateData['keterangan'] = $keterangan;
                    }

                    $riwayat->laptop->update($updateData);
                }

                $riwayat->update([
                    'status' => 'selesai',
                    'tanggal_selesai' => now(),
                ]);

                DataPeminjam::where('laptop_id', $riwayat->laptop_id)
                    ->where('department', $riwayat->department)
                    ->where('status_peminjaman', 'active')
                    ->update([
                        'status_peminjaman' => 'expired',
                        'tanggal_selesai' => now()
                    ]);
            });

            $message = $statusBaru === 'diarsip' 
                ? 'Laptop berhasil diarsipkan'
                : 'Status laptop berhasil diubah menjadi ' . $statusBaru;

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);

        } catch (\Throwable $e) {
            Log::error('updateStatusHistori error: ' . $e->getMessage(), [
                'historyId' => $historyId,
                'payload' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}