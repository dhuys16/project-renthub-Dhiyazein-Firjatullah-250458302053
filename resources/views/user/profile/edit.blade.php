@extends('layouts.customer') 

@section('title', 'Edit Profil')

@section('part', 'Edit Profil')

@section('content')
<div class="w-full px-6 py-6 mx-auto mb-12">
    
    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Edit Profil Saya</h2>
        
        <a href="{{ route('user.profile.show') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
            ← Kembali ke Detail Profil
        </a>
    </div>

    {{-- KONTEN FORMULIR --}}
    <div class="bg-white p-8 rounded-xl shadow-lg">
        
        {{-- Form menggunakan metode POST, tapi menggunakan @method('PUT') untuk update --}}
        <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Penting untuk memicu method update() di Controller --}}
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">

                {{-- Kolom 1: Informasi Dasar --}}
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Dasar</h3>
                    
                    <div class="mb-6 border p-4 rounded-lg bg-gray-50 text-center">
                        <p class="text-sm font-medium text-gray-700 mb-3">Foto Profil Saat Ini:</p>
                        
                        @php
                            // Tentukan sumber gambar (path)
                            $photoPath = $user->photo_profile 
                                ? asset('storage/' . $user->photo_profile) 
                                : asset('assets/img/user.jpg'); // Ganti 'assets/img/user.jpg' sesuai path default Anda
                        @endphp
                        
                        <img class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-indigo-400" 
                             src="{{ $photoPath }}" 
                             alt="{{ $user->name }}'s Photo">
                    </div>
                    {{-- ⭐ AKHIR AREA PRATINJAU FOTO ⭐ --}}

                    {{-- Input Foto Profil --}}
                    <div class="mb-6">
                        <label for="photo_profile" class="block text-sm font-medium text-gray-700">Ubah Foto Profil</label>
                        <input type="file" name="photo_profile" id="photo_profile"
                               class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md shadow-sm p-2.5 
                                      file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 @error('photo_profile') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Max 2MB (JPG, JPEG, PNG). Biarkan kosong jika tidak ingin mengubah.</p>
                        @error('photo_profile')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="mb-4">
                        <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                        <input type="text" username="username" id="username" required
                               value="{{ old('username', $user->username) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nama Lengkap --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="name" id="name" required
                               value="{{ old('name', $user->name) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email (Login)</label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email', $user->email) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Nomor Telepon --}}
                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
                        <input type="text" name="phone_number" id="phone_number"
                               value="{{ old('phone_number', $user->phone_number) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('phone_number') border-red-500 @enderror">
                        @error('phone_number')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- Kolom 2: Informasi Tambahan Vendor/Alamat --}}
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Detail Identitas & Lokasi</h3>

                    {{-- Alamat Lengkap --}}
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                        <textarea name="address" id="address" rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                        @error('address')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Link Google Maps --}}
                    <div class="mb-4">
                        <label for="link_gmaps" class="block text-sm font-medium text-gray-700">Link Google Maps (Lokasi)</label>
                        <input type="url" name="link_gmaps" id="link_gmaps"
                               value="{{ old('link_gmaps', $user->link_gmaps) }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('link_gmaps') border-red-500 @enderror">
                        @error('link_gmaps')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-4 border-t border-gray-200">
                <button type="submit" class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white !bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                    Simpan Perubahan
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection