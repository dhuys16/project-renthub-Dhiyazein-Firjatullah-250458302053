@extends('layouts.baseadmin')

@section('title', 'Admin')

@section('body')
  <body class="m-0 font-sans text-base antialiased font-normal dark:bg-slate-900 leading-default !bg-blue-500 text-slate-500">
    
    <div id="sidenav-overlay" class="fixed inset-0 bg-black/50 z-[980] hidden xl:hidden transition-opacity duration-300 opacity-0" onclick="toggleSidebar()"></div>

    <aside id="sidenav-main" class="fixed inset-y-0 left-0 flex-wrap items-center justify-between block w-full p-0 my-4 overflow-y-auto antialiased transition-transform duration-300 -translate-x-full bg-white border-0 shadow-xl dark:shadow-none dark:bg-slate-850 max-w-64 ease-nav-brand z-[990] xl:ml-6 rounded-2xl xl:translate-x-0" aria-expanded="false">
      <div class="h-19">
          <i class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times dark:text-white text-slate-400 xl:hidden" onclick="toggleSidebar()"></i>
          
          <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap dark:text-white text-slate-700 flex justify-center items-center" href="{{route('home')}}" >
              <img src="{{asset('assets/img/2.jpg')}}" class="max-h-10" alt="main_logo" />
          </a>
      </div>

      <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent dark:bg-gradient-to-r dark:from-transparent dark:via-white dark:to-transparent" />

      <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
          
          <li class="mt-0.5 w-full">
            <a class="{{ request()->routeIs('admin.dashboard') ? 'bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-500' }} py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80" href="{{ route('admin.dashboard') }}">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-blue-500 ni ni-tv-2"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Dashboard</span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a class="{{ request()->is('admin/users*') ? 'bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-500' }} py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80" href="/admin/users">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-slate-700 ni ni-single-02"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Data User</span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <a class="{{ request()->is('admin/products*') ? 'bg-blue-500/13 font-semibold text-slate-700' : 'text-slate-500' }} py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors hover:bg-gray-100 dark:text-white dark:opacity-80" href="/admin/products">
              <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                <i class="relative top-0 text-sm leading-normal text-yellow-500 ni ni-app"></i>
              </div>
              <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Data Produk</span>
            </a>
          </li>

          <li class="mt-0.5 w-full">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left py-2.7 text-sm ease-nav-brand my-0 mx-2 flex items-center whitespace-nowrap rounded-lg px-4 transition-colors hover:bg-red-50 text-slate-500 dark:text-white dark:opacity-80 dark:hover:bg-red-900/20">
                    <div class="mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-center stroke-0 text-center xl:p-2.5">
                        <i class="relative top-0 text-sm leading-normal text-red-500 fa-solid fa-arrow-right-from-bracket"></i>
                    </div>
                    <span class="ml-1 duration-300 opacity-100 pointer-events-none ease">Log Out</span>
                </button>
            </form>
          </li>
        </ul>
      </div>
    </aside>
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out xl:ml-68 rounded-xl">
      
      <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 mt-4 transition-all shadow-none duration-250 ease-in-out rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="false">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
            
            <div class="flex items-center">
                
                <button type="button" onclick="toggleSidebar()" class="mr-4 !text-white xl:hidden focus:outline-none">
                    <div class="w-6 relative">
                        <i class="fas fa-bars text-xl"></i>
                    </div>
                </button>

                <nav>
                    <ol class="flex flex-wrap pt-1 bg-transparent rounded-lg">
                    <li class="text-sm leading-normal text-white dark:text-white opacity-50">
                        <a href="javascript:;">Pages</a>
                    </li>
                    </ol>
                    <h6 class="mb-0 font-bold capitalize text-white dark:text-white">@yield('part')</h6>
                </nav>
            </div>

            <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto justify-end">
                <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                    <li class="flex items-center">
                        @php
                            $adminUser = Auth::user(); 
                            $photoPath = $adminUser && $adminUser->photo_profile 
                                ? asset('storage/' . $adminUser->photo_profile) 
                                : asset('assets/img/user.jpg');
                        @endphp

                        <a href="{{ route('user.profile.show') ?? '#' }}" class="block px-0 py-2 text-sm font-semibold text-white dark:text-white transition-all ease-nav-brand flex items-center">
                            <span class="hidden sm:inline text-base font-bold mr-2">
                                {{ $adminUser->username ?? $adminUser->name ?? 'Admin' }}
                            </span>
                            <img class="w-10 h-10 rounded-full object-cover border border-gray-300 shadow-sm" 
                                src="{{ $photoPath }}" 
                                alt="Profile" />
                        </a>
                    </li>
                </ul>
            </div>
        </div>
      </nav>
      <div class="w-full pb-2">
          @yield('content')
      </div>
      <footer class="pt-4 fixed bottom-0 bg-white mb-4 w-[75%] mx-6 border border-white rounded-xl shadow-lg dark:bg-slate-850 dark:shadow-dark-lg">
        <div class="w- full pb-3 ml-4">
          <div class="flex flex-row items-center lg:justify-between">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-sm text-black">
            &copy; {{ date('Y') }} RentHub. All rights reserved.
            </div>
          </div>
        </div>
      </footer> 
    </main>
  </body>

  <script src="{{asset('argon/build/assets/js/plugins/chartjs.min.js')}}" async></script>
  <script src="{{asset('argon/build/assets/js/plugins/perfect-scrollbar.min.js')}}" async></script>
  <script src="{{asset('argon/build/assets/js/argon-dashboard-tailwind.js?v=1.0.1')}}" async></script>

  <script>
    function toggleSidebar() {
        const sidenav = document.getElementById('sidenav-main');
        const overlay = document.getElementById('sidenav-overlay');
        
        // Cek apakah sidebar sedang tersembunyi (memiliki class -translate-x-full)
        if (sidenav.classList.contains('-translate-x-full')) {
            // Buka Sidebar
            sidenav.classList.remove('-translate-x-full');
            sidenav.classList.add('translate-x-0', 'shadow-xl');
            
            // Tampilkan Overlay
            overlay.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                overlay.classList.add('opacity-100');
            }, 10); // delay kecil agar transisi opacity berjalan
        } else {
            // Tutup Sidebar
            sidenav.classList.add('-translate-x-full');
            sidenav.classList.remove('translate-x-0', 'shadow-xl');
            
            // Sembunyikan Overlay
            overlay.classList.remove('opacity-100');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300); // sesuaikan dengan duration-300
        }
    }
  </script>

  @stack('scripts')
@endsection