<div class="full-component-wrapper">
    
    <div
      class="relative z-10 overflow-hidden pt-[30px] pb-[60px] md:pt-[35px] lg:pt-[40px]"
    >
        <div
            class="absolute bottom-0 left-0 w-full h-px bg-linear-to-r from-stroke/0 via-stroke to-stroke/0"
        ></div>
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full px-4">
                    <div class="text-center">
                        <h1
                            class="mb-4 text-3xl font-bold text-dark sm:text-4xl md:text-[40px] md:leading-[1.2]"
                        >
                            Create a new account
                        </h1>
                        <p class="mb-5 text-base text-body-color">
                            Bergabunglah dengan RentHub untuk memulai transaksi sewa.
                        </p>
                        <ul class="flex items-center justify-center gap-[10px]">
                            <li>
                                <a
                                    href="{{ route('home') }}"
                                    class="flex items-center gap-[10px] text-base font-medium text-dark"
                                >
                                    Home
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ route('register') }}"
                                    class="flex items-center gap-[10px] text-base font-medium text-body-color"
                                >
                                    <span class="text-body-color"> / </span>
                                    Sign Up
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="bg-[#F4F7FF] py-14 lg:py-[90px]">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap -mx-4">
                <div class="w-full px-4">
                    <div
                        class="wow fadeInUp relative mx-auto max-w-[525px] overflow-hidden rounded-xl shadow-form bg-white py-14 px-8 text-center sm:px-12 md:px-[60px]"
                        data-wow-delay=".15s"
                    >
                        <div class="mb-10 text-center">
                            <h1 class="mx-auto inline-block max-w-[160px] text-2xl">

                                RentHub 
                            </h1>
                        </div>
                        
                        {{-- FORMULIR LIVEWIRE DIMULAI DI SINI --}}
                        <form wire:submit.prevent="register">
                            
                            {{-- Input NIK --}}
                            <div class="mb-[22px]">
                                <label for="ktp_file" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    KTP (untuk jaminan)
                                </label>
                                <input
                                    {{-- Menggunakan wire:model.lazy="nik" sesuai kode sebelumnya. 
                                    Idealnya gunakan wire:model tanpa modifier untuk file upload. --}}
                                    wire:model="ktp" 
                                    key="input-ktp"
                                    type="file"
                                    id="ktp_file"
                                    accept="image/jpeg,image/png,image/jpg" {{-- Membatasi hanya gambar --}}
                                    
                                    {{-- PENTING: Untuk file upload, Anda mungkin perlu menambahkan wire:loading atau validasi tambahan di Livewire component PHP --}}
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('ktp') border-red-500 @enderror"
                                />
                                {{-- Anda bisa menampilkan nama file yang sedang diunggah di sini --}}
                                @if ($ktp)
                                    <p class="mt-1 text-xs text-indigo-600">File terpilih: {{ is_string($ktp) ? $ktp : $ktp->getClientOriginalName() }}</p>
                                @endif

                                @error('ktp')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Input Name --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="name"
                                    type="text"
                                    placeholder="Nama Lengkap"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('name') border-red-500 @enderror"
                                />
                                @error('name')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>
                            
                            {{-- Input Username --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="username"
                                    type="text"
                                    placeholder="Username"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('username') border-red-500 @enderror"
                                />
                                @error('username')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>
                            
                            {{-- Input Email --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="email"
                                    type="email"
                                    placeholder="Email"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('email') border-red-500 @enderror"
                                />
                                @error('email')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Input Phone Number --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="phone_number"
                                    type="text"
                                    placeholder="Nomor Telepon"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('phone_number') border-red-500 @enderror"
                                />
                                @error('phone_number')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>
                            
                            {{-- Input Address --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="address"
                                    type="text"
                                    placeholder="Alamat Lengkap"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('address') border-red-500 @enderror"
                                />
                                @error('address')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Input Link Google Maps --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="link_gmaps"
                                    type="url"
                                    placeholder="Link Google Maps (Lokasi)"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('link_gmaps') border-red-500 @enderror"
                                />
                                @error('link_gmaps')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>
                            
                            {{-- Input Password --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="password"
                                    type="password"
                                    placeholder="Password"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('password') border-red-500 @enderror"
                                />
                                @error('password')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Input Confirm Password --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="passwordConfirmation"
                                    type="password"
                                    placeholder="Konfirmasi Password"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('passwordConfirmation') border-red-500 @enderror"
                                />
                                @error('passwordConfirmation')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- CHECKBOX TERMS AND CONDITIONS --}}
                            <div class="mb-6 mt-4 text-left">
                                <div class="flex items-center">
                                    <input type="checkbox" wire:model.live="terms" id="terms" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    
                                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                                        Saya setuju dengan 
                                        <a href="#" onclick="showTermsModal()" class="font-medium text-primary hover:underline">
                                            Syarat dan Ketentuan
                                        </a>
                                    </label>
                                </div>
                                @error('terms')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            {{-- Submit Button --}}
                            <div class="mb-9">
                                <button
                                    type="submit"
                                    :disabled="!$terms" 
                                    wire:loading.attr="disabled" 
                                    wire:target="register" 
                                    class="w-full px-5 py-3 text-base text-white transition duration-300 ease-in-out border rounded-md cursor-pointer border-primary bg-primary hover:bg-blue-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span wire:loading.remove wire:target="register">Daftar</span>
                                    <span wire:loading wire:target="register">Memproses...</span>
                                </button>
                            </div>
                        </form>
                        {{-- END FORMULIR LIVEWIRE --}}

                        {{-- Login Link --}}
                        <p class="text-base text-body-secondary">
                            Already have an account?
                            <a href="{{ route('login') }}" class="text-primary hover:underline">
                                Sign In
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- ⭐ MODAL SYARAT & KETENTUAN ⭐ --}}
    <div id="terms-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-[100]">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <h1 class="text-2xl font-extrabold text-indigo-700 mb-2">📜 Syarat dan Ketentuan RentHub</h1>
            <p class="mb-6 text-sm italic border-b pb-3">
                Dengan menekan tombol "Saya Setuju dan Terima", Anda menyatakan telah membaca, memahami, dan menyetujui semua poin Syarat dan Ketentuan di bawah ini.
            </p>

            {{-- 1. Ketentuan Umum Akun dan Platform --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">1. Ketentuan Umum Akun dan Platform</h2>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Persetujuan Data: Anda menjamin bahwa semua data yang diberikan saat mendaftar adalah valid, benar, dan bertanggung jawab atas semua aktivitas akun Anda.</li>
                <li>Perubahan Syarat: RentHub berhak mengubah Syarat dan Ketentuan ini sewaktu-waktu. Perubahan akan berlaku efektif segera setelah diunggah ke platform.</li>
            </ul>

            {{-- 2. Peran Vendor (Penyedia Sewa) --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">2. Peran Vendor (Penyedia Sewa)</h2>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Kewajiban Kualitas: Vendor wajib menjamin kualitas, kelayakan pakai, dan kebersihan produk yang disewakan sesuai dengan deskripsi yang diunggah.</li>
                <li>Jaminan Pengembalian (KTP): Vendor berhak meminta jaminan tambahan, seperti Kartu Identitas (KTP/SIM) penyewa, sebagai jaminan bahwa barang yang disewakan akan dikembalikan tepat waktu dan dalam kondisi semula. KTP hanya digunakan untuk tujuan verifikasi dan jaminan pengembalian.</li>
            </ul>

            {{-- 3. Biaya Layanan dan Pembayaran --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">3. Biaya Layanan dan Pembayaran</h2>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Biaya Layanan RentHub: RentHub mengenakan biaya layanan sebesar 10% (sepuluh persen) dari total transaksi sewa untuk setiap penyewaan produk yang berhasil melalui platform.</li>
                <li>Pembayaran: Semua pembayaran harus dilakukan melalui sistem pembayaran resmi RentHub.</li>
            </ul>

            {{-- 4. Tanggung Jawab Penyewa dan Denda --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">4. Tanggung Jawab Penyewa dan Denda</h2>
            <p class="mb-2 text-sm">Penyewa bertanggung jawab penuh atas barang yang disewa sejak barang diterima hingga dikembalikan kepada Vendor.</p>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Denda Keterlambatan Pengembalian: Jika barang tidak dikembalikan tepat pada waktu yang disepakati, penyewa akan dikenakan denda harian sebesar 100% (seratus persen) dari harga sewa harian hingga barang dikembalikan.</li>
                <li>Denda Kerusakan: Jika barang dikembalikan dalam kondisi rusak (tidak dapat diperbaiki), penyewa wajib membayar biaya perbaikan penuh yang ditetapkan oleh Vendor, ditambah kerugian waktu sewa selama masa perbaikan.</li>
                <li>Ganti Rugi Kehilangan: Jika barang hilang total atau rusak parah hingga tidak dapat digunakan lagi (kerusakan total), penyewa wajib mengganti rugi sebesar nilai penuh barang baru yang ditentukan oleh Vendor.</li>
            </ul>

            {{-- 5. Pembatalan --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">5. Pembatalan</h2>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Kebijakan Pembatalan: Pembatalan pesanan setelah pembayaran dapat dikenakan biaya pembatalan yang besarnya bervariasi tergantung waktu pembatalan, sesuai dengan kebijakan pembatalan yang ditetapkan oleh Vendor dan platform.</li>
                <li>Pengembalian Dana: Pengembalian dana (jika ada) akan diproses sesuai dengan kebijakan platform setelah dikurangi biaya layanan dan denda pembatalan (jika berlaku).</li>
            </ul>

            {{-- 6. Privasi Data --}}
            <h2 class="text-lg font-bold text-gray-800 mt-4 mb-2">6. Privasi Data</h2>
            <ul class="list-disc list-inside ml-4 space-y-1 text-sm">
                <li>Kebijakan Privasi: Data pribadi Anda akan digunakan sesuai dengan Kebijakan Privasi RentHub. Kami berkomitmen untuk melindungi kerahasiaan data Anda dan tidak akan menyalahgunakannya.</li>
            </ul>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeTermsModal()" class="px-4 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300">
                    Tutup
                </button>
                <button type="button" onclick="acceptTermsAndClose()" class="px-4 py-2 bg-primary text-white font-medium rounded-lg hover:bg-primary/90">
                    Saya Setuju dan Terima
                </button>
            </div>
        </div>
    </div>

    {{-- ⭐ LOGIKA JAVASCRIPT MODAL ⭐ --}}
    <script>
        function showTermsModal() {
            document.getElementById('terms-modal').classList.remove('hidden');
        }

        function closeTermsModal() {
            document.getElementById('terms-modal').classList.add('hidden');
        }

        function acceptTermsAndClose() {
            // 1. Centang checkbox
            document.getElementById('terms').checked = true;
            // 2. Memicu event input Livewire
            document.getElementById('terms').dispatchEvent(new Event('input')); 
            // 3. Tutup modal
            closeTermsModal();
        }
    </script>
</div>