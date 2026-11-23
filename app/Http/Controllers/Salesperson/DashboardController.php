<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil statistik order dan jualan hari ini
        $todayOrdersCount = Order::whereDate('created_at', today())->count();
    $todaySales = Order::whereDate('created_at', today())->sum('total_price');

        // 2. Ambil HANYA temujanji yang tarikhnya adalah HARI INI sahaja dari database
        $appointments = Appointment::with('customer')
            ->whereDate('appointmentDate', today())
            ->orderBy('appointmentTime', 'asc')
            ->get();

        // 3. Hantar data ke paparan salesperson.dashboard
        return view('salesperson.dashboard', compact('appointments', 'todayOrdersCount', 'todaySales'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:Completed,Cancelled,No Show,Arrived',
        ]);

        // Kemas kini status temujanji di dalam pangkalan data
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->status = $request->status;
            $appointment->save();
        }

        return back()->with('success', 'Appointment status updated successfully!');
    }
   public function appointmentsIndex(Request $request)
    {
        $query = Appointment::with('customer')->orderBy('appointmentDate', 'desc')->orderBy('appointmentTime', 'asc');

        // Tapisan mengikut tarikh jika dipilih
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('appointmentDate', $request->date);
        }

        // Tapisan mengikut status jika dipilih
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(10);

        return view('salesperson.appointments.index', compact('appointments'));
    }
}
