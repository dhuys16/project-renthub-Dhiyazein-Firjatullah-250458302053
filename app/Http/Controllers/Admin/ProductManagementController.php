<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProductManagementController extends Controller
{
    private $productCategories = ['electronics', 'photography', 'vehicles', 'tools', 'others'];

    /**
     * Menampilkan daftar SEMUA produk di sistem.
     * (Route: GET /admin/products)
     */
    public function index()
    {
        $products = Product::query()
            // Menghitung rata-rata rating dan melampirkannya sebagai 'reviews_avg_rating'
            ->withAvg('reviews', 'rating')
            ->withCount('reviews') 
            ->latest()
            ->paginate(12);

        return view('admin.products.index', compact('products'));
    }

    /**
     * Menampilkan formulir untuk membuat produk baru.
     * Admin dapat membuat produk dan menunjuk Vendor pemiliknya.
     * (Route: GET /admin/products/create)
     */
    public function create()
    {
        $categories = $this->productCategories;

        $vendors = User::where('role', 'vendor')->get(); 

        return view('admin.products.create', compact('categories', 'vendors'));
    }

    /**
     * Menyimpan produk baru ke database.
     * (Route: POST /admin/products)
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:1000',
            'vendor_id' => 'required|exists:users,id', 
            'category' => ['required', Rule::in($this->productCategories)], 
            'picture' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // Handle upload file
        if ($request->hasFile('picture')) {
            $validatedData['picture'] = $request->file('picture')->store('products', 'public');
        }

        Product::create($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk baru berhasil ditambahkan oleh Admin.');
    }

    /**
     * Menampilkan detail produk tertentu.
     * (Route: GET /admin/products/{product})
     */
    public function show(Product $product)
    {
        // Tampilkan view detail produk
        return view('admin.products.show', compact('product'));
    }

    /**
     * Menampilkan formulir untuk mengedit produk.
     * (Route: GET /admin/products/{product}/edit)
     */
    public function edit(Product $product)
    {
        $categories = $this->productCategories;
        $vendors = User::where('role', 'vendor')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'vendors'));
    }

    /**
     * Memperbarui produk yang sudah ada di database.
     * (Route: PUT/PATCH /admin/products/{product})
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_per_day' => 'required|numeric|min:1000',
            'stock' => 'required|integer|min:1',
            'vendor_id' => 'required|exists:users,id',
            'category' => ['required', Rule::in($this->productCategories)],
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Gambar opsional saat update
            'status' => 'nullable|string',
        ]);
        
        // Handle update file jika ada file baru diupload
        if ($request->hasFile('picture')) {
            // Hapus file lama jika ada
            if ($product->picture) {
                Storage::disk('public')->delete($product->picture);
            }
            $validatedData['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product->update($validatedData);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Menghapus produk dari database.
     * (Route: DELETE /admin/products/{product})
     */
    public function destroy(Product $product)
    {
        // Hapus file gambar terkait sebelum menghapus record
        if ($product->picture) {
            Storage::disk('public')->delete($product->picture);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}