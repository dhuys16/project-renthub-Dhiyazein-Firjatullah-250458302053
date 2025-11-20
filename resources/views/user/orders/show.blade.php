@extends('layouts.customer')

@section('content')
<div class="w-full px-6 py-6 mx-auto mb-12">
    
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

    {{-- Header & Tombol Kembali --}}
    <div class="mb-6 flex justify-between items-center bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-gray-800">Detail Pesanan #{{ $order->id }}</h2>
        <div>
            <a href="{{ route('user.orders.index') }}" 
               class="inline-block px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition">
                ← Kembali ke Riwayat
            </a>
        </div>
    </div>

    {{-- KONTEN UTAMA --}}
    
    @if ($order->status === 'canceled')
        
        {{-- TAMPILAN JIKA DIBATALKAN --}}
        <div class="bg-white p-12 rounded-xl shadow-lg text-center border-4 border-red-200">
            <svg class="w-16 h-16 mx-auto text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <h3 class="mt-4 text-3xl font-extrabold text-red-600">Pesanan Ini Sudah Dibatalkan</h3>
            <p class="mt-2 text-lg text-gray-600">
                Pesanan #{{ $order->id }} telah dibatalkan pada {{ $order->updated_at->format('d M Y') }}.
            </p>
            <p class="text-sm text-gray-500 mt-4">
                Silakan hubungi layanan pelanggan untuk informasi lebih lanjut jika diperlukan.
            </p>
        </div>

    @else
        
        {{-- TAMPILAN JIKA STATUS AKTIF --}}
        @php
            $detail = $order->details->first();
            $product = $detail->product;
            $canCancel = in_array($order->status, ['pending', 'paid']);
            // Cek apakah review sudah ada (Opsional, perlu relasi review)
            // $hasReviewed = $product->reviews()->where('user_id', Auth::id())->exists(); 
            $hasReviewed = false; // Ganti ini dengan logika DB Anda
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- KOLOM 1 & 2: Detail Order & Item & REVIEW FORM --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- ... (Status & Ringkasan, Detail Produk Sewa) ... (Tidak berubah) --}}

                {{-- Status & Ringkasan --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Status & Waktu</h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-medium text-gray-600">Status:</span>
                        @php
                            $statusClass = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-indigo-100 text-indigo-800',
                                'completed' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ][$order->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="px-4 py-2 text-base font-semibold rounded-full {{ $statusClass }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-3">Pesanan dibuat pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>

                {{-- Detail Produk Sewa --}}
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Item Sewa</h3>
                    
                    <div class="flex space-x-4">
                        <div class="flex-shrink-0 w-24 h-24 overflow-hidden rounded-md border border-gray-200">
                            <img src="{{ asset('storage/' . ($product->picture ?? 'placeholder.jpg')) }}" 
                                 alt="{{ $product->name ?? 'Produk Dihapus' }}" 
                                 class="w-full h-full object-cover object-center">
                        </div>

                        <div class="flex-grow">
                            <p class="text-lg font-bold text-gray-900">{{ $product->name ?? 'Produk Dihapus' }}</p>
                            <p class="text-sm text-gray-600 mt-1">
                                Harga: Rp {{ number_format($product->price_per_day, 0, ',', '.') }} / Hari
                            </p>
                            <p class="text-sm text-gray-600">
                                Tanggal: {{ \Carbon\Carbon::parse($detail->rent_start_date)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($detail->rent_end_date)->format('d M Y') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                Vendor: {{ $product->vendor->username ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- ⭐ BAGIAN BARU: FORM REVIEW (Hanya jika Completed) ⭐ --}}
                @if ($order->status === 'completed')
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-green-200">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Berikan Ulasan Anda</h3>

                        @if ($hasReviewed)
                            <div class="text-center py-4 bg-green-50 rounded-lg">
                                <p class="text-green-700 font-semibold">Terima kasih! Anda sudah memberikan ulasan untuk produk ini.</p>
                            </div>
                        @else
                            <form action="{{ route('user.reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                {{-- Input Rating (Bintang 1-5) BARU --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Beri Nilai (Bintang)</label>
                                    
                                    {{-- ⭐ Kontainer Rating - Menggunakan CSS RTL (Right-to-Left) untuk urutan klik ⭐ --}}
                                    <div class="rating-stars flex justify-center text-3xl mb-1 flex-row-reverse" id="star-rating-container">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <input type="radio" id="star-{{ $i }}" name="rating" value="{{ $i }}" class="hidden" required>
                                            <label for="star-{{ $i }}" 
                                                class="text-gray-300 cursor-pointer transition-colors duration-150 ease-in-out hover:text-yellow-500"
                                                onmouseover="highlightStars({{ $i }})"
                                                onmouseout="resetStars()"
                                                onclick="selectStar({{ $i }})">
                                                ★
                                            </label>
                                        @endfor
                                    </div>
                                    
                                    @error('rating')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                {{-- Input Komentar --}}
                                <div class="mb-6">
                                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-1">Komentar/Ulasan Anda</label>
                                    <textarea name="comment" id="comment" rows="3" placeholder="Bagaimana pengalaman Anda dengan produk ini?"
                                              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2.5 @error('comment') border-red-500 @enderror"></textarea>
                                    @error('comment')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>

                                <button type="submit" class="w-full px-4 py-3 text-white font-medium rounded-lg !bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                                    Kirim Ulasan
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
                {{-- ⭐ AKHIR FORM REVIEW ⭐ --}}


                {{-- Tombol Aksi Pembatalan (Hanya jika canCancel) --}}
                @if ($canCancel)
                <div class="bg-red-50 p-6 rounded-xl shadow-lg border border-red-200">
                    <h3 class="text-lg font-semibold text-red-700 mb-3">Batalkan Pesanan</h3>
                    <p class="text-sm text-red-600 mb-4">Anda dapat membatalkan pesanan ini karena statusnya masih **{{ ucfirst($order->status) }}**.</p>
                    
                    <form action="{{ route('user.orders.cancel', $order) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('PERINGATAN! Apakah Anda benar-benar yakin ingin membatalkan pesanan #{{ $order->id }}?')"
                                class="w-full px-4 py-3 text-white font-medium rounded-lg bg-red-600 hover:bg-red-700 transition duration-150">
                            Batalkan Pesanan Ini
                        </button>
                    </form>
                </div>
                @endif
            </div>

            {{-- KOLOM 3: Ringkasan Biaya --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-lg sticky top-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Ringkasan Pembayaran</h3>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm text-gray-600">
                            <span>Harga Sewa (x{{ $detail->quantity }} Unit):</span>
                            <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 border-t pt-2">
                            <span>Metode Pembayaran:</span>
                            <span class="font-medium">{{ ucfirst($order->payment_method ?? 'Belum Dipilih') }}</span>
                        </div>
                    </div>
                    
                    <div class="flex justify-between text-xl font-bold text-gray-900 border-t border-gray-300 mt-4 pt-4">
                        <span>TOTAL TAGIHAN:</span>
                        <span class="text-indigo-600">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
    
</div>

{{-- Tambahkan Style & Script agar Bintang Berfungsi --}}
<style>
/* CSS untuk membuat bintang yang dipilih dan yang di-hover berwarna kuning */
.rating-stars input:checked ~ label {
    color: #FBBF24; /* yellow-500 */
}
.rating-stars:not(:hover) input:checked ~ label {
    color: #FBBF24; /* yellow-500 */
}
.rating-stars label:hover,
.rating-stars label:hover ~ label {
    color: #FBBF24; /* yellow-500 */
}
</style>

<script>
    // Logika untuk menahan highlight setelah klik (opsional, untuk UX yang lebih baik)
    let selectedRating = 0;

    function highlightStars(value) {
        document.querySelectorAll('#star-rating-container label').forEach((label, index) => {
            const starValue = 5 - index;
            if (starValue <= value) {
                label.style.color = '#FBBF24'; // Highlight kuning
            } else {
                label.style.color = '#D1D5DB'; // Abu-abu
            }
        });
    }

    function resetStars() {
        if (selectedRating === 0) {
            document.querySelectorAll('#star-rating-container label').forEach(label => {
                label.style.color = '#D1D5DB'; // Reset ke abu-abu jika belum ada yang dipilih
            });
        } else {
            highlightStars(selectedRating); // Tahan bintang yang sudah dipilih
        }
    }

    function selectStar(value) {
        selectedRating = value;
        highlightStars(value); // Panggil highlight untuk mengunci warna
    }
    
    // Inisialisasi: panggil reset saat halaman dimuat
    document.addEventListener('DOMContentLoaded', resetStars);
</script>
@endsection