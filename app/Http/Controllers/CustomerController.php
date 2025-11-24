<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Digunakan untuk Database Transaction
use App\Models\Product;
use App\Models\Order;      // Model untuk Tabel orders
use App\Models\OrderDetail; // Model untuk Tabel order_details
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Memproses data pemesanan yang dikirimkan melalui form checkout.
     * Route: POST /booking/checkout (booking.checkout)
     */
    public function processBooking(Request $request)
    {
        // 1. VALIDASI DATA
        $validatedData = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rent_start_date' => 'required|date|after_or_equal:today',
            'rent_end_date' => 'required|date|after_or_equal:rent_start_date',
            'payment_method' => 'required|string|in:cash,transfer', // Ditambahkan!
        ]);

        $product = Product::findOrFail($validatedData['product_id']);
        
        $startDate = new \DateTime($validatedData['rent_start_date']);
        $endDate = new \DateTime($validatedData['rent_end_date']);
        $durationDays = $startDate->diff($endDate)->days + 1;
        
        // Subtotal dihitung (Harga per hari * Jumlah Hari)
        $subtotalPrice = $durationDays * $product->price_per_day; 
        
        // Total price untuk Order = Subtotal (asumsi belum ada diskon/pajak)
        $totalPrice = $subtotalPrice; 

        // 2. CEK KONFLIK JADWAL (Dilakukan di tabel order_details)
        // Cek OrderDetails (karena info tanggal ada di sana)
        $conflict = OrderDetail::where('product_id', $product->id)
        ->whereHas('order', function ($query) {
            // Hanya cek Order yang statusnya 'paid' atau 'processing'
            $query->whereIn('status', ['paid', 'processing']); 
        })
        ->where(function ($query) use ($validatedData) {
            // GANTI 'start_date' dan 'end_date' dengan nama kolom yang BENAR di tabel order_details
            $query->where('rent_start_date', '<=', $validatedData['rent_end_date'])
                ->where('rent_end_date', '>=', $validatedData['rent_start_date']);
        })
        ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Produk ini sudah disewa pada rentang tanggal yang Anda pilih. Silakan pilih tanggal lain.');
        }

        // 3. SIMPAN DATA MENGGUNAKAN TRANSACTION
      
            // A. INSERT KE TABEL 'orders' (Informasi Umum)
            $order = Order::create([
                'customer_id' => Auth::id(), 
                'total_price' => $totalPrice, // Total price pesanan
                'status' => 'pending', 
                'payment_method' => $validatedData['payment_method'],
                // Tambahkan field umum lainnya jika ada (misal: payment_method_id)
            ]);

            // B. INSERT KE TABEL 'order_details' (Informasi Item Sewa)
            OrderDetail::create([
                'order_id' => $order->id, // Menggunakan ID pesanan yang baru dibuat
                'product_id' => $product->id,
                'rent_start_date' => $validatedData['rent_start_date'],
                'rent_end_date' => $validatedData['rent_end_date'],
                'subtotal' => $subtotalPrice, // Subtotal item
                // Quantity disetel ke 1 karena tidak ada di form
            ]);

            DB::commit(); // Pastikan kedua operasi berhasil

            // 4. PENGALIHAN (REDIRECT)
            return redirect()
                ->route('products.index', $order) 
                ->with('success', 'Pemesanan berhasil dibuat! Silakan lihat detail untuk pembayaran.');

    }


    // -----------------------------------------------------
    // Rute 2: Riwayat Pesanan & Pembatalan (orders.)
    // -----------------------------------------------------
    
    /**
     * Menampilkan daftar semua pesanan yang dimiliki oleh user yang sedang login.
     * Route: GET user/orders (user.orders.index)
     */
    public function orderHistory()
    {
        // Tetap ambil data dari tabel 'orders'
        $orders = Order::where('customer_id', Auth::id()) 
                            // Muat relasi orderDetails dan produk di dalamnya
                            ->with('details.product') 
                            ->latest() 
                            ->paginate(10); 
        
        return view('user.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan tertentu.
     * Route: GET user/orders/{order} (user.orders.show)
     */
    public function showOrder(Order $order) 
    {
        // Cek Otorisasi
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Pesanan ini bukan milik Anda.');
        }

        // Muat relasi orderDetails dan data produk serta vendornya
        $order->load('details.product.vendor');

        return view('user.orders.show', compact('order'));
    }

    public function cancelOrder(Order $order)
    {
        // 1. Otorisasi: Pastikan pesanan dimiliki oleh customer yang sedang login
        // Asumsi kolom kunci asing di tabel orders adalah 'customer_id'
        if ($order->customer_id !== Auth::id()) {
            return back()->with('error', 'Akses ditolak: Pesanan bukan milik Anda.');
        }

        // 2. Validasi Status: Pesanan hanya dapat dibatalkan jika statusnya masih 'pending' atau 'confirmed'.
        // Sesuaikan daftar status di bawah ini agar sesuai dengan ENUM/konstanta di model Order Anda.
        $allowedStatusesToCancel = ['pending', 'confirmed'];
        
        if (!in_array($order->status, $allowedStatusesToCancel)) {
            return back()->with('error', 
                "Pesanan tidak dapat dibatalkan karena statusnya sudah '{$order->status}'.");
        }

        // 3. Update Status
        $order->status = 'canceled';
        $order->save();

        // 4. Redirect dengan pesan sukses
        // Asumsi Anda memiliki route 'user.orders.index'
        return redirect()->route('user.orders.index')
            ->with('success', "Pesanan #{$order->id} berhasil dibatalkan.");
    }

    public function showVendorProfile(User $vendor)
    {
        // 1. Validasi: Pastikan user ini benar-benar vendor
        if ($vendor->role !== 'vendor') {
            return redirect()->route('products.index')->with('error', 'Profil yang diminta bukan profil Vendor.');
        }

        // 2. Muat produk yang tersedia dari vendor tersebut
        $products = Product::where('vendor_id', $vendor->id)
                            ->withAvg('reviews', 'rating')->withCount('reviews')
                            ->latest()
                            ->paginate(12);

        // 3. Tampilkan view baru: resources/views/products/vendor_catalogue/show.blade.php
        return view('products.vendor_catalogue.show', compact('vendor', 'products'));
    }
}