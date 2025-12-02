@extends('layouts.basecus')

@section('body')
    @php
        // Logika Dinamis untuk Foto dan Nama
        $loggedInUser = Auth::user(); 
        $photoPath = $loggedInUser && $loggedInUser->photo_profile 
            ? asset('storage/' . $loggedInUser->photo_profile) 
            : asset('assets/img/user.jpg');
        $displayName = $loggedInUser ? ($loggedInUser->username ?? $loggedInUser->name ?? 'Customer') : 'Guest';
    @endphp

    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
      <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
        
        <div
          x-ref="loading"
          class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
        >
          Loading.....
        </div>

        <aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block">
          <div class="flex flex-col h-full">
            
            <nav aria-label="Main" class="px-2 py-4 space-y-2">
              {{-- Logo di tengah --}}
              <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700 flex justify-center items-center" href="{{route('home')}}">
                <img src="{{asset('assets/img/2.jpg')}}" class="" width="150px" alt="main_logo" />
              </a>
              <hr class="border-gray-200 dark:border-gray-700 my-2">
              
              {{-- 1. Dashboard/Beranda --}}
              <a
                  href="{{ route('products.index') }}"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-indigo-50 hover:text-indigo-600"
                  :class="{'bg-indigo-100 dark:bg-indigo-700 dark:text-white text-indigo-700': route()->is('products.index')}"
              >
                  <span aria-hidden="true">
                    <i class="fa-solid fa-home"></i>
                  </span>
                  <span class="ml-2 text-sm font-semibold"> Katalog Produk </span>
              </a>

              {{-- 2. Riwayat Pesanan (Orders) --}}
              <a
                  href="{{ route('user.orders.index') }}"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-indigo-50 hover:text-indigo-600"
                  :class="{'bg-indigo-100 dark:bg-indigo-700 dark:text-white text-indigo-700': route()->is('user.orders.*')}"
              >
                  <span aria-hidden="true">
                    <i class="fa-solid fa-table-list"></i>
                  </span>
                  <span class="ml-2 text-sm font-semibold"> Riwayat Pesanan </span>
              </a>
              
              <hr class="border-gray-200 dark:border-gray-700 my-2">

              {{-- 3. Jadilah Vendor --}}
              <a
                  href="{{ route('customer.vendor.form') }}"
                  class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-yellow-50 hover:text-yellow-600"
                  :class="{'bg-yellow-100 dark:bg-yellow-700 dark:text-white text-yellow-700': route()->is('customer.vendor.form')}"
              >
                  <span aria-hidden="true">
                    <i class="fa-solid fa-store"></i>
                  </span>
                  <span class="ml-2 text-sm font-semibold"> Jadilah Vendor </span>
              </a>
            </nav>
            
            <div class="flex-shrink-0 p-4 border-t dark:border-primary-darker">
              <p class="text-xs text-gray-400">© {{ date('Y') }} RentHub</p>
            </div>
          </div>
        </aside>

        <div class="flex-1 h-full overflow-x-hidden overflow-y-auto bg-blue-500">
          <header class="relative bg-white dark:bg-darker shadow-lg">
            <div class="flex items-center justify-between p-4 border-b dark:border-primary-darker">

              <div class="flex items-center">
                  <h1 class="inline-block text-2xl font-bold tracking-wider text-indigo-600 dark:text-light">
                      @yield('part')
                  </h1>
              </div>
              
              {{-- Tombol ini akan membuka Menu Sekunder (yang berisi link sidebar) --}}
              <button
                  @click="isMobileMainMenuOpen = !isMobileMainMenuOpen"
                  class="p-1 transition-colors duration-200 rounded-md text-gray-500 dark:text-light hover:bg-gray-100 dark:hover:bg-primary md:hidden"
                  aria-label="Toggle menu"
              >
                  <i x-show="!isMobileMainMenuOpen" class="fas fa-bars h-6 w-6"></i>
                  <i x-show="isMobileMainMenuOpen" class="fas fa-times h-6 w-6"></i>
              </button>

              <nav aria-label="Secondary" class="hidden space-x-2 md:flex md:items-center">
                {{-- [MODIFIKASI] Tampilkan Username --}}
                <span class="text-sm font-semibold text-gray-700 dark:text-light mr-2">
                   <span >{{ $displayName }}</span>
                </span>

                <div class="relative" x-data="{ open: false }">
                  <button
                      @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })"
                      type="button"
                      aria-haspopup="true"
                      :aria-expanded="open ? 'true' : 'false'"
                      class="transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100"
                  >
                      <span class="sr-only">User menu</span>
                      {{-- FOTO PROFIL DINAMIS --}}
                      <img class="w-10 h-10 rounded-full object-cover" src="{{ $photoPath }}" alt="{{ $displayName }}" />
                  </button>

                  <div
                      x-show="open"
                      x-ref="userMenu"
                      x-transition:enter="transition-all transform ease-out"
                      x-transition:enter-start="translate-y-1/2 opacity-0"
                      x-transition:enter-end="translate-y-0 opacity-100"
                      x-transition:leave="transition-all transform ease-in"
                      x-transition:leave-start="translate-y-0 opacity-100"
                      x-transition:leave-end="translate-y-1/2 opacity-0"
                      @click.away="open = false"
                      @keydown.escape="open = false"
                      class="absolute right-0 w-48 py-1 bg-white rounded-md shadow-lg top-12 ring-1 ring-black ring-opacity-5 dark:bg-dark focus:outline-none"
                      tabindex="-1"
                      role="menu"
                      aria-orientation="vertical"
                      aria-label="User menu"
                  >
                      <a
                          href="{{route('user.profile.show')}}"
                          role="menuitem"
                          class="flex items-center p-2 w-full text-gray-500 transition-colors rounded-md dark:text-light hover:bg-indigo-100 hover:text-indigo-600"
                      >
                          <span><i class="fa-solid fa-user"></i></span>
                          <span class="ml-2 text-sm">Profil Saya</span>
                      </a>
                      
                      {{-- Log Out --}}
                      <form method="POST" action="{{ route('logout') }}" class="mt-1">
                          @csrf
                          <button type="submit"
                              class="flex items-center p-2 w-full text-gray-500 transition-colors rounded-md dark:text-light hover:bg-red-100 hover:text-red-600"
                          >
                              <span aria-hidden="true">
                                  <i class="w-5 h-5 fas fa-sign-out-alt"></i> 
                              </span>
                              <span class="ml-2 text-sm"> Log Out </span>
                          </button>
                      </form>
                  </div>
                </div>
              </nav>
            </div>
            
            {{-- Menggunakan kode mobile menu yang sudah kita buat di welcome.blade.php untuk konsistensi --}}
            <div
                class="border-b md:hidden dark:border-primary-darker"
                x-show="isMobileMainMenuOpen"
                @click.away="isMobileMainMenuOpen = false"
            >
                <nav aria-label="Main" class="px-2 py-4 space-y-2">
                    

                    {{-- 1. Katalog Produk (Menggantikan Beranda) --}}
                    <a
                        href="{{ route('products.index') }}"
                        class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-indigo-50 hover:text-indigo-600"
                    >
                        <span aria-hidden="true"><i class="fa-solid fa-store"></i></span>
                        <span class="ml-2 text-sm font-semibold"> Katalog Produk </span>
                    </a>

                    {{-- 2. Riwayat Pesanan --}}
                    <a
                        href="{{ route('user.orders.index') }}"
                        class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-indigo-50 hover:text-indigo-600"
                    >
                        <span aria-hidden="true"><i class="fa-solid fa-table-list"></i></span>
                        <span class="ml-2 text-sm font-semibold"> Riwayat Pesanan </span>
                    </a>
                    
                    <hr class="border-gray-200 dark:border-gray-700 my-2">

                    {{-- 3. Jadilah Vendor --}}
                    <a
                        href="{{ route('customer.vendor.form') }}"
                        class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-yellow-50 hover:text-yellow-600"
                    >
                        <span aria-hidden="true"><i class="fa-solid fa-book-open"></i></span>
                        <span class="ml-2 text-sm font-semibold"> Jadilah Vendor </span>
                    </a>
                    
                    {{-- 4. Profil Saya --}}
                    {{-- PROFILE INFO (Top of Mobile Menu) --}}
                    <div class="flex items-center p-2 space-x-3 border-b pb-3 mb-2 dark:border-primary-darker">
                      <a href="{{route('user.profile.show')}}" class="flex items-center space-x-3">
                        <img class="w-10 h-10 rounded-full object-cover" src="{{ $photoPath }}" alt="{{ $displayName }}" />
                        <span class="text-base font-semibold text-gray-900 dark:text-light">{{ $displayName }}</span>
                      </a>
                    </div>

                    {{-- 5. Log Out (Menggunakan Form POST) --}}
                    <form method="POST" action="{{ route('logout') }}" class="pt-2">
                        @csrf
                        <button type="submit"
                            class="flex items-center p-2 w-full text-red-500 transition-colors rounded-md dark:text-red-400 hover:bg-red-100 hover:text-red-600"
                        >
                            <span aria-hidden="true">
                                <i class="w-5 h-5 fas fa-sign-out-alt"></i>
                            </span>
                            <span class="ml-2 text-sm font-semibold"> Log Out </span>
                        </button>
                    </form>
                </nav>
            </div>
          </header>

          <main class="p-4">
            @yield('content')
          </main>

          <footer class="flex items-center justify-between p-4 bg-white border-t dark:bg-darker dark:border-primary-darker
                        fixed bottom-0 inset-x-0 z-50 w-full"> {{-- <--- TAMBAHAN KELAS UNTUK FIX FOOTER --}}
              <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-black">
              &copy; {{ date('Y') }} RentHub. All rights reserved.
              </div>
          </footer>
        </div>
      </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>
    <script src="{{asset('kwd/public/build/js/script.js')}}"></script>
    <script>
      // Fungsi setup() harus mengembalikan objek state yang digunakan oleh x-data
      const setup = () => {
        
        const getTheme = () => { /* ... kode getTheme ... */ return false; } // Asumsi fungsi ini ada
        const setTheme = (value) => { /* ... kode setTheme ... */ }
        const getColor = () => { /* ... kode getColor ... */ return 'cyan'; }
        const setColors = (color) => { /* ... kode setColors ... */ }
        const updateBarChart = (on) => { /* ... */ }
        const updateDoughnutChart = (on) => { /* ... */ }
        const updateLineChart = () => { /* ... */ }

        // Logika state yang digunakan oleh setup()
        return {
          loading: true,
          isDark: getTheme(),
          toggleTheme() {
            this.isDark = !this.isDark
            setTheme(this.isDark)
          },
          setLightTheme() { 
            this.isDark = false;
            setTheme(this.isDark);
          },
          setDarkTheme() { 
            this.isDark = true;
            setTheme(this.isDark);
          },
          color: getColor(),
          selectedColor: 'cyan',
          setColors,
          
          // [STATE BARU YANG DIBUTUHKAN UNTUK HAMBURGER MENU]
          isSidebarOpen: false, // State untuk toggle sidebar (tombol kiri)
          isMobileMainMenuOpen: false, // State untuk mobile menu overlay (tombol kanan)

          // [HANDLER BARU UNTUK TOMBOL]
          toggleSidebarMenu() {
            this.isSidebarOpen = !this.isSidebarOpen
          },
          toggleMobileMainMenu() {
            this.isMobileMainMenuOpen = !this.isMobileMainMenuOpen
          },

          // Menggunakan logic Anda yang lama, tetapi dikoreksi untuk fungsi di atas
          isSettingsPanelOpen: false,
          openSettingsPanel() { /* ... */ },
          isNotificationsPanelOpen: false,
          openNotificationsPanel() { /* ... */ },
          isSearchPanelOpen: false,
          openSearchPanel() { /* ... */ },
          isMobileSubMenuOpen: false,
          openMobileSubMenu() { /* ... */ },
          
          updateBarChart,
          updateDoughnutChart,
          updateLineChart,
        }
      }
    </script>
@endsection