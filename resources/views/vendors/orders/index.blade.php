@extends('layouts.vendor')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Manajemen Pesanan Masuk</h2>
    </div>

    {{-- Kartu Daftar Pesanan --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        
        @if ($orders->isEmpty())
            <div class="text-center py-12">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 12c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-18c-5.52 0-10 4.48-10 10s4.48 10 10 10 10-4.48 10-10-4.48-10-10-10z"/></svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Pesanan Baru</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Semua transaksi Anda terpantau di sini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID | Tanggal Masuk
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Produk Sewa
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Penyewa | Total
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                Aksi Vendor
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                        {{-- Ambil detail item pertama dari pesanan (asumsi 1 item per order) --}}
                        @php
                            $detail = $order->details->first();
                        @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $order->id }}<br>
                                <span class="text-xs text-gray-500">{{ $order->created_at->format('d M Y H:i') }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                <span class="font-semibold">{{ $detail->product->name ?? 'Produk Dihapus' }}</span><br>
                                <span class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($detail->rent_start_date)->format('d/m') }} - 
                                    {{ \Carbon\Carbon::parse($detail->rent_end_date)->format('d/m') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $order->user->name ?? 'User Dihapus' }}<br>
                                <span class="font-bold text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-blue-100 text-blue-800',
                                        'processing' => 'bg-indigo-100 text-indigo-800',
                                        'completed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ][$order->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">                            
                                <a href="{{ route('vendors.orders.show', $order) }}" class="text-indigo-600 hover:text-indigo-900 transition block mt-2">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection