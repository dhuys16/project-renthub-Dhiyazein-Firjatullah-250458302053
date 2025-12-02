@extends('layouts.customer') {{-- Asumsi ini menggunakan layout Customer --}}

@section('title', 'Jadilah Vendor')

@section('part', 'Gabung Vendor')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white p-8 md:p-10 rounded-xl shadow-2xl border-t-8 border-indigo-600">

        <header class="text-center mb-10">
            {{-- Header --}}
            <h1 class="text-4xl font-extrabold text-indigo-600">🤝 Bergabunglah sebagai Vendor RentHub</h1>
            <p class="mt-4 text-lg text-gray-600">
                Ubah aset-aset Anda yang menganggur menjadi sumber penghasilan pasif yang menguntungkan. RentHub siap menghubungkan Anda dengan ribuan penyewa potensial!
            </p>
        </header>

        <hr class="my-8 border-gray-200">

        {{-- KEUNTUNGAN EKSKLUSIF --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">✨ Keuntungan Eksklusif Menjadi Vendor</h2>
            <p class="mb-6 text-gray-600">Menjadi Vendor RentHub memberikan Anda kendali penuh dan potensi pertumbuhan yang besar.</p>

            <dl class="space-y-6">
                {{-- Item 1: Penghasilan Pasif --}}
                <div class="flex items-start">
                    <i class="fas fa-money-bill-wave text-green-500 w-6 h-6 mr-4 mt-1"></i>
                    <div>
                        <dt class="text-lg font-semibold text-gray-900">💰 Penghasilan Pasif Maksimal</dt>
                        <dd class="text-gray-600">Dapatkan uang dari barang-barang yang Anda sewakan—tanpa perlu repot mencari penyewa.</dd>
                    </div>
                </div>

                {{-- Item 2: Manajemen Mudah --}}
                <div class="flex items-start">
                    <i class="fas fa-tachometer-alt text-indigo-500 w-6 h-6 mr-4 mt-1"></i>
                    <div>
                        <dt class="text-lg font-semibold text-gray-900">💡 Manajemen Super Mudah</dt>
                        <dd class="text-gray-600">Kelola semua produk, pesanan, dan keuangan Anda dari satu dashboard Vendor yang intuitif.</dd>
                    </div>
                </div>

                {{-- Item 3: Jangkauan Pasar Luas --}}
                <div class="flex items-start">
                    <i class="fas fa-chart-line text-yellow-500 w-6 h-6 mr-4 mt-1"></i>
                    <div>
                        <dt class="text-lg font-semibold text-gray-900">📈 Jangkauan Pasar yang Luas</dt>
                        <dd class="text-gray-600">Produk Anda akan dilihat oleh komunitas penyewa RentHub yang besar dan terpercaya, meningkatkan peluang sewa berkali-kali lipat.</dd>
                    </div>
                </div>

                {{-- Item 4: Keamanan --}}
                <div class="flex items-start">
                    <i class="fas fa-lock text-blue-500 w-6 h-6 mr-4 mt-1"></i>
                    <div>
                        <dt class="text-lg font-semibold text-gray-900">🛡️ Keamanan Terjamin</dt>
                        <dd class="text-gray-600">Kami mendukung Anda dengan sistem yang jelas dan persyaratan yang melindungi aset Anda.</dd>
                    </div>
                </div>
            </dl>
        </section>

        <hr class="my-8 border-gray-200">

        {{-- LANGKAH AKTIVASI --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">🚀 Apa yang Harus Anda Lakukan? (3 Langkah Cepat)</h2>
            <p class="mb-6 text-gray-600">Proses peralihan role dari Customer ke Vendor sangat mudah dan cepat!</p>

            <ol class="space-y-4 list-decimal list-inside text-gray-600">
                <li class="text-lg font-semibold text-gray-900">Aktivasi Akun Vendor</li>
                <p class="ml-6 text-gray-600">Klik tombol di bawah untuk mengaktifkan akun Vendor Anda.</p>

                <li class="text-lg font-semibold text-gray-900">Unggah & Promosikan Produk</li>
                <p class="ml-6 text-gray-600">Tambahkan foto, deskripsi detail, dan tentukan harga sewa harian/mingguan/bulanan.</p>

                <li class="text-lg font-semibold text-gray-900">Konfirmasi & Sewakan</li>
                <p class="ml-6 text-gray-600">Setujui pesanan yang masuk melalui dashboard Vendor Anda. Selamat, barang Anda kini menghasilkan uang!</p>
            </ol>
        </section>

        <hr class="my-8 border-gray-200">

        {{-- PERSYARATAN --}}
        <section class="mb-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">✅ Persyaratan Dasar</h2>
            <p class="text-gray-600">Kami berkomitmen untuk membangun komunitas penyewaan yang terpercaya dan bertanggung jawab.</p>
            
            <ul class="list-disc list-inside mt-4 text-gray-700 space-y-2">
                {{-- [KETENTUAN BARU]: Biaya Layanan --}}
                <li>Setiap biaya sewa yang dibayarkan customer akan dipotong sebesar **2%** sebagai biaya layanan (service fee) RentHub.</li>
                
                <li>Anda setuju untuk bertanggung jawab penuh atas kondisi produk yang disewakan dan mematuhi semua ketentuan layanan RentHub.</li>
        </section>

        {{-- TOMBOL CALL TO ACTION --}}
        <div class="text-center mt-10">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">➡️ Jadi Vendor Sekarang!</h2>
            
            {{-- Tombol: Jadi Vendor Sekarang --}}
            {{-- Asumsi route untuk mengaktifkan status/mengubah role adalah 'customer.vendor.activate' --}}
            <form method="POST" action="{{ route('customer.vendor.register') }}">
                @csrf
                
                {{-- Tombol Submit Form --}}
                <button type="submit"
                class="inline-flex justify-center py-4 px-4 border border-transparent shadow-lg text-lg font-medium rounded-xl text-white  !bg-green-600 hover:bg-green-700 transition duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    JADI VENDOR SEKARANG
                </button>
            </form>
        </div>
    </div>
</div>
@endsection