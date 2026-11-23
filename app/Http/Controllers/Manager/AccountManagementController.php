<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountManagementController extends Controller
{
    /**
     * Display a listing of all user accounts (Manager managing Staff).
     */
    public function index()
    {
        $users = User::where('role', '!=', 'customer')->orderBy('created_at', 'desc')->get();
        return view('manager.manage-account', compact('users'));
    }

    /**
     * Store a newly created staff account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(['manager', 'salesperson'])], // Hanya Manager & Salesperson
            'password' => 'required|string|min:8',
            'phoneNO'  => 'required|string|max:15',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'phoneNO'  => $request->phoneNO,
            'status'   => 'active',
        ]);

        return redirect()->back()->with('success', 'Staff account successfully registered.');
    }

    /**
     * Update the specified staff account.
     */
    public function update(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($id)],
            'role'     => ['required', Rule::in(['manager', 'salesperson'])],
            'password' => 'nullable|string|min:8',
            'phoneNO'  => 'required|string|max:15',
        ]);

        $user->name    = $request->name;
        $user->email   = $request->email;
        $user->role    = $request->role;
        $user->phoneNO = $request->phoneNO;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Staff account information successfully updated.');
    }

    /**
     * Set staff account status to inactive.
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'status' => 'inactive'
        ]);

        return redirect()->back()->with('success', 'The staff account status has been updated to inactive.');
    }

    public function edit(int $id)
    {
        $editUser = User::findOrFail($id);
        $users = User::where('role', '!=', 'customer')->orderBy('created_at', 'desc')->get();

        return view('manager.manage-account', compact('editUser', 'users'));
    }
}
