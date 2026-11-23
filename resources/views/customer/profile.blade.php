@extends('layouts.customer')

@section('content')
<div class="space-y-6">
    <!-- Header Aluan -->
    <div class="bg-[#3b127e] rounded-3xl p-6 md:p-8 text-white shadow-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl md:text-2xl font-black uppercase tracking-wider">My Profile</h1>
            <p class="text-xs text-purple-200 mt-1">Manage your personal details and account information.</p>
        </div>
        <div class="bg-white/10 px-4 py-2 rounded-2xl border border-purple-400/30 text-xs font-semibold">
            Role: <span class="capitalize">{{ Auth::user()->role ?? 'Customer' }}</span>
        </div>
    </div>

    <!-- Mesej Kejayaan (Jika ada kemas kini) -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Kad Maklumat Profil -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Kotak Kiri: Ringkasan Avatar & Status -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col items-center text-center">
            <div class="w-20 h-20 rounded-2xl bg-purple-100 text-[#3b127e] text-3xl flex items-center justify-center mb-4 shadow-inner">
                <i class="fa-solid fa-user-circle"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900 truncate w-full">{{ $customer->customerName ?? Auth::user()->name }}</h3>
            <p class="text-[11px] text-slate-500 mb-2">{{ $customer->customerEmail ?? Auth::user()->email }}</p>

            <!-- Paparan ID Pelanggan di Kotak Kiri -->
            @if($customer)
                <span class="bg-purple-50 text-[#3b127e] font-black text-[10px] px-3 py-1 rounded-full mb-4 border border-purple-100">
                    ID: #{{ $customer->id }}
                </span>
            @endif

            <div class="w-full pt-4 border-t border-slate-100 flex justify-between items-center text-xs">
                <span class="text-slate-500 font-medium">Member Status</span>
                <span class="bg-emerald-50 text-emerald-600 font-bold px-2.5 py-1 rounded-xl text-[10px]">Active Member</span>
            </div>
        </div>

        <!-- Kotak Kanan: Butiran Maklumat Terperinci -->
        <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-100 md:col-span-2 space-y-6">
            <h4 class="text-xs font-black uppercase text-slate-400 tracking-wider">Personal Information</h4>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
                <!-- Kotak Customer ID Terperinci -->
                <div>
                    <span class="block text-slate-400 font-medium mb-1">Customer ID / Member Number</span>
                    <div class="p-3 bg-purple-50/50 rounded-xl font-black text-[#3b127e] border border-purple-100 flex items-center justify-between">
                        <span>#{{ $customer->id ?? '-' }}</span>
                        <span class="text-[10px] bg-purple-100 text-purple-700 px-2 py-0.5 rounded-lg font-bold">Show to Salesperson</span>
                    </div>
                </div>

                <div>
                    <span class="block text-slate-400 font-medium mb-1">Full Name</span>
                    <div class="p-3 bg-slate-50 rounded-xl font-bold text-slate-800 border border-slate-100">
                        {{ $customer->customerName ?? Auth::user()->name }}
                    </div>
                </div>

                <div>
                    <span class="block text-slate-400 font-medium mb-1">Email Address</span>
                    <div class="p-3 bg-slate-50 rounded-xl font-bold text-slate-800 border border-slate-100">
                        {{ $customer->customerEmail ?? Auth::user()->email }}
                    </div>
                </div>

                <div>
                    <span class="block text-slate-400 font-medium mb-1">Phone Number</span>
                    <div class="p-3 bg-slate-50 rounded-xl font-bold text-slate-800 border border-slate-100">
                        {{ $customer->customerPhoneNumber ?? 'Not Provided' }}
                    </div>
                </div>

                <div class="md:col-span-2">
                    <span class="block text-slate-400 font-medium mb-1">Account Registered</span>
                    <div class="p-3 bg-slate-50 rounded-xl font-bold text-slate-800 border border-slate-100">
                        {{ Auth::user()->created_at ? Auth::user()->created_at->format('d/m/Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
