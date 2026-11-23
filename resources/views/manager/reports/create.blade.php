@extends('layouts.manager')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto py-6">
    <!-- Borang Generate System Report -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <div class="mb-6">
            <h2 class="text-2xl font-black text-[#2e1065]">Generate System Report</h2>
            <p class="text-xs text-slate-500 mt-1">Select the report type and date range to generate analytics or logs.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-xs font-medium space-y-1">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('manager.reports.generate') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Report Type</label>
                <select name="report_type" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-purple-500">
                    <option value="" disabled selected>-- Choose report type --</option>
                    <option value="Monthly Sales">Monthly Sales</option>
                    <option value="Appointment Report">Appointment Report</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">Start Date</label>
                    <input type="date" name="start_date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1">End Date</label>
                    <input type="date" name="end_date" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:border-purple-500">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('manager.reports.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Back to Manage Reports
                </a>
                <button type="submit" class="px-6 py-2.5 bg-[#6d28d9] text-white rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-sm">
                    Generate Report
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
