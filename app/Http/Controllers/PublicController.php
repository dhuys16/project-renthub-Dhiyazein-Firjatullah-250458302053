<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;


class PublicController extends Controller
{
    // Rute: GET /products (products.index)
    public function index(Request $request)
    {
        // Logika query untuk menampilkan katalog produk
        $query = Product::query();

        // Filter wajib: Hanya tampilkan produk yang memiliki harga (> 0)
        $query->where('price_per_day', '>', 0);
        
        // Contoh filter kategori (menggunakan ENUM 'category')
        if ($request->has('category') && $request->category !== 'all') {
            // Daftar ENUM kategori (harus sama dengan di migrasi/Controller Admin)
            $categories = ['electronics', 'photography', 'vehicles', 'tools', 'others'];
            
            if (in_array($request->category, $categories)) {
                $query->where('category', $request->category);
            }
        }

        $products = $query->latest()->paginate(12);

        // Perlu dipastikan view ini ada: resources/views/products/catalogue/index.blade.php
        return view('products.catalogue.index', compact('products'));
    }

    // Rute: GET /products/{product:slug} (products.show)
    public function show(Product $product)
    {
        // Muat ulasan terkait dan user yang memberikan ulasan
        $product->load('reviews.customer');

        // Perlu dipastikan view ini ada: resources/views/products/catalogue/show.blade.php
        return view('products.catalogue.show', compact('product'));
    }

    // Rute: POST /reviews (user.reviews.store) - Terproteksi oleh Middleware
    public function storeReview(Request $request)
    {
        // HANYA Customer yang sudah login yang bisa memanggil ini
        
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Catatan: Dalam proyek nyata, Anda harus memverifikasi bahwa Auth::user()
        // telah menyelesaikan transaksi sewa untuk product_id ini.
        
        Review::create([
            'product_id' => $request->product_id,
            'customer_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Redirect kembali ke halaman detail produk
        return back()->with('success', 'Ulasan berhasil ditambahkan! Terima kasih.');
    }
}