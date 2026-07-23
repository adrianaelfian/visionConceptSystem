<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Guna model User tunggal

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

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

       return redirect()->route('login')->with('success', 'Logout successful!');
    }
}
