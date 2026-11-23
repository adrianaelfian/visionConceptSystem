<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customerID'      => 'required|exists:customers,id',
            'appointmentDate' => 'required|date|after_or_equal:today',
            'appointmentTime' => 'required',
        ]);

        // Semak ketersediaan slot (pastikan tidak dikira jika status Cancelled)
        $isAvailable = Appointment::where('appointmentDate', $request->appointmentDate)
                                   ->where('appointmentTime', $request->appointmentTime)
                                   ->where('status', '!=', 'Cancelled')
                                   ->doesntExist();

        if (!$isAvailable) {
            return back()->withErrors(['appointmentTime' => 'This slot is not available!'])->withInput();
        }

        Appointment::create([
            'customerID'      => $request->customerID,
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'status'          => 'Scheduled'
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment successfully made!');
    }

    public function index()
    {
        $appointments = Appointment::with('customer')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $customers = Customer::all();
        return view('admin.appointments.create', compact('customers'));
    }

    public function edit(mixed $id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('admin.appointments.edit', compact('appointment'));
    }

    public function update(Request $request, mixed $id)
    {
        $request->validate([
            'appointmentDate' => 'required|date|after_or_equal:today',
            'appointmentTime' => 'required',
        ]);

        $appointment = Appointment::findOrFail($id);
        $primaryKey = $appointment->getKeyName();

        // Semak ketersediaan slot kecuali untuk temujanji ini sendiri
        $isAvailable = Appointment::where('appointmentDate', $request->appointmentDate)
                                   ->where('appointmentTime', $request->appointmentTime)
                                   ->where('status', '!=', 'Cancelled')
                                   ->where($primaryKey, '!=', $id)
                                   ->doesntExist();

        if (!$isAvailable) {
            return back()->withErrors(['appointmentTime' => 'This slot is not available!'])->withInput();
        }

        $appointment->update([
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'status'          => 'Rescheduled'
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated!');
    }

    public function destroy(mixed $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'Cancelled']);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment cancelled successfully!');
    }

    public function generateReport(Request $request)
    {
        // Fetch data dengan pilihan tapisan tarikh jika ada
        $query = Appointment::query();

        if ($request->has('start_date') && $request->has('end_date') && $request->start_date && $request->end_date) {
            $query->whereBetween('appointmentDate', [$request->start_date, $request->end_date]);
        }

        $appointments = $query->with('customer')->get();

        return view('admin.reports.appointments', compact('appointments'));
    }
}
