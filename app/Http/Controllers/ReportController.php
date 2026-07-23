<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
   public function generateReport(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date'   => 'required|date|after_or_equal:start_date',
    ]);

    $orders = Order::whereBetween('created_at', [$request->start_date, $request->end_date])->get();

    if ($orders->isEmpty()) {
        return back()->withErrors(['msg' => 'No data available for this period.']);
    }

    $totalSales = $orders->sum('total_price');
    $orderCount = $orders->count();

    Report::create([
        'report_type'  => 'Monthly Sales Report',
        'start_date'   => $request->start_date,
        'end_date'     => $request->end_date,
        'data_summary' => [
            'total_sales' => $totalSales,
            'total_orders' => $orderCount
        ]
    ]);

    // Ambil senarai laporan terkini untuk dipaparkan terus di bawah borang
    $reports = Report::orderBy('created_at', 'desc')->get();

    return redirect()->back()
        ->with('success', 'Report successfully generated!')
        ->with('reports', $reports); // Hantar data reports melalui session / flash data
}

    public function viewReports()
    {
        // Mendapatkan semua laporan yang telah dijana, disusun dari yang terbaru
        $reports = Report::orderBy('created_at', 'desc')->get();

        // Semak peranan pengguna atau arahkan ke folder view yang betul
        if (auth()->user()->role === 'manager') {
            return view('manager.reports.index', compact('reports'));
        }

        return view('admin.reports.index', compact('reports'));
    }

    public function show(int $id)
    {
        // Mendapatkan butiran spesifik bagi satu laporan
        $report = Report::findOrFail($id);

        // Semak peranan pengguna untuk paparan terperinci (show)
        if (auth()->user()->role === 'manager') {
            return view('manager.reports.show', compact('report'));
        }

        return view('admin.reports.show', compact('report'));
    }

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function create()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('admin.reports.create', compact('reports'));
    }

    public function destroy(int $id)
{
    $report = Report::findOrFail($id);
    $report->delete();

    return redirect()->back()->with('success', 'Report deleted successfully!');
}

    public function exportPdf(int $id)
{
    $report = Report::findOrFail($id);

    $pdf = Pdf::loadView('manager.reports.pdf', compact('report'));

    return $pdf->download('report-' . $report->id . '.pdf');
}
}
