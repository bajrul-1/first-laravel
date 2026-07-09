<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function authenticate(Request $request){
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'super_admin') {
                return redirect()->intended('/super-admin/dashboard');
            } elseif ($user->role === 'company_admin') {
                return redirect()->intended('/company/dashboard');
            } elseif ($user->role === 'employee') {
                return redirect()->intended('/employee/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.'
        ])->onlyInput('email');
    }
}
