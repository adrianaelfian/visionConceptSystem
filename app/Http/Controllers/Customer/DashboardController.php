<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Appointment;
use App\Models\Warranty;
use App\Models\EyeCondition;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:customer']);
    }

    public function profile()
    {
        $customer = Auth::user()->customer;
        return view('customer.profile', compact('customer'));
    }

    // Dashboard Utama Customer
    public function dashboard()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('login')->withErrors(['msg' => 'Profil pelanggan tidak ditemui. Sila hubungi khidmat pelanggan.']);
        }

        $orders       = Order::where('customer_id', $customer->id)->latest()->get();
        $appointments = Appointment::where('customerID', $customer->id)->latest()->get();
        $warranties   = Warranty::where('customer_id', $customer->id)->where('end_date', '>=', now())->get();

        // Ambil rekod preskripsi / keadaan mata terkini milik pelanggan
        $latestPrescription = $customer->eyeConditions()->latest()->first();

        return view('customer.dashboard', compact('customer', 'orders', 'appointments', 'warranties', 'latestPrescription'));
    }

    // Papar borang Book Appointment
    // PENTING: tukar nama view ini kepada nama fail blade borang anda
    public function createAppointment()
{
    $customer = Auth::user()->customer;

    $appointments = $customer
        ? Appointment::where('customerID', $customer->id)
            ->orderByDesc('appointmentDate')
            ->orderByDesc('appointmentTime')
            ->get()
        : collect();

    return view('customer.appointments.create', compact('appointments'));
}

    // Simpan Tempahan Temujanji (Book Appointment)
    public function storeAppointment(Request $request)
{
    $request->validate([
        'appointmentDate' => 'required|date|after_or_equal:today',
        'appointmentTime' => 'required',
        'reasons'         => 'required|string|max:500',
    ]);

    $customer = Auth::user()->customer;

    if (!$customer) {
        return back()->withErrors(['msg' => 'Profil pelanggan tidak wujud.']);
    }

    // Semak jika tarikh hari ini dan masa telah lepas
    $bookingDate = $request->appointmentDate;
    $bookingTime = $request->appointmentTime;
    $appointmentDateTime = Carbon::parse("$bookingDate $bookingTime");

    if (Carbon::today()->isSameDay($bookingDate) && $appointmentDateTime->isPast()) {
        return back()->withErrors([
            'appointmentTime' => 'Ralat: Anda tidak boleh menempah slot masa yang telah lepas untuk hari ini.'
        ])->withInput();
    }

    // Semak jika slot sudah ditempah
    $existingAppointment = Appointment::whereDate('appointmentDate', $request->appointmentDate)
        ->where('appointmentTime', $request->appointmentTime)
        ->where('status', '!=', 'Cancelled')
        ->first();

    if ($existingAppointment) {
        return back()->withErrors(['appointmentTime' => 'This slot is not available.'])->withInput();
    }

    Appointment::create([
        'customerID'      => $customer->id,
        'created_by'      => Auth::id(),
        'appointmentDate' => $request->appointmentDate,
        'appointmentTime' => $request->appointmentTime,
        'reason'          => $request->reasons,
        'status'          => 'Pending',
    ]);

    return redirect()->back()->with('success', 'Appointment successfully made! Our team will verify your slot as soon as possible.');
}

    // Paparan Senarai Waranti Pelanggan
    public function warranties()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $warranties = Warranty::where('customer_id', $customer->id)->latest()->get();
        return view('customer.warranty', compact('warranties'));
    }

    // Mengambil Slot Masa yang Masih Kosong (AJAX Endpoint)
    public function getAvailableSlots(Request $request)
{
    $date = $request->query('date');

    // Senarai penuh slot masa perniagaan anda
    $allSlots = ['10:00:00', '11:30:00', '14:00:00', '15:30:00', '17:00:00'];

    // Semak slot yang sudah ditempah dalam database untuk tarikh tersebut
    $bookedSlots = Appointment::whereDate('appointmentDate', $date)
        ->where('status', '!=', 'Cancelled')
        ->pluck('appointmentTime')
        ->toArray();

    $slotsStatus = [];
    $isToday = Carbon::today()->isSameDay($date);
    $currentTime = Carbon::now();

    foreach ($allSlots as $slot) {
        $slotTime = Carbon::parse($slot);

        // 1. Semak jika slot sudah dibook orang lain
        if (in_array($slot, $bookedSlots)) {
            $slotsStatus[$slot] = 'booked';
        }
        // 2. SEMAKAN TAMBAHAN: Jika hari ini dan masa slot sudah lepas, set sebagai 'booked' (disabled)
        elseif ($isToday && $slotTime->isPast()) {
            $slotsStatus[$slot] = 'booked';
        } else {
            $slotsStatus[$slot] = 'available';
        }
    }

    return response()->json($slotsStatus);
}
    // Membatalkan Temujanji (Cancel)
    public function cancelAppointment($id)
    {
        $customer = Auth::user()->customer;
        $appointment = Appointment::where('id', $id)->where('customerID', $customer->id)->firstOrFail();

        // Hanya benarkan batal jika status masih Pending
        if ($appointment->status === 'Pending') {
            $appointment->update(['status' => 'Cancelled']);
            return redirect()->back()->with('success', 'Appointment successfully cancelled.');
        }

        return redirect()->back()->withErrors(['msg' => 'Temujanji ini tidak boleh dibatalkan kerana sudah diproses.']);
    }

    // Mengemas kini Temujanji (Reschedule / Update)
    public function updateAppointment(Request $request, $id)
    {
        $request->validate([
            'appointmentDate' => 'required|date|after_or_equal:today',
            'appointmentTime' => 'required',
            'reasons'         => 'required|string|max:500',
        ]);

        $customer = Auth::user()->customer;
        $appointment = Appointment::where('id', $id)->where('customerID', $customer->id)->firstOrFail();

        if ($appointment->status !== 'Pending') {
            return redirect()->back()->withErrors(['msg' => 'Hanya temujanji berstatus Pending sahaja boleh dikemas kini.']);
        }

        // Semak jika slot baru sudah ditempah oleh orang lain (selain temujanji sendiri)
        $existingAppointment = Appointment::whereDate('appointmentDate', $request->appointmentDate)
            ->where('appointmentTime', $request->appointmentTime)
            ->where('status', '!=', 'Cancelled')
            ->where('id', '!=', $id)
            ->first();

        if ($existingAppointment) {
            return redirect()->back()->withErrors(['appointmentTime' => 'Slot masa ini telah ditempah. Sila pilih masa lain.']);
        }

        $appointment->update([
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'reason'          => $request->reasons,
        ]);

        return redirect()->back()->with('success', 'Appointment successfully updated/rescheduled!');
    }
    public function editAppointment($id)
{
    $appointment = Appointment::where('appointmentID', $id) // Guna 'id' jika pangkalan data menggunakan id biasa
        ->where('status', 'Pending')
        ->firstOrFail();

    return view('customer.appointments.edit', compact('appointment'));
}

// Papar semua senarai pesanan pelanggan
    public function ordersIndex()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $orders = Order::where('customer_id', $customer->id)->latest()->get();

        return view('customer.orders.index', compact('orders'));
    }

    // Papar butiran terperinci satu pesanan (Optional jika perlukan page detail)
    public function showOrder($id)
    {
        $customer = Auth::user()->customer;
        $order = Order::where('id', $id)->where('customer_id', $customer->id)->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }
}
