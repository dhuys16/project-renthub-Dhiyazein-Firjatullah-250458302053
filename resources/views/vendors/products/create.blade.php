@extends('layouts.vendor')

@section('title','Tambah Produk Baru')

@section('part', 'Tambah Produk')

@section('content')

<div class="w-full px-6 py-6 mx-auto">
    <div class="mb-6 bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Tambah Produk Baru ke RentHub</h2>
        <p class="text-gray-600">Isi detail produk yang ingin Anda sewakan.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg mb-[64px]">
    
    {{-- Kartu Formulir --}}
        
        {{-- Pesan Sukses/Error --}}
        @if (session('success'))
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                **Gagal menyimpan produk!** Silakan periksa kembali input Anda.
            </div>
        @endif

        {{-- Formulir POST ke VendorProductController@store --}}
        <form action="{{ route('vendors.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- 1. Nama Barang --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm @error('name') border-red-500 @enderror">
                    @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 2. Harga Per Hari --}}
                <div>
                    <label for="price_per_day" class="block text-sm font-medium text-gray-700">Harga Sewa (/Hari)</label>
                    <input type="number" name="price_per_day" id="price_per_day" value="{{ old('price_per_day') }}" required min="1000"
                           class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm @error('price_per_day') border-red-500 @enderror">
                    @error('price_per_day') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 3. Kategori (ENUM) --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Kategori Produk</label>
                    <select name="category" id="category" required
                            class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm @error('category') border-red-500 @enderror">
                        <option value="">-- Pilih Kategori --</option>
                        {{-- Variabel $categories dikirim dari Controller VendorProductController@create --}}
                        @foreach ($categories as $cat) 
                            <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                {{ ucfirst($cat) }}
                            </option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- 4. Foto Produk (Kolom 'picture') --}}
                <div class="md:col-span-2">
                    <label for="picture" class="block text-sm font-medium text-gray-700">Foto Utama Produk</label>
                    <input type="file" name="picture" id="picture" required 
                           class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none @error('picture') border-red-500 @enderror">
                    @error('picture') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-1 text-xs text-gray-500">Maksimum 2MB (JPG, JPEG, PNG). Akan disimpan di kolom 'picture'.</p>
                </div>

                {{-- 5. Deskripsi (Full Width) --}}
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi Rinci</label>
                    <textarea name="description" id="description" rows="4" required
                              class="mt-1 block w-full rounded-md !border-[2px] !border-gray-300 shadow-sm @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
            
            {{-- Tombol Submit --}}
            <div class="mt-8 pt-4 border-t border-gray-200 flex justify-end">
                <button type="submit" class="inline-flex justify-center rounded-md !border !border-transparent !bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Simpan Produk
                </button>
            </div>
        </form>

    </div> {{-- Akhir Kartu Formulir --}}

</div>

@endsection