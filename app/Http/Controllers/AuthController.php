<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('content.authentications.auth-login-basic');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $ldapApiUrl = env('LDAP_API_URL');

        try {
            $response = Http::asForm()->withoutVerifying()->post($ldapApiUrl, [
                'username' => $request->name,
                'password' => $request->password,
            ]);

            \Log::info('LDAP Response:', [
                'status_code' => $response->status(),
                'body' => $response->body(),
            ]);

            $data = json_decode($response->body(), true);

            // 🔹 Login via API
            if ($response->successful() && isset($data['message']) && strtolower($data['message']) === 'success') {
                $ldapUser = $data['datas'] ?? null;

                $user = User::firstOrCreate(
                    ['name' => $request->name],
                    [
                        'email' => $ldapUser['mail'] ?? $request->name.'@example.com',
                        'password' => Hash::make($request->password),
                        'role' => 'user'
                    ]
                );

                Auth::login($user);
                session(['ldap_user' => $ldapUser]);

                return redirect()->route('dashboard');
            }
        } catch (\Exception $e) {
            \Log::error('LDAP connection error: '.$e->getMessage());
        }

        // 🔹 Pastikan user admin fallback ada
        $adminUser = User::where('email', 'admin123@gmail.com')->first();
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'admin123',
                'email' => 'admin123@gmail.com',
                'password' => Hash::make('Sz09K7z'),
                'role' => 'admin',
            ]);
        }

        // 🔹 Login lokal (admin atau user biasa)
        if (Auth::attempt(['email' => $request->name, 'password' => $request->password]) ||
            Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['name' => 'Login gagal: Nama atau password salah.'])->withInput();
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user'
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        Auth::logout();
        session()->flush();
        return redirect('/login');
    }
}
