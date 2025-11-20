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
                                <input
                                    wire:model.lazy="nik"
                                    key="input-nik"
                                    type="text"
                                    placeholder="Nomor NIK (16 digit)"
                                    maxlength="16"
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-dark-6 focus:border-primary focus-visible:shadow-none @error('nik') border-red-500 @enderror"
                                />
                                @error('nik')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
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
            <h3 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Syarat dan Ketentuan RentHub</h3>
            <div class="max-h-96 overflow-y-auto text-sm text-gray-700 space-y-3 mb-6">
                <p>1. **Ketentuan Umum:** Dengan mendaftar, Anda menyetujui bahwa data yang diberikan adalah valid dan bertanggung jawab atas semua aktivitas akun Anda.</p>
                <p>2. **Peran Vendor:** Pendaftaran sebagai Vendor bersifat final dan tunduk pada persetujuan admin (jika ada verifikasi). Vendor wajib menjamin kualitas produk sewaan.</p>
                <p>3. **Biaya:** Ada biaya layanan sebesar 10% dari total transaksi untuk setiap penyewaan produk yang berhasil melalui platform RentHub.</p>
                <p>4. **Pembatalan:** Pembatalan pesanan setelah pembayaran dapat dikenakan denda atau penalti sesuai kebijakan yang berlaku.</p>
                <p>5. **Privasi:** Data pribadi Anda akan digunakan sesuai kebijakan privasi kami.</p>
            </div>
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