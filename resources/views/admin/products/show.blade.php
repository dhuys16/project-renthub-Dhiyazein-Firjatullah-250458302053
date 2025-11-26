@extends('layouts.admin ')

@section('title', 'Detail Produk: ' . $product->name)

@section('part', 'Detail Pengguna')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-xl font-bold text-gray-800">Detail Produk: {{ $product->name }}</h2>
        
        {{-- Tombol Kembali dan Edit --}}
        <div>
            <a href="{{ route('admin.products.index') }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                ← Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- Layout Dua Kolom untuk Detail --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-[64px]">

        {{-- KOLOM 1 & 2: Foto dan Deskripsi --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Foto Produk --}}
                <div class="order-1 md:order-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Foto Utama</h3>
                    <div class="w-full h-80 overflow-hidden rounded-lg border-2 border-gray-100">
                        @if ($product->picture)
                            <img src="{{ asset('storage/' . $product->picture) }}" 
                                 alt="Foto {{ $product->name }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">Tidak Ada Foto</div>
                        @endif
                    </div>
                </div>

                {{-- Detail Utama --}}
                <div class="order-2 md:order-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Detail Produk</h3>
                    <table class="w-full text-left text-sm text-gray-600">
                        <tbody>
                            <tr class="border-b">
                                <th class="py-2 pr-4 font-medium w-1/3">Harga Sewa</th>
                                <td class="py-2">**{{ $product->price_per_day ? $product->getFormattedPriceAttribute() . ' / Hari' : '-' }}**</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 pr-4 font-medium">Stok Tersedia</th>
                                <td class="py-2 text-lg font-bold text-green-600">{{ $product->stock }} Unit</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 pr-4 font-medium">Kategori</th>
                                <td class="py-2">{{ ucfirst($product->category) }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 pr-4 font-medium">Status</th>
                                <td class="py-2">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($product->status ?? 'Draft') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-800 mt-6 mb-3 border-b pb-2">Deskripsi</h3>
                    <p class="text-base text-gray-700 whitespace-pre-wrap">{{ $product->description }}</p>
                </div>
            </div>
        </div>

        {{-- KOLOM 3: Statistik dan Vendor --}}
        <div class="lg:col-span-1 flex flex-col gap-6">

            {{-- Statistik Rating & Ulasan --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Statistik</h3>
                
                {{-- Asumsi helper rating ada --}}
                @php
                    $avgRating = $product->average_rating ?? 0;
                    $reviewCount = $product->reviews->count() ?? 0; 
                @endphp
                
                <div class="flex items-center justify-between mb-4">
                    <span class="text-yellow-500 text-2xl font-bold">{{ number_format($avgRating, 1) }}</span>
                    <span class="text-sm text-gray-500">{{ $reviewCount }} Ulasan</span>
                </div>

                <div class="text-center">
                    <i class="fas fa-chart-bar text-indigo-500 mr-2"></i>
                    {{-- Menggunakan atribut order_details_count yang dimuat oleh Controller --}}
                    <span class="text-sm text-gray-600">Total disewa: <strong>{{ $product->order_details_count ?? 0 }}</strong> kali </span> 
                </div>
            </div>

            {{-- Detail Vendor --}}
            <div class="bg-white p-4 rounded-lg shadow-md mb-6 border-l-4 border-indigo-500">
                <h4 class="font-semibold text-lg mb-3">
                    <i class="fas fa-store mr-2 text-indigo-500"></i> Info Kepemilikan
                </h4>
                
                @php
                    $vendor = $product->vendor; // Ambil objek Vendor (User Model)
                    
                    // Logika foto profil dinamis
                    $photoPath = $vendor->photo_profile 
                        ? asset('storage/' . $vendor->photo_profile) 
                        : asset('assets/img/user.jpg');
                @endphp

                {{-- [PERBAIKAN KRITIS] --}}
                {{-- 1. Mengubah 'items-center' menjadi 'items-end' untuk perataan rata bawah. --}}
                <a href="{{ route('user.public.vendors.show', $vendor) }}" 
                class="block p-3 -m-3 rounded-lg hover:bg-gray-100 transition duration-150 ease-in-out cursor-pointer flex items-end space-x-4">
                    
                    {{-- 2. Mengubah ukuran menjadi h-16 w-16 untuk tampilan yang lebih besar dan jelas. --}}
                    {{-- 'object-cover' akan memastikan foto tetap proporsional dan tidak gepeng. --}}
                    <img class="h-16 w-16 rounded-full object-cover border border-gray-300 shadow-sm flex-shrink-0" 
                        src="{{ $photoPath }}" 
                        alt="{{ $vendor->username ?? $vendor->name }}">

                    <div>
                        {{-- Username Vendor --}}
                        <p class="text-base font-bold text-gray-900 leading-tight">
                            {{ $vendor->username ?? $vendor->name }}
                        </p>
                        {{-- Label --}}
                        <p class="text-sm text-indigo-600 mt-1 flex items-center">
                            <i class="fas fa-external-link-alt mr-1 text-xs"></i> Kunjungi Toko
                        </p>
                    </div>
                </a>
            </div>
            
        </div>

    </div>

</div>
@endsection