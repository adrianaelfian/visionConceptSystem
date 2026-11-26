<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Tailwind CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="flex min-h-screen">

            <!-- SIDEBAR KIRI (Warna Ungu Gelap Premium) -->
            @include('layouts.navigation-manager')

            <!-- KANDUNGAN UTAMA SEBELAH KANAN -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 md:p-10">
                    @yield('content')
                </main>
            </div>

        </div>
    </body>
</html>
