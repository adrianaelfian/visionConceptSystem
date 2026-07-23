<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function displayForm(){

        if(auth()->user()->role !== 'salesperson'){
            return redirect()->route('dashboard')->with('error','You do not have permission to access this page.');
        }

        // Pastikan laluan folder view anda betul (cth: salesperson.customer.create atau customer.create)
        return view('salesperson.customer.create');
    }

    public function saveProfile(Request $request){
        $validated = $request->validate([
            'customerName'        => 'required|string',
            'customerEmail'       => 'required|email|unique:customers,customerEmail',
            'customerPhoneNumber' => 'required|string|unique:customers,customerPhoneNumber', // Diselaraskan kepada customerPhoneNumber
        ],[
            // Exception [E1]: Incomplete Information
            'customerName.required'        => 'Incomplete Information: Name fields is required.',
            'customerEmail.required'       => 'Incomplete Information: Email fields is required.',
            'customerPhoneNumber.required' => 'Incomplete Information: Phone Number fields is required.',

            // Exception [E2]: Invalid/Duplicate Credentials
            'customerEmail.unique'         => 'Invalid Credentials: This email is already registered.',
            'customerPhoneNumber.unique'   => 'Invalid Credentials: This phone number is already registered.',
        ]);

        // Simpan data ke dalam model Customer mengikut kolum DB anda
        Customer::create([
        'customerName'        => $validated['customerName'],
        'customerEmail'       => $validated['customerEmail'],
        'customerPhoneNumber' => $validated['customerPhoneNumber'], // Sesuaikan nama kolum kiri mengikut database anda
    ]);

        return redirect()->back()->with('success', 'Customer profile successfully created!');
    }

   public function index(Request $request)
{
    if(auth()->user()->role !== 'salesperson'){
        return redirect()->route('dashboard')->with('error','You do not have permission to access this page.');
    }

    $search = $request->input('search');

    // Carian mengikut nama atau nombor telefon
    $customers = Customer::when($search, function ($query, $search) {
        return $query->where('customerName', 'like', "%{$search}%")
                     ->orWhere('customerPhoneNumber', 'like', "%{$search}%");
    })->latest()->paginate(10);

    return view('salesperson.customer.index', compact('customers', 'search'));
}
}
