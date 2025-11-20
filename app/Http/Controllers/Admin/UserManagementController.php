<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar SEMUA pengguna (Admin, Vendor, Customer).
     * (Route: GET /admin/users)
     */
    public function index(Request $request)
    {
        // Mendapatkan nomor halaman untuk Customer (default page=1)
        $customerPage = $request->input('customer_page', 1);
        
        // Mendapatkan nomor halaman untuk Vendor (default page=1)
        $vendorPage = $request->input('vendor_page', 1); 

        // 1. Ambil data CUSTOMER
        $customers = User::where('role', 'customer')
                         ->orderBy('id', 'asc')
                         ->paginate(10, ['*'], 'customer_page', $customerPage);

        // 2. Ambil data VENDOR
        $vendors = User::where('role', 'vendor')
    // ⭐ TAMBAHKAN INI: Memuat hitungan relasi 'products' ⭐
                        ->withCount('products') 
                        ->orderBy('id', 'asc')
                        ->paginate(10, ['*'], 'vendor_page', $vendorPage);

        // View: resources/views/admin/user-management/index.blade.php
        return view('admin.users.index', compact('customers', 'vendors'));
    }

    /**
     * Menghapus pengguna dari database.
     * (Route: DELETE /admin/users/{user})
     */
    public function show(User $user)
    {
        // 1. Otorisasi (Opsional tapi baik): Pastikan hanya Admin yang bisa melihat detail
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }

        // 2. Muat Relasi (Jika Vendor)
        // Jika user adalah vendor, kita mungkin ingin tahu berapa banyak produk yang dia miliki.
        if ($user->role === 'vendor') {
            // Asumsi relasi products() sudah didefinisikan di Model User
            $user->loadCount('products'); 
        }

        // 3. Tampilkan View
        // View: resources/views/admin/user-management/show.blade.php
        return view('admin.users.show', compact('user'));
    }
}