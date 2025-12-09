<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaptopData;
use App\Models\SoldLaptop;
use Cloudinary\Cloudinary;

class LaptopController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $laptops = LaptopData::when($search, function ($query, $search) {
                return $query->where('merek', 'like', "%{$search}%")
                             ->orWhere('tipe', 'like', "%{$search}%")
                             ->orWhere('spesifikasi', 'like', "%{$search}%")
                             ->orWhere('serial_number', 'like', "%{$search}%")
                             ->orWhere('status', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString();

        return view('content.tables.tables-laptop', compact('laptops'));
    }

    public function create()
    {
        return view('content.laptop.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'spesifikasi' => 'required|string',
            'status' => 'required|in:in stock,in use,diarsip',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'keterangan' => 'required_if:status,diarsip|nullable|string|max:500',
        ]);

        $tahun = date('y');
        $prefix = 'LAP-' . $tahun . '-';

        $lastLaptop = LaptopData::where('kode', 'like', $prefix . '%')
            ->orderBy('kode', 'desc')
            ->first();

        $newNumber = $lastLaptop
            ? str_pad(((int) substr($lastLaptop->kode, -3)) + 1, 3, '0', STR_PAD_LEFT)
            : '001';

        $kodeBaru = $prefix . $newNumber;

        $data = $request->only(['merek', 'tipe', 'spesifikasi', 'status', 'keterangan']);
        $data['kode'] = $kodeBaru;
        $data['stok'] = 1;

        if ($request->hasFile('foto')) {
            try {
                $cloudinary = new Cloudinary([
                    'cloud' => [
                        'cloud_name' => config('cloudinary.cloud_name'),
                        'api_key'    => config('cloudinary.api_key'),
                        'api_secret' => config('cloudinary.api_secret'),
                    ]
                ]);

                $uploadResult = $cloudinary->uploadApi()->upload(
                    $request->file('foto')->getRealPath(),
                    ['folder' => 'laptops', 'resource_type' => 'image']
                );

                if (isset($uploadResult['secure_url'])) {
                    $data['foto'] = $uploadResult['secure_url'];
                    $data['public_id'] = $uploadResult['public_id'];
                }
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['foto' => 'Gagal upload foto: ' . $e->getMessage()]);
            }
        }

        LaptopData::create($data);

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'status' => 'required|in:in stock,in use,diarsip',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'hapus_foto' => 'nullable|in:0,1',
            'keterangan' => 'required_if:status,diarsip|string|max:500'
        ], [
            'keterangan.required_if' => 'Keterangan wajib diisi untuk pengarsipan'
        ]);

        $laptop = LaptopData::findOrFail($id);
        $originalStatus = $laptop->status;

        $laptop->merek = $request->merek;
        $laptop->tipe = $request->tipe;
        $laptop->spesifikasi = $request->spesifikasi;
        $laptop->status = $request->status;

        if ($request->status === 'diarsip') {
            $laptop->keterangan = $request->keterangan;
        }

        if ($request->hasFile('foto')) {
            if ($laptop->foto && file_exists(public_path($laptop->foto))) {
                unlink(public_path($laptop->foto));
            }

            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/laptop'), $filename);
            $laptop->foto = 'uploads/laptop/' . $filename;
        }

        if ($request->hapus_foto == '1' && $laptop->foto) {
            if (file_exists(public_path($laptop->foto))) {
                unlink(public_path($laptop->foto));
            }
            $laptop->foto = null;
        }

        $laptop->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status laptop berhasil diubah',
                'redirect_to_borrow' => ($originalStatus !== 'in use' && $request->status === 'in use')
            ]);
        }

        if ($originalStatus !== 'in use' && $request->status === 'in use') {
            return redirect("/peminjaman/create/{$laptop->id}")
                ->with('info', 'Status laptop diubah. Silakan lengkapi form peminjaman.');
        }

        return redirect()->route('laptop.index', $laptop->id)
            ->with('success', 'Data laptop berhasil diupdate');
    }

    public function archive(Request $request, $id)
    {
        $laptop = LaptopData::findOrFail($id);
        $data = $request->json()->all();

        $laptop->status = 'diarsip';
        $laptop->keterangan = $data['keterangan'] ?? null;
        $laptop->save();

        return response()->json(['message' => 'Laptop berhasil diarsipkan']);
    }

    public function restore($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $laptop->status = 'in stock';
        $laptop->keterangan = null;
        $laptop->save();

        return redirect()
            ->route('laptop.arsip')
            ->with('success', 'Laptop berhasil dikembalikan!');
    }

    public function arsipLaptop(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $query = LaptopData::where('status', 'diarsip');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                  ->orWhere('merek', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%")
                  ->orWhere('spesifikasi', 'like', "%{$search}%");
            });
        }

        $laptops = $query->orderBy('id', 'desc')->paginate($perPage)->withQueryString();

        return view('content.tables.archive-laptop', compact('laptops'));
    }

    // ================= API ================= //

    public function apiArsipLaptop(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $laptops = LaptopData::where('status', 'diarsip')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('merek', 'like', "%{$search}%")
                      ->orWhere('tipe', 'like', "%{$search}%")
                      ->orWhere('spesifikasi', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%");
                });
            })
            ->paginate($perPage)
            ->withQueryString();

        return response()->json($laptops);
    }

    public function apiRestore($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $laptop->status = 'in stock';
        $laptop->keterangan = null;
        $laptop->save();

        return response()->json(['message' => 'Laptop berhasil dikembalikan']);
    }

    public function apiArchive(Request $request, $id)
    {
        $laptop = LaptopData::findOrFail($id);
        $keterangan = $request->input('keterangan') ?? $request->json('keterangan');

        $laptop->status = 'diarsip';
        $laptop->keterangan = $keterangan;
        $laptop->save();

        return response()->json([
            'message' => 'Laptop berhasil diarsipkan',
            'keterangan' => $keterangan
        ]);
    }

    public function getData(Request $request)
    {
        $query = LaptopData::query();

        // Filter pencarian
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('merek', 'like', "%{$request->search}%")
                ->orWhere('kode', 'like', "%{$request->search}%")
                ->orWhere('tipe', 'like', "%{$request->search}%")
                ->orWhere('spesifikasi', 'like', "%{$request->search}%");
            });
        }

        // Filter status
        if ($request->status && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('per_page', 10);
        $laptops = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($laptops);
    }
    
    public function edit($id)
    {
        $laptop = LaptopData::with('peminjamanAktif')->findOrFail($id);

        $peminjamAktif = $laptop->peminjamanAktif;

        return view('content.tables.edit-laptop', compact('laptop', 'peminjamAktif'));
    }

    public function show($id)
    {
        $laptop = LaptopData::where('id', $id)
                            ->where('status', 'diarsip') 
                            ->firstOrFail();

        return view('content.laptop.arsip-detail', compact('laptop'));
    }

    public function updateKeterangan(Request $request, $id)
    {
        $laptop = LaptopData::where('id', $id)
                            ->where('status', 'diarsip')
                            ->firstOrFail();

        $laptop->keterangan = $request->keterangan;
        $laptop->save();

        return back()->with('success', 'Keterangan berhasil diperbarui.');
    }   
    
    public function showSoldForm($id)
    {
        $laptop = LaptopData::findOrFail($id);
        
        if ($laptop->status !== 'in stock') {
            return redirect()->route('laptop.index')
                ->with('error', 'Laptop tidak dapat dijual karena status saat ini: ' . $laptop->status);
        }
        
        return view('content.tables.sold-laptop', compact('laptop'));
    }

    public function processSold(Request $request, $id)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_id' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $laptop = LaptopData::findOrFail($id);
        
        if ($laptop->status !== 'in stock') {
            return redirect()->route('laptop.index')
                ->with('error', 'Laptop tidak dapat dijual karena status saat ini: ' . $laptop->status);
        }

        \DB::transaction(function () use ($laptop, $request) {
            $laptop->status = 'sold';
            $laptop->save();

            SoldLaptop::create([
                'laptop_id' => $laptop->id,
                'buyer_name' => $request->buyer_name,
                'buyer_id' => $request->buyer_id,
                'sold_price' => $request->price,
                'notes' => $request->notes,
                'sold_at' => now(),
            ]);
        });

        return redirect()->route('laptop.index')
            ->with('success', 'Laptop berhasil dijual kepada ' . $request->buyer_name);
    }

   public function sold(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');

        $soldLaptops = LaptopData::where('laptop_data.status', 'sold')
            ->join('sold_laptops', 'laptop_data.id', '=', 'sold_laptops.laptop_id')
            ->select('laptop_data.*', 'sold_laptops.sold_at', 'sold_laptops.buyer_name', 'sold_laptops.buyer_id', 'sold_laptops.sold_price', 'sold_laptops.notes')
            ->when($search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('sold_laptops.buyer_name', 'like', "%{$search}%")
                    ->orWhere('laptop_data.kode', 'like', "%{$search}%")
                    ->orWhere('laptop_data.merek', 'like', "%{$search}%")
                    ->orWhere('laptop_data.tipe', 'like', "%{$search}%");
                });
            })
            ->orderBy('sold_laptops.sold_at', 'desc')
            ->paginate($perPage)
            ->withQueryString(); 

        if ($request->ajax()) {
            return view('content.peminjaman.table-soldlaptop', compact('soldLaptops'));
        }

        return view('content.tables.listlaptopsold', compact('soldLaptops', 'perPage', 'search'));
    }

    public function soldDetail($id)
    {
        $laptop = LaptopData::where('laptop_data.id', $id)  
            ->where('laptop_data.status', 'sold')  
            ->join('sold_laptops', 'laptop_data.id', '=', 'sold_laptops.laptop_id')
            ->select('laptop_data.*', 'sold_laptops.*')
            ->firstOrFail();

        return view('content.tables.sold-detail', compact('laptop'));
    }

}
