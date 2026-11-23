@extends('layouts.customer') <!-- Sesuaikan dengan nama layout pelanggan anda -->

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header Section -->
        <div class="bg-gradient-to-r from-purple-900 to-[#3b127e] p-6 rounded-2xl text-white shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl font-black">Welcome Back, {{ Auth::user()->name }}!</h1>
                <p class="text-xs text-purple-200 mt-1">Manage your eyewear orders, view appointments, and track your vision care.</p>
            </div>
            <a href="{{ route('customer.appointments.create') }}" class="bg-white text-[#6d28d9] px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-50 transition shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-calendar-plus"></i>
                <span>Book New Appointment</span>
            </a>
        </div>

       <!-- 👤 PROFIL & MAKSUMAT AKAUN PELANGGAN -->
<div class="bg-gradient-to-br from-white via-purple-50/20 to-purple-100/30 rounded-3xl p-6 shadow-sm border border-purple-100/60 relative overflow-hidden" x-data="{ editing: false }">
    <!-- Hiasan latar belakang sudut (Watermark Icon) -->
    <div class="absolute -right-4 -bottom-4 text-purple-100/50 text-8xl pointer-events-none">
        <i class="fa-solid fa-id-card"></i>
    </div>

    <div class="relative z-10">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-purple-100/80 pb-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-[#3b127e] text-white flex items-center justify-center shadow-md">
                    <i class="fa-solid fa-user-gear text-sm"></i>
                </div>
                <div>
                    <h3 class="text-sm font-black text-[#2e1065] uppercase tracking-wider">My Account Profile</h3>
                    <p class="text-[11px] text-slate-500">Manage your personal details and registered contact info</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 font-extrabold text-[10px] px-3 py-1 rounded-full flex items-center gap-1.5 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Active Member
                </span>

                <!-- Butang Toggle Edit Profile -->
                <button @click="editing = !editing" class="px-3.5 py-1.5 bg-[#3b127e] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs flex items-center gap-1.5">
                    <i class="fa-solid" :class="editing ? 'fa-xmark' : 'fa-pen-to-square'"></i>
                    <span x-text="editing ? 'Cancel' : 'Edit Profile'"></span>
                </button>
            </div>
        </div>

        <!-- 1. PAPARAN MAKSUMAT BIASA (Papar bila editing = false) -->
        <div x-show="!editing" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-center transition-all">
            <!-- Kotak Kiri: Avatar & ID Pelanggan -->
            <div class="flex items-center space-x-4 md:col-span-1 border-b md:border-b-0 md:border-r border-purple-100/80 pb-4 md:pb-0">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#3b127e] to-purple-600 text-white text-2xl flex items-center justify-center shadow-md shrink-0">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div class="overflow-hidden">
                    <h4 class="text-xs font-black text-slate-900 truncate">{{ $customer->customerName ?? Auth::user()->name }}</h4>
                    <span class="inline-block mt-1 bg-purple-100 text-[#3b127e] font-black text-[10px] px-3 py-0.5 rounded-lg border border-purple-200 shadow-2xs">
                        ID: #{{ $customer->id ?? '-' }}
                    </span>
                </div>
            </div>

            <!-- Kotak Kanan: Butiran Maklumat (Grid 3 Kolum) -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs md:col-span-3">
                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-purple-50 shadow-2xs">
                    <span class="block text-slate-400 font-bold text-[10px] uppercase mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-envelope text-purple-400"></i> Email Address
                    </span>
                    <span class="font-bold text-slate-800 truncate block">{{ $customer->customerEmail ?? Auth::user()->email }}</span>
                </div>

                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-purple-50 shadow-2xs">
                    <span class="block text-slate-400 font-bold text-[10px] uppercase mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-phone text-purple-400"></i> Phone Number
                    </span>
                    <span class="font-bold text-slate-800">{{ $customer->customerPhoneNumber ?? 'Not Provided' }}</span>
                </div>

                <div class="bg-white/80 backdrop-blur-xs p-3.5 rounded-2xl border border-purple-50 shadow-2xs">
                    <span class="block text-slate-400 font-bold text-[10px] uppercase mb-1 flex items-center gap-1.5">
                        <i class="fa-solid fa-calendar-days text-purple-400"></i> Member Since
                    </span>
                    <span class="font-bold text-slate-800">
                        {{ Auth::user()->created_at ? Auth::user()->created_at->format('d M Y') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 2. BORANG KEMASKINI (Papar bila butang 'Edit Profile' ditekan) -->
        <form x-show="editing" action="{{ route('profile.update') }}" method="POST" class="space-y-4 pt-2" style="display: none;">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-semibold text-slate-800 focus:outline-none focus:border-[#3b127e]" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-semibold text-slate-800 focus:outline-none focus:border-[#3b127e]" required>
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $customer->customerPhoneNumber ?? '') }}" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 bg-white font-semibold text-slate-800 focus:outline-none focus:border-[#3b127e]" placeholder="Cth: 0123456789">
                </div>
            </div>

            <div class="flex justify-end space-x-2 pt-2">
                <button type="button" @click="editing = false" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

        <!-- 📊 STATISTIK RINGKASAN PELANGGAN (2 Kad Seimbang) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Card 1: Total Orders -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-purple-50 text-[#6d28d9] rounded-xl text-lg shrink-0">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400">Total Eyewear Orders</span>
                    <div class="text-2xl font-black mt-0.5 text-slate-800">{{ $orders->count() }}</div>
                </div>
            </div>

            <!-- Card 2: My Appointments -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-4">
                <div class="p-3 bg-sky-50 text-sky-600 rounded-xl text-lg shrink-0">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <span class="text-[10px] font-bold uppercase text-slate-400">My Appointments</span>
                    <div class="text-2xl font-black mt-0.5 text-slate-800">{{ $appointments->count() }}</div>
                </div>
            </div>
        </div>

        <!-- Latest Eye Prescription Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-purple-900 text-white flex items-center justify-center">
                <!-- Ikon Mata / Cermin Mata -->
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-sm font-bold tracking-wider uppercase text-purple-950">Latest Eye Prescription</h2>
                <p class="text-xs text-gray-500">Your most recent recorded optical measurements</p>
            </div>
        </div>

        @if($latestPrescription)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                Recorded on {{ $latestPrescription->created_at->format('d M Y') }}
            </span>
        @endif
    </div>

    @if($latestPrescription)
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] font-semibold uppercase tracking-wider text-gray-400">
                        <th class="py-3 px-4">Eye</th>
                        <th class="py-3 px-4">Sphere (SPH)</th>
                        <th class="py-3 px-4">Cylinder (CYL)</th>
                        <th class="py-3 px-4">Axis</th>
                        <th class="py-3 px-4">Add</th>
                        <th class="py-3 px-4">PD (mm)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    <tr class="hover:bg-gray-50/50">
                        <td class="py-3 px-4 font-semibold text-purple-900">Right Eye (OD)</td>
                        <td class="py-3 px-4">{{ $latestPrescription->right_sph ?? '0.00' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->right_cyl ?? '0.00' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->right_axis ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->right_add ?? '-' }}</td>
                        <td class="py-3 px-4 font-medium" rowspan="2">{{ $latestPrescription->pd ?? '-' }} mm</td>
                    </tr>
                    <tr class="hover:bg-gray-50/50">
                        <td class="py-3 px-4 font-semibold text-purple-900">Left Eye (OS)</td>
                        <td class="py-3 px-4">{{ $latestPrescription->left_sph ?? '0.00' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->left_cyl ?? '0.00' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->left_axis ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $latestPrescription->left_add ?? '-' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-6 bg-gray-50/70 rounded-xl border border-dashed border-gray-200">
            <p class="text-sm text-gray-500">No eye prescription recorded yet.</p>
            <a href="{{ route('customer.appointments.create') }}" class="inline-block mt-2 text-xs font-semibold text-purple-700 hover:text-purple-900">
                Book an eye examination →
            </a>
        </div>
    @endif
</div>

        <!-- 📋 RECENT ORDERS TABLE -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex justify-between items-center mb-5">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Recent Eyewear Orders</span>
                </div>

                <a href="{{ route('customer.orders.index') }}" class="text-xs font-bold text-purple-600 hover:underline flex items-center gap-1">
        View All Orders <i class="fa-solid fa-arrow-right text-[10px]"></i>
    </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead class="text-slate-400 border-b border-slate-100 uppercase text-[10px]">
                        <tr>
                            <th class="pb-3">Order ID</th>
                            <th class="pb-3">Date</th>
                            <th class="pb-3">Total Price</th>
                            <th class="pb-3">Status</th>
                            <th class="pb-3 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-slate-700 font-medium">
                        @forelse($orders->take(5) as $order)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 font-bold text-slate-900">#ORD-{{ $order->id }}</td>
                            <td class="py-4 text-slate-500">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="py-4 font-semibold text-purple-950">RM {{ number_format($order->total_price, 2) }}</td>
                            <td class="py-4">
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-full text-[10px] font-extrabold">
                                    {{ $order->order_status ?? 'Processing' }}
                                </span>
                            </td>
                            <td class="py-4 text-right">
                                @if (\Illuminate\Support\Facades\Route::has('customer.orders.show'))
                                    <a href="{{ route('customer.orders.show', $order->id) }}" class="text-purple-600 font-bold hover:underline bg-purple-50 px-3 py-1 rounded-lg">View</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 font-medium">
                                <i class="fa-solid fa-folder-open block text-xl mb-1 text-slate-300"></i>
                                You have no recent eyewear orders.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
