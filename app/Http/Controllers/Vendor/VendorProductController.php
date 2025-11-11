<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException; 
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    private $productCategories = ['electronics', 'photography', 'vehicles', 'tools', 'others'];

    /**
     * Menampilkan daftar produk HANYA MILIK VENDOR YANG SEDANG LOGIN.
     * (Route: GET /vendor/products)
     */
    public function index()
    {
        // KRITIS: Filter berdasarkan ID vendor yang sedang login
        $products = Product::where('vendor_id', Auth::id()) 
                            ->latest()
                            ->paginate(15);

        // Catatan: Ubah nama view ke 'vendor.products.index' jika folder Anda 'vendor'
        return view('vendors.products.index', compact('products'));
    }

    /**
     * Menampilkan formulir untuk membuat produk baru.
     * (Route: GET /vendor/products/create)
     */
    public function create()
    {
        $categories = $this->productCategories;
        // Vendor TIDAK PERLU daftar vendor lain. Mereka hanya menginput produk untuk diri sendiri.

        return view('vendors.products.create', compact('categories'));
    }

    /**
     * Menyimpan produk baru ke database.
     * (Route: POST /vendor/products)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:1000',
            'category' => ['required', Rule::in($this->productCategories)], 
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // Handle upload file
        if ($request->hasFile('picture')) {
            $validatedData['picture'] = $request->file('picture')->store('products', 'public');
        }

        // KRITIS: Set vendor_id secara otomatis dari user yang sedang login
        $validatedData['vendor_id'] = Auth::id();
        
        Product::create($validatedData);

        return redirect()->route('vendors.products.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail produk tertentu (Harus dimiliki oleh Vendor).
     * (Route: GET /vendor/products/{product})
     */
    public function show(Product $product)
    {
        // KRITIS: Pengecekan kepemilikan
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan melihat produk ini.');
        }
        return view('vendors.products.show', compact('product'));
    }

    /**
     * Menampilkan formulir untuk mengedit produk (Harus dimiliki oleh Vendor).
     * (Route: GET /vendor/products/{product}/edit)
     */
    public function edit(Product $product)
    {
        // KRITIS: Pengecekan kepemilikan
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit produk ini.');
        }
        $categories = $this->productCategories;
        return view('vendors.products.edit', compact('product', 'categories'));
    }

    /**
     * Memperbarui produk yang sudah ada di database (Harus dimiliki oleh Vendor).
     * (Route: PUT/PATCH /vendor/products/{product})
     */
    public function update(Request $request, Product $product)
    {
        // KRITIS: Pengecekan kepemilikan di awal
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan memperbarui produk vendor lain.');
        }
        
        // ... Logika validasi dan update file sama seperti sebelumnya ...

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:1000',
            'category' => ['required', Rule::in($this->productCategories)],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);
        
        // Handle update file...

        $product->update($validatedData);

        return redirect()->route('vendors.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database (Harus dimiliki oleh Vendor).
     * (Route: DELETE /vendor/products/{product})
     */
    public function destroy(Product $product)
    {
        // KRITIS: Pengecekan kepemilikan di awal
        if ($product->vendor_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan menghapus produk vendor lain.');
        }

        // Hapus file gambar terkait dan record
        if ($product->picture) {
            Storage::disk('public')->delete($product->picture);
        }
        $product->delete();

        return redirect()->route('vendors.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}