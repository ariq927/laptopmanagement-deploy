<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Peminjaman;
use App\Models\LaptopData;
use Illuminate\Support\Facades\DB;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalLaptop = LaptopData::count();
        $inStock = LaptopData::where('status', 'in stock')->count();
        $inUse = LaptopData::where('status', 'in use')->count();
        $diarsip = LaptopData::where('status', 'diarsip')->count();

        $pinjamanUser = collect();
        if ($user) {
            $pinjamanUser = Peminjaman::with('laptop')
                ->where('user_id', $user->username)
                ->get();
        }

        $levelJabatanCase = "
            CASE
                WHEN LOWER(department) REGEXP 'general[[:space:]]?manager' THEN 'General Manager'
                WHEN LOWER(department) REGEXP 'senior[[:space:]]?manager' THEN 'Senior Manager'
                WHEN LOWER(department) REGEXP 'manager[^a-z]' OR LOWER(department) LIKE '%manager%' THEN 'Manager'
                WHEN LOWER(department) REGEXP 'supervisor[[:space:]]?senior' THEN 'Supervisor Senior'
                WHEN LOWER(department) REGEXP 'supervisor[^a-z]' OR LOWER(department) LIKE '%supervisor%' THEN 'Supervisor'
                WHEN LOWER(department) REGEXP 'staf[[:space:]]?senior' THEN 'Staf Senior'
                WHEN LOWER(department) REGEXP 'staf[^a-z]' OR LOWER(department) LIKE '%staf%' THEN 'Staf'
                WHEN LOWER(department) REGEXP 'teknisi[^a-z]' OR LOWER(department) LIKE '%teknisi%' THEN 'Teknisi'
                WHEN LOWER(department) REGEXP 'operator[^a-z]' OR LOWER(department) LIKE '%operator%' THEN 'Operator'
                ELSE 'Lainnya'
            END as jabatan_level
        ";

        $laptopStats = DB::table('histori_peminjaman')
            ->select(
                DB::raw('YEAR(tanggal_mulai) as tahun'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('tahun')
            ->orderBy('tahun', 'asc')
            ->get();

        $jabatanPerTahun = DB::table('histori_peminjaman')
            ->select(
                DB::raw('YEAR(tanggal_mulai) as tahun'),
                DB::raw($levelJabatanCase)
            )
            ->whereNotNull('department')
            ->where('department', '!=', '')
            ->where('department', '!=', '-')
            ->groupBy('tahun', DB::raw('jabatan_level'))
            ->selectRaw('COUNT(*) as total')
            ->orderBy('tahun')
            ->get()
            ->groupBy('tahun')
            ->map(function ($items) {
                return $items->pluck('total', 'jabatan_level')->toArray();
            });

        $ldapData = session('ldap_user');

        if ($ldapData) {
            $userData = [
                'name' => $ldapData['displayName'] ?? 'Guest',
                'email' => $ldapData['mail'] ?? '-',
                'department' => $this->extractDepartment($ldapData['distinguishedName'] ?? ''),
            ];
        } elseif ($user) {
            $userData = [
                'name' => $user->name ?? 'Guest',
                'email' => $user->email ?? '-',
                'department' => $user->department ?? '-',
            ];
        } else {
            $userData = [
                'name' => 'Guest',
                'email' => '-',
                'department' => '-',
            ];
        }

        return view('content.dashboard.dashboards-analytics', [
            'user' => $userData,
            'isGuest' => $userData['name'] === 'Guest',
            'totalLaptop' => $totalLaptop,
            'inStock' => $inStock,
            'inUse' => $inUse,
            'diarsip' => $diarsip,
            'pinjamanUser' => $pinjamanUser,
            'laptopStats' => $laptopStats,
            'jabatanPerTahun' => $jabatanPerTahun,
        ]);
    }

    private function extractDepartment($dn)
    {
        preg_match_all('/OU=([^,]+)/', $dn, $matches);
        return $matches[1][1] ?? '-';
    }
}