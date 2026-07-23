@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 py-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">Report Details #REP-{{ $report->id }}</h1>
                <p class="text-xs text-slate-500 mt-0.5">Detailed breakdown and analytics summary.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition flex items-center space-x-2">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Reports</span>
            </a>
        </div>

        <div class="bg-white p-8 rounded-2xl shadow-xs border border-slate-100 space-y-6">
            <!-- Maklumat Asas -->
            <div class="grid grid-cols-2 gap-4 pb-6 border-b border-slate-100">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Report Type</span>
                    <span class="text-sm font-bold text-slate-800">{{ $report->report_type }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Generated Date</span>
                    <span class="text-sm font-bold text-slate-800">{{ $report->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Period Range</span>
                    <span class="text-sm font-bold text-slate-800">{{ $report->start_date }} to {{ $report->end_date }}</span>
                </div>
            </div>

            <!-- Kad Statistik Ringkasan -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-purple-50/50 border border-purple-100 p-5 rounded-2xl">
                    <span class="block text-[11px] font-bold text-purple-600 uppercase mb-1">Total Sales</span>
                    <span class="text-2xl font-black text-[#2e1065]">
                        RM {{ number_format($report->data_summary['total_sales'] ?? 0, 2) }}
                    </span>
                </div>
                <div class="bg-slate-50 border border-slate-100 p-5 rounded-2xl">
                    <span class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Total Orders</span>
                    <span class="text-2xl font-black text-slate-800">
                        {{ $report->data_summary['total_orders'] ?? 0 }} Orders
                    </span>
                </div>
            </div>

            <!-- Tindakan / Cetak -->
            <div class="pt-4 flex justify-end">
                <button onclick="window.print()" class="bg-[#6d28d9] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs flex items-center space-x-2">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Report</span>
                </button>
            </div>
        </div>
    </div>
@endsection
