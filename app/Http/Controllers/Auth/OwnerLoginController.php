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
        return view('auth.owner-login');
    }

    // ২. ওনার লগইন রিকোয়েস্ট প্রসেস করা
    public function login(Request $request)
    {
        // ইনপুট ভ্যালিডেশন
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // 🚀 কাস্টম 'owner' গার্ড ব্যবহার করে লগইন চেষ্টা করা
        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('owner')->attempt($credentials, $request->filled('remember'))) {
            // লগইন সফল হলে ওনারের কোম্পানির স্ল্যাগ বের করে ড্যাশবোর্ডে পাঠানো
            $companySlug = Auth::guard('owner')->user()->company->company_slug;
            
            return redirect()->intended('/' . $companySlug . '/dashboard');
        }

        // লগইন ব্যর্থ হলে এরর ব্যাক করা
        return back()->withErrors([
            'email' => 'The provided credentials do not match our owner records.',
        ])->withInput($request->only('email', 'remember'));
    }

    // ৩. ওনার লগআউট মেকানিজম
    public function logout()
    {
        Auth::guard('owner')->logout();
        return redirect()->route('owner.login');
    }
}
