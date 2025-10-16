<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = strtolower($request->get('q', ''));

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('API_IMORNING_TOKEN'),
                'Cookie' => 'PHPSESSID=' . env('API_IMORNING_SESSION'),
                'User-Agent' => 'LaravelHttpClient/1.0',
                'Accept' => 'application/json',
            ])
            ->withOptions(['verify' => false])
            ->get(env('PEGAWAI_API_URL') . '/get/data/employee');

            if ($response->failed()) {
                return response()->json([
                    'error' => 'Gagal ambil data pegawai',
                    'status' => $response->status(),
                ], $response->status());
            }

            $json = $response->json();

            $data = collect($json['data'] ?? [])->map(function ($emp) {
                return [
                    'employeeCode' => $emp['employeeCode'] ?? '-',
                    'employeeName' => trim(($emp['employeeName'] ?? '-') . ' - ' . ($emp['employeeCode'] ?? '-')),
                    'department' => $emp['department'] ?? '-',
                    'phone' => $emp['employeeEmail'] ?? '-',
                ];
            });

            if ($search) {
                $data = $data->filter(function ($emp) use ($search) {
                    return str_contains(strtolower($emp['employeeName']), $search);
                });
            }

            $data = $data->take(50)->values();

            return response()->json(['data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
