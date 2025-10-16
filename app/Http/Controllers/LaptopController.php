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
            'serial_number' => 'required|string|unique:laptop_data,serial_number',
            'status' => 'required|in:tersedia,dipinjam,maintenance',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['merek', 'tipe', 'spesifikasi', 'serial_number', 'status']);
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
                    [
                        'folder' => 'laptops',
                        'resource_type' => 'image'
                    ]
                );
                
                if (isset($uploadResult['secure_url'])) {
                    $data['foto'] = $uploadResult['secure_url'];
                    $data['public_id'] = $uploadResult['public_id'];
                } else {
                    throw new \Exception('Tidak ada secure_url di response');
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
        $data = $request->only(['merek', 'tipe', 'spesifikasi', 'serial_number']);

        $cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
                'api_key'    => env('CLOUDINARY_API_KEY'),
                'api_secret' => env('CLOUDINARY_API_SECRET'),
            ],
        ]);

        if ($request->has('hapus_foto') && $laptop->public_id) {
            $cloudinary->uploadApi()->destroy($laptop->public_id);
            $data['foto'] = null;
            $data['public_id'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($laptop->public_id) {
                $cloudinary->uploadApi()->destroy($laptop->public_id);
            }

            $uploaded = $cloudinary->uploadApi()->upload(
                $request->file('foto')->getRealPath(),
                ['folder' => 'laptops']
            );

            $data['foto'] = $uploaded['secure_url'];
            $data['public_id'] = $uploaded['public_id'];
        }

        $laptop->update($data);

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil diperbarui!');
    }

    public function archive($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $laptop->status = 'diarsip';
        $laptop->save();

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil diarsipkan.');
    }

    public function restore($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $laptop->status = 'tersedia';
        $laptop->save();

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil dikembalikan.');
    }

    public function arsipLaptop(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $laptops = LaptopData::where('status', 'diarsip')
                    ->paginate($perPage)
                    ->withQueryString();

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
        $laptop->status = 'tersedia';
        $laptop->save();

        return response()->json(['message' => 'Laptop berhasil dikembalikan']);
    }

    public function apiArchive($id)
    {
        $laptop = LaptopData::findOrFail($id);
        $laptop->status = 'diarsip';
        $laptop->save();

        return response()->json(['message' => 'Laptop berhasil diarsipkan']);
    }
}
