<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;


class UserController extends Controller
{
    // -----------------------------------------------------
    // Rute 3: Pengaturan Profil (profile.)
    // -----------------------------------------------------

    /**
     * Menampilkan halaman detail profil user.
     * Route: GET user/profile (user.profile.show)
     */
    public function showProfile()
    {
        $user = Auth::user();
        // Perlu dipastikan view ini ada: resources/views/user/profile/show.blade.php
        return view('user.profile.show', compact('user'));
    }

    /**
     * Menampilkan halaman form untuk mengedit profil user.
     * Route: GET user/profile/edit (user.profile.edit)
     */
    public function editProfile()
    {
        $user = Auth::user();
        // Perlu dipastikan view ini ada: resources/views/user/profile/edit.blade.php
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Memperbarui data profil user.
     * Route: PUT user/profile (user.profile.update)
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Rule::unique('users')->ignore($user->id) memastikan user bisa menggunakan email lamanya
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
            // Jika ada field lain, tambahkan di sini
        ]);

        $user->update($validatedData);

        return redirect()->route('user.profile.show')
                         ->with('success', 'Profil berhasil diperbarui!');
    }
}