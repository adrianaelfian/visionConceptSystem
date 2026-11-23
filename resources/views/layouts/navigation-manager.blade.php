<aside class="w-64 bg-[#3b127e] text-white flex flex-col justify-between hidden md:flex border-r border-purple-900/50">
    <div class="p-6">
        <!-- Logo / Nama Syarikat -->
        <div class="flex items-center space-x-3 mb-8">
            <img src="{{ asset('visionConceptLogo.png') }}" alt="Vision Concept Logo" class="w-10 h-10 object-contain rounded-xl brightness-0 invert">
            <div>
                <div class="text-xs font-black tracking-wider uppercase">Vision Concept</div>
                <div class="text-[10px] text-purple-300">Manager Portal</div>
            </div>
        </div>

        <!-- Navigasi Menu Manager -->
        <nav class="space-y-1.5 text-xs font-semibold">
            <!-- Menu Dashboard -->
            <a href="{{ route('manager.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('manager.dashboard') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                <span>Dashboard</span>
            </a>

            <!-- Menu Manage Account (Tukar ikon kepada fa-users-gear atau fa-user-shield) -->
            <a href="{{ route('manager.accounts.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('manager.accounts.*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-users-gear w-4 text-center"></i>
                <span>Manage Account</span>
            </a>

            <!-- Menu Manage Reports -->
            <a href="{{ route('manager.reports.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('manager.reports.*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-file-lines w-4 text-center"></i>
                <span>Reports</span>
            </a>

            <!-- Menu Manage Inventory Item -->
            <a href="{{ route('manager.items.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-xs font-semibold transition {{ request()->routeIs('manager.items.*') ? 'bg-white text-[#3b127e] shadow-sm font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-boxes-stacked w-4 text-center"></i>
                <span>Manage Inventory</span>
            </a>
        </nav>
    </div>

    <!-- Bahagian Profil & Log Keluar -->
    <div class="p-6 border-t border-purple-900/50 bg-[#320f6b]">
        <div class="flex items-center justify-between">
            <div class="overflow-hidden pr-2">
                <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Manager' }}</div>
                <div class="text-[10px] text-purple-300 capitalize">{{ Auth::user()->role ?? 'Manager' }}</div>
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
