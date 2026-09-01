<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerLoginController extends Controller
{
    // ১. ওনার লগইন পেজের ভিউ দেখানো
    public function showLoginForm()
    {
        if (Auth::guard('owner')->check() && Auth::guard('owner')->user()->company) {
            return redirect('/' . Auth::guard('owner')->user()->company->company_slug . '/dashboard');
        }

        return view('auth.owner-login');
    }

    // ২. ওনার লগইন রিকোয়েস্ট প্রসেস করা
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        
        // 🚀 'owner' গার্ড দিয়ে owners টেবিলে লগইন চেক
        if (Auth::guard('owner')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $owner = Auth::guard('owner')->user();

            if ($owner->company) {
                return redirect()->intended('/' . $owner->company->company_slug . '/dashboard');
            }

            Auth::guard('owner')->logout();
            return back()->withErrors([
                'email' => 'No active company found for this owner account.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our owner records.',
        ])->withInput($request->only('email', 'remember'));
    }

    // ৩. ওনার লগআউট মেকানিজম
    public function logout(Request $request)
    {
        Auth::guard('owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('owner.login');
    }
}