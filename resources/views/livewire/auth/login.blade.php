<div class="full-component-wrapper">
    
    <section class="bg-gray-100 py-14 lg:py-[45px]">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap mx-4">
                <div class="w-full px-4">
                    <div
                        class="wow fadeInUp relative mx-auto max-w-[525px] overflow-hidden rounded-xl shadow-form bg-white py-14 px-8 text-center sm:px-12 md:px-[60px]"
                        data-wow-delay=".15s"
                    >
                        {{-- Header Logo --}}
                        <div class="mb-10 text-center">
                            <a href="{{ route('home') }}" class="mx-auto inline-block max-w-[160px]">
                                {{-- Ganti dengan Logo Anda --}}
                                <h1 class="text-3xl">RentHub</h1>
                            </a>
                            <h2 class="mt-4 text-3xl font-extrabold text-center text-gray-900 leading-9">
                                Masuk ke Akunmu
                            </h2>
                        </div>
                        
                        {{-- FORMULIR LIVEWIRE DIMULAI DI SINI --}}
                        <form wire:submit.prevent="authenticate">
                            
                            {{-- Input Email/Username (LoginId) --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="loginId"
                                    type="text"
                                    placeholder="Email Atau Username"
                                    required
                                    autofocus
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-gray-600 focus:border-primary focus-visible:shadow-none @error('loginId') border-red-500 @enderror"
                                />
                                @error('loginId')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Input Password --}}
                            <div class="mb-[22px]">
                                <input
                                    wire:model.lazy="password"
                                    type="password"
                                    placeholder="Password"
                                    required
                                    class="w-full px-5 py-3 text-base transition bg-transparent border rounded-md outline-hidden border-stroke text-body-color placeholder:text-gray-600 focus:border-primary focus-visible:shadow-none @error('password') border-red-500 @enderror"
                                />
                                @error('password')<p class="mt-2 text-sm text-red-600 text-left">{{ $message }}</p>@enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="mb-9">
                                <button
                                    type="submit"
                                    class="w-full px-5 py-3 text-base text-white transition duration-300 ease-in-out border rounded-md cursor-pointer border-primary bg-primary hover:bg-blue-dark focus:ring-indigo focus:border-indigo-700"
                                >
                                    Login
                                </button>
                            </div>
                        </form>

                        {{-- Register Link --}}
                        <p class="text-base text-body-secondary">
                            Belum punya akun?
                            <a href="{{ route('register') }}" class="text-primary hover:underline">
                                Daftar Sekarang
                            </a>
                        </p>

                        {{-- Background SVG Circles (Dihapus karena tidak relevan dengan form) --}}
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>