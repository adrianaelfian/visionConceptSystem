<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\AppointmentLog;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customerID'      => 'required|exists:customers,id',
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required',
        ]);

        $isAvailable = Appointment::where('appointmentDate', $request->appointmentDate)
                                   ->where('appointmentTime', $request->appointmentTime)
                                   ->doesntExist();
        if (!$isAvailable) {
            return back()->withErrors(['error' => 'This slot is not available!']);
        }

        $appointment = Appointment::create([
            'customerID'      => $request->customerID,
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'status'          => 'Scheduled'
        ]);

        AppointmentLog::create([
            'appointmentID' => $appointment->id ?? $appointment->appointmentID,
            'action'        => 'Created',
            'details'       => 'New appointment created for customer ID ' . $request->customerID
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
            'appointmentDate' => 'required|date|after:today',
            'appointmentTime' => 'required',
        ]);

        $appointment = Appointment::findOrFail($id);
        $primaryKey = $appointment->getKeyName(); // Mendapatkan nama primary key model (id / appointmentID)

        $isAvailable = Appointment::where('appointmentDate', $request->appointmentDate)
                                   ->where('appointmentTime', $request->appointmentTime)
                                   ->where($primaryKey, '!=', $id)
                                   ->doesntExist();

        if (!$isAvailable) {
            return back()->withErrors(['error' => 'This slot is not available']);
        }

        $appointment->update([
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'status'          => 'Rescheduled'
        ]);

        AppointmentLog::create([
            'appointmentID' => $id,
            'action'        => 'Updated',
            'details'       => 'Appointment rescheduled to ' . $request->appointmentDate . ' at ' . $request->appointmentTime
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated!');
    }

    public function destroy(mixed $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => 'Cancelled']);

        AppointmentLog::create([
            'appointmentID' => $id,
            'action'        => 'Cancelled',
            'details'       => 'Appointment cancelled by admin'
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Appointment cancelled successfully!');
    }
}
