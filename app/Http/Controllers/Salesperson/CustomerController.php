<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:salesperson']);
    }

    // Papar borang pendaftaran pelanggan baharu
    public function displayForm()
    {
        return view('salesperson.customer.create');
    }

    // Simpan profil pelanggan baharu
    public function saveProfile(Request $request)
    {
        $validated = $request->validate([
            'customerName'        => 'required|string',
            'customerEmail'       => 'required|email|unique:customers,customerEmail',
            'customerPhoneNumber' => 'required|string|regex:/^[0-9]+$/|unique:customers,customerPhoneNumber',
        ], [
            // Exception [E1]: Incomplete Information
            'customerName.required'        => 'Incomplete Information: Name field is required.',
            'customerEmail.required'       => 'Incomplete Information: Email field is required.',
            'customerPhoneNumber.required' => 'Incomplete Information: Phone Number field is required.',

            // Exception [E1 / Format]: Format tidak sah jika ada huruf/simbol
            'customerPhoneNumber.regex'    => 'Invalid Format: Phone number must contain digits only (no letters allowed).',

            // Exception [E2]: Invalid/Duplicate Credentials
            'customerEmail.unique'         => 'Invalid Credentials: This email is already registered.',
            'customerPhoneNumber.unique'   => 'Invalid Credentials: This phone number is already registered.',
        ]);

        Customer::create([
            'customerName'        => $validated['customerName'],
            'customerEmail'       => $validated['customerEmail'],
            'customerPhoneNumber' => $validated['customerPhoneNumber'],
        ]);

        return redirect()->back()->with('success', 'Customer profile successfully created!');
    }

    // Senarai pelanggan dengan fungsi carian
    public function index(Request $request)
{
    $query = Customer::query();

    if ($request->has('search') && $request->search != '') {
        $searchTerm = trim($request->search);

        // Buang perkataan 'CUST-', 'cust-' atau '#' jika pengguna menaipnya
        $numericId = preg_replace('/[^0-9]/', '', $searchTerm);

        $query->where(function($q) use ($searchTerm, $numericId) {
            if (!empty($numericId)) {
                $q->where('id', 'LIKE', "%{$numericId}%");
            }
            // Boleh cari juga mengikut nama atau emel jika mahu
            $q->orWhere('customerName', 'LIKE', "%{$searchTerm}%")
              ->orWhere('customerEmail', 'LIKE', "%{$searchTerm}%");
        });
    }

    $customers = $query->latest()->paginate(10);

    return view('salesperson.customer.index', compact('customers'));
}

    // Papar borang edit pelanggan
    public function edit(int $id)
    {
        $customer = Customer::findOrFail($id);
        return view('salesperson.customer.edit', compact('customer'));
    }

    // Kemaskini data pelanggan
    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'customerName'        => 'required|string',
            'customerEmail'       => 'required|email|unique:customers,customerEmail,' . $customer->id,
            'customerPhoneNumber' => 'required|string|regex:/^[0-9]+$/|unique:customers,customerPhoneNumber,' . $customer->id,
        ], [
            'customerPhoneNumber.regex'  => 'Invalid Format: Phone number must contain digits only (no letters allowed).',
            'customerEmail.unique'       => 'Invalid Credentials: This email is already registered.',
            'customerPhoneNumber.unique' => 'Invalid Credentials: This phone number is already registered.',
        ]);

        $customer->update([
            'customerName'        => $validated['customerName'],
            'customerEmail'       => $validated['customerEmail'],
            'customerPhoneNumber' => $validated['customerPhoneNumber'],
        ]);

        return redirect()->route('salesperson.customer.index')->with('success', 'Customer profile successfully updated!');
    }

    // Padam rekod pelanggan
    public function destroy(int $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('salesperson.customer.index')->with('success', 'Customer successfully deleted!');
    }
}
