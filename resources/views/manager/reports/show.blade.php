@extends('layouts.manager')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header & Back Button / Download PDF Action -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Report Details #{{ $report->id }}</h2>
            <p class="text-sm text-slate-500 mt-1">Detailed information and performance summary for this report.</p>
        </div>

        <div class="flex items-center space-x-3">
            <!-- Butang Download PDF -->
            <a href="{{ route('manager.reports.pdf', $report->id) }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-purple-600 text-white hover:bg-purple-700 transition shadow-sm">
                <i class="fa-solid fa-download text-xs"></i>
                <span>Download PDF</span>
            </a>

            <!-- Butang Kembali -->
            <a href="{{ route('manager.reports.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Back</span>
            </a>
        </div>
    </div>

    <!-- Main Information Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Report Type</span>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $report->report_type }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Generated Date</span>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $report->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Start Date</span>
                <p class="text-base font-semibold text-slate-700 mt-1">{{ $report->start_date }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">End Date</span>
                <p class="text-base font-semibold text-slate-700 mt-1">{{ $report->end_date }}</p>
            </div>
        </div>

        <!-- Data Summary Section -->
        <div>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-slate-400 mb-4">Statistics & Summary</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-purple-50/50 border border-purple-100 rounded-2xl p-5">
                    <div class="flex items-center space-x-3 text-purple-600 mb-2">
                        <i class="fa-solid fa-money-bill-wave text-xl"></i>
                        <span class="text-sm font-semibold">Total Sales</span>
                    </div>
                    <p class="text-2xl font-black text-purple-900">
                        RM {{ number_format($report->data_summary['total_sales'] ?? 0, 2) }}
                    </p>
                </div>

                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
                    <div class="flex items-center space-x-3 text-blue-600 mb-2">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span class="text-sm font-semibold">Total Orders</span>
                    </div>
                    <p class="text-2xl font-black text-blue-900">
                        {{ $report->data_summary['total_orders'] ?? 0 }} Orders
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
