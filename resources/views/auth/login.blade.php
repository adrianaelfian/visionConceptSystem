<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Sign In</title>

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-slate-900 text-slate-950 min-h-screen relative flex flex-col justify-between overflow-x-hidden" x-data="{ selectedRole: 'customer' }">

    <!-- 1. LATAR BELAKANG GAMBAR PENUH DENGAN KABUR & TONA UNGU -->
    <div class="absolute inset-0 z-0">
        <!-- Simpan gambar anda di folder public/images/ atau guna pautan rasmi -->
        <img src="{{ asset('images/background.jpg') }}" alt="Eyewear Background" class="w-full h-full object-cover filter brightness-90 scale-105">
        <!-- Lapisan Penapis Ungu (Purple Tint Overlay) supaya serasi dengan tema sistem -->
        <div class="absolute inset-0 bg-gradient-to-tr from-[#3b127e]/90 via-[#3b127e]/70 to-purple-900/60 backdrop-blur-[2px]"></div>
    </div>

    <!-- 2. HEADER ATAS (Logo Kiri, Tagline Kanan) -->
    <header class="relative z-10 w-full py-6 px-8 sm:px-12 flex justify-between items-center max-w-7xl mx-auto text-white">
        <!-- Tagline Kecil Kanan -->
        <div class="w-full text-center text-base sm:text-lg md:text-xl font-semibold tracking-widest uppercase text-purple-200/80">
    Vision Concept Eyewear Studio
</div>
    </header>

    <!-- 3. KANDUNGAN UTAMA: KAD KACA TERAPUNG (Glassmorphism) -->
    <main class="relative z-10 max-w-md w-full mx-auto px-4 py-6 flex-grow flex items-center justify-center">
        <div class="bg-white/85 backdrop-blur-xl p-8 sm:p-10 rounded-[36px] border border-white/40 shadow-2xl w-full space-y-6">

            <!-- Logo Kecil & Tajuk Selamat Datang -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-[#3b127e] text-white flex items-center justify-center mx-auto shadow-md mb-3">
                    <img src="{{ asset('images/visionConceptLogo.png') }}" alt="Vision Concept Logo" class="h-10 sm:h-12 w-auto object-contain brightness-0 invert">
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h1>
                <p class="text-xs text-slate-600" x-text="
                    selectedRole === 'customer' ? 'Log in to continue your eyewear & appointment journey' :
                    (selectedRole === 'salesperson' ? 'Staff Portal: Handle counter sales & prescriptions' :
                    'Manager Portal: Oversee store operations & reports')
                ">
                </p>
            </div>

            <!-- Tab Pilihan Peranan (Customer, Salesperson, Manager) -->
            <div class="grid grid-cols-3 gap-1 bg-purple-100/70 p-1 rounded-2xl text-[11px] font-bold border border-purple-200/50">
                <button type="button" @click="selectedRole = 'customer'"
                    :class="selectedRole === 'customer' ? 'bg-[#3b127e] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
                    class="py-2 rounded-xl transition-all">
                    Customer
                </button>
                <button type="button" @click="selectedRole = 'salesperson'"
                    :class="selectedRole === 'salesperson' ? 'bg-[#3b127e] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
                    class="py-2 rounded-xl transition-all">
                    Salesperson
                </button>
                <button type="button" @click="selectedRole = 'manager'"
                    :class="selectedRole === 'manager' ? 'bg-[#3b127e] text-white shadow-md' : 'text-slate-600 hover:text-slate-900'"
                    class="py-2 rounded-xl transition-all">
                    Manager
                </button>
            </div>

            <!-- Paparan Ralat -->
            @if ($errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Borang Log Masuk -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <input type="hidden" name="role" x-model="selectedRole">

                <!-- Email Field -->
                <div>
                    <label class="block text-[11px] font-bold text-slate-700 uppercase mb-1">Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200/80 bg-white/70 text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#3b127e] focus:bg-white transition"
                            placeholder="name@example.com">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label class="block text-[11px] font-bold text-slate-700 uppercase">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-[11px] font-bold text-[#6d28d9] hover:underline">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password" required autocomplete="current-password"
                            class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200/80 bg-white/70 text-xs font-semibold text-slate-800 focus:outline-none focus:border-[#3b127e] focus:bg-white transition"
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#3b127e] focus:ring-[#3b127e] w-4 h-4">
                        <span class="text-slate-600 font-medium">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 bg-[#3b127e] hover:bg-purple-900 text-white rounded-xl text-xs font-bold shadow-lg hover:shadow-xl transition duration-200 flex items-center justify-center space-x-2">
                    <span>Sign In as <span class="capitalize" x-text="selectedRole"></span></span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </button>
            </form>

            <!-- Pautan Daftar Akaun (Hanya untuk Customer) -->
            <div class="text-center pt-2 border-t border-slate-200/60" x-show="selectedRole === 'customer'">
                <p class="text-xs text-slate-600">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-bold text-[#6d28d9] hover:underline">Sign up</a>
                </p>
            </div>

        </div>
    </main>

    <!-- 4. FOOTER BAWAH -->
    <footer class="relative z-10 w-full py-6 text-center text-purple-200/70">
        <p class="text-[10px]">&copy; {{ date('Y') }} Vision Concept Management System. All rights reserved.</p>
    </footer>

</body>
</html>
