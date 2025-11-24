<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VendorOrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan yang masuk yang terkait dengan produk Vendor ini.
     * (Route: GET /vendor/orders)
     */
    public function index()
    {
        // 🔑 SOLUSI: Deklarasikan objek Auth::user() sebagai Model User
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Ambil status filter dari request
        $status = request('status', 'all');

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Ambil ID produk yang dimiliki oleh Vendor yang sedang login
        $vendorProductIds = $user->products()->pluck('id'); 
        
        // 2. Ambil Order yang memiliki OrderDetails
        $query = Order::whereIn('id', function ($query) use ($vendorProductIds) {
            $query->select('order_id')
                  ->from('order_details')
                  ->whereIn('product_id', $vendorProductIds);
        })
        ->with('customer', 'details.product');
        
        // Terapkan filter status jika bukan 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(15)->appends(['status' => $status]);
        
        return view('vendors.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan tertentu.
     * (Route: GET /vendor/orders/{order})
     */
    public function show(Order $order)
    {
        // Cek apakah user yang login adalah Vendor/Admin
        if (Auth::user()->role !== 'vendor' && Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak.');
        }
        
        // 1. Otorisasi (PENTING): Cek kepemilikan produk dalam pesanan.
        $isOwnedByVendor = $order->details()
                                 ->whereHas('product', function($query) {
                                     $query->where('vendor_id', Auth::id());
                                 })
                                 ->exists();

        if (!$isOwnedByVendor) {
            abort(403, 'Pesanan tidak ditemukan atau tidak milik Anda.');
        }

        // Muat detail yang diperlukan untuk tampilan
        // Asumsi relasi di Order Model adalah 'customer'
        $order->load('customer', 'details.product.vendor'); 
        
        return view('vendors.orders.show', compact('order'));
    }

    /**
     * Mengkonfirmasi pesanan (Mengubah status dari 'pending' menjadi 'processing').
     * Route: POST vendor/orders/{order}/confirm (vendors.orders.confirm)
     */
    public function confirm(Order $order)
    {
        // 1. Otorisasi (PENTING)
        $isOwnedByVendor = $order->details()->whereHas('product', function($query) {
             $query->where('vendor_id', Auth::id());
        })->exists();

        if (!$isOwnedByVendor) {
            abort(403, 'Akses ditolak. Anda tidak berhak mengkonfirmasi pesanan ini.');
        }

        // 2. Cek Status yang Valid
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan dengan status "' . ucfirst($order->status) . '" tidak dapat dikonfirmasi.');
        }

        // 3. Update Status
        $order->status = 'confirmed'; // KOREKSI STATUS: 'processing' (sedang disiapkan)
        $order->save();
        
        return redirect()->route('vendors.orders.show', $order)
                         ->with('success', '✅ Pesanan #' . $order->id . ' berhasil dikonfirmasi. Pesanan sekarang dalam status **Processing**.');
    }

    /**
     * Menolak pesanan (Mengubah status dari 'pending' menjadi 'cancelled').
     * Route: POST vendor/orders/{order}/reject (vendors.orders.reject)
     */
    public function reject(Order $order, Request $request)
    {
        // 1. Otorisasi (PENTING)
        $isOwnedByVendor = $order->details()->whereHas('product', function($query) {
             $query->where('vendor_id', Auth::id());
        })->exists();

        if (!$isOwnedByVendor) {
            abort(403, 'Akses ditolak. Anda tidak berhak menolak pesanan ini.');
        }
        
        // 2. Cek Status yang Valid
        if ($order->status !== 'pending') {
            return back()->with('error', 'Pesanan dengan status "' . ucfirst($order->status) . '" tidak dapat ditolak.');
        }

        // 3. Update Status
        $order->status = 'canceled'; // KOREKSI STATUS: 'cancelled' (digunakan di DB)
        $order->save();

        return redirect()->route('vendors.orders.index')
                         ->with('success', '❌ Pesanan #' . $order->id . ' berhasil ditolak dan status diubah menjadi **Cancelled**.');
    }
    
    // -----------------------------------------------------

    /**
     * Mengubah status pesanan untuk aksi 'Sedang Disewa' atau 'Selesai'.
     * Ditangani oleh Route::resource update (PUT/PATCH /vendor/orders/{order}).
     * Digunakan untuk mengubah status dari processing -> rented -> completed.
     */
    public function rented(Order $order)
    {
        // 1. Otorisasi (PENTING)
        $isOwnedByVendor = $order->details()->whereHas('product', function($query) {
             $query->where('vendor_id', Auth::id());
        })->exists();

        if (!$isOwnedByVendor) {
            abort(403, 'Akses ditolak.');
        }

        // 2. Cek Transisi Status yang Valid
        if (!in_array($order->status, ['confirmed'])) {
            return back()->with('error', 'Pesanan hanya dapat diubah menjadi Sedang Disewa jika statusnya confirmed.');
        }

        // 3. Update Status
        $order->status = 'rented'; 
        $order->save()  ;

        return redirect()->route('vendors.orders.show', $order)
                         ->with('success', '➡️ Status berhasil diubah menjadi Sedang Disewa.');
    }
    
    // -----------------------------------------------------

    /**
     * Mengubah status dari 'rented' menjadi 'completed' (Selesai).
     * Route: POST vendor/orders/{order}/complete
     */
    public function complete(Order $order)
    {
        // 1. Otorisasi (PENTING)
        $isOwnedByVendor = $order->details()->whereHas('product', function($query) {
             $query->where('vendor_id', Auth::id());
        })->exists();

        if (!$isOwnedByVendor) {
            abort(403, 'Akses ditolak.');
        }

        // 2. Cek Transisi Status yang Valid
        if ($order->status !== 'rented') {
            return back()->with('error', 'Pesanan hanya dapat diselesaikan jika statusnya **Sedang Disewa**.');
        }

        // 3. Update Status
        $order->status = 'completed'; 
        $order->save();

        return redirect()->route('vendors.orders.show', $order)
                         ->with('success', '✅ Pesanan berhasil diselesaikan. Status diubah menjadi **Completed**.');
    }

    public function showCustomerProfile(User $customer)
    {
        // PENTING: Anda mungkin perlu menambahkan otorisasi di sini
        // Misalnya: hanya izinkan melihat profil customer yang pernah bertransaksi dengan vendor ini.
        // if (!$this->vendorHasRelationshipWithCustomer($customer)) { ... }

        // Asumsi data yang diperlukan (address, phone, dll.) sudah ada di objek $customer
        
        // Mengarahkan ke view baru
        return view('vendors.customers.show', compact('customer'));
    }
}