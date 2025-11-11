@extends('layouts.vendor')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    <div class="bg-white p-6 rounded-xl shadow-lg mb-[64px]">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Produk: {{ $product->name }}</h2>
        <p class="text-gray-600">Perbarui detail produk yang Anda sewakan.</p>
    </div>

    {{-- Kartu Formulir --}}
        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                **Gagal menyimpan perubahan!** Silakan periksa kembali input Anda.
            </div>
        @endif

        {{-- Formulir Update (Menggunakan Method PATCH) --}}
        <form action="{{ route('vendors.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH') {{-- KRITIS: Menggunakan metode HTTP PATCH untuk update --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- 1. Nama Barang --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required 
                           class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 2. Harga Per Hari --}}
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700">Harga Sewa (/Hari)</label>
                    <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day', $product->price_per_day) }}" required min="1000"
                           class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('price_per_day') border-red-500 @enderror">
                    @error('price_per_day') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                {{-- 4. Kategori (ENUM) --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                    <select name="category" id="category" required
                            class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('category') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($categories as $cat) 
                            <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 5. Foto Produk (Kolom 'picture') --}}
                <div class="md:col-span-2">
                    <label for="picture" class="block text-sm font-medium text-gray-700">Ganti Foto Utama Produk</label>
                    <div class="mb-3">
                        <p class="text-xs text-gray-500">Foto Saat Ini:</p>
                        @if ($product->picture)
                            <img src="{{ asset('storage/' . $product->picture) }}" alt="Foto Lama" class="mt-1 h-24 w-24 object-cover rounded-md border">
                        @else
                            <p class="text-sm text-gray-400">Tidak ada foto terpasang.</p>
                        @endif
                    </div>
                    <input type="file" name="picture" id="picture" 
                           class="mt-1 block w-full text-sm text-gray-900 border !border-[2px] !border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('picture') border-red-500 @enderror">
                    @error('picture') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ingin mengubah gambar. Maks. 2MB.</p>
                </div>

                {{-- 6. Deskripsi (Full Width) --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Rinci</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
            
            {{-- Tombol Submit --}}
            <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('vendors.products.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 !bg-red-500 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-600 focus:outline-none">
                    Batal
                </a>
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent !bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div> {{-- Akhir Kartu Formulir --}}

</div>
@endsection