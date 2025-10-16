<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginBasic extends Controller
{
    public function index()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $response = Http::asForm()->post(env('LDAP_API_URL'), [
                'username' => $request->username,
                'password' => $request->password,
            ]);

            $result = $response->json();

            if ($response->ok() && isset($result['status']) && (int)$result['status'] === 200) {
                $userData = $result['datas'] ?? [];

                $department = '-';
                if (!empty($userData['distinguishedName'])) {
                    if (preg_match('/OU=([^,]+)/', $userData['distinguishedName'], $matches)) {
                        $department = trim($matches[1]);
                    }
                }

                $user = User::updateOrCreate(
                    ['username' => $userData['sAMAccountName'] ?? $request->username],
                    [
                        'name'       => $userData['displayName'] ?? $request->username,
                        'email'      => $userData['mail'] ?? $request->username . '@example.com',
                        'department' => $department,
                        'password'   => bcrypt($request->password), 
                    ]
                );

                Auth::login($user);

                return redirect()->route('dashboard')->with('success', 'Login berhasil!');
            }

            return back()->withErrors([
                'username' => $result['message'] ?? 'Login gagal. Periksa username atau password.',
            ])->withInput($request->only('username'));

        } catch (\Exception $e) {
            return back()->withErrors([
                'username' => 'Terjadi kesalahan koneksi ke server LDAP: ' . $e->getMessage(),
            ])->withInput($request->only('username'));
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('auth-login-basic')->with('success', 'Berhasil logout!');
    }
}
