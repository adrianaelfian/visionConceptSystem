<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EyeCondition;
use App\Models\Customer;

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
        // Validasi data diselaraskan dengan had optik standard (SPH antara -20 hingga 20)
        $validatedData = $request->validate([
            'customer_id'   => 'required|integer',
            'appointmentID' => 'nullable|integer', // Ditetapkan nullable untuk kes walk-in
            'rightPower'    => 'required|numeric|between:-20,20',
            'leftPower'     => 'required|numeric|between:-20,20',
            'rightCylinder' => 'required|numeric',
            'leftCylinder'  => 'required|numeric',
            'rightAxis'     => 'required|integer',
            'leftAxis'      => 'required|integer',
            'pupilDistance' => 'required|numeric',
        ]);

        // Masukkan ID salesperson yang sedang log masuk secara automatik (Memenuhi Business Rule UCD200)
        $validatedData['salespersonID'] = auth()->id();

        $condition = EyeCondition::create($validatedData);

        if ($condition) {
            // Workflow Option A: Simpan data dan terus ke modul Order (Save & Proceed to Order)
            if ($request->input('action') === 'save_and_order') {
                return redirect()->route('salesperson.orders.create', ['customer_id' => $validatedData['customer_id']])
                               ->with('success', 'Prescription record successfully logged. Please proceed with order entry.');
            }

            // Workflow Option B: Simpan sahaja dan kembali ke senarai rekod pelanggan
            return redirect()->route('salesperson.conditions.index', ['customer_id' => $validatedData['customer_id']])
                             ->with('success', 'Prescription data saved securely.');
        }

        return redirect()->back()->withErrors(['error' => 'Failed to store the dataset into the database repository.']);
    }

    /**
     * Display a listing of the customer's eye condition records.
     */
    public function index(int $customer_id)
    {
        $customer = Customer::findOrFail($customer_id);

        // Memaparkan senarai sejarah rekod mengikut tarikh terkini (historical paper trail)
        $conditions = EyeCondition::where('customer_id', $customer_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('salesperson.conditions.index', compact('customer', 'conditions'));
    }

    /*
     * Nota: Fungsi edit dan update di bawah boleh dikekalkan jika sistem anda
     * memerlukan fungsi pengemaskinian, atau boleh dibuang jika mengikut
     * 100% rule SRS (di mana preskripsi tidak boleh diedit setelah disimpan).
     */
    public function edit(int $id)
    {
        $condition = EyeCondition::where('conditionID', $id)->firstOrFail();
        return view('salesperson.conditions.edit', compact('condition'));
    }

    public function update(Request $request, int $id)
    {
        $condition = EyeCondition::where('conditionID', $id)->firstOrFail();

        $request->validate([
            'rightPower'    => 'required|numeric|between:-20,20',
            'leftPower'     => 'required|numeric|between:-20,20',
            'rightCylinder' => 'required|numeric',
            'leftCylinder'  => 'required|numeric',
            'rightAxis'     => 'required|integer',
            'leftAxis'      => 'required|integer',
            'pupilDistance' => 'required|numeric',
        ]);

        $condition->update($request->all());

        return redirect()->route('salesperson.conditions.index', ['customer_id' => $condition->customer_id])
                         ->with('success', 'Eye prescription updated successfully.');
    }
}
