@extends('layouts.customer')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    {{-- HEADER DAN TOMBOL AKSI --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Detail Produk: {{ $product->name }}</h2>
        
        {{-- Tombol Kembali dan Edit (Edit disembunyikan jika ini dilihat oleh Customer, bukan Vendor) --}}
        <div>
            <a href="{{ route('vendors.products.index') }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                ← Kembali ke Daftar
            </a>
            {{-- Tambahkan logika otoritas di sini untuk tombol Edit jika perlu --}}
        </div>
    </div>
    
    {{-- Ambil data rating dan ulasan di awal --}}
    @php
        $avgRating = $product->average_rating ?? 0;
        $reviewCount = $product->reviews->count() ?? 0; 
        $stockAvailable = $product->stock ?? 1; // Pastikan ada variabel stok yang benar
    @endphp

    {{-- Layout TIGA Kolom Utama (Detail dan Form) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-[64px] items-start">

        {{-- KOLOM 1 & 2: Foto, Detail, Deskripsi, Rating, Vendor, dan ULASAN --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg flex flex-col">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
                
                {{-- KIRI: Foto Produk & Rating --}}
                <div class="order-1 md:order-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Foto Utama</h3>
                    
                    {{-- Kotak Foto --}}
                    <div class="w-full h-80 overflow-hidden rounded-lg border-2 border-gray-100 mb-4">
                        @if ($product->picture)
                            <img src="{{ asset('storage/' . $product->picture) }}" 
                                 alt="Foto {{ $product->name }}" 
                                 class="w-full h-full object-contain">
                        @else
                            <div class="flex items-center justify-center h-full text-gray-400">Tidak Ada Foto</div>
                        @endif
                    </div>
                    
                    {{-- Rating di Bawah Foto --}}
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-100 text-center">
                        <p class="text-sm font-medium text-gray-700 mb-1">Rating Produk</p>
                        <span class="text-yellow-500 text-3xl font-extrabold mr-2">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-sm text-gray-500">({{ $reviewCount }} Ulasan)</span>
                    </div>
                </div>

                {{-- KANAN: Detail Utama dan Deskripsi --}}
                <div class="order-2 md:order-none">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Detail Produk</h3>
                    <table class="w-full text-left text-sm text-gray-600">
                        <tbody>
                            <tr class="border-b">
                                <th class="py-2 pr-4 font-medium w-1/3">Harga Sewa</th>
                                <td class="py-2">
                                    <span class="font-bold text-lg text-indigo-600">
                                        {{ $product->price_per_day ? $product->getFormattedPriceAttribute() : '-' }} / Hari
                                    </span>
                                </td>
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
                    <div class="text-base text-gray-700 whitespace-pre-wrap leading-relaxed">
                        {{ $product->description }}
                    </div>
                </div>
            </div>
            
            {{-- INFORMASI KEPEMILIKAN (TETAP DI BAWAH) --}}
            <div class="mt-8 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Informasi Kepemilikan</h3>
                <p class="text-sm text-gray-600">
                    Diunggah Oleh: **{{ $product->vendor->username ?? 'Vendor Tidak Ditemukan' }}**
                </p>
                <p class="text-sm text-gray-600 mt-1">
                    Email Vendor: <span class="font-medium text-indigo-600">{{ $product->vendor->email ?? '-' }}</span>
                </p>
                <p class="text-xs text-gray-400 mt-2">
                    Terakhir Diperbarui: {{ $product->updated_at->diffForHumans() }}
                </p>
            </div>
            
            {{-- ULASAN PELANGGAN (DIPINDAHKAN KE BAWAH INFORMASI KEPEMILIKAN) --}}
            <div class="mt-8 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Ulasan Pelanggan ({{ $reviewCount }})</h3>
                
                <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                    @forelse ($product->reviews as $review)
                        {{-- Elemen Setiap Ulasan --}}
                        <div class="border-b pb-4 last:border-b-0">
                            <div class="flex items-center mb-1">
                                <span class="font-semibold text-sm text-gray-900 mr-2">
                                    {{ $review->customer->name ?? 'Pengguna Anonim' }}
                                </span>
                                {{-- Tampilkan Rating Bintang --}}
                                <span class="text-yellow-500 text-sm">
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->rating)
                                            ★
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 italic mb-1">"{{ $review->comment }}"</p>
                            <span class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        {{-- Placeholder jika tidak ada ulasan --}}
                        <div class="flex items-center justify-center text-center py-4">
                            <p class="text-gray-500 font-medium">
                                Belum ada komentar untuk produk ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>

        {{-- KOLOM 3: FORMULIR PEMESANAN (BARU) --}}
        <div class="lg:col-span-1 flex flex-col gap-6">

            <div class="bg-white p-6 rounded-xl shadow-lg sticky top-6">
                <h3 class="text-xl font-bold text-indigo-600 mb-4">Formulir Pemesanan</h3>
                <p class="text-xs text-gray-500 mb-4">Isi tanggal sewa dan kuantitas di bawah untuk melanjutkan.</p>
                
                {{-- Form yang akan POST ke route booking.checkout --}}
                <form action="{{ route('orders.checkout') }}" method="POST">
                    @csrf
                    
                    {{-- Field tersembunyi untuk ID Produk --}}
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Input Tanggal Mulai Sewa --}}
                    <div class="mb-4">
                        <label for="rent_start_date" class="block text-sm font-medium text-gray-700 mb-1">Mulai Sewa</label>
                        <input type="date" name="rent_start_date" id="rent_start_date" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('rent_start_date') border-red-500 @enderror">
                        @error('rent_start_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Tanggal Akhir Sewa --}}
                    <div class="mb-4">
                        <label for="rent_end_date" class="block text-sm font-medium text-gray-700 mb-1">Akhir Sewa</label>
                        <input type="date" name="rent_end_date" id="rent_end_date" required 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 focus:ring-indigo-500 focus:border-indigo-500 @error('rent_end_date') border-red-500 @enderror">
                        @error('rent_end_date')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Tombol Submit --}}
                    <div class="w-full px-6 py-3 flex justify-center text-base font-medium rounded-md shadow-sm text-white transition duration-150 ease-in-out !bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                        <button type="submit" class="text-xl">                
                            Pesan
                        </button>
                    </div>
                </form>
            </div>
            
        </div>

    </div>

</div>
@endsection