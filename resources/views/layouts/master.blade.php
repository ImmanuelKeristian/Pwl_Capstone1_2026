<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-menu-fixed" dir="ltr"
      data-theme="theme-default"
      data-assets-path="{{ asset('assets/') }}/" {{-- PASTIKAN INI SESUAI: Path ke folder aset Sneat di public/ Anda. Jika Anda menaruhnya di public/sneat-assets/, ganti menjadi {{ asset('sneat-assets/') }}/ --}}
      data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    
    <title>@yield('title', config('app.name', 'Sneat'))</title> {{-- Judul Halaman Dinamis --}}
    
    <meta name="description" content="@yield('meta_description', 'Deskripsi default aplikasi Anda.')" />
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- CSRF Token untuk AJAX jika perlu --}}

    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" /> {{-- Sesuaikan path jika perlu --}}

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    {{-- <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" /> --}} {{-- Hanya jika Anda menggunakan Apex Charts di banyak halaman --}}

    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <script src="{{ asset('assets/js/config.js') }}"></script>

    @stack('styles') {{-- Untuk CSS tambahan khusus halaman --}}
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            {{-- Sidebar/Menu --}}
            @include('layouts.menu')

            {{-- Layout content --}}
            <div class="layout-page">

                {{-- Navbar Atas --}}
                @include('layouts.navbar')

                {{-- Konten Utama Halaman --}}
                <div class="content-wrapper">
                    @yield('web-content') {{-- Konten spesifik halaman akan dirender di sini --}}

                    {{-- Footer --}}
                    @include('layouts.footer')

                    <div class="content-backdrop fade"></div>
                </div>
                {{-- /Konten Utama Halaman --}}
            </div>
            {{-- /Layout content --}}
        </div>

        {{-- Overlay untuk menu mobile --}}
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    {{-- <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script> --}} {{-- Hanya jika perlu global, lebih baik di push per halaman --}}

    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script> --}} {{-- Ini spesifik dashboard demo, lebih baik di push per halaman jika dipakai --}}
    
    {{-- <script async defer src="https://buttons.github.io/buttons.js"></script> --}} {{-- Jika tidak pakai GitHub buttons, hapus saja --}}

    @stack('scripts') {{-- Untuk JavaScript tambahan khusus halaman --}}
</body>
</html>