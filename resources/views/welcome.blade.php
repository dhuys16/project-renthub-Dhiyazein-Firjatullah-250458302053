@extends('layouts.app')

@section('title', 'RentHub - Solusi Sewa Barang Terbaik')

@section('body')

<div class="min-h-screen bg-white">
    
    <header x-data="{ open: false }" class="fixed w-full bg-white shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20 my-4">
                
                {{-- Logo RentHub --}}
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="text-3xl font-extrabold text-indigo-600 ml-6">
                        <img src="{{asset('assets/img/2.jpg')}}" alt="logo" 
                            {{-- [PERBAIKAN]: h-10 untuk mobile, md:h-16 untuk desktop --}}
                            class="h-12 w-auto md:h-16 inline-block">
                    </a>
                </div>

                {{-- Desktop Links (Hidden on mobile) --}}
                <div class="hidden sm:ml-6 sm:flex sm:space-x-4 sm:items-center"> {{-- Menggunakan space-x-4 untuk jarak antar tombol --}}
    
                {{-- Navigasi Utama (Menggunakan Anchor Links) --}}
                <a href="{{ route('products.index') }}" 
                class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-md font-medium transition duration-150">
                    Mulai Sewa
                </a>
                <a href="#about" 
                class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-md font-medium transition duration-150">
                    Tentang Kami
                </a>
                <a href="#faq" 
                class="text-gray-500 hover:text-indigo-600 px-3 py-2 text-md font-medium transition duration-150">
                    FAQ
                </a>

                {{-- ================================================================= --}}
                {{-- Tombol Dashboard/Logout (Jika Login) --}}
                {{-- ================================================================= --}}
                @auth
                    @php
                        $dashboardRoute = Auth::user()->isAdmin() ? route('admin.dashboard') : (Auth::user()->isVendor() ? route('vendors.dashboard') : route('products.index'));
                    @endphp
                    
                    {{-- Tombol 1: Dashboard (Background Solid Indigo) --}}
                    <a href="{{ $dashboardRoute }}" 
                    class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg text-md font-medium transition duration-150 shadow-md">
                        Dashboard
                    </a>
                    
                    {{-- Tombol 2: Logout (Bordered Red) --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                class="border border-red-500 text-red-600 hover:bg-red-50 px-4 py-2 rounded-lg text-md font-medium transition duration-150">
                            Logout
                        </button>
                    </form>
                @endauth

                {{-- ================================================================= --}}
                {{-- Tombol Login/Register (Jika Guest) --}}
                {{-- ================================================================= --}}
                @guest
                    {{-- Tombol 1: Login (Bordered Indigo) --}}
                    <a href="{{ route('login') }}" 
                    class="border border-indigo-500 text-indigo-600 hover:bg-indigo-50 px-4 py-2 rounded-lg text-md font-medium transition duration-150">
                        Login
                    </a>
                    
                    {{-- Tombol 2: Register (Background Solid Indigo) --}}
                    <a href="{{ route('register') }}" 
                    class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg text-md font-medium transition duration-150 shadow-md">
                        Register
                    </a>
                @endguest

            </div>

                {{-- Hamburger Button (Visible on mobile) --}}
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open" type="button" 
                            class="inline-flex items-center text-4xl justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" 
                            aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i x-show="!open" class="fas fa-bars h-12 w-12"></i>
                        <i x-show="open" class="fas fa-times h-12 w-12"></i>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu (Alpine.js Dropdown) --}}
        <div x-show="open" @click.away="open = false" class="sm:hidden" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                
                {{-- Navigasi Utama Mobile (Anchor Links) --}}
                <a href="{{ route('products.index') }}" class="block px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-indigo-600">
                    Mulai Sewa
                </a>
                <a href="#about" @click="open = false" class="block px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-indigo-600">
                    Tentang Kami
                </a>
                <a href="#faq" @click="open = false" class="block px-3 py-2 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-indigo-600">
                    FAQ
                </a>
                
                {{-- ================================================================= --}}
                {{-- Tombol Dashboard/Logout (Jika Login) --}}
                {{-- ================================================================= --}}
                @auth
                    <div class="px-3 pt-4 border-t border-gray-200 space-y-2"> 
                        {{-- Tombol 1: Dashboard (Background Solid Indigo) --}}
                        <a href="{{ $dashboardRoute }}" 
                            class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            Dashboard
                        </a>

                        {{-- Tombol 2: Logout (Background Bordered Merah) --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                class="w-full flex items-center justify-center px-4 py-2 border border-red-500 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 transition">
                                Logout ({{ Auth::user()->name }})
                            </button>
                        </form>
                    </div>
                @endauth

                {{-- ================================================================= --}}
                {{-- Tombol Login/Register (Jika Guest) --}}
                {{-- ================================================================= --}}
                @guest
                    <div class="px-3 pt-4 border-t border-gray-200 space-y-2">
                        {{-- Tombol 1: Login (Bordered Indigo) --}}
                        <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-4 py-2 border border-indigo-500 text-sm font-medium rounded-md text-indigo-600 hover:bg-indigo-50 transition">
                            Login
                        </a>
                        
                        {{-- Tombol 2: Register (Background Solid Indigo) --}}
                        <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition">
                            Register
                        </a>
                    </div>
                @endguest
            </div>
        </div>
    </header>
    <main class="pt-20">

        <div id="hero" class="relative overflow-hidden bg-white"> {{-- Pastikan wrapper luar adalah bg-white --}}
            <div class="max-w-7xl mx-auto">
                
                {{-- [PERBAIKAN KRITIS] Hapus bg-gray-50 dari div di bawah ini --}}
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    
                    {{-- [PERBAIKAN SVG] Tambahkan z-20 untuk memastikan rendering di atas --}}
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2 z-20" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <div class="pt-16 mx-auto max-w-7xl px-4 sm:pt-24 sm:px-6 lg:px-8">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Sewa Apa Saja, Kapan Saja</span>
                                <span class="block text-indigo-600 xl:inline">Tanpa Ribet.</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                Dapatkan akses ke berbagai produk (kendaraan, peralatan, elektronik) dari Vendor terpercaya di seluruh Indonesia.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                
                                {{-- Tombol Mulai Sewa --}}
                                <div class="rounded-md shadow">
                                    <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                                        Mulai Sewa Sekarang
                                    </a>
                                </div>
                                
                                <div class="mt-3 sm:mt-0 sm:ml-3">
                                    <a href="#about" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg md:px-10">
                                        Tentang Kami
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- IMAGE SECTION --}}
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2">
                
                {{-- 1. GAMBAR MOBILE: Tampil di layar kecil, tersembunyi di desktop --}}
                {{-- Asumsi Anda membuat gambar baru yang lebih portrait atau memiliki fokus di tengah, dengan path 'assets/images/hero/mobile-hero.jpg' --}}
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:hidden block" 
                    src="{{ asset('assets/img/mobil.jpg') }}" 
                    alt="Peralatan Disewakan (Mobile)">
                    
                {{-- 2. GAMBAR DESKTOP: Tampil di layar besar, tersembunyi di mobile --}}
                <img class="h-56 w-full object-cover sm:h-72 md:h-96 lg:w-full lg:h-full hidden lg:block" 
                    src="{{ asset('assets/img/1.jpg') }}" 
                    alt="Peralatan Disewakan (Desktop)">
            </div>
        </div>
        
        <section id="about" class="py-20 bg-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">TENTANG KAMI</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Mengapa Harus Memilih RentHub?
                    </p>
                </div>
                
                <div class="mt-10">
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Keamanan Terjamin</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Semua Vendor dan transaksi diverifikasi untuk memastikan proses sewa berjalan aman dan bebas risiko.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <i class="fas fa-boxes"></i>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Katalog Lengkap</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Ratusan jenis produk, mulai dari peralatan fotografi, kendaraan, hingga alat berat tersedia di satu platform.
                            </dd>
                        </div>
                        
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <i class="fas fa-handshake"></i>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Dukungan Penuh</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Tim dukungan siap membantu Anda mulai dari proses pemesanan hingga pengembalian barang sewaan.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Lokasi Fleksibel</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Cari dan sewa barang dari Vendor yang lokasinya terdekat dengan Anda.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:text-center">
            <h2 class="text-base text-indigo-600 font-semibold tracking-wide uppercase">PERTANYAAN UMUM</h2>
            <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                Apa yang Perlu Anda Ketahui?
            </p>
        </div>
        
        <div class="mt-10 space-y-6">
            {{-- FAQ Item 1: Menjadi Vendor --}}
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <button @click="open = !open" class="flex justify-between items-center w-full focus:outline-none">
                    <span class="text-lg font-semibold text-gray-900">Bagaimana cara menjadi Vendor?</span>
                    <i class="fas" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
                </button>
                <p x-show="open" class="mt-4 text-gray-600 transition-all duration-300">
                    Anda harus mendaftar sebagai Customer, lalu melalui halaman Profil, Anda dapat mengajukan permohonan untuk menjadi Vendor dengan melengkapi dokumen yang diperlukan (misalnya KTP/izin usaha).
                </p>
            </div>

            {{-- FAQ Item 2: Jaminan --}}
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <button @click="open = !open" class="flex justify-between items-center w-full focus:outline-none">
                    <span class="text-lg font-semibold text-gray-900">Apakah ada jaminan untuk barang yang disewa?</span>
                    <i class="fas" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
                </button>
                <p x-show="open" class="mt-4 text-gray-600 transition-all duration-300">
                    Ya, setiap transaksi dilengkapi dengan sistem keamanan yang melindungi baik Vendor maupun Customer. Detail jaminan dan deposit diatur per produk oleh masing-masing Vendor.
                </p>
            </div>
            
            {{-- FAQ Item 3: Pembatalan --}}
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <button @click="open = !open" class="flex justify-between items-center w-full focus:outline-none">
                    <span class="text-lg font-semibold text-gray-900">Bagaimana jika saya ingin membatalkan pesanan?</span>
                    <i class="fas" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
                </button>
                <p x-show="open" class="mt-4 text-gray-600 transition-all duration-300">
                    Pembatalan dapat dilakukan melalui halaman Pesanan Anda, selama status pesanan masih 'Pending' atau 'Confirmed'. Jika pesanan sudah dalam proses pengiriman, pembatalan mungkin dikenakan biaya.
                </p>
            </div>

            {{-- [FAQ Item 4: Biaya Layanan] --}}
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <button @click="open = !open" class="flex justify-between items-center w-full focus:outline-none">
                    <span class="text-lg font-semibold text-gray-900">Berapa biaya layanan yang dibebankan kepada Customer?</span>
                    <i class="fas" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
                </button>
                <p x-show="open" class="mt-4 text-gray-600 transition-all duration-300">
                    Sebagai Customer, Anda membayar harga sewa Vendor ditambah 2% biaya layanan *platform*. Biaya ini sudah termasuk dalam Harga yang Ditampilkan di halaman produk.
                </p>
            </div>

            {{-- [FAQ Item 5: Kualitas Produk] --}}
            <div x-data="{ open: false }" class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <button @click="open = !open" class="flex justify-between items-center w-full focus:outline-none">
                    <span class="text-lg font-semibold text-gray-900">Bagaimana saya tahu produk yang saya sewa berkualitas baik?</span>
                    <i class="fas" :class="{'fa-chevron-up': open, 'fa-chevron-down': !open}"></i>
                </button>
                <p x-show="open" class="mt-4 text-gray-600 transition-all duration-300">
                    Setiap produk memiliki ulasan dan rating dari penyewa sebelumnya. Kami sangat menyarankan Anda memilih produk dengan rating tinggi dan meninjau ulasan sebelum membuat pesanan.
                </p>
            </div>
        </div>
    </div>
</section>
    </main>

    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-400">
            &copy; {{ date('Y') }} RentHub. All rights reserved. | <a href="#about" class="hover:text-white">Kebijakan</a>
        </div>
    </footer>

</div>
@endsection