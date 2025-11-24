@extends('layouts.customer') 

@section('title', 'Profil Saya')

@section('part', 'Profil Saya')

@section('content')
<div class="w-full px-6 py-6 mx-auto mb-12">
    
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- HEADER DAN TOMBOL EDIT --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Detail Profil Saya</h2>
        
        <a href="{{ route('user.profile.edit') }}" 
           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg shadow-md hover:bg-indigo-700 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-7-3l-4 4m8-4l-4 4m8-4l-4 4m0 0l-4-4m4 4l4-4m-4 4V7a2 2 0 00-2-2h-3m-7 0a2 2 0 012-2h3"></path></svg>
            Edit Profil
        </a>
    </div>

    {{-- KONTEN DETAIL PROFIL --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Kolom Kiri: Ringkasan Dasar & Role --}}
        <div class="lg:col-span-1 bg-white p-6 rounded-xl shadow-lg text-center">
            <div class="mb-4">
                @php
                    // Tentukan sumber gambar (path)
                    // Asumsi kolom foto di DB adalah 'photo_profile'
                    $photoPath = $user->photo_profile 
                        ? asset('storage/' . $user->photo_profile) 
                        : asset('assets/img/user.jpg'); // Ganti 'assets/img/user.jpg' sesuai path default Anda
                @endphp
                
                <img class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-indigo-200" 
                    src="{{ $photoPath }}" 
                    alt="{{ $user->username ?? $user->name }}'s Photo">
            </div>
            
            <h3 class="text-2xl font-bold text-gray-900">{{ $user->username ?? $user->name }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ $user->email }}</p>
            
            <span class="px-4 py-1 text-xs font-semibold rounded-full 
                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 
                            ($user->role === 'vendor' ? 'bg-indigo-100 text-indigo-800' : 
                            'bg-green-100 text-green-800') }}">
                {{ ucfirst($user->role) }}
            </span>

            @if ($user->role !== 'vendor')
                <div class="mt-4">
                    <a href="{{ route('customer.vendor.form') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Ingin Menjadi Vendor?
                    </a>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan: Detail Kontak & Alamat --}}
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Akun Lengkap</h3>
            
            <table class="w-full text-left text-base text-gray-600">
                <tbody>
                    
                    {{-- Nama Lengkap --}}
                    <tr class="border-b">
                        <th class="py-3 pr-4 font-medium w-1/3">Username</th>
                        <td class="py-3">{{ $user->username }}</td>
                    </tr>

                    <tr class="border-b">
                        <th class="py-3 pr-4 font-medium w-1/3">Nama Lengkap</th>
                        <td class="py-3">{{ $user->name }}</td>
                    </tr>
                    
                    {{-- Email --}}
                    <tr class="border-b">
                        <th class="py-3 pr-4 font-medium w-1/3">Email (Login)</th>
                        <td class="py-3">{{ $user->email }}</td>
                    </tr>
                    
                    {{-- Nomor Telepon --}}
                    <tr class="border-b">
                        <th class="py-3 pr-4 font-medium w-1/3">Nomor Telepon</th>
                        <td class="py-3">{{ $user->phone_number ?? 'Belum Diisi' }}</td>
                    </tr>
                    
                    {{-- Alamat --}}
                    <tr>
                        <th class="py-3 pr-4 font-medium align-top">Alamat Lengkap</th>
                        <td class="py-3 whitespace-pre-wrap">{{ $user->address ?? 'Belum Diisi' }}</td>
                    </tr>

                    {{-- Link Google Maps --}}
                    <tr>
                        <th class="py-3 pr-4 font-medium align-top">Lokasi (Gmaps)</th>
                        <td class="py-3">
                            @if ($user->link_gmaps)
                                <a href="{{ $user->link_gmaps }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 underline">
                                    Lihat Lokasi di Google Maps
                                </a>
                            @else
                                Belum Diisi
                            @endif
                        </td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection