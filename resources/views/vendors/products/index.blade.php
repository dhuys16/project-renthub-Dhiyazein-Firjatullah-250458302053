@extends('layouts.vendor')

@section('title', 'Manajemen Produk')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="mb-6 flex justify-end">
        <a href="{{ route('vendors.products.create') }}" 
           class="inline-block px-6 py-2.5 font-bold text-center text-white align-middle transition-all bg-indigo-600 rounded-lg shadow-md hover:bg-indigo-700">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse ($products as $product)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden transition-shadow duration-300 hover:shadow-2xl">
            
            {{-- Foto Produk --}}
            <div class="h-48 overflow-hidden">
                {{-- Asumsi kolom 'picture' menyimpan path gambar --}}
                <a href="{{ route('vendors.products.show', $product) }}">
                <img src="{{ asset('storage/' . $product->picture) }}" 
                     alt="Foto {{ $product->name }}" 
                     class="w-full h-full object-contain">
                </a>
            </div>

            <div class="p-4">
                {{-- Nama Barang --}}
                <a href="{{ route('vendors.products.show', $product) }}">
                    <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                    
                    {{-- Rating (Asumsi Anda punya helper getAvgRatingAttribute di Model Product) --}}
                    <div class="flex items-center text-yellow-500 mb-3">
                        @php
                            // Menggunakan accessor yang sudah dibuat di Model Product
                            $rating = $product->average_rating ?? 0; 
                            $ratingInt = floor($rating);
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $ratingInt)
                                <i class="fas fa-star text-sm text-yellow-500"></i>
                            @else
                                <i class="far fa-star text-sm text-gray-300"></i>
                            @endif
                        @endfor
                        <span class="ml-2 text-sm text-gray-500">({{ number_format($rating, 1) }})</span>
                    </div>

                    {{-- Detail Tambahan --}}
                    <p class="text-sm text-gray-600 mb-4">
                        Kategori: {{ ucfirst($product->category) }}
                    </p>

                    <p class="px-3 py-1 text-xs font-semibold rounded-full {{ $product->status == 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($product->status ?? 'Draft') }}
                    </p>
                </a>
                {{-- Tombol Aksi vendor --}}
                <div class="flex justify-start gap-3 items-center">
                    {{-- Tombol Edit --}}
                    <a href="{{ route('vendors.products.edit', $product) }}" 
                       class="text-sm font-semibold !text-white !bg-blue-500 !hover:bg-blue-700 py-1 px-3 rounded transition duration-150">
                        Edit
                    </a>
                    
                    {{-- Tombol Hapus (Menggunakan Form POST/DELETE) --}}
                    <form action="{{ route('vendors.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="!bg-red-500 !hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition duration-150">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
            <div class="col-span-3 text-center py-10 bg-white rounded-lg shadow-md">
                <p class="text-gray-500">Belum ada produk yang terdaftar dalam sistem.</p>
            </div>
        @endforelse
    </div>
    
    {{-- Pagination --}}
    <div class="mt-6">
        {{ $products->links() }}
    </div>

</div>
@endsection