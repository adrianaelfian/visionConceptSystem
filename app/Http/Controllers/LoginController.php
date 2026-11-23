<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Customer;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function verifyUser(Request $request)
    {
        // 1. Validasi input borang
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'role'     => 'required',
        ]);

        // 2. Semak pengguna di dalam jadual 'users'
        $user = DB::table('users')->where('email', $request->email)->first();

        // 3. Semak kewujudan, padanan kata laluan, dan peranan (role)
        if (!$user || !Hash::check($request->password, $user->password) || trim($user->role) !== trim($request->role)) {
            return back()->withErrors([
                'email' => 'Invalid email, password, or selected role.'
            ])->withInput();
        }

        // 🛑 TAMBAHAN: Semak sama ada akaun telah dinyahaktifkan (inactive)
        if (isset($user->status) && trim($user->status) === 'inactive') {
            return back()->withErrors([
                'email' => 'Your account has been set to inactive. Please contact the manager.'
            ])->withInput();
        }

        // 4. Muat semula model User sebenar dan mulakan sesi rasmi Laravel
        $userModel = User::find($user->id);
        Auth::login($userModel, $request->filled('remember'));
        $request->session()->regenerate();

        // 5. Alihkan pengguna mengikut peranan masing-masing secara terus
        $role = trim($user->role);

        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        if ($role === 'manager') {
            return redirect()->route('manager.dashboard');
        }
        if ($role === 'salesperson') {
            return redirect()->route('salesperson.dashboard');
        }

        // 🛠️ DITAMBAH: Pengalihan khas untuk Pelanggan (Customer)
        if ($role === 'customer') {
            return redirect()->route('customer.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($request) {
            // 1. Create account in 'users' table
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'password' => Hash::make($request->password),
                'role'     => 'customer',
            ]);

            // 2. Create linked profile in 'customers' table
            Customer::create([
                'user_id'             => $user->id,
                'customerName'        => $request->name,
                'customerEmail'       => $request->email,
                'customerPhoneNumber' => $request->phone,
            ]);

            return $user;
        });

        // 3. Log in & redirect to customer dashboard
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.dashboard')->with('success', 'Account successfully created!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logout successful!');
    }
}
