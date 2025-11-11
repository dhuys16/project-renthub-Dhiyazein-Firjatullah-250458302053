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

        // Pengecekan keamanan ekstra (meskipun sudah ada middleware 'auth')
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Ambil ID produk yang dimiliki oleh Vendor yang sedang login
        // Sekarang Intelephense/PHP mengenali $user sebagai Model User
        $vendorProductIds = $user->products()->pluck('id'); 
        // -----------------------------------------------------------------

        // 2. Ambil Order yang memiliki OrderDetails
        $orders = Order::whereIn('id', function ($query) use ($vendorProductIds) {
            $query->select('order_id')
                  ->from('order_details')
                  ->whereIn('product_id', $vendorProductIds);
        })
        ->with('customer') 
        ->latest()
        ->paginate(15);
        
        // ... kode lainnya ...

        return view('vendors.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan tertentu.
     * (Route: GET /vendor/orders/{order})
     */
        public function show(Order $order)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user(); 
        
        // 1. Otorisasi: Pastikan setidaknya satu produk dalam Order ini dimiliki oleh Vendor yang login
        $vendorProductIds = $user->products()->pluck('id'); // <<< Perbaikan di sini
        // ...
    }

    /**
     * Mengubah status pesanan dari 'pending' menjadi 'confirmed'.
     * (Route: POST /vendor/orders/{order}/confirm)
     */
    public function confirm(Order $order)
{
    /** @var \App\Models\User $user */
    $user = Auth::user(); 
    
    // 1. Otorisasi: Pastikan Vendor bisa mengkonfirmasi pesanan ini
    $vendorProductIds = $user->products()->pluck('id'); // <<< Perbaikan di sini
    // ...
}
}