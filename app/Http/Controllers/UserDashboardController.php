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
        $tersedia = LaptopData::where('status', 'tersedia')->count();
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

        return view('content.dashboard.dashboards-analytics', [
            'user' => $userData,
            'isGuest' => $userData['name'] === 'Guest',
            'totalLaptop' => $totalLaptop,
            'tersedia' => $tersedia,
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
