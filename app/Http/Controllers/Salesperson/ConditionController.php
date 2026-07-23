<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EyeCondition;
use App\Models\Customer; // Tukar dari Appointment kepada Customer

class ConditionController extends Controller
{
    /**
     * Render and display the eye condition prescription creation form.
     */
    public function create(int $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);
        return view('salesperson.conditions.create', compact('customer', 'customer_id'));
    }

    /**
     * Store a newly created optical prescription record into the datastore.
     */
    public function store(Request $request)
    {
        // Validasi data mengikut lajur customer_id
        $validatedData = $request->validate([
            'customer_id'   => 'required|integer',
            'rightPower'    => 'required|numeric|between:-20,20',
            'leftPower'     => 'required|numeric|between:-20,20',
            'rightCylinder' => 'required|numeric',
            'leftCylinder'  => 'required|numeric',
            'rightAxis'     => 'required|integer',
            'leftAxis'      => 'integer|nullable',
            'pupilDistance' => 'required|numeric',
        ]);

        $validatedData['salespersonID'] = auth()->id();
        $condition = EyeCondition::create($validatedData);

        if ($condition) {
            // Workflow Option A: Save data dan terus ke Order Management dengan menghantar customer_id
            if ($request->input('action') === 'save_and_order') {
                return redirect()->route('salesperson.orders.create', ['customer_id' => $validatedData['customer_id']])
                                 ->with('success', 'Condition record successfully logged. Please proceed with order entry.');
            }

            // Workflow Option B: Simpan sahaja dan kembali ke direktori
            return redirect()->route('salesperson.customer.index')->with('success', 'Prescription data saved securely.');
        }

        return redirect()->back()->withErrors(['error' => 'Failed to store the dataset into the database repository.']);
    }
}
