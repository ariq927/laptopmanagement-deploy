<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaptopData;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalLaptop = LaptopData::count();
        $laptopDipinjam = DB::table('data_peminjam')->count();
        $totalUser = DB::table('users')->count();

        return view('content.admin.dashboard', compact('totalLaptop', 'laptopDipinjam', 'totalUser'));
    }

    public function laptopIndex()
    {
        $laptops = LaptopData::all();
        return view('content.tables.tables-laptop', compact('laptops'));
    }

    public function summary()
    {
        $peminjaman = DB::table('peminjamans')->get();
        return view('content.admin.summary', compact('peminjaman'));
    }

    public function loanAnalytics()
    {
        $labels = ['Jan', 'Feb', 'Mar'];
        $values = [5, 8, 3];

        $totalLaptop = LaptopData::count();
        $laptopDipinjam = 0; 
        $totalUser = DB::table('users')->count();

        return view('content.admin.loan-analytics', compact('labels', 'values', 'totalLaptop', 'laptopDipinjam', 'totalUser'));
    }

    public function storeLaptop(Request $request)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255',
            'stok' => 'nullable|integer|min:1',
        ]);

        LaptopData::create([
            'merek' => $request->merek,
            'tipe' => $request->tipe,
            'spesifikasi' => $request->spesifikasi,
            'serial_number' => $request->serial_number,
            'stok' => $request->stok ?? 1,
        ]);

        return redirect()->back()->with('success', 'Laptop berhasil ditambahkan');
    }

    public function editLaptop($id)
    {
        $laptop = LaptopData::findOrFail($id); // cari data laptop berdasarkan id
        return view('content.tables.edit-laptop', compact('laptop'));
    }

    public function destroyLaptop($id)
        {
            $laptop = LaptopData::findOrFail($id);
            $laptop->delete();

            return redirect()->back()->with('success', 'Laptop berhasil dihapus');
        }

    public function updateLaptop(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:laptop_data,serial_number,' . $id,
            'spesifikasi' => 'nullable|string',
            'stok' => 'nullable|integer|min:1',
        ]);

        $laptop = LaptopData::findOrFail($id);

        $laptop->merek = $request->merek;
        $laptop->tipe = $request->tipe;
        $laptop->serial_number = $request->serial_number;
        $laptop->spesifikasi = $request->spesifikasi;
        $laptop->stok = $request->stok ?? 1;
        $laptop->save();

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil diperbarui!');

    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $laptops = LaptopData::when($search, function($query, $search) {
                            return $query->where('merek', 'like', "%{$search}%")
                                        ->orWhere('tipe', 'like', "%{$search}%")
                                        ->orWhere('spesifikasi', 'like', "%{$search}%")
                                        ->orWhere('serial_number', 'like', "%{$search}%");
                        })
                        ->paginate($perPage)
                        ->withQueryString();

        return view('content.tables.tables-laptop', compact('laptops', 'perPage'));
    }




    public function removeUserLoan($id)
    {
        DB::table('peminjamans')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Peminjaman user berhasil dihapus');
    }
    
}
