@extends('layouts.vendor')

@section('title', 'Profil Pelanggan: ' . $customer->username)

@section('content')
<div class="w-full py-6 px-6">
    
    {{-- Header --}}
    <div class="flex flex-wrap mb-6">
        <div class="flex-none w-full px-3">
            <div class="flex justify-between items-center bg-white p-6 rounded-xl shadow-xl">
                <h6 class="dark:text-white text-xl font-bold">Detail Pemesan: {{ $customer->username }}</h6>
                <a href="{{ route('admin.users.index') }}" 
                   class="text-sm font-semibold text-blue-500 hover:text-blue-700 transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap">
        
        {{-- KOLOM KIRI: INFO DASAR & GAMBAR --}}
        <div class="w-full max-w-full px-3 lg:w-4/12">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl rounded-2xl bg-clip-border text-center">
                <div class="p-6">
                    @php
                        // Logika untuk menentukan path foto profil
                        $photoPath = $customer->photo_profile 
                            ? asset('storage/' . $customer->photo_profile) 
                            : asset('assets/img/user.jpg'); 
                    @endphp
                    
                    <img src="{{ $photoPath }}" 
                         alt="Foto Profil"
                         class="inline-flex items-center justify-center mx-auto mb-4 h-24 w-24 rounded-full object-cover border-4 border-{{ $customer->role === 'vendor' ? 'indigo' : 'green' }}-300">
                    
                    <h5 class="font-bold text-lg text-slate-700">{{ $customer->name }}</h5>
                    <p class="text-sm text-slate-400 mb-4">{{ $customer->email }}</p>
                    
                    <span class="px-3 py-1 text-xs font-semibold uppercase rounded-full 
                                 {{ $customer->role === 'admin' ? 'bg-red-100 text-red-800' : 
                                    ($customer->role === 'vendor' ? 'bg-indigo-100 text-indigo-800' : 
                                    'bg-green-100 text-green-800') }}">
                        {{ ucfirst($customer->role) }}
                    </span>
                    
                    @if ($customer->role === 'vendor')
                    <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm font-semibold text-slate-600">Total Produk: {{ $customer->products_count ?? '0' }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: DETAIL LENGKAP & KONTAK --}}
        <div class="w-full max-w-full px-3 lg:w-8/12">
            <div class="relative flex flex-col min-w-0 break-words bg-white border-0 border-solid shadow-xl rounded-2xl bg-clip-border">
                <div class="p-6">
                    <h6 class="font-bold text-lg mb-4 border-b pb-2">Informasi Akun & Kontak</h6>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm text-slate-600">
                        
                        <div>
                            <p class="font-semibold text-slate-700">Nama Lengkap:</p>
                            <p class="mb-3">{{ $customer->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700">Username:</p>
                            <p class="mb-3">{{ $customer->username }}</p>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700">Nomor Telepon:</p>
                            <p class="mb-3">{{ $customer->phone_number ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="font-semibold text-slate-700">Email:</p>
                            <p class="mb-3">{{ $customer->email }}</p>
                        </div>
                        
                        <div>
                            <p class="font-semibold text-slate-700">Terdaftar Sejak:</p>
                            <p class="mb-3">{{ $customer->created_at->format('d M Y') }}</p>
                        </div>
                        
                        @if ($customer->role === 'vendor')
                        <div>
                            <p class="font-semibold text-slate-700">Status Verifikasi:</p>
                            <p class="mb-3">
                                {{-- Asumsi ada kolom is_verified --}}
                                <span class="text-xs font-semibold {{ $customer->is_verified ? 'text-green-500' : 'text-red-500' }}">
                                    {{ $customer->is_verified ? 'Terverifikasi' : 'Belum Diverifikasi' }}
                                </span>
                            </p>
                        </div>
                        @endif

                    </div>
                    
                    <h6 class="font-bold text-lg mt-6 mb-4 border-b pb-2">Alamat & Lokasi</h6>
                    
                    <div class="text-sm text-slate-600">
                        <p class="font-semibold text-slate-700">Alamat Lengkap:</p>
                        <p class="mb-3 whitespace-pre-wrap">{{ $customer->address ?? 'N/A' }}</p>
                        
                        <p class="font-semibold text-slate-700">Link Google Maps:</p>
                        <p>
                            @if ($customer->link_gmaps)
                                <a href="{{ $customer->link_gmaps }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline">
                                    Lihat Lokasi
                                </a>
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection