@extends('layouts.customer') {{-- Menggunakan layout customer --}}

@section('title', 'Katalog Vendor: ' . $vendor->name)

@section('part', 'Vendor Profile')

@section('content')
<div class="container mx-auto px-4 py-8 lg:pt-5">

    <div class="mb-6 bg-white w-full px-6 py-4 rounded-xl shadow-lg">
        {{-- Tombol Kembali ke Halaman Sebelumnya --}}
        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 text-lg font-medium inline-flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Halaman Sebelumnya
        </a>
    </div>
    {{-- ===================================================================== --}}
    {{-- BAGIAN 1: INFORMASI VENDOR --}}
    {{-- ===================================================================== --}}
    <div class="bg-white p-6 md:p-10 rounded-xl shadow-2xl mb-6 border-t-8 border-indigo-600">
        
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6">
            <i class="fas fa-store mr-3 text-indigo-600"></i> Profil Vendor
        </h1>

        <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-10 border-b pb-6">
            
            {{-- Foto Profil Vendor --}}
            <div class="text-center">
                @php
                    // Logika foto profil yang telah dikonfirmasi berhasil (menggunakan $vendor)
                    $photoPath = $vendor->photo_profile 
                        ? asset('storage/' . $vendor->photo_profile) 
                        : asset('assets/img/user.jpg');
                @endphp
                
                <img class="rounded-full object-cover border-4 border-indigo-500 shadow-md" width="200" 
                     src="{{ $photoPath }}" 
                     alt="{{ $vendor->name }}'s Photo">
                <p class="text-gray-500 text-sm mt-2 font-medium">Vendor Sejak: {{ $vendor->created_at->format('d M Y') }}</p>
            </div>

            {{-- Detail Info Vendor --}}
            <div class="grid grid-cols-1     sm:grid-cols-2 gap-x-12 gap-y-4 w-full">
                
                {{-- Nama Vendor --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama Toko/Vendor</p>
                    <p class="text-xl font-bold text-gray-800">{{ $vendor->name }}</p>
                </div>
                
                {{-- Email --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">Email Kontak</p>
                    <p class="text-lg text-indigo-600 font-semibold">{{ $vendor->email }}</p>
                </div>
                
                {{-- No. Telp --}}
                <div>
                    <p class="text-sm font-medium text-gray-500">No. Telepon</p>
                    <p class="text-lg text-gray-800 font-semibold">{{ $vendor->phone_number ?? 'Tidak tersedia' }}</p>
                </div>

                {{-- Alamat --}}
                <div class="sm:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Alamat Vendor</p>
                    <p class="text-lg text-gray-800 font-medium leading-relaxed">{{ $vendor->address ?? 'Alamat belum diisi.' }}</p>
                    
                    @if ($vendor->address)
                        @php
                            $googleMapsUrlText = ($vendor->link_gmaps);
                        @endphp
                        <a href="{{ $googleMapsUrlText }}" target="_blank" 
                           class="inline-flex items-center text-indigo-500 hover:text-indigo-700 font-medium text-sm mt-2">
                            <i class="fas fa-map-marker-alt mr-2"></i> Cari di Google Maps
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- ===================================================================== --}}
    {{-- BAGIAN 2: DAFTAR PRODUK YANG DISEWAKAN (Grid 3 Kolom) --}}
    {{-- ===================================================================== --}}
    <div class="bg-white p-3 md:p-5 rounded-xl shadow-2xl mb-4">
    <h2 class="text-3xl font-extrabold text-gray-900">
        Produk Tersedia dari {{ $vendor->name }} ({{ $products->total() }} Item)
    </h2>
    </div>
    @if ($products->isEmpty())
        <div class="col-span-3 text-center py-10 bg-white rounded-lg shadow-md">
            <p class="text-gray-500">Maaf, vendor ini belum memiliki produk yang tersedia saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            @foreach ($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden flex flex-col transition-all duration-300 hover:shadow-2xl hover:scale-[1.02]">
                <a href="{{ route('products.show', $product) }}">
                {{-- Foto Produk --}}
                <div class="h-56 overflow-hidden">
                    <img src="{{ asset('storage/' . $product->picture) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-full object-contain">
                </div>

                <div class="p-5 flex flex-col flex-grow ">
                    
                    {{-- Nama Barang --}}
                    <div class="mb-2">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                    </div>
                    <div class="mb-2">   
                    {{-- [MODIFIKASI 2]: Tampilkan Status Produk --}}
                    <p class="text-xs font-medium ">
                        Status: 
                        <span class="font-semibold px-2  rounded {{ $product->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($product->status) }}
                        </span>
                    </p>
                    </div>

                    {{-- Kategori --}}
                    <div class="mb-2">
                    <p class="text-xs text-indigo-600 font-medium">{{ ucfirst($product->category) }}</p>
                    </div>
                    {{-- Rating --}}
                    <div class="flex items-center text-yellow-500 mb-3">
                        @php
                            $rating = $product->average_rating ?? 0;
                            $ratingInt = floor($rating);
                            $reviewCount = $product->reviews_count ?? 0;
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $ratingInt)
                                <i class="fas fa-star text-sm text-yellow-500"></i>
                            @else
                                <i class="far fa-star text-sm text-gray-300"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-sm text-gray-500">({{ number_format($rating, 1) }}) ({{ $reviewCount }} ulasan)</span>
                    </div>

                    {{-- Harga --}}
                    <div class="mt-auto pt-3">
                        <p class="text-2xl font-extrabold text-gray-900 mb-4">
                            {{ $product->getFormattedPriceAttribute() }}
                            <span class="text-sm font-normal text-gray-500">/ Hari</span>
                        </p>
                    </div>
                    </a>
                    
                    {{-- [MODIFIKASI 3]: Tombol Sewa/Lihat Detail (Dinonaktifkan jika maintenance) --}}
                    @php
                        $isDisabled = $product->status !== 'available';
                    @endphp
                    <a href="{{ route('products.show', $product) }}" 
                       class="inline-flex justify-center rounded-md border border-transparent py-3 text-sm font-medium text-white shadow-sm w-full text-center
                       {{ $isDisabled ? 'bg-gray-400 cursor-not-allowed pointer-events-none' : 'bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2' }}"
                       @if ($isDisabled) aria-disabled="true" @endif>
                        {{ $isDisabled ? 'Sedang Maintenance' : 'Lihat Detail & Sewa' }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        <div class="mt-10 flex justify-center">
            {{ $products->links() }}
        </div>
    @endif

</div>
@endsection