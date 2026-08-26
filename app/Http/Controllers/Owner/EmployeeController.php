<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display the employee list / roster.
     */
    public function index($company_slug)
    {
        // 🚀 ফিক্স: 'slug' বদলে 'company_slug' করা হলো
        $company = Company::where('company_slug', $company_slug)->firstOrFail();
        $employees = Employee::where('company_id', $company->id)->latest()->get();

        return view('owner.employees.index', compact('company_slug', 'employees'));
    }

    /**
     * Display the onboarding form.
     */
    public function create($company_slug)
    {
        return view('owner.employees.create', compact('company_slug'));
    }

    /**
     * Store new employee in database.
     */
    public function store(Request $request, $company_slug)
    {
        $company = Company::where('company_slug', $company_slug)->firstOrFail();

        // ⭐️ ১. ভ্যালিডেশন (role তুলে দেওয়া হয়েছে)
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:employees,email',
            'mobile'      => 'required|string|max:15',
            'dob'         => 'required|date',
            'department'  => 'required|string',
            'designation' => 'required|string',

            // Optionals
            'father_name'            => 'nullable|string|max:255',
            'avatar'                 => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'blood_group'            => 'nullable|string|max:5',
            'address'                => 'nullable|string',
            'pincode'                => 'nullable|string|max:10',
            'document_type'          => 'nullable|string',
            'document_file'          => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_relation'     => 'nullable|string',
            'emergency_mobile'       => 'nullable|string|max:15',
            'joining_date'           => 'nullable|date',
        ]);

        // 📁 ২. ফাইল আপলোড
        $avatarPath = $request->hasFile('avatar') ? $request->file('avatar')->store('employees/avatars', 'public') : null;
        $documentPath = $request->hasFile('document_file') ? $request->file('document_file')->store('employees/documents', 'public') : null;

        // 🔑 ৩. র্যান্ডম পাসওয়ার্ড
        $randomPassword = Str::random(8);

        // 💾 ৪. সেভ (role এর ভ্যালু বাই ডিফল্ট 'employee')
        Employee::create([
            'company_id'             => $company->id,
            'name'                   => $request->name,
            'email'                  => $request->email,
            'mobile'                 => $request->mobile,
            'dob'                    => $request->dob,
            'department'             => $request->department,
            'designation'            => $request->designation,
            'role'                   => 'employee', // 👈 ডিফল্ট এমপ্লয়ি রোল
            'password'               => Hash::make($randomPassword),
            'status'                 => 'active',

            // Optionals
            'father_name'            => $request->father_name,
            'avatar'                 => $avatarPath,
            'blood_group'            => $request->blood_group,
            'address'                => $request->address,
            'pincode'                => $request->pincode,
            'document_type'          => $request->document_type,
            'document_file'          => $documentPath,
            'emergency_contact_name' => $request->emergency_contact_name,
            'emergency_relation'     => $request->emergency_relation,
            'emergency_mobile'       => $request->emergency_mobile,
            'joining_date'           => $request->joining_date ?? now(),
        ]);

        return redirect()->route('company.owner.employees.index', $company_slug)
            ->with('success', "Employee onboarded successfully! Temp Password: " . $randomPassword);
    }

    /**
     * 📊 Helper: প্রোফাইল কত % কমপ্লিট তা বের করার মেথড
     */
    public static function calculateProfileCompletion(Employee $employee)
    {
        $fields = [
            'name', 'email', 'mobile', 'dob', 'department', 
            'designation', 'father_name', 'avatar', 'address', 
            'pincode', 'document_file', 'emergency_mobile'
        ];

        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($employee->$field)) {
                $completed++;
            }
        }

        return round(($completed / count($fields)) * 100);
    }
}