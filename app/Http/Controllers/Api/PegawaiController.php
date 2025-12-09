<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');

        $url = env('PEGAWAI_API_URL') . '/get/data/employee';
        if ($search) {
            $url .= '?employeeName=' . urlencode($search);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('API_IMORNING_TOKEN'),
                'Cookie' => env('API_IMORNING_COOKIE'),
                'User-Agent' => 'LaravelHttpClient/1.0',
                'Accept' => 'application/json',
            ])
            ->withOptions(['verify' => false])
            ->timeout(30)
            ->get($url);

            if ($response->failed()) {
                Log::error('API Pegawai Error', [
                    'status' => $response->status(),
                    'url' => $url,
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'error' => 'Gagal mengambil data pegawai',
                    'message' => 'Status: ' . $response->status()
                ], $response->status());
            }

            $json = $response->json();
            
            Log::info('API Pegawai Response', [
                'total_data' => count($json['data'] ?? []),
                'search' => $search
            ]);

            $data = collect($json['data'] ?? [])->take(50)->values();

            return response()->json(['data' => $data]);

        } catch (\Exception $e) {
            Log::error('API Pegawai Exception', [
                'message' => $e->getMessage(),
                'url' => $url
            ]);

            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}