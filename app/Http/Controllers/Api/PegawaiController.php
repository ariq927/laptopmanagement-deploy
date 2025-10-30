<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = strtolower($request->get('q', ''));

        $url = env('PEGAWAI_API_URL') . '/get/data/employee';
        if ($search) {
            $url .= '?employeeName=' . urlencode($search);
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('API_IMORNING_TOKEN'),
            'Cookie' => 'PHPSESSID=' . env('API_IMORNING_SESSION'),
            'User-Agent' => 'LaravelHttpClient/1.0',
            'Accept' => 'application/json',
        ])
        ->withOptions(['verify' => false])
        ->get($url);

        if ($response->failed()) {
            return response()->json(['error' => 'Gagal ambil data pegawai'], $response->status());
        }

        $json = $response->json();
        $data = collect($json['data'] ?? [])->take(50)->values();

        return response()->json(['data' => $data]);
    }

}
