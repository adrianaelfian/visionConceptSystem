<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountManagementController extends Controller
{
    /**
     * Display a listing of all user accounts (excluding Admin).
     */
    public function index()
    {
        // Retrieve all user accounts that are not admins, ordered by latest creation date
        $users = User::orderBy('created_at', 'desc')->get();

        // Pass the retrieved accounts data to the blade view using compact
        return view('admin.manage-account', compact('users'));
    }

    /**
     * Store a newly created user account in the database (Part: Create Account).
     */
    public function store(Request $request)
    {
        // Validate the incoming request data for form provisioning
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'role'     => ['required', Rule::in(['admin','manager', 'salesperson'])],
            'password' => 'required|string|min:8',
            'phoneNO'=>'required|string|max:15',
        ]);

        // Insert the validated user records into the application datastore
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => Hash::make($request->password), // Cryptographically hash the password
            'phoneNO' =>$request->phoneNO,
        ]);

        // Redirect back to the index page with a flash success notification
        return redirect()->back()->with('success', 'User account successfully registered.');
    }

    /**
     * Update the specified user account information (Part: Update Account).
     */
    public function update(Request $request,int $id)
    {
        // Find the specific user account record or throw a 404 exception if not found
        $user = User::findOrFail($id);

        // Validate the operational modification payload
        $request->validate([
            'name'     => 'required|string|max:255',
            // Ensure the updated email remains unique while ignoring the current user record being modified
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('user', 'email')->ignore($id, 'id')],
            'role'     => ['required', Rule::in(['admin','manager', 'salesperson'])],
            'password' => 'nullable|string|min:8', // Made nullable so changing the password remains optional
        ]);

        // Re-assign the non-sensitive parameters
        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        // Apply a new hashed password only if the input field is filled by the Admin
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Commit and save the changes to phpMyAdmin
        $user->save();

        return redirect()->back()->with('success', 'Account information successfully updated.');
    }

    /**
     * Remove the specified user account from the database (Part: Delete Account).
     */
    public function destroy(int $id)
    {
        // Gantikan $id dengan $id mengikut parameter yang diterima
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'The account has been permanently deleted.');
    }
}
