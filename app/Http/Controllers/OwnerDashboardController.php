<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OwnerDashboardController extends Controller
{
    // ==========================================
    // 1. Owner Main Dashboard Entry Point
    // ==========================================
    public function dashboard($company_slug)
    {
        // 🚀 কাস্টম ওনার গার্ড দিয়ে বর্তমানে লগইন থাকা ওনারের ডেটা তুলে আনা
        $owner = Auth::guard('owner')->user();
        
        if (!$owner) {
            return redirect()->route('owner.login');
        }

        return view('owner.dashboard', compact('company_slug', 'owner'));
    }

    // ==========================================
    // 2. Employee Index / Roster List View
    // ==========================================
    public function employeeIndex($company_slug)
    {
        $owner = Auth::guard('owner')->user();
        
        if (!$owner) {
            return redirect()->route('owner.login');
        }

        // 🚀 সিকিউরিটি চেক: ওনার শুধুমাত্র তার নিজের কোম্পানির কর্মচারীদের দেখতে পাবেন
        $employees = User::where('company_id', $owner->company_id)->get();

        return view('owner.employees.index', compact('company_slug', 'employees'));
    }

    // ==========================================
    // 3. Employee Create Form View
    // ==========================================
    public function employeeCreate($company_slug)
    {
        $owner = Auth::guard('owner')->user();
        
        if (!$owner) {
            return redirect()->route('owner.login');
        }

        return view('owner.employees.create', compact('company_slug'));
    }

    // ==========================================
    // 4. Core Employee Store Method (Cleaned & Dynamic)
    // ==========================================
    public function employeeStore(Request $request, $company_slug)
    {
        $owner = Auth::guard('owner')->user();
        
        if (!$owner) {
            return redirect()->route('owner.login');
        }

        // A. Data Validation (Strict Front-end Form Fields Match)
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users,email',
            'mobile'     => 'required|string|max:20|unique:users,mobile',
            'dob'        => 'required|date',
            'department' => 'required|string|max:255',
            'category'   => 'required|string|max:255',
        ]);

        // B. Auto-Password Generation Formula: [NAME_FIRST_4_CAPITAL] + @ + [DOB_YEAR]
        $cleanName = str_replace(' ', '', $request->name);
        $namePart  = strtoupper(substr($cleanName, 0, 4));
        $yearPart  = Carbon::parse($request->dob)->format('Y');
        
        $generatedPassword = $namePart . '@' . $yearPart;

        // C. Smart Access Level Allocation Logic
        // Management পদের ক্ষেত্রে সিস্টেম রোল 'manager' হবে, বাকি সবার জন্য ডিফল্ট 'employee'
        $managementRoles = ['Manager', 'Accountant', 'Assistant Manager'];
        $systemRole = in_array($request->category, $managementRoles) ? 'manager' : 'employee';

        // D. Save Record Linked directly with Owner's Onboarded Company
        User::create([
            'company_id'   => $owner->company_id, // 🚀 ওনারের কোম্পানি আইডি ফরেন কি লিংক
            'company_name' => $owner->company->company_name,
            'company_slug' => $company_slug,
            'name'         => $request->name,
            'email'        => $request->email,
            'mobile'       => $request->mobile,
            'dob'          => $request->dob,
            'department'   => $request->department,
            'category'     => $request->category,
            'role'         => $systemRole, 
            'password'     => Hash::make($generatedPassword), // Secure Hashing
        ]);

        // E. Redirect back to index list with live default password banner notice
        return redirect()->route('company.owner.employees.index', $company_slug)
            ->with('success', 'Staff registered successfully! System credentials generated. Default Password is: ' . $generatedPassword);
    }
}