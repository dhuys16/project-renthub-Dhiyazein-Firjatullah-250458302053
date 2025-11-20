@extends('layouts.vendor')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    
    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Ambil detail yang diperlukan --}}
    @php
        $detail = $order->details->first();
        $product = $detail->product;
        // Asumsi relasi di Model Order adalah 'customer'
        $customer = $order->customer; 
        $currentStatus = $order->status;
    @endphp

    {{-- HEADER & AKSI --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h2>
        
        <div>
            <a href="{{ route('vendors.orders.index') }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                ← Kembali ke Daftar Pesanan
            </a>
        </div>
    </div>

    {{-- Layout Utama (3 Kolom) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mb-12">

        {{-- KOLOM 1 & 2: Detail Pesanan dan Produk --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Status & Ringkasan Pesanan --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Status Pesanan</h3>
                <div class="flex items-center justify-between">
                    <span class="text-lg font-medium text-gray-600">Status Saat Ini:</span>
                    @php
                        $statusClass = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-indigo-100 text-indigo-800',
                            'paid' => 'bg-blue-100 text-blue-800', // Asumsi status paid belum disewa
                            'rented' => 'bg-cyan-100 text-cyan-800', // Status baru untuk sedang disewa
                            'completed' => 'bg-green-110 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                        ][$currentStatus] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <span class="px-4 py-2 text-base font-semibold rounded-full {{ $statusClass }}">
                        {{ ucfirst($currentStatus) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-3">Pesanan dibuat pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            {{-- Detail Item Pesanan (OrderDetails) --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Item Sewa</h3>
                
                @foreach ($order->details as $detail)
                    <div class="flex space-x-4 border-b py-4 last:border-b-0">
                        <div class="flex-shrink-0 w-24 h-24 overflow-hidden rounded-md border border-gray-200">
                            <img src="{{ asset('storage/' . ($detail->product->picture ?? 'placeholder.jpg')) }}" 
                                 alt="{{ $detail->product->name ?? 'Produk Dihapus' }}" 
                                 class="w-full h-full object-cover object-center">
                        </div>

                        <div class="flex-grow">
                            <p class="text-lg font-bold text-gray-900">{{ $detail->product->name ?? 'Produk Dihapus' }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Tanggal Sewa: {{ \Carbon\Carbon::parse($detail->rent_start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($detail->rent_end_date)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-600">Durasi: {{ \Carbon\Carbon::parse($detail->rent_start_date)->diffInDays(\Carbon\Carbon::parse($detail->rent_end_date)) + 1 }} hari</p>
                            <p class="mt-2 text-sm font-medium text-gray-900">
                                Subtotal: Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Informasi Pelanggan (DIKOREKSI: Menggunakan $customer) --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Informasi Pelanggan</h3>
                <table class="w-full text-left text-sm text-gray-600">
                    <tbody>
                        <tr class="border-b">
                            <th class="py-2 pr-4 font-medium w-1/3">Nama Pelanggan</th>
                            <td class="py-2">{{ $customer->username ?? 'User Dihapus' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 pr-4 font-medium">Email</th>
                            <td class="py-2">{{ $customer->email ?? '-' }}</td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 pr-4 font-medium">Nomor Telepon</th>
                            <td class="py-2">{{ $customer->phone_number ?? '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            {{-- TAMPILAN JIKA DITOLAK (CANCELLED) --}}
            @if ($currentStatus === 'cancelled')
                <div class="bg-red-50 p-6 rounded-xl shadow-lg text-center border-4 border-red-300">
                    <svg class="w-16 h-16 mx-auto text-red-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="mt-2 text-2xl font-extrabold text-red-700">PESANAN INI DITOLAK</h3>
                    <p class="mt-1 text-base text-gray-600">
                        Pesanan ini telah dibatalkan/ditolak oleh Vendor atau sistem.
                    </p>
                    {{-- Tambahkan alasan penolakan jika ada di DB: --}}
                    {{-- <p class="text-sm italic text-red-500 mt-2">Alasan: {{ $order->cancellation_reason ?? '-' }}</p> --}}
                </div>
            @endif

        </div>

        {{-- KOLOM 3: Ringkasan Biaya dan Aksi --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Ringkasan Biaya --}}
            <div class="bg-white p-6 rounded-xl shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Ringkasan Biaya</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Metode Pembayaran:</span>
                        <span class="font-medium">{{ ucfirst($order->payment_method ?? 'Belum Dipilih') }}</span>
                    </div>
                </div>
                
                <div class="flex justify-between text-xl font-bold text-gray-900 border-t border-gray-300 mt-4 pt-4">
                    <span>TOTAL:</span>
                    <span class="text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Tombol Aksi Dinamis --}}
            <div class="bg-gray-50 p-6 rounded-xl shadow-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Kelola Pesanan</h3>

                {{-- Aksi Kondisional --}}
                @if ($currentStatus === 'pending')
                    <p class="text-sm text-gray-600 mb-4">Pesanan menunggu konfirmasi Vendor.</p>
                    
                    {{-- Tombol Konfirmasi --}}
                    <form action="{{ route('vendors.orders.confirm', $order) }}" method="POST" class="w-full mb-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 text-white font-medium rounded-lg bg-green-600 hover:bg-green-700 transition duration-150">
                            ✅ Konfirmasi Pesanan
                        </button>
                    </form>

                    {{-- Tombol Tolak --}}
                    <form action="{{ route('vendors.orders.reject', $order) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Yakin ingin menolak pesanan ini?')"
                                class="w-full px-4 py-3 text-red-600 font-medium rounded-lg border border-red-300 bg-white hover:bg-red-50 transition duration-150">
                            ❌ Tolak Pesanan
                        </button>
                    </form>
                
                @elseif ($currentStatus === 'confirmed')
                    {{-- Status: Dikonfirmasi/Sudah Bayar, Menunggu Disewa --}}
                    <p class="text-sm text-gray-600 mb-4">Pesanan siap diserahkan kepada pelanggan.</p>
                    
                    {{-- Tombol 'Sedang Disewa' (Mengubah status menjadi 'rented') --}}
                    <form action="{{ route('vendors.orders.rented', $order) }}" method="POST" class="w-full">
                        @csrf
                        
                        {{-- Hapus input hidden 'status' karena method rented() di Controller tidak menggunakannya lagi --}}
                        {{-- <input type="hidden" name="status" value="rented"> --}} 

                        <button type="submit" class="w-full px-4 py-3 text-white font-medium rounded-lg !bg-cyan-600 hover:bg-cyan-700 transition duration-150">
                            Sedang Disewa
                        </button>
                    </form>
                
                @elseif ($currentStatus === 'rented')
                    {{-- Status: Sedang Disewa --}}
                    <p class="text-sm text-gray-600 mb-4 font-bold text-cyan-700">Barang saat ini sedang disewa oleh pelanggan.</p>
                    
                    {{-- Tombol 'Selesai' (Mengubah status menjadi 'completed') --}}
                    <form action="{{ route('vendors.orders.complete', $order) }}" method="POST" class="w-full">
                        @csrf
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="w-full px-4 py-3 text-white font-medium rounded-lg bg-green-600 hover:bg-green-700 transition duration-150">
                            ✅ Pesanan Telah Selesai
                        </button>
                    </form>

                @elseif ($currentStatus === 'completed')
                    {{-- Status: Selesai --}}
                    <div class="text-center py-4">
                        <p class="text-lg font-extrabold text-green-700">✅ PESANAN TELAH SELESAI</p>
                        <p class="text-sm text-gray-500 mt-1">Transaksi ini telah ditutup dengan sukses.</p>
                    </div>

                @elseif ($currentStatus === 'canceled')
                    {{-- Status: Dibatalkan --}}
                    <div class="text-center py-4">
                        <p class="text-lg font-extrabold text-red-700">❌ PESANAN DIBATALKAN</p>
                        <p class="text-sm text-gray-500 mt-1">Tidak ada aksi lebih lanjut yang diperlukan.</p>
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection