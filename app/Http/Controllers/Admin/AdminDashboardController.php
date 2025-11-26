<?php

// app/Http/Controllers/Admin/AdminDashboardController.php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon; // Pastikan Carbon diimpor jika Anda menggunakan Laravel yang lebih baru

class AdminDashboardController extends Controller
{
    public function index()
    {
        // ... (Perhitungan kartu statistik lainnya)
         $totalUsers = User::count();

        // 2. Total Products
        $totalProducts = Product::count();

        // 3. Pesanan Bulan Ini
        $startOfMonth = now()->startOfMonth();
        $ordersThisMonth = Order::where('created_at', '>=', $startOfMonth)->count();

        // 4. Pesanan Minggu Ini
        $startOfWeek = now()->startOfWeek(Carbon::SUNDAY); // Atau Carbon::MONDAY, tergantung konvensi minggu Anda
        $ordersThisWeek = Order::where('created_at', '>=', $startOfWeek)->count();
        // --- Data Grafik 1: Total Order Count per Kategori (All-Time) ---
        // Menghitung jumlah order (COUNT(orders.id)) yang terkait dengan setiap kategori produk.
        $totalOrdersByCategory = OrderDetail::selectRaw('products.category, COUNT(orders.id) as order_count')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id') // Join ke orders untuk menghitung order, bukan order_details
            ->groupBy('products.category')
            ->orderByDesc('order_count')
            ->get();

        // --- Data Grafik 2: Total Order Count Harian (30 Hari Terakhir) ---
        $startDate = Carbon::now()->subDays(29)->startOfDay(); // Mulai dari 29 hari yang lalu + hari ini = 30 hari

        $dailyOrdersLast30Days = Order::selectRaw('DATE(created_at) as order_date, COUNT(id) as total_orders')
            ->where('created_at', '>=', $startDate)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('order_date')
            ->get();

        
        // --- Mempersiapkan array 30 hari penuh (penting agar grafik tidak putus) ---
        $dateRange = [];
        $currentDate = Carbon::now()->subDays(29);
        for ($i = 0; $i < 30; $i++) {
            $dateRange[$currentDate->toDateString()] = 0; // Inisialisasi dengan 0
            $currentDate->addDay();
        }

        // Gabungkan hasil DB ke dalam array 30 hari
        foreach ($dailyOrdersLast30Days as $order) {
            $dateRange[$order->order_date->toDateString()] = (int) $order->total_orders;
        }

        $chart2Data = [
            'labels' => array_keys($dateRange), // Tanggal (YYYY-MM-DD)
            'counts' => array_values($dateRange), // Jumlah pesanan
        ];

        // Pass data ke view
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'ordersThisMonth', 
            'ordersThisWeek',
            'totalOrdersByCategory', // Chart 1
            'chart2Data' // Chart 2
        ));
    }
}