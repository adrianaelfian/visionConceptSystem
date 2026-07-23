@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <!-- Header Title & Export Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manager Reports Overview</h2>
            <p class="text-sm text-slate-500 mt-1">List of all generated sales and business performance reports.</p>
        </div>

        <!-- Quick Action Buttons -->
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-sm font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition shadow-sm">
                <i class="fa-solid fa-print text-xs"></i>
                <span>Print / PDF</span>
            </button>
        </div>
    </div>

    <!-- Success Message (if any) -->
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-medium shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Reports Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/70 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Report Type</th>
                        <th class="py-4 px-6">Start Date</th>
                        <th class="py-4 px-6">End Date</th>
                        <th class="py-4 px-6">Generated Date</th>
                        <th class="py-4 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($reports as $report)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="py-4 px-6 font-semibold text-slate-700">#{{ $report->id }}</td>
                            <td class="py-4 px-6 font-medium text-slate-800">{{ $report->report_type }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $report->start_date }}</td>
                            <td class="py-4 px-6 text-slate-600">{{ $report->end_date }}</td>
                            <td class="py-4 px-6 text-slate-500">{{ $report->created_at->format('d M Y, h:i A') }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center space-x-2">
                                    <a href="{{ route('manager.reports.show', $report->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-purple-50 text-purple-700 hover:bg-purple-600 hover:text-white transition shadow-sm">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                        <span>View</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <i class="fa-solid fa-folder-open text-3xl text-slate-300"></i>
                                    <p class="text-sm font-medium">No reports found at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
