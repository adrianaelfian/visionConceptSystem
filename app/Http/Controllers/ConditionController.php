<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConditionController extends Controller
{
    // openCondtionForm() & displayForm()
    public function create(){
        return view('condition.create');
    }

    public function store(Request $request)
    {
        //validate the input data based type of data
        $validatedData = $request->validate([
            'appointmentID' => 'required|integer',
            'rightPower'    => 'required|numeric',
            'leftPower'     => 'required|numeric',
            'rightCylinder' => 'required|numeric',
            'leftCylinder'  => 'required|numeric',
            'rightAxis'     => 'required|integer',
            'leftAxis'      => 'required|integer',
            'pupilDistance' => 'required|integer',
        ]);
        // keep data in database(Entity:CONDITION)
        $inserted = DB::table('condition')->insert([
            'appointmentID' => $validatedData['appointmentID'],
            'rightPower'    => $validatedData['rightPower'],
            'leftPower'     => $validatedData['leftPower'],
            'rightCylinder' => $validatedData['rightCylinder'],
            'leftCylinder'  => $validatedData['leftCylinder'],
            'rightAxis'     => $validatedData['rightAxis'],
            'leftAxis'      => $validatedData['leftAxis'],
            'pupilDistance' => $validatedData['pupilDistance'],
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        //Return true and displaySaveSuccessful()
        if($inserted){

            if($request->input('action') === 'save_and_order'){
                return redirect()->route('order.create')->with('success', 'Condition saved. Now create order.');
            }
            return redirect()->back()->with('success', true);
        }

        return redirect()->back()->withErrors(['error' => 'Failed to store the data']);
    }
}
