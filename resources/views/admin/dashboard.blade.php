@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header Section -->
        <div>
            <h1 class="text-2xl font-black text-[#2e1065]">Welcome Back, Admin!</h1>
            <p class="text-xs text-slate-500 mt-0.5">Here is the operational overview for Vision Concept Eyewear (GM Klang) today.</p>
        </div>

        <!-- 📊 GRID 4 KAD STATISTIK UTAMA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Card 1: Total Customers -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                <span class="text-[10px] font-bold uppercase text-slate-400">Total Customers</span>
                <div class="text-3xl font-black mt-2 text-slate-800">1,240</div>
            </div>

            <!-- Card 2: Pending Appointments -->
            <div class="bg-purple-50 p-5 rounded-2xl border border-purple-100 shadow-sm">
                <span class="text-[10px] font-bold uppercase text-purple-600">Total Appointments</span>
                <div class="text-3xl font-black mt-2 text-purple-900">5</div>
            </div>

            <!-- Card 3: Account Verifications -->
            <div class="bg-sky-50 p-5 rounded-2xl border border-sky-100 shadow-sm">
                <span class="text-[10px] font-bold uppercase text-sky-600">Registered Staff</span>
                <div class="text-3xl font-black mt-2 text-sky-900">8</div>
            </div>

            <!-- Card 4: Report Action -->
            <div class="bg-pink-50 border border-pink-200 p-5 rounded-2xl shadow-sm text-slate-800 flex flex-col justify-between">
    <span class="text-[10px] font-bold uppercase text-pink-600">Monthly Reports</span>
    <a href="{{ route('admin.reports.create') }}" class="mt-2 text-xs font-bold underline text-pink-700 hover:text-pink-900 text-left inline-block">
        Generate Now →
    </a>
</div>
        </div>

        <!-- ⚡ SEKSYEN QUICK STAFF ACTIONS -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-xs">
    <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-1.5 mb-4">
        <span>Admin Quick Actions</span>
    </div>
    <div class="flex flex-wrap gap-2">
        <!-- 1. Butang Cipta Akaun Staf -->
        <a href="{{ route('admin.accounts.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition flex items-center space-x-1.5">
            <i class="fa-solid fa-user-plus text-sky-500"></i>
            <span>Create Staff Account</span>
        </a>

        <!-- 2. Butang Tempahan Temujanji -->
        <a href="{{ route('admin.appointments.create') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition flex items-center space-x-1.5">
            <i class="fa-solid fa-calendar-plus text-emerald-500"></i>
            <span>Book Appointment</span>
        </a>

        <!-- 3. Butang Penjanaan Laporan -->
        <a href="{{ route('admin.reports.create') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition flex items-center space-x-1.5">
            <i class="fa-solid fa-file-lines text-amber-500"></i>
            <span>Generate Report</span>
        </a>
    </div>
</div>

        <!-- 📋 SEKSYEN TODAY'S APPOINTMENT QUEUE -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-xs">
            <div class="flex justify-between items-center mb-6">
                <div class="text-xs font-bold text-[#3b127e] flex items-center space-x-2">
                    <i class="fa-solid fa-list-check"></i>
                    <span>Today's Appointment Queue</span>
                </div>
                <span class="px-2.5 py-1 bg-purple-50 text-[#6d28d9] text-[10px] font-extrabold rounded-full">Active Queue</span>
            </div>

            <table class="w-full text-left text-xs">
                <thead class="text-slate-400 border-b border-slate-100">
                    <tr>
                        <th class="pb-3">Customer Name</th>
                        <th class="pb-3">Time</th>
                        <th class="pb-3">Status</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <tr>
                        <td class="py-4 font-semibold text-slate-700">Ali bin Abu</td>
                        <td class="py-4">10:00 AM</td>
                        <td class="py-4"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">Confirmed</span></td>
                        <td class="py-4 text-right">
                            <button class="text-purple-600 hover:underline font-semibold">View</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
