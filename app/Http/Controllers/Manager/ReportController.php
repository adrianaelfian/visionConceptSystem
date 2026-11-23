<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Report;
use App\Models\Appointment;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:manager']);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        // Semak jenis laporan yang dipilih oleh pengguna
        if ($request->report_type === 'Appointment Report') {
            $appointments = Appointment::whereBetween('created_at', [$request->start_date, $request->end_date])->get();

            if ($appointments->isEmpty()) {
                return back()->withErrors(['msg' => 'No appointment data available for this period.']);
            }

            $totalAppointments = $appointments->count();
            $totalAttended = $appointments->where('status', 'Completed')->count();

            $dataSummary = [
                'total_appointments' => $totalAppointments,
                'total_attended'     => $totalAttended,
            ];
        } else {
            $orders = Order::whereBetween('created_at', [$request->start_date, $request->end_date])->get();

            if ($orders->isEmpty()) {
                return back()->withErrors(['msg' => 'No order data available for this period.']);
            }

            $totalSales = $orders->sum('total_price');
            $orderCount = $orders->count();

            $dataSummary = [
                'total_sales'  => $totalSales,
                'total_orders' => $orderCount,
            ];
        }

        // Simpan data laporan ke dalam pangkalan data
        Report::create([
            'report_type'  => $request->report_type,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'data_summary' => $dataSummary
        ]);

        return redirect()->route('manager.reports.index')
            ->with('success', 'Report successfully generated and added to the list!');
    }

    public function viewReports()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('manager.reports.index', compact('reports'));
    }

    public function show(int $id)
    {
        $report = Report::findOrFail($id);
        return view('manager.reports.show', compact('report'));
    }

    public function create()
    {
        $reports = Report::orderBy('created_at', 'desc')->get();
        return view('manager.reports.create', compact('reports'));
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
