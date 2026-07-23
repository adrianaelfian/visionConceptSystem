<aside class="w-64 bg-[#3b127e] text-white flex flex-col justify-between hidden md:flex border-r border-purple-900/50">
    <div class="p-6">
        <!-- Logo / Nama Syarikat -->
        <div class="flex items-center space-x-3 mb-8">
            <!-- Gambar Logo -->
            <img src="{{ asset('visionConceptLogo.png') }}" alt="Vision Concept Logo" class="w-10 h-10 object-contain rounded-xl brightness-0 invert">

            <div>
                <div class="text-xs font-black tracking-wider uppercase">Vision Concept</div>
                <div class="text-[10px] text-purple-300">Admin Portal</div>
            </div>
        </div>

        <!-- Navigasi Menu Admin -->
        <nav class="space-y-1.5 text-xs font-semibold">
            <!-- Menu Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-chart-pie w-4"></i>
                <span>Dashboard</span>
            </a>

            <!-- Menu Manage Accounts -->
            <a href="{{ route('admin.accounts.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.accounts*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-users-gear w-4"></i>
                <span>Manage Accounts</span>
            </a>

            <!-- Menu Manage Appointments -->
            <a href="{{ route('admin.appointments.index') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.appointments*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-calendar-days w-4"></i>
                <span>Manage Appointments</span>
            </a>

            <!-- Menu Manage Reports -->
            <a href="{{ route('admin.reports.create') }}" class="flex items-center space-x-3 px-4 py-3 {{ request()->routeIs('admin.reports*') ? 'bg-white text-[#3b127e] shadow-md font-bold' : 'text-purple-200 hover:bg-white/5 hover:text-white' }} rounded-xl transition">
                <i class="fa-solid fa-file-lines w-4"></i>
                <span>Manage Reports</span>
            </a>
        </nav>
    </div>

    <!-- Bahagian Profil & Log Keluar -->
    <div class="p-6 border-t border-purple-900/50 bg-[#320f6b]">
        <div class="flex items-center justify-between">
            <div class="overflow-hidden pr-2">
                <div class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="text-[10px] text-purple-300 capitalize">{{ Auth::user()->role ?? 'Admin' }}</div>
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
