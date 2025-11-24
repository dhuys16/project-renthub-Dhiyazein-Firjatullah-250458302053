<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


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
            'name' => 'nullable|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:500',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'username' => ['nullable', 'string', 'max:50', Rule::unique('users')->ignore($user->id)], // Unique rule untuk username
            'link_gmaps' => 'nullable|url|max:255',
        ]);

        // === START: LOGIKA PENANGANAN FILE UPLOAD KRITIS ===
        if ($request->hasFile('photo_profile')) {
            
            // Hapus foto lama dari storage (jika ada)
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }

            // Simpan foto baru ke folder 'profiles' dan dapatkan path-nya
            $path = $request->file('photo_profile')->store('profiles', 'public');
            
            // Ganti objek file dengan path string di array data yang divalidasi
            $validatedData['photo_profile'] = $path;

        } else {
            // Jika user tidak mengunggah file baru, hapus key 'photo_profile' dari array
            // agar Laravel tidak mencoba mengisi kolom DB dengan nilai kosong (string kosong)
            unset($validatedData['photo_profile']);
        }
        // === END: LOGIKA FILE UPLOAD ===

        // Update data termasuk username dan photo_profile (jika ada path baru)
        $user->update($validatedData);

        return redirect()->route('user.profile.show')
                        ->with('success', 'Profil berhasil diperbarui!');
    }

    public function becomeVendorForm()
    {
        $user = Auth::user();
        
        // Pengecekan: Jika user sudah menjadi vendor, redirect.
        if ($user->role === 'vendor') {
            return redirect()->route('vendors.dashboard')->with('info', 'Anda sudah terdaftar sebagai Vendor.');
        }

        // View: resources/views/user/vendor/become-vendor-info.blade.php
        return view('user.vendor.become-vendor-info');
    }

    /**
     * Memproses aksi dan mengubah role Customer menjadi Vendor.
     * Route: POST /register-vendor-action (customer.vendor.register)
     */
    public function registerVendor(Request $request)
    {
        $user = Auth::user();

        // Pengecekan keamanan: Pastikan user belum menjadi vendor
        if ($user->role === 'vendor') {
            return redirect()->route('vendors.dashboard')->with('error', 'Anda sudah terdaftar sebagai Vendor.');
        }

        // 1. Update Role User
        try {
            $user->update([
                'role' => 'vendor',
                // Opsional: Set is_verified ke true/false
            ]);

            // 2. Redirect ke Dashboard Vendor Baru
            return redirect()->route('vendors.dashboard')
                             ->with('success', '🎉 Selamat! Akun Anda telah diubah menjadi Vendor. Silakan mulai mengelola produk Anda.');

        } catch (\Exception $e) {
            return back()
                         ->with('error', 'Gagal memproses pendaftaran Vendor. Mohon coba lagi.');
        }
    }
}