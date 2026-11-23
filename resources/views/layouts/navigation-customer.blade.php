<aside class="w-64 bg-[#3b127e] text-white flex flex-col justify-between hidden md:flex border-r border-purple-900/50">
    <div class="p-6">
        <!-- Logo / Nama Syarikat -->
        <div class="flex items-center space-x-3 mb-8">
            <!-- Gambar Logo -->
            <img src="{{ asset('visionConceptLogo.png') }}" alt="Vision Concept Logo" class="w-10 h-10 object-contain rounded-xl brightness-0 invert">

            <div>
                <div class="text-xs font-black tracking-wider uppercase">Vision Concept</div>
                <div class="text-[10px] text-purple-300">Customer Portal</div>
            </div>
        </div>

        <!-- Navigasi Menu Customer -->
        <nav class="space-y-1.5 text-xs font-semibold">
            <!-- Menu Dashboard -->
            <a href="{{ route('customer.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('customer.dashboard') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-chart-pie w-4"></i>
                <span>Dashboard</span>
            </a>

            <!-- Menu My Appointments -->
            <a href="{{ route('customer.appointments.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('customer.appointments*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-calendar-days w-4"></i>
                <span>My Appointments</span>
            </a>

            <!-- Menu Warranties -->
            <a href="{{ route('customer.warranties') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('customer.warranties*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-shield-halved w-4"></i>
                <span>Warranties</span>
            </a>

            <!-- Navigasi: My Orders -->
<a href="{{ route('customer.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('customer.orders*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
    <i class="fa-solid fa-bag-shopping w-4"></i>
    <span>My Orders</span>
</a>
        </nav>
    </div>

    <!-- Bahagian Profil & Log Keluar -->
    <div class="p-6 border-t border-purple-900/50 bg-[#320f6b]">
        <div class="flex items-center justify-between">
            <div class="overflow-hidden pr-2">
                <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Customer' }}</div>
                <div class="text-[10px] text-purple-300 capitalize">{{ Auth::user()->role ?? 'Customer' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 text-purple-300 hover:text-white transition flex items-center justify-center" title="Logout">
                    <i class="fa-solid fa-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
