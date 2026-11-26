<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Warranty;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    // Senarai penuh slot masa perniagaan
    private const SLOTS = ['10:00:00', '11:30:00', '14:00:00', '15:30:00', '17:00:00'];

    public function __construct()
    {
        $this->middleware(['auth', 'role:customer']);
    }

    /* ---------------------------------------------------------------
     |  Helpers
     |---------------------------------------------------------------*/

    // Dapatkan customer, atau henti dengan 403 jika tiada
    private function customerOrFail()
    {
        $customer = Auth::user()->customer;
        abort_unless($customer, 403, 'Profil pelanggan tidak ditemui.');

        return $customer;
    }

    // Dapatkan temujanji MILIK customer ini sahaja
    // Jika primary key jadual anda ialah 'appointmentID', tukar di sini sahaja.
    private function ownAppointment($id): Appointment
    {
        return Appointment::where('id', $id)
            ->where('customerID', $this->customerOrFail()->id)
            ->firstOrFail();
    }

    private function appointmentRules(): array
    {
        return [
            'appointmentDate' => ['required', 'date', 'after_or_equal:today'],
            'appointmentTime' => ['required', Rule::in(self::SLOTS)],
            'reasons'         => ['required', 'string', 'max:500'],
        ];
    }

    // Ubah "10:00" kepada "10:00:00" supaya sepadan dengan senarai slot
    private function normalizeTime(Request $request): void
    {
        $time = $request->input('appointmentTime');

        if (is_string($time) && strlen($time) === 5) {
            $request->merge(['appointmentTime' => $time . ':00']);
        }
    }

    private function slotTaken(string $date, string $time, $ignoreId = null): bool
    {
        return Appointment::whereDate('appointmentDate', $date)
            ->where('appointmentTime', $time)
            ->where('status', '!=', 'Cancelled')
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }

    /* ---------------------------------------------------------------
     |  Profile
     |---------------------------------------------------------------*/

    public function profile()
    {
        $customer = Auth::user()->customer;

        return view('customer.profile', compact('customer'));
    }

    public function updateProfile(Request $request)
{
    $user     = $request->user();
    $customer = $user->customer;

    $validated = $request->validate([
        'name'         => ['required', 'string', 'max:255'],
        'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        'phone_number' => ['nullable', 'string', 'max:30'],
    ]);

    // Jika medan wujud (walaupun kosong) guna nilainya; jika tiada, kekalkan yang lama
    $phone = array_key_exists('phone_number', $validated)
        ? $validated['phone_number']
        : ($customer->customerPhoneNumber ?? $user->phoneNO);

    $user->update([
        'name'    => $validated['name'],
        'email'   => $validated['email'],
        'phoneNO' => $phone,
    ]);

    if ($customer) {
        $customer->update([
            'customerName'        => $validated['name'],
            'customerEmail'       => $validated['email'],
            'customerPhoneNumber' => $phone,
        ]);
    }

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

    /* ---------------------------------------------------------------
     |  Dashboard
     |---------------------------------------------------------------*/

    public function dashboard(Request $request)
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            // Logout dahulu untuk elak redirect loop (route login biasanya 'guest' sahaja)
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->withErrors(['msg' => 'Profil pelanggan tidak ditemui. Sila hubungi khidmat pelanggan.']);
        }

        $orders       = Order::where('customer_id', $customer->id)->latest()->get();
        $appointments = Appointment::where('customerID', $customer->id)->latest()->get();
        $warranties   = Warranty::where('customer_id', $customer->id)->where('end_date', '>=', now())->get();

        // Rekod preskripsi / keadaan mata terkini
        $latestPrescription = $customer->eyeConditions()->latest()->first();

        return view('customer.dashboard', compact('customer', 'orders', 'appointments', 'warranties', 'latestPrescription'));
    }

    /* ---------------------------------------------------------------
     |  Appointments
     |---------------------------------------------------------------*/

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

    public function storeAppointment(Request $request)
    {
        $this->normalizeTime($request);
        $request->validate($this->appointmentRules());

        $customer = Auth::user()->customer;

        if (!$customer) {
            return back()->withErrors(['msg' => 'Profil pelanggan tidak wujud.']);
        }

        // Tolak slot yang sudah lepas (berfungsi untuk hari ini sahaja kerana tarikh lepas sudah ditolak oleh validation)
        if (Carbon::parse("{$request->appointmentDate} {$request->appointmentTime}")->isPast()) {
            return back()->withErrors([
                'appointmentTime' => 'Ralat: Anda tidak boleh menempah slot masa yang telah lepas untuk hari ini.',
            ])->withInput();
        }

        if ($this->slotTaken($request->appointmentDate, $request->appointmentTime)) {
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

    public function editAppointment($id)
    {
        $appointment = $this->ownAppointment($id);

        abort_unless($appointment->status === 'Pending', 403);

        return view('customer.appointments.edit', compact('appointment'));
    }

    public function updateAppointment(Request $request, $id)
    {
        $this->normalizeTime($request);
        $request->validate($this->appointmentRules());

        $appointment = $this->ownAppointment($id);

        if ($appointment->status !== 'Pending') {
            return redirect()->back()->withErrors(['msg' => 'Hanya temujanji berstatus Pending sahaja boleh dikemas kini.']);
        }

        if (Carbon::parse("{$request->appointmentDate} {$request->appointmentTime}")->isPast()) {
            return back()->withErrors([
                'appointmentTime' => 'Ralat: Anda tidak boleh menempah slot masa yang telah lepas.',
            ])->withInput();
        }

        // Semak slot baru (abaikan temujanji sendiri)
        if ($this->slotTaken($request->appointmentDate, $request->appointmentTime, $appointment->id)) {
            return redirect()->back()
                ->withErrors(['appointmentTime' => 'Slot masa ini telah ditempah. Sila pilih masa lain.'])
                ->withInput();
        }

        $appointment->update([
            'appointmentDate' => $request->appointmentDate,
            'appointmentTime' => $request->appointmentTime,
            'reason'          => $request->reasons,
        ]);

        return redirect()->back()->with('success', 'Appointment successfully updated/rescheduled!');
    }

    public function cancelAppointment($id)
    {
        $appointment = $this->ownAppointment($id);

        // Hanya benarkan batal jika status masih Pending
        if ($appointment->status === 'Pending') {
            $appointment->update(['status' => 'Cancelled']);

            return redirect()->back()->with('success', 'Appointment successfully cancelled.');
        }

        return redirect()->back()->withErrors(['msg' => 'Temujanji ini tidak boleh dibatalkan kerana sudah diproses.']);
    }

    // AJAX: slot masa yang masih kosong
    public function getAvailableSlots(Request $request)
    {
        $request->validate(['date' => ['required', 'date']]);

        $date = $request->query('date');

        $bookedSlots = Appointment::whereDate('appointmentDate', $date)
            ->where('status', '!=', 'Cancelled')
            ->pluck('appointmentTime')
            ->toArray();

        $slotsStatus = [];

        foreach (self::SLOTS as $slot) {
            $isBooked = in_array($slot, $bookedSlots, true);
            $isPast   = Carbon::parse("$date $slot")->isPast();

            $slotsStatus[$slot] = ($isBooked || $isPast) ? 'booked' : 'available';
        }

        return response()->json($slotsStatus);
    }

    /* ---------------------------------------------------------------
     |  Warranties & Orders
     |---------------------------------------------------------------*/

    public function warranties()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $warranties = Warranty::where('customer_id', $customer->id)->latest()->get();

        return view('customer.warranty', compact('warranties'));
    }

    public function ordersIndex()
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $orders = Order::where('customer_id', $customer->id)->latest()->get();

        return view('customer.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        $customer = Auth::user()->customer;

        if (!$customer) {
            return redirect()->route('customer.dashboard');
        }

        $order = Order::with(['orderItems.item', 'customer'])
            ->where('id', $id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }
}
