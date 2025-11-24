@extends('layouts.admin')

@section('content')

<div class="grid grid-cols-1 mx-10 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    {{-- Kartu 1: Total Pengguna --}}
    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-md font-medium text-gray-500">Total Pengguna</p>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalUsers }}</h2>
        </div>
        <div class="p-3 bg-indigo-100 text-indigo-600 rounded-full">
             <i class="fas fa-users fa-2x"></i>
        </div>
    </div>

    {{-- Kartu 2: Total Produk --}}
    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-md font-medium text-gray-500 mb-6">Total Produk</p>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-4">{{ $totalProducts }}</h2>
        </div>
        <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full">
            <i class="fas fa-box fa-2x"></i>
        </div>
    </div>

    {{-- Kartu 3: Pesanan Bulan Ini --}}
    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-md font-medium text-gray-500">Pesanan Bulan Ini</p>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $ordersThisMonth }}</h2>
        </div>
        <div class="p-3 bg-blue-100 text-blue-600 rounded-full">
            <i class="fas fa-calendar-alt fa-2x"></i>
        </div>
    </div>

    {{-- Kartu 4: Pesanan Minggu Ini --}}
    <div class="bg-white p-6 rounded-xl shadow-lg flex items-center justify-between">
        <div>
            <p class="text-md font-medium text-gray-500">Pesanan Minggu Ini</p>
            <h2 class="text-3xl font-extrabold text-gray-900 mt-1">{{ $ordersThisWeek }}</h2>
        </div>
        <div class="p-3 bg-green-100 text-green-600 rounded-full">
            <i class="fas fa-clipboard-list fa-2x"></i>
        </div>
    </div>

</div>

{{-- ... Konten dashboard lainnya --}}
{{-- resources/views/admin/dashboard.blade.php --}}

{{-- ... Kartu Dashboard (Total Users, Products, Orders) ... --}}

<div class="mt-8 mx-10">
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Grafik Pesanan Berdasarkan Kategori Produk</h3>
        
        <div class="relative h-96">
            {{-- Element Canvas untuk Chart.js --}}
            <canvas id="ordersByCategoryChart"></canvas>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
{{-- Pastikan Chart.js sudah dimuat. Jika belum, tambahkan CDN ini: --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. Ambil data dari PHP (ordersByCategory)
    const ordersByCategoryData = @json($ordersByCategory);

    // 2. Persiapkan Label dan Data
    const categories = ordersByCategoryData.map(item => item.category.toUpperCase());
    const orderCounts = ordersByCategoryData.map(item => item.order_count);

    // 3. Inisialisasi Chart.js
    const ctx = document.getElementById('ordersByCategoryChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar', // Gunakan Bar Chart untuk perbandingan
        data: {
            labels: categories,
            datasets: [{
                label: 'Jumlah Pesanan',
                data: orderCounts,
                backgroundColor: [
                    'rgba(79, 70, 229, 0.7)', // Indigo
                    'rgba(16, 185, 129, 0.7)', // Green
                    'rgba(245, 158, 11, 0.7)', // Amber
                    'rgba(239, 68, 68, 0.7)', // Red
                    'rgba(147, 51, 234, 0.7)', // Violet
                    'rgba(6, 182, 212, 0.7)' // Cyan
                ],
                borderColor: 'rgba(55, 48, 163, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Pesanan'
                    },
                    ticks: {
                        // Memastikan hanya angka integer yang tampil di sumbu Y
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false // Sembunyikan legenda karena sudah jelas di label
                }
            }
        }
    });
</script>
@endpush