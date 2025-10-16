<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsersDetail;

class AccountSettingsAccount extends Controller
{
  public function index()
  {
    return view('content.pages.pages-account-settings-account');
  }

 public function update(Request $request)
  {
      $validated = $request->validate([
          'nama' => 'required|string|max:255',
          'email' => 'required|email',
          'phone' => 'nullable|string|max:20',
          'department' => 'nullable|string|max:255',
          'address' => 'nullable|string|max:255',
      ]);

      $userDetail = \App\Models\UsersDetail::where('user_id', auth()->id())->first();

      if (!$userDetail) {
          $userDetail = new \App\Models\UsersDetail();
          $userDetail->user_id = auth()->id(); 
      }

      $userDetail->fill($validated);
      $userDetail->save();

      return redirect()->back()->with('success', 'Profil berhasil diperbarui');
  }

  public function edit()
  {
      $userDetail = \App\Models\UsersDetail::where('user_id', auth()->id())->first();

      return view('pages.account-settings', compact('userDetail'));
  }


}
