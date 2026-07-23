@extends('layouts.admin')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-[#2e1065]">System Reports</h1>
                <p class="text-xs text-slate-500 mt-0.5">View and manage all generated system analytics and performance reports.</p>
            </div>
            <a href="{{ route('admin.reports.create') }}" class="bg-[#6d28d9] hover:bg-purple-800 text-white px-5 py-2.5 rounded-xl text-xs font-bold transition shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-file-circle-plus"></i>
                <span>+ Generate New Report</span>
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-2xl shadow-xs border border-slate-100">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                        <th class="py-4 px-4 font-bold">Report ID</th>
                        <th class="py-4 px-4 font-bold">Report Type</th>
                        <th class="py-4 px-4 font-bold">Date Range</th>
                        <th class="py-4 px-4 font-bold">Generated At</th>
                        <th class="py-4 px-4 font-bold text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-50 font-medium">
                    @forelse($reports as $rep)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-4 font-bold text-slate-900">#REP-{{ $rep->id }}</td>
                        <td class="py-4 px-4 text-slate-800 font-bold">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold bg-purple-50 text-[#6d28d9]">
                                {{ $rep->report_type }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-slate-600">{{ $rep->start_date }} hingga {{ $rep->end_date }}</td>
                        <td class="py-4 px-4 text-slate-500">{{ $rep->created_at->format('Y-m-d H:i') }}</td>
                        <td class="py-4 px-4 text-right">
                            <a href="{{ route('manager.reports.show', $rep->id) }}" class="text-purple-600 font-bold hover:text-purple-800 bg-purple-50 px-3 py-1.5 rounded-lg transition">
                                VIEW DETAILS
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 font-medium">
                            <i class="fa-solid fa-file-excel block text-2xl mb-2 text-slate-300"></i>
                            No reports generated yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
