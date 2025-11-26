@extends('layouts.vendor') {{-- Asumsi ini adalah layout vendor Anda --}}

@section('title', 'Dashboard Vendor')

@section('part', 'dashboard')

@section('content')
<div class="container mx-auto px-10 py-8 mb-12">
    {{-- KARTU KPI KINERJA --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        {{-- Kartu 1: Total Pendapatan (Revenue) --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-indigo-500">
            <p class="text-sm font-medium text-gray-500">Total Pendapatan (All Time)</p>
            <h2 class="text-xl font-extrabold text-gray-900 mt-1">
                {{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}
            </h2>
        </div>

        {{-- Kartu 2: Total Produk Terdaftar --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500">
            <p class="text-sm font-medium text-gray-500">Total Produk Aktif</p>
            <h2 class="text-xl font-extrabold text-gray-900 mt-1">{{ $totalProducts }}</h2>
        </div>

        {{-- Kartu 3: Pesanan Menunggu Proses --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500">
            <p class="text-sm font-medium text-gray-500">Pesanan Menunggu Proses</p>
            <h2 class="text-xl font-extrabold text-gray-900 mt-1">{{ $pendingOrders }}</h2>
        </div>

        {{-- Kartu 4: Rating Rata-rata --}}
        <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-pink-500">
            <p class="text-sm font-medium text-gray-500">Rating Rata-rata</p>
            <h2 class="text-xl font-extrabold text-gray-900 mt-1">
                <i class="fas fa-star text-yellow-500"></i> {{ number_format($avgRating, 1) }}
            </h2>
        </div>
    </div>
    
    {{-- BAGIAN AKSI CEPAT DAN STATUS INVENTORY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Produk Perlu Perhatian --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Status Inventory & Perhatian</h3>
            <div class="space-y-4">
                
                {{-- Produk Maintenance --}}
                <div class="p-3 bg-red-50 rounded-lg flex items-center justify-between border border-red-200">
                    <div>
                        <p class="font-semibold text-red-700">Produk dalam Maintenance</p>
                        <p class="text-sm text-red-600">{{ $maintenanceProducts }} item perlu diatur ulang statusnya agar bisa disewa.</p>
                    </div>
                    <a href="{{ route('vendors.products.index', ['status' => 'maintenance']) }}" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        Atur Produk <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                {{-- Pesanan Pending (Ulangi untuk memastikan terlihat) --}}
                <div class="p-3 bg-yellow-50 rounded-lg flex items-center justify-between border border-yellow-200">
                    <div>
                        <p class="font-semibold text-yellow-700">Pesanan Baru Menunggu Proses</p>
                        <p class="text-sm text-yellow-600">{{ $pendingOrders }} pesanan menunggu konfirmasi/pengiriman.</p>
                    </div>
                    <a href="{{ route('vendors.orders.index', ['status' => 'pending']) }}" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                        Proses Sekarang <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

            </div>
        </div>
        
        {{-- Kolom Kanan: Chart Placeholder --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tren Pendapatan (6 Bulan Terakhir)</h3>
        <div class="h-64 relative">
            <canvas id="vendorRevenueChart"></canvas>
        </div>
        </div>  

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Pastikan Chart.js sudah dimuat di layout utama Vendor Anda --}}
<script>
    const revenueTrendData = @json($chartData);
    const primaryColor = 'rgba(79, 70, 229, 0.8)';

    const chartCtx = document.getElementById('vendorRevenueChart').getContext('2d');
    
    new Chart(chartCtx, {
        type: 'line',
        data: {
            labels: revenueTrendData.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: revenueTrendData.revenue,
                borderColor: primaryColor.replace('0.8', '1'),
                backgroundColor: primaryColor.replace('0.8', '0.2'), // Warna area di bawah garis
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Revenue' },
                    ticks: { 
                        // Memformat sumbu Y menjadi format mata uang
                        callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                    }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });
</script>
@endpush