<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @hasSection('title')

            <title>@yield('title') - {{ config('RentHub') }}</title>
        @else
            <title>{{ config('RentHub') }}</title>
        @endif

        <!-- Favicon -->
		<link rel="shortcut icon" href="{{ url(asset('favicon.ico')) }}">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @livewireScripts

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link
      rel="shortcut icon"
      href="{{asset('play/assets/images/favicon.png')}}"
      type="image/x-icon"
    />
    <link rel="stylesheet" href="{{asset('play/assets/css/swiper-bundle.min.css')}}" />
    <link rel="stylesheet" href="{{asset('play/assets/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('play/src/css/tailwind.css')}}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body>
        @yield('body')
    </body>
</html>
