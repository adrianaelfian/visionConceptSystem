<aside class="w-64 bg-[#3b127e] text-white flex flex-col justify-between hidden md:flex border-r border-purple-900/50">
    <div class="p-6">
        <!-- Logo / Nama Syarikat -->
        <div class="flex items-center space-x-3 mb-8">
            <img src="{{ asset('visionConceptLogo.png') }}" alt="Vision Concept Logo" class="w-10 h-10 object-contain rounded-xl brightness-0 invert">
            <div>
                <div class="text-xs font-black tracking-wider uppercase">Vision Concept</div>
                <div class="text-[10px] text-purple-300">Salesperson Portal</div>
            </div>
        </div>

        <!-- Navigasi Menu Ikut Skop Kerja Salesperson (Gaya Manager) -->
        <nav class="space-y-1.5 text-xs font-semibold">
            <a href="{{ route('salesperson.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('salesperson.dashboard') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-chart-pie w-4"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('salesperson.customer.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('salesperson.customer.*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-users w-4"></i>
                <span>Customer Details</span>
            </a>


            <a href="{{ route('salesperson.orders.create') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('salesperson.orders.create') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-cart-plus w-4"></i>
                <span>Create Order</span>
            </a>

            <a href="{{ route('salesperson.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('salesperson.orders.index', 'salesperson.orders.edit') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-clipboard-list w-4"></i>
                <span>Manage Orders</span>
            </a>
        </nav>
    </div>

    <!-- Bahagian Profil & Log Keluar -->
    <div class="p-6 border-t border-purple-900/50 bg-[#320f6b]">
        <div class="flex items-center justify-between">
            <div class="overflow-hidden pr-2">
                <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Salesperson' }}</div>
                <div class="text-[10px] text-purple-300 capitalize">{{ Auth::user()->role ?? 'Salesperson' }}</div>
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
