<?php

namespace App\Http\Controllers\Vendor;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendorId = Auth::id();
        
        // 1. Total Produk Terdaftar (scoping ke vendor)
        $totalProducts = Product::where('vendor_id', $vendorId)->count();

        // 2. Rating Rata-rata (scoping Reviews melalui Product)
        $avgRating = Review::whereHas('product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->avg('rating');

        // 3. Pesanan Menunggu Proses (status pending)
        // Order harus terfilter berdasarkan produk milik vendor ini
        $pendingOrders = Order::where('status', 'pending')
            ->whereHas('details.product', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
            })->count();

        // 4. Total Pendapatan (Revenue All-Time)
        // ASUMSI: Menggunakan SUM(products.price_per_day) sebagai proxy untuk revenue
        $totalRevenue = OrderDetail::join('products', 'order_details.product_id', '=', 'products.id')
            ->where('products.vendor_id', $vendorId)
            ->sum('products.price_per_day'); 
            
        // 5. Produk dalam Maintenance (untuk Action Item)
        $maintenanceProducts = Product::where('vendor_id', $vendorId)
            ->where('status', 'maintenance')
            ->count();

        $startDate = Carbon::now()->subMonths(5)->startOfMonth(); 

        $monthlyRevenueTrend = OrderDetail::selectRaw("
            DATE_FORMAT(orders.created_at, '%Y-%m') as order_month, 
            SUM(products.price_per_day) as monthly_revenue"
        )
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id') 
            ->where('products.vendor_id', $vendorId) // Scope ke Vendor saat ini
            ->where('orders.created_at', '>=', $startDate) // Batasi 6 bulan
            ->groupBy('order_month')
            ->orderBy('order_month')
            ->get();
        
        // --- Mempersiapkan array 6 bulan penuh (agar grafik tidak putus) ---
        $monthRange = [];
        $currentMonth = Carbon::now()->subMonths(5)->startOfMonth();
        for ($i = 0; $i < 6; $i++) {
            $monthKey = $currentMonth->format('Y-m');
            $monthLabel = $currentMonth->format('M Y');
            $monthRange[$monthKey] = ['label' => $monthLabel, 'revenue' => 0];
            $currentMonth->addMonth();
        }

        // Gabungkan hasil DB ke dalam array 6 bulan
        foreach ($monthlyRevenueTrend as $revenueData) {
            $monthKey = $revenueData->order_month;
            if (isset($monthRange[$monthKey])) {
                $monthRange[$monthKey]['revenue'] = (float) $revenueData->monthly_revenue;
            }
        }

        $chartData = [
            'labels' => array_column($monthRange, 'label'),
            'revenue' => array_column($monthRange, 'revenue'),
        ];


        // Kirim data ke view
        return view('vendors.dashboard', compact(
            'totalProducts',
            'avgRating',
            'pendingOrders',
            'totalRevenue',
            'maintenanceProducts',
            'chartData'
        ));
    }
}
