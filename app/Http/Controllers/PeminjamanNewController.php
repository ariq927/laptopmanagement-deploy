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

        $laptop->update(['status' => 'dipinjam']);
    });

    return redirect()->route('laptop.index')->with('success', 'Peminjaman berhasil disimpan!');
}

    public function index(Request $request)
{
    $perPage = $request->input('per_page', 10);
    $search = $request->input('search', '');

    $peminjams = DataPeminjam::with('laptop')
        ->where(function($query) use ($search) {
            $query->where('nama', 'like', "%$search%")
                  ->orWhere('department', 'like', "%$search%");
        })
        ->latest()
        ->paginate($perPage)
        ->withQueryString();

    return view('content.tables.tables-basic', compact('peminjams', 'search', 'perPage'));
}


    public function selesai($id)
    {
        $pinjam = DataPeminjam::findOrFail($id);

        DB::transaction(function () use ($pinjam) {
            if ($pinjam->laptop) {
                $pinjam->laptop->status = 'tersedia';
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

        return redirect()->back()->with('success', 'Peminjaman selesai, laptop sekarang tersedia.');
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

        if ($pinjam->laptop) {
            $pinjam->laptop->status = 'tersedia';
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

        return response()->json(['success' => true]);
    }
}
