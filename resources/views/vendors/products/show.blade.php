@extends('layouts.vendor')

@section('title', 'Detail Produk: ' . $product->name)

@section('part', 'Detail Produk')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Detail Produk: {{ $product->name }}</h2>
        
        {{-- Tombol Kembali dan Edit --}}
        <div>
            <a href="{{ route('vendors.products.index') }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                ← Kembali ke Daftar
            </a>
            <a href="{{ route('vendors.products.edit', $product) }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-md hover:bg-indigo-700 transition ml-2">
                Edit Produk
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
                    <span class="text-sm text-gray-600">Total disewa: **X** kali (Perlu Query Transaksi)</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-xl mb-6 border-t-4 border-indigo-500">
                <h3 class="text-xl font-bold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-cogs mr-3 text-indigo-500"></i> Kelola Status Produk
                </h3>

                <div class="border-t pt-4">
                    <p class="max-w-[80%] text-gray-700 mb-4 text-lg">
                        Status saat ini: 
                        {{-- Menampilkan status dengan styling dinamis --}}
                        <span class="font-bold uppercase text-sm px-3 py-1 ml-2 rounded-full 
                        @if ($product->status == 'available') 
                            bg-green-100 text-green-800
                        @elseif ($product->status == 'maintenance') 
                            bg-yellow-100 text-yellow-800
                        @else
                            bg-gray-100 text-gray-800
                        @endif">
                            {{ $product->status }}
                        </span>
                    </p>


                    @if ($product->status == 'available' || $product->status == 'maintenance')
                        @php
                            // Logika penentuan status baru dan tombol
                            $nextStatus = ($product->status === 'available') ? 'maintenance' : 'available';
                            $buttonLabel = ($product->status === 'available') ? 'Ubah ke Maintenance' : 'Ubah ke Available';
                            $buttonClass = ($product->status === 'available') ? '!bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-500' : '!bg-green-500 hover:bg-green-600 focus:ring-green-500';
                            $confirmMessage = "Apakah Anda yakin ingin mengubah status produk ini menjadi " . ucfirst($nextStatus) . "?";
                        @endphp
                    
                        {{-- Form untuk mengirim permintaan perubahan status --}}
                        <form action="{{ route('products.update.status', $product) }}" 
                            method="POST" 
                            onsubmit="return confirm('{{ $confirmMessage }}')">
                            @csrf
                            @method('PATCH')
                            
                            <button type="submit" 
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-transparent {{ $buttonClass }} py-3 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2">
                                <i class="fas fa-exchange-alt mr-2"></i> {{ $buttonLabel }}
                            </button>
                        </form>
                    @else
                        <div class="p-3 bg-red-50 border-l-4 border-red-500 text-red-700">
                            <p class="text-sm">
                                Produk ini berstatus **{{ ucfirst($product->status) }}**. Perubahan status manual tidak diizinkan.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>

    </div>

</div>
@endsection