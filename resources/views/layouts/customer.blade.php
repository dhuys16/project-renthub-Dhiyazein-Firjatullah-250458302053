@extends('layouts.basecus')

@section('body')
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden'); setColors(color);" :class="{ 'dark': isDark}">
      <div class="flex h-screen antialiased text-gray-900 bg-blue-500 dark:bg-dark dark:text-light">
        <!-- Loading screen -->
        <div
          x-ref="loading"
          class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker"
        >
          Loading.....
        </div>

        <!-- Sidebar -->
        <aside class="flex-shrink-0 hidden w-64 bg-white border-r dark:border-primary-darker dark:bg-darker md:block">
          <div class="flex flex-col h-full">
            <!-- Sidebar links -->
            <nav aria-label="Main" class="flex-1 px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">
                <h1 class="px-2 py-4 text-3xl font-bold text-center text-primary-dark dark:text-light">
                  RentHub
                </h1>
                {{-- 1. Dashboard (Tautan Langsung) --}}
                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.dashboard')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-home"></i>
                    </span>
                    <span class="ml-2 text-sm"> Beranda </span>
                </a>

                {{-- 2. Riwayat Pesanan (Orders) --}}
                <a
                    href="{{ route('user.orders.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.orders.*')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-table-list"></i>
                    </span>
                    
                    <span class="ml-2 text-sm"> Riwayat Pesanan </span>
                </a>
                
                {{-- 3. Edit Profil --}}
                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.profile.*')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-user"></i>
                    </span>
                    <span class="ml-2 text-sm"> Edit Profil </span>
                </a>

                {{-- 4. Log Out (Menggunakan Form POST untuk keamanan) --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="flex items-center p-2 w-full text-gray-500 transition-colors rounded-md dark:text-light hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-600"
                    >
                        <span aria-hidden="true">
                            <i class="w-5 h-5 fas fa-sign-out-alt"></i> {{-- Gunakan ikon Logout --}}
                        </span>
                        <span class="ml-2 text-sm"> Log Out </span>
                    </button>
                </form>
            </nav>

            <!-- Sidebar footer -->
      </div>
    </aside>

        <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
          <!-- Navbar -->
          <header class="relative bg-white dark:bg-darker">
            <div class="flex items-center justify-between p-2 border-b dark:border-primary-darker">
              <!-- Mobile menu button -->
              <button
                @click="isMobileMainMenuOpen = !isMobileMainMenuOpen"
                class="p-1 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:hidden focus:outline-none focus:ring"
              >
                <span class="sr-only">Open main manu</span>
                <span aria-hidden="true">
                  <svg
                    class="w-8 h-8"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </span>
              </button>

              <!-- Brand -->
              <h1 class="inline-block text-2xl font-bold tracking-wider text-primary-dark dark:text-light">
                Katalog RentHub
              </h1>              
              

              <!-- Mobile sub menu button -->
              <button
                @click="isMobileSubMenuOpen = !isMobileSubMenuOpen"
                class="p-1 transition-colors duration-200 rounded-md text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark md:hidden focus:outline-none focus:ring"
              >
                <span class="sr-only">Open sub manu</span>
                <span aria-hidden="true">
                  <svg
                    class="w-8 h-8"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"
                    />
                  </svg>
                </span>
              </button>

              <!-- Desktop Right buttons -->
              <nav aria-label="Secondary" class="hidden space-x-2 md:flex md:items-center">
                <!-- Toggle dark theme button -->
                <button aria-hidden="true" class="relative focus:outline-none" x-cloak @click="toggleTheme">
                  <div
                    class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-lighter"
                  ></div>
                  <div
                    class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-150 transform scale-110 rounded-full shadow-sm"
                    :class="{ 'translate-x-0 -translate-y-px  bg-white text-primary-dark': !isDark, 'translate-x-6 text-primary-100 bg-primary-darker': isDark }"
                  >
                    <svg
                      x-show="!isDark"
                      class="w-4 h-4"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                      />
                    </svg>
                    <svg
                      x-show="isDark"
                      class="w-4 h-4"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                      />
                    </svg>
                  </div>
                </button>
                <!-- User avatar button -->
                <div class="relative" x-data="{ open: false }">
                  <button
                    @click="open = !open; $nextTick(() => { if(open){ $refs.userMenu.focus() } })"
                    type="button"
                    aria-haspopup="true"
                    :aria-expanded="open ? 'true' : 'false'"
                    class="transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100"
                  >
                    <span class="sr-only">User menu</span>
                    <img class="w-10 h-10 rounded-full" src="{{asset('kwd/public/build/images/avatar.jpg')}}" alt="Ahmed Kamel" />
                  </button>

                  <!-- User dropdown menu -->
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
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Your Profile
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Settings
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Logout
                    </a>
                  </div>
                </div>
              </nav>

              <!-- Mobile sub menu -->
              <nav
                x-transition:enter="transition duration-200 ease-in-out transform sm:duration-500"
                x-transition:enter-start="-translate-y-full opacity-0"
                x-transition:enter-end="translate-y-0 opacity-100"
                x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
                x-transition:leave-start="translate-y-0 opacity-100"
                x-transition:leave-end="-translate-y-full opacity-0"
                x-show="isMobileSubMenuOpen"
                @click.away="isMobileSubMenuOpen = false"
                class="absolute flex items-center p-4 bg-white rounded-md shadow-lg dark:bg-darker top-16 inset-x-4 md:hidden"
                aria-label="Secondary"
              >
                <div class="space-x-2">
                  <!-- Toggle dark theme button -->
                  <button aria-hidden="true" class="relative focus:outline-none" x-cloak @click="toggleTheme">
                    <div
                      class="w-12 h-6 transition rounded-full outline-none bg-primary-100 dark:bg-primary-lighter"
                    ></div>
                    <div
                      class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 transform scale-110 rounded-full shadow-sm"
                      :class="{ 'translate-x-0 -translate-y-px  bg-white text-primary-dark': !isDark, 'translate-x-6 text-primary-100 bg-primary-darker': isDark }"
                    >
                      <svg
                        x-show="!isDark"
                        class="w-4 h-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
                        />
                      </svg>
                      <svg
                        x-show="isDark"
                        class="w-4 h-4"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                      >
                        <path
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                        />
                      </svg>
                    </div>
                  </button>
                  <!-- Search button -->
                  <button
                    @click="openSearchPanel(); $nextTick(() => { $refs.searchInput.focus(); setTimeout(() => {isMobileSubMenuOpen= false}, 100) })"
                    class="p-2 transition-colors duration-200 rounded-full text-primary-lighter bg-primary-50 hover:text-primary hover:bg-primary-100 dark:hover:text-light dark:hover:bg-primary-dark dark:bg-dark focus:outline-none focus:bg-primary-100 dark:focus:bg-primary-dark focus:ring-primary-darker"
                  >
                    <span class="sr-only">Open search panel</span>
                    <svg
                      class="w-7 h-7"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="none"
                      viewBox="0 0 24 24"
                      stroke="currentColor"
                      aria-hidden="true"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                      />
                    </svg>
                  </button>
                </div>

                <!-- User avatar button -->
                <div class="relative ml-auto" x-data="{ open: false }">
                  <button
                    @click="open = !open"
                    type="button"
                    aria-haspopup="true"
                    :aria-expanded="open ? 'true' : 'false'"
                    class="block transition-opacity duration-200 rounded-full dark:opacity-75 dark:hover:opacity-100 focus:outline-none focus:ring dark:focus:opacity-100"
                  >
                    <span class="sr-only">User menu</span>
                    <img class="w-10 h-10 rounded-full" src="{{asset('kwd/public/build/images/avatar.jpg')}}" alt="Ahmed Kamel" />
                  </button>

                  <!-- User dropdown menu -->
                  <div
                    x-show="open"
                    x-transition:enter="transition-all transform ease-out"
                    x-transition:enter-start="translate-y-1/2 opacity-0"
                    x-transition:enter-end="translate-y-0 opacity-100"
                    x-transition:leave="transition-all transform ease-in"
                    x-transition:leave-start="translate-y-0 opacity-100"
                    x-transition:leave-end="translate-y-1/2 opacity-0"
                    @click.away="open = false"
                    class="absolute right-0 w-48 py-1 origin-top-right bg-white rounded-md shadow-lg top-12 ring-1 ring-black ring-opacity-5 dark:bg-dark"
                    role="menu"
                    aria-orientation="vertical"
                    aria-label="User menu"
                  >
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Your Profile
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Settings
                    </a>
                    <a
                      href="#"
                      role="menuitem"
                      class="block px-4 py-2 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-light dark:hover:bg-primary"
                    >
                      Logout
                    </a>
                  </div>
                </div>
              </nav>
            </div>
            <!-- Mobile main manu -->
            <div
              class="border-b md:hidden dark:border-primary-darker"
              x-show="isMobileMainMenuOpen"
              @click.away="isMobileMainMenuOpen = false"
            >
              <nav aria-label="Main" class="px-2 py-4 space-y-2">
                {{-- 1. Dashboard (Tautan Langsung) --}}
                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.dashboard')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-home"></i>
                    </span>
                    <span class="ml-2 text-sm"> Beranda </span>
                </a>

                {{-- 2. Riwayat Pesanan (Orders) --}}
                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.orders.*')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-table-list"></i>
                    </span>
                    
                    <span class="ml-2 text-sm"> Riwayat Pesanan </span>
                </a>
                
                {{-- 3. Edit Profil --}}
                <a
                    href="{{ route('products.index') }}"
                    class="flex items-center p-2 text-gray-500 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                    :class="{'bg-primary-100 dark:bg-primary': route()->is('customer.profile.*')}"
                >
                    <span aria-hidden="true">
                      <i class="fa-solid fa-user"></i>
                    </span>
                    <span class="ml-2 text-sm"> Edit Profil </span>
                </a>

                {{-- 4. Log Out (Menggunakan Form POST untuk keamanan) --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="flex items-center p-2 w-full text-gray-500 transition-colors rounded-md dark:text-light hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-600"
                    >
                        <span aria-hidden="true">
                            <i class="w-5 h-5 fas fa-sign-out-alt"></i> {{-- Gunakan ikon Logout --}}
                        </span>
                        <span class="ml-2 text-sm"> Log Out </span>
                    </button>
                </form>
              </nav>
            </div>
          </header>

          <!-- Main content -->
          <main>
            @yield('content')
          </main>

          <!-- Main footer -->
          <footer
            class="flex items-center justify-between p-4 bg-white border-t dark:bg-darker dark:border-primary-darker"
          >
            <div>K-WD &copy; 2021</div>
            <div>
              Made by
              <a href="#" target="_blank" class="text-blue-500 hover:underline"
                >Ahmed Kamel</a
              >
            </div>
          </footer>
        </div>

        <!-- Panels -->
        <!-- Notification panel -->
        <!-- Backdrop -->
        <div
          x-transition:enter="transition duration-300 ease-in-out"
          x-transition:enter-start="opacity-0"
          x-transition:enter-end="opacity-100"
          x-transition:leave="transition duration-300 ease-in-out"
          x-transition:leave-start="opacity-100"
          x-transition:leave-end="opacity-0"
          x-show="isNotificationsPanelOpen"
          @click="isNotificationsPanelOpen = false"
          class="fixed inset-0 z-10 bg-primary-darker"
          style="opacity: 0.5"
          aria-hidden="true"
        ></div>
        <!-- Panel -->
        <section
          x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
          x-transition:enter-start="-translate-x-full"
          x-transition:enter-end="translate-x-0"
          x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
          x-transition:leave-start="translate-x-0"
          x-transition:leave-end="-translate-x-full"
          x-ref="notificationsPanel"
          x-show="isNotificationsPanelOpen"
          @keydown.escape="isNotificationsPanelOpen = false"
          tabindex="-1"
          aria-labelledby="notificationPanelLabel"
          class="fixed inset-y-0 z-20 w-full max-w-xs bg-white dark:bg-darker dark:text-light sm:max-w-md focus:outline-none"
        >
          <div class="absolute right-0 p-2 transform translate-x-full">
            <!-- Close button -->
            <button
              @click="isNotificationsPanelOpen = false"
              class="p-2 text-white rounded-md focus:outline-none focus:ring"
            >
              <svg
                class="w-5 h-5"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="flex flex-col h-screen" x-data="{ activeTabe: 'action' }">
            <!-- Panel header -->
            <div class="flex-shrink-0">
              <div class="flex items-center justify-between px-4 pt-4 border-b dark:border-primary-darker">
                <h2 id="notificationPanelLabel" class="pb-4 font-semibold">Notifications</h2>
                <div class="space-x-2">
                  <button
                    @click.prevent="activeTabe = 'action'"
                    class="px-px pb-4 transition-all duration-200 transform translate-y-px border-b focus:outline-none"
                    :class="{'border-primary-dark dark:border-primary': activeTabe == 'action', 'border-transparent': activeTabe != 'action'}"
                  >
                    Action
                  </button>
                  <button
                    @click.prevent="activeTabe = 'user'"
                    class="px-px pb-4 transition-all duration-200 transform translate-y-px border-b focus:outline-none"
                    :class="{'border-primary-dark dark:border-primary': activeTabe == 'user', 'border-transparent': activeTabe != 'user'}"
                  >
                    User
                  </button>
                </div>
              </div>
            </div>

            <!-- Panel content (tabs) -->
            <div class="flex-1 pt-4 overflow-y-hidden hover:overflow-y-auto">
              <!-- Action tab -->
              <div class="space-y-4" x-show.transition.in="activeTabe == 'action'">
                <a href="#" class="block">
                  <div class="flex px-4 space-x-4">
                    <div class="relative flex-shrink-0">
                      <span
                        class="z-10 inline-block p-2 overflow-visible rounded-full bg-primary-50 text-primary-light dark:bg-primary-darker"
                      >
                        <svg
                          class="w-7 h-7"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                          />
                        </svg>
                      </span>
                      <div class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                      <h5 class="text-sm font-semibold text-gray-600 dark:text-light">
                        New project "KWD Dashboard" created
                      </h5>
                      <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                        Looks like there might be a new theme soon
                      </p>
                      <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 9h ago </span>
                    </div>
                  </div>
                </a>
                <a href="#" class="block">
                  <div class="flex px-4 space-x-4">
                    <div class="relative flex-shrink-0">
                      <span
                        class="inline-block p-2 overflow-visible rounded-full bg-primary-50 text-primary-light dark:bg-primary-darker"
                      >
                        <svg
                          class="w-7 h-7"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                          />
                        </svg>
                      </span>
                      <div class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                      <h5 class="text-sm font-semibold text-gray-600 dark:text-light">
                        KWD Dashboard v0.0.2 was released
                      </h5>
                      <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                        Successful new version was released
                      </p>
                      <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 2d ago </span>
                    </div>
                  </div>
                </a>
                <template x-for="i in 20" x-key="i">
                  <a href="#" class="block">
                    <div class="flex px-4 space-x-4">
                      <div class="relative flex-shrink-0">
                        <span
                          class="inline-block p-2 overflow-visible rounded-full bg-primary-50 text-primary-light dark:bg-primary-darker"
                        >
                          <svg
                            class="w-7 h-7"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            />
                          </svg>
                        </span>
                        <div
                          class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"
                        ></div>
                      </div>
                      <div class="flex-1 overflow-hidden">
                        <h5 class="text-sm font-semibold text-gray-600 dark:text-light">
                          New project "KWD Dashboard" created
                        </h5>
                        <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                          Looks like there might be a new theme soon
                        </p>
                        <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 9h ago </span>
                      </div>
                    </div>
                  </a>
                </template>
              </div>

              <!-- User tab -->
              <div class="space-y-4" x-show.transition.in="activeTabe == 'user'">
                <a href="#" class="block">
                  <div class="flex px-4 space-x-4">
                    <div class="relative flex-shrink-0">
                      <span class="relative z-10 inline-block overflow-visible rounded-ful">
                        <img
                          class="object-cover rounded-full w-9 h-9"
                          src="{{asset('kwd/public/build/images/avatar.jpg')}}"
                          alt="Ahmed kamel"
                        />
                      </span>
                      <div class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                      <h5 class="text-sm font-semibold text-gray-600 dark:text-light">Ahmed Kamel</h5>
                      <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                        Shared new project "K-WD Dashboard"
                      </p>
                      <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 1d ago </span>
                    </div>
                  </div>
                </a>
                <a href="#" class="block">
                  <div class="flex px-4 space-x-4">
                    <div class="relative flex-shrink-0">
                      <span class="relative z-10 inline-block overflow-visible rounded-ful">
                        <img
                          class="object-cover rounded-full w-9 h-9"
                          src="{{asset('kwd/public/build/images/avatar-1.jpg')}}"
                          alt="Ahmed kamel"
                        />
                      </span>
                      <div class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                      <h5 class="text-sm font-semibold text-gray-600 dark:text-light">John</h5>
                      <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                        Commit new changes to K-WD Dashboard project.
                      </p>
                      <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 10h ago </span>
                    </div>
                  </div>
                </a>
                <a href="#" class="block">
                  <div class="flex px-4 space-x-4">
                    <div class="relative flex-shrink-0">
                      <span class="relative z-10 inline-block overflow-visible rounded-ful">
                        <img
                          class="object-cover rounded-full w-9 h-9"
                          src="{{asset('kwd/public/build/images/avatar.jpg')}}"
                          alt="Ahmed kamel"
                        />
                      </span>
                      <div class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"></div>
                    </div>
                    <div class="flex-1 overflow-hidden">
                      <h5 class="text-sm font-semibold text-gray-600 dark:text-light">Ahmed Kamel</h5>
                      <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                        Release new version "K-WD Dashboard"
                      </p>
                      <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 20d ago </span>
                    </div>
                  </div>
                </a>
                <template x-for="i in 10" x-key="i">
                  <a href="#" class="block">
                    <div class="flex px-4 space-x-4">
                      <div class="relative flex-shrink-0">
                        <span class="relative z-10 inline-block overflow-visible rounded-ful">
                          <img
                            class="object-cover rounded-full w-9 h-9"
                            src="{{asset('kwd/public/build/images/avatar.jpg')}}"
                            alt="Ahmed kamel"
                          />
                        </span>
                        <div
                          class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-darker"
                        ></div>
                      </div>
                      <div class="flex-1 overflow-hidden">
                        <h5 class="text-sm font-semibold text-gray-600 dark:text-light">Ahmed Kamel</h5>
                        <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-lighter">
                          Release new version "K-WD Dashboard"
                        </p>
                        <span class="text-sm font-normal text-gray-400 dark:text-primary-light"> 20d ago </span>
                      </div>
                    </div>
                  </a>
                </template>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>

    <!-- All javascript code in this project for now is just for demo DON'T RELY ON IT  -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.bundle.min.js"></script>
    <script src="{{asset('kwd/public/build/js/script.js')}}"></script>
    <script>
      const setup = () => {
        const getTheme = () => {
          if (window.localStorage.getItem('dark')) {
            return JSON.parse(window.localStorage.getItem('dark'))
          }

          return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
        }

        const setTheme = (value) => {
          window.localStorage.setItem('dark', value)
        }

        const getColor = () => {
          if (window.localStorage.getItem('color')) {
            return window.localStorage.getItem('color')
          }
          return 'cyan'
        }

        const setColors = (color) => {
          const root = document.documentElement
          root.style.setProperty('--color-primary', `var(--color-${color})`)
          root.style.setProperty('--color-primary-50', `var(--color-${color}-50)`)
          root.style.setProperty('--color-primary-100', `var(--color-${color}-100)`)
          root.style.setProperty('--color-primary-light', `var(--color-${color}-light)`)
          root.style.setProperty('--color-primary-lighter', `var(--color-${color}-lighter)`)
          root.style.setProperty('--color-primary-dark', `var(--color-${color}-dark)`)
          root.style.setProperty('--color-primary-darker', `var(--color-${color}-darker)`)
          this.selectedColor = color
          window.localStorage.setItem('color', color)
          //
        }

        const updateBarChart = (on) => {
          const data = {
            data: randomData(),
            backgroundColor: 'rgb(207, 250, 254)',
          }
          if (on) {
            barChart.data.datasets.push(data)
            barChart.update()
          } else {
            barChart.data.datasets.splice(1)
            barChart.update()
          }
        }

        const updateDoughnutChart = (on) => {
          const data = random()
          const color = 'rgb(207, 250, 254)'
          if (on) {
            doughnutChart.data.labels.unshift('Seb')
            doughnutChart.data.datasets[0].data.unshift(data)
            doughnutChart.data.datasets[0].backgroundColor.unshift(color)
            doughnutChart.update()
          } else {
            doughnutChart.data.labels.splice(0, 1)
            doughnutChart.data.datasets[0].data.splice(0, 1)
            doughnutChart.data.datasets[0].backgroundColor.splice(0, 1)
            doughnutChart.update()
          }
        }

        const updateLineChart = () => {
          lineChart.data.datasets[0].data.reverse()
          lineChart.update()
        }

        return {
          loading: true,
          isDark: getTheme(),
          toggleTheme() {
            this.isDark = !this.isDark
            setTheme(this.isDark)
          },
          setLightTheme() {
            this.isDark = false
            setTheme(this.isDark)
          },
          setDarkTheme() {
            this.isDark = true
            setTheme(this.isDark)
          },
          color: getColor(),
          selectedColor: 'cyan',
          setColors,
          toggleSidbarMenu() {
            this.isSidebarOpen = !this.isSidebarOpen
          },
          isSettingsPanelOpen: false,
          openSettingsPanel() {
            this.isSettingsPanelOpen = true
            this.$nextTick(() => {
              this.$refs.settingsPanel.focus()
            })
          },
          isNotificationsPanelOpen: false,
          openNotificationsPanel() {
            this.isNotificationsPanelOpen = true
            this.$nextTick(() => {
              this.$refs.notificationsPanel.focus()
            })
          },
          isSearchPanelOpen: false,
          openSearchPanel() {
            this.isSearchPanelOpen = true
            this.$nextTick(() => {
              this.$refs.searchInput.focus()
            })
          },
          isMobileSubMenuOpen: false,
          openMobileSubMenu() {
            this.isMobileSubMenuOpen = true
            this.$nextTick(() => {
              this.$refs.mobileSubMenu.focus()
            })
          },
          isMobileMainMenuOpen: false,
          openMobileMainMenu() {
            this.isMobileMainMenuOpen = true
            this.$nextTick(() => {
              this.$refs.mobileMainMenu.focus()
            })
          },
          updateBarChart,
          updateDoughnutChart,
          updateLineChart,
        }
      }
    </script>
@endsection