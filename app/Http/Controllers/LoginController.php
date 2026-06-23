<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Staff;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //Display frmLogin
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function verifyUser(Request $request)
    {
        $request->validate([
            'email'=> 'required|email',
            'password' => 'required',
        ]);

        $staff = Staff::checkCredentials($request->email, $request->password);

        if($staff){
            Auth::login($staff);

            if($staff->role === 'Admin'){
                return redirect()->route('admin.dashboad');
            } elseif($staff->role === 'Manager'){
                return redirect()->route('manager.dashboard');
            }else{
                return redirect()->route('salesperson.dashboard');
            }
        }

        return back()->withErrors(['loginError' =>'Invalid email or password.']);
    }
}
