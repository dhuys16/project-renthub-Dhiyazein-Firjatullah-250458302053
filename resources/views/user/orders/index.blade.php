@extends('layouts.customer') 

@section('title', 'Riwayat Pesanan')

@section('part', 'Riwayat Pesanan')

@section('content')
<div class="w-full px-6 py-6 mx-auto">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Kartu Daftar Pesanan --}}
    <div class="bg-white p-6 rounded-xl shadow-lg">
        @if ($orders->isEmpty())
            <div class="text-center py-12">
                {{-- ... (Empty state content) ... --}}
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk Sewa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Sewa</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($orders as $order)
                            @php
                                $detail = $order->details->first();
                            @endphp
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $order->id }}</td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $detail->product->name ?? 'Produk Dihapus' }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($detail->rent_start_date)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($detail->rent_end_date)->format('d/m/Y') }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
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
                            
                            {{-- KOLOM AKSI BARU: HANYA LIHAT DETAIL --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                                <a href="{{ route('user.orders.show', $order) }}" 
                                   class="text-xs px-3 py-1 rounded-md bg-indigo-500 text-white hover:bg-indigo-600 transition">
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