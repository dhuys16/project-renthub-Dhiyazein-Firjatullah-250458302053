@extends('layouts.admin')

@section('title', 'Daftar User')

@section('part', 'Daftar Pengguna')

@section('content')
<body class="m-0 font-sans text-base antialiased font-normal leading-default bg-gray-50 text-slate-500">
    <div class="absolute bg-blue-500 min-h-75"></div>
    <main class="relative h-full max-h-screen transition-all duration-200 ease-in-out rounded-xl">
        <div class="w-full py-6 px-6">
            
            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
                
            <section class="mb-[40px]">
            <div class="flex flex-wrap mb-10">
                <div class="flex-none w-full px-3">
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-solid shadow-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 border-solid rounded-t-2xl border-b-transparent">
                            <h6>Tabel Customer ({{ $customers->total() }})</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center w-full mb-0 align-top border-collapse text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 ">No.</th>
                                            <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 ">User Info</th>
                                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 ">Username</th>
                                            <th class="px-6 py-3 font-bold text-center uppercase align-middle bg-transparent border-b border-collapse shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400 ">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $customer)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2 py-1">
                                                    <h6 class="text-sm font-semibold leading-normal text-slate-700">
                                                        {{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">                                          
                                                <div class="flex px-2 py-1">
                                                    <div class="flex flex-col justify-center">
                                                        <h6 class="mb-0 text-sm leading-normal text-slate-700">{{ $customer->name }}</h6>
                                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $customer->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $customer->username }}</p>
                                            </td>
                                            <td class="p-2 text-center align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <a href="{{ route('admin.users.show', $customer) }}" class="text-xs font-semibold leading-tight text-indigo-600 hover:text-indigo-800"> Detail </a>
                                                {{-- Tambahkan tombol delete jika diperlukan --}}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- PAGINATION CUSTOMER --}}
                        <div class="p-4">
                            {{ $customers->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="flex-none w-full px-3">
                    <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 border-solid shadow-xl rounded-2xl bg-clip-border">
                        <div class="p-6 pb-0 mb-0 border-b-0 border-solid rounded-t-2xl border-b-transparent">
                            <h6>Tabel Vendor ({{ $vendors->total() }})</h6>
                        </div>
                        <div class="flex-auto px-0 pt-0 pb-2">
                            <div class="p-0 overflow-x-auto">
                                <table class="items-center justify-center w-full mb-0 align-top border-collapse text-slate-500">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th class="px-6 py-3 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400">No.</th>
                                            <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400">Vendor Info</th>
                                            <th class="px-6 py-3 pl-2 font-bold text-left uppercase align-middle bg-transparent border-b shadow-none text-xxs border-b-solid tracking-none whitespace-nowrap text-slate-400">Total Produk</th>
                                            <th class="px-6 py-3 font-semibold capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="border-t">
                                        @foreach ($vendors as $vendor)
                                        <tr>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <div class="flex px-2">
                                                    <h6 class="text-sm font-semibold leading-normal text-slate-700">
                                                        {{ $loop->iteration + ($vendors->currentPage() - 1) * $vendors->perPage() }}
                                                    </h6>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">                                              
                                                <div class="flex px-2">
                                                    <div class="my-auto">
                                                        <h6 class="mb-0 text-sm leading-normal text-slate-700">{{ $vendor->username }}</h6>
                                                        <p class="mb-0 text-xs leading-tight text-slate-400">{{ $vendor->email }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <p class="mb-0 text-sm font-semibold leading-normal text-slate-700">{{ $vendor->products_count ?? 'N/A' }}</p>
                                            </td>
                                            <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap shadow-transparent">
                                                <a href="{{ route('admin.users.show', $vendor) }}" class="text-xs font-semibold leading-tight text-indigo-600 hover:text-indigo-800"> Detail </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- PAGINATION VENDOR --}}
                        <div class="p-4">
                            {{ $vendors->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
            </section>
        </div>
    </main>
</body>
@endsection