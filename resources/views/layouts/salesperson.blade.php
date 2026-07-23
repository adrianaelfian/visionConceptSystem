<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Salesperson Portal</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="flex min-h-screen">

            <!-- SIDEBAR KIRI (Warna Ungu Gelap Premium) -->
            @include('layouts.navigation-salesperson')

            <!-- KANDUNGAN UTAMA SEBELAH KANAN -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-10 space-y-6">

                    <!-- Paparan Notifikasi Global -->
                    @if(session('success'))
                        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-xs font-bold flex items-center space-x-2 shadow-xs">
                            <i class="fa-solid fa-circle-check text-base"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium space-y-1 shadow-xs">
                            @foreach($errors->all() as $error)
                                <div class="flex items-center space-x-2">
                                    <i class="fa-solid fa-circle-exclamation font-bold"></i>
                                    <span>{{ $error }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Kandungan Halaman (Slot) -->
                    @yield('content')
                </main>
            </div>

        </div>
    </body>
</html>
