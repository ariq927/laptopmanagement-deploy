<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaptopData;
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

        $data = $request->only(['merek', 'tipe', 'spesifikasi', 'status']);
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

    public function update(Request $request, LaptopData $laptop)
    {
        $data = $request->only(['merek', 'tipe', 'spesifikasi', 'serial_number', 'status']);

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        if ($request->has('hapus_foto') && $request->hapus_foto == '1' && $laptop->public_id) {
            $cloudinary->uploadApi()->destroy($laptop->public_id);
            $data['foto'] = null;
            $data['public_id'] = null;
        } elseif ($request->hasFile('foto')) {
            if ($laptop->public_id) {
                $cloudinary->uploadApi()->destroy($laptop->public_id);
            }

            $uploaded = $cloudinary->uploadApi()->upload(
                $request->file('foto')->getRealPath(),
                ['folder' => 'laptops']
            );

            $data['foto'] = $uploaded['secure_url'];
            $data['public_id'] = $uploaded['public_id'];
        } else {
            $data['foto'] = $laptop->foto;
            $data['public_id'] = $laptop->public_id;
        }

        $laptop->update($data);

        return back()->with('success', 'Data laptop berhasil diperbarui.');
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

    // ================= API for React ================= //

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

        if ($request->search) {
            $query->where('merek', 'like', "%{$request->search}%")
                ->orWhere('kode', 'like', "%{$request->search}%")
                ->orWhere('status', 'like', "%{$request->search}%")
                ->orWhere('tipe', 'like', "%{$request->search}%");
        }

        $perPage = $request->input('per_page', 10);
        $laptops = $query->paginate($perPage);

        return response()->json($laptops);
    }
}
