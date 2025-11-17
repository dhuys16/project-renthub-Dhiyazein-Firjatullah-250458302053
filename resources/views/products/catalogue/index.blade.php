@extends('layouts.customer') {{-- Menggunakan layout publik/utama --}}

@section('title', 'Katalog Barang RentHub')

@section('content')
<div class="container mx-auto px-4 py-8 lg:pt-5">
    
    <div class="mb-8 flex justify-start">
        {{-- Tombol 'Semua' --}}
        <a href="{{ route('products.index') }}" class="px-4 py-2 text-sm font-medium rounded-full {{ !request()->has('category') ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} transition">Semua</a>
        
        {{-- Tombol Kategori ENUM (Sesuaikan dengan ENUM Anda) --}}
        <a href="{{ route('products.index', ['category' => 'vehicles']) }}" class="ml-3 px-4 py-2 text-sm font-medium rounded-full {{ request('category') == 'vehicles' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} transition">Kendaraan</a>
        
        <a href="{{ route('products.index', ['category' => 'photography']) }}" class="ml-3 px-4 py-2 text-sm font-medium rounded-full {{ request('category') == 'photography' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} transition">Fotografi</a>
        
        <a href="{{ route('products.index', ['category' => 'electronics']) }}" class="ml-3 px-4 py-2 text-sm font-medium rounded-full {{ request('category') == 'electronics' ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-700' }} transition">Elektronik</a>
        
        {{-- Tambahkan kategori ENUM lainnya di sini --}}
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        
        @forelse ($products as $product)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:scale-[1.02]">
            <a href="{{ route('products.show', $product) }}">
            {{-- Foto Produk --}}
            <div class="h-56 overflow-hidden">
                <img src="{{ asset('storage/' . $product->picture) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-contain">
            </div>

            <div class="p-5 flex flex-col flex-grow">
                
                {{-- Nama Barang --}}
                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                
                {{-- Kategori --}}
                <p class="text-xs text-indigo-600 font-medium mb-3">{{ ucfirst($product->category) }}</p>

                {{-- Rating (Model Accessor) --}}
                <div class="flex items-center text-yellow-500 mb-3">
                    @php
                        // Memastikan variabel $rating didefinisikan (menggunakan Accessor/Helper Model)
                        $rating = $product->average_rating ?? 0;
                        $ratingInt = floor($rating);
                    @endphp
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= $ratingInt ? 'text-yellow-500' : 'text-gray-300' }}"></i>
                    @endfor
                    <span class="ml-2 text-sm text-gray-500">({{ number_format($rating, 1) }})</span>
                </div>

                {{-- Harga --}}
                <div class="mt-auto pt-3">
                    <p class="text-2xl font-extrabold text-gray-900 mb-4">
                        {{ $product->getFormattedPriceAttribute() }}
                        <span class="text-sm font-normal text-gray-500">/ Hari</span>
                    </p>
                </div>
                </a>
                {{-- Tombol Pesan Sekarang --}}
                {{-- KRITIS: Mengarahkan ke Route products.show --}}
                <a href="{{ route('products.show', $product) }}" 
                   class="inline-flex justify-center rounded-md border border-transparent bg-green-600 py-3 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 w-full text-center">
                    Pesan Sekarang
                </a>
            </div>
            
        </div>
        @empty
            <div class="col-span-3 text-center py-10 bg-white rounded-lg shadow-md">
                <p class="text-gray-500">Maaf, belum ada produk yang tersedia saat ini.</p>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    <div class="mt-10 flex justify-center">
        {{ $products->links() }}
    </div>

</div>
@endsection