@extends('layouts.customer')

@section('content')
<div class="container mx-auto p-4 md:p-8">
    <div class="bg-white p-8 rounded-xl shadow-lg">
        
        <h1 class="text-4xl font-extrabold text-indigo-600 mb-4">Bergabunglah sebagai Vendor RentHub!</h1>
        <p class="text-lg text-gray-600 mb-8">Ubah aset yang tidak terpakai menjadi sumber penghasilan. Kami akan membantu Anda menjangkau ribuan penyewa.</p>
        
        <hr class="my-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Keuntungan --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Keuntungan Menjadi Vendor</h3>
                <ul class="space-y-3 text-gray-700 list-disc list-inside">
                    <li>**Penghasilan Pasif:** Dapatkan uang dari barang yang Anda sewakan.</li>
                    <li>**Manajemen Mudah:** Kelola semua pesanan dan produk dari satu dashboard.</li>
                    <li>**Jangkauan Luas:** Produk Anda dilihat oleh komunitas pengguna RentHub yang besar.</li>
                </ul>
            </div>
            
            {{-- Syarat dan Proses --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Apa yang Harus Anda Lakukan?</h3>
                <ol class="space-y-3 text-gray-700 list-decimal list-inside">
                    <li>**Pendaftaran:** Klik tombol di bawah untuk mengubah peran akun Anda.</li>
                    <li>**Upload Produk:** Tambahkan foto, deskripsi, dan harga sewa harian.</li>
                    <li>**Konfirmasi:** Setujui pesanan yang masuk dari dashboard Vendor.</li>
                </ol>
            </div>

            {{-- Info Tambahan --}}
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-3">Persyaratan Dasar</h3>
                <p class="text-gray-700 text-sm">
                    Anda setuju untuk bertanggung jawab penuh atas kondisi produk yang disewakan dan mematuhi semua ketentuan layanan RentHub.
                    (Persyaratan KTP dan alamat akan diminta saat Anda pertama kali mengupload produk/sebelum verifikasi penuh).
                </p>
            </div>
            
        </div>

        <hr class="my-8">

        {{-- Tombol Aksi --}}
        <div class="text-center">
            <form action="{{ route('customer.vendor.register') }}" method="POST">
                @csrf
                <button type="submit" class="px-8 py-4 text-xl font-bold rounded-lg shadow-xl text-white !bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    ➡️ Jadi Vendor Sekarang!
                </button>
            </form>
        </div>

    </div>
</div>
@endsection