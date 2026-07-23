@extends('layouts.admin')

@section('content')
    <div class="py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <h2 class="text-2xl font-black text-[#2e1065] mb-2">Generate System Report</h2>
            <p class="text-xs text-slate-500 mb-6">Select the report type and date range to generate analytics or logs.</p>

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-xs font-bold mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.reports.generate') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Report Type</label>
                    <select name="report_type" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e] bg-white" required>
                        <option value="">-- Choose report type --</option>
                        <option value="appointments">Appointment Summary Report</option>
                        <option value="activities">System Activity Logs</option>
                        <option value="customers">Customer Registration Report</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">Start Date</label>
                        <input type="date" name="start_date" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" required>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 uppercase mb-1">End Date</label>
                        <input type="date" name="end_date" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#3b127e]" required>
                    </div>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 transition">Cancel</a>
                    <button type="submit" class="bg-[#6d28d9] text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-purple-800 transition shadow-xs flex items-center space-x-2">
                        <i class="fa-solid fa-file-arrow-down"></i>
                        <span>Generate Report</span>
                    </button>
                </div>
            </form>
        </div>
    <!-- Tambah Bahagian Ini di Bawah Kotak Borang untuk Paparan Hasil Laporan -->
        @isset($reports)
        <div class="max-w-4xl mx-auto mt-8 bg-white p-8 rounded-2xl shadow-xs border border-slate-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-base font-black text-[#2e1065]">Report Generation Results</h3>
                    <p class="text-xs text-slate-500">Showing summary data based on your selected criteria.</p>
                </div>
                <button onclick="window.print()" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    <i class="fa-solid fa-print mr-1.5"></i> Print / Export PDF
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-bold">
                        <tr>
                            <th class="p-3 rounded-l-xl">No</th>
                            <th class="p-3">Details / Info</th>
                            <th class="p-3">Date Recorded</th>
                            <th class="p-3">Status / Category</th>
                            <th class="p-3 rounded-r-xl text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($reports as $index => $row)
                        <tr>
                            <td class="p-3 font-semibold text-slate-700">{{ $index + 1 }}</td>
                            <td class="p-3">
                                <span class="font-bold text-slate-700">{{ $row->report_type }}</span><br>
                                <span class="text-[11px] text-slate-500">
                                    Total Sales: RM {{ number_format($row->data_summary['total_sales'] ?? 0, 2) }} |
                                    Total Orders: {{ $row->data_summary['total_orders'] ?? 0 }}
                                </span>
                            </td>
                            <td class="p-3">{{ $row->created_at->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-700 rounded-md font-medium">Active</span>
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('admin.reports.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this report?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition" title="Delete Report">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-slate-400">No records found for the selected date range.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endisset
    </div>
@endsection
