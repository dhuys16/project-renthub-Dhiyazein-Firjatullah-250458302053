<?php

// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon; // Pastikan Carbon diimpor jika Anda menggunakan Laravel yang lebih baru

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Total Users
        $totalUsers = User::count();

        // 2. Total Products
        $totalProducts = Product::count();

        // 3. Pesanan Bulan Ini
        $startOfMonth = now()->startOfMonth();
        $ordersThisMonth = Order::where('created_at', '>=', $startOfMonth)->count();

        // 4. Pesanan Minggu Ini
        $startOfWeek = now()->startOfWeek(Carbon::SUNDAY); // Atau Carbon::MONDAY, tergantung konvensi minggu Anda
        $ordersThisWeek = Order::where('created_at', '>=', $startOfWeek)->count();

        $ordersByCategory = OrderDetail::selectRaw('products.category, COUNT(order_details.id) as order_count')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->groupBy('products.category')
            ->orderByDesc('order_count') // Urutkan dari yang terbanyak
            ->get();
        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'ordersThisMonth', 
            'ordersThisWeek',
            'ordersByCategory'
        ));
    }
}