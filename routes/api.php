<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PegawaiController;
use App\Models\LaptopData;
use Illuminate\Http\Request;
use App\Http\Controllers\PeminjamanNewController;
use App\Http\Controllers\LaptopController;

Route::get('/pegawai', [PegawaiController::class, 'index']);

Route::get('/laptop', function (Request $request) {
    $query = LaptopData::query();

    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('merek', 'like', '%' . $request->search . '%')
              ->orWhere('tipe', 'like', '%' . $request->search . '%')
              ->orWhere('spesifikasi', 'like', '%' . $request->search . '%')
              ->orWhere('serial_number', 'like', '%' . $request->search . '%')
              ->orWhere('status', 'like', '%' . $request->search . '%');
        });
    }

    return $query->paginate($request->per_page ?? 10);
});

Route::patch('/laptop/{id}/archive', function ($id) {
    $laptop = LaptopData::findOrFail($id);
    $laptop->status = 'diarsip';
    $laptop->save();
    return response()->json(['success' => true, 'laptop' => $laptop]);
});

Route::patch('/laptop/{id}/restore', function ($id) {
    $laptop = LaptopData::findOrFail($id);
    $laptop->status = 'tersedia';
    $laptop->save();
    return response()->json(['success' => true, 'laptop' => $laptop]);
});


Route::get('/laptop/arsip', [LaptopController::class, 'apiArsipLaptop']);
Route::patch('/laptop/{id}/restore', [LaptopController::class, 'apiRestore']);
Route::patch('/laptop/{id}/archive', [LaptopController::class, 'apiArchive']);


Route::get('/laptops/arsip', function() {
    return LaptopData::where('status', 'diarsip')->get();
});

//Peminjam
Route::get('/peminjam', [PeminjamanNewController::class, 'apiIndex']);
Route::delete('/peminjam/{id}/selesai', [PeminjamanNewController::class, 'apiSelesai']);