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

        $laptopStats = LaptopData::select('merek', DB::raw('COUNT(*) as total'))
            ->groupBy('merek')
            ->orderByDesc('total')
            ->get();

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

        // Kirim ke view
        return view('content.dashboard.dashboards-analytics', [
            'user' => $userData,
            'isGuest' => $userData['name'] === 'Guest',
            'totalLaptop' => $totalLaptop,
            'inStock' => $inStock,
            'inUse' => $inUse,
            'diarsip' => $diarsip,
            'pinjamanUser' => $pinjamanUser,
            'laptopStats' => $laptopStats,
        ]);
    }

    private function extractDepartment($dn)
    {
        preg_match_all('/OU=([^,]+)/', $dn, $matches);

        if (!empty($matches[1]) && isset($matches[1][1])) {
            return $matches[1][1];
        }

        return '-';
    }
}
