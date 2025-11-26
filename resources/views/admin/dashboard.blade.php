@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('part', 'dashboard')

@section('content')
<section class="mb-[80px]">
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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8 mx-10">

        {{-- CHART 1: Total Order Count per Kategori (Bar Chart) --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Total Jumlah Pesanan per Kategori</h3>
            <div class="relative h-96">
                <canvas id="totalOrdersByCategoryChart"></canvas>
            </div>
        </div>

        {{-- CHART 2: Total Order Count Harian (Line Chart) --}}
        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Tren Jumlah Pesanan (30 Hari Terakhir)</h3>
            <div class="relative h-96">
                <canvas id="dailyOrdersChart"></canvas>
            </div>
        </div>

    </div>
</section>
@endsection

@push('scripts')
{{-- Pastikan Chart.js sudah dimuat. Jika belum, tambahkan CDN ini: --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Warna untuk grafik
    const primaryColor = 'rgba(79, 70, 229, 0.8)';
    const secondaryColors = [
        'rgba(16, 185, 129, 0.8)', // Green
        'rgba(245, 158, 11, 0.8)', // Amber
        'rgba(239, 68, 68, 0.8)', // Red
        'rgba(147, 51, 234, 0.8)', // Violet
        'rgba(6, 182, 212, 0.8)' // Cyan
    ];

    // ====================================================================
    // CHART 1: Total Order Count per Kategori (BAR CHART)
    // ====================================================================
    const totalOrdersData = @json($totalOrdersByCategory);
    const chart1Ctx = document.getElementById('totalOrdersByCategoryChart').getContext('2d');
    
    new Chart(chart1Ctx, {
        type: 'bar',
        data: {
            labels: totalOrdersData.map(item => item.category.toUpperCase()),
            datasets: [{
                label: 'Jumlah Pesanan',
                data: totalOrdersData.map(item => item.order_count),
                backgroundColor: secondaryColors,
                borderColor: secondaryColors.map(c => c.replace('0.8', '1')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jumlah Pesanan' },
                    ticks: { 
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                }
            },
            plugins: { legend: { display: false } }
        }
    });

    // ====================================================================
    // CHART 2: Total Order Count Harian (LINE CHART)
    // ====================================================================
    const dailyOrdersData = @json($chart2Data);

    const chart2Ctx = document.getElementById('dailyOrdersChart').getContext('2d');
    
    new Chart(chart2Ctx, {
        type: 'line',
        data: {
            labels: dailyOrdersData.labels.map(date => {
                // Konversi tanggal YYYY-MM-DD menjadi DD Mmm
                const parts = date.split('-');
                return parts[2] + '/' + parts[1];
            }),
            datasets: [{
                label: 'Pesanan Harian',
                data: dailyOrdersData.counts,
                borderColor: primaryColor.replace('0.8', '1'),
                backgroundColor: primaryColor.replace('0.8', '0.2'),
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
                    title: { display: true, text: 'Jumlah Pesanan' },
                    ticks: { 
                        callback: function(value) {
                            if (Number.isInteger(value)) {
                                return value;
                            }
                        }
                    }
                },
                x: {
                    title: { display: true, text: 'Tanggal' }
                }
            },
            plugins: {
                legend: { display: true, position: 'top' }
            }
        }
    });
</script>
@endpush