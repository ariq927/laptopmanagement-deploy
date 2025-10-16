<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(array_merge($credentials, ['role' => 'admin']))) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email/password salah atau Anda bukan admin.',
        ]);
    }

    public function showRegisterForm()
    {
        return view('auth.admin-register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        Auth::login($admin);

        return redirect()->route('admin.dashboard');
    }

    public function editLaptop($id)
{
    $laptop = LaptopData::findOrFail($id);
    return view('content.laptop.edit-laptop', compact('laptop'));
}

    public function updateLaptop(Request $request, $id)
    {
        $request->validate([
            'merek' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'spesifikasi' => 'nullable|string',
            'serial_number' => 'nullable|string|max:255',
            'stok' => 'nullable|integer|min:1',
        ]);

        $laptop = LaptopData::findOrFail($id);
        $laptop->update([
            'merek' => $request->merek,
            'tipe' => $request->tipe,
            'spesifikasi' => $request->spesifikasi,
            'serial_number' => $request->serial_number,
            'stok' => $request->stok ?? 1,
        ]);

        return redirect()->route('laptop.index')->with('success', 'Laptop berhasil diupdate');
    }

}
