<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;

class EmployeeOnboardForm extends Component
{
    use WithFileUploads;

    public $company_slug;

    // Employee Form Fields
    public $name;
    public $email;
    public $phone;
    public $designation = 'salesman'; // salesman, worker, manager
    public $salary;
    public $joining_date;
    public $password;
    public $profile_photo;

    public function mount($company_slug = null)
    {
        $this->company_slug = $company_slug;
        $this->joining_date = date('Y-m-d');
    }

    public function saveEmployee()
    {
        $company = Company::where('company_slug', $this->company_slug)->firstOrFail();

        $this->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'required|string|max:20',
            'designation' => 'required|in:salesman,worker,manager',
            'salary'      => 'required|numeric|min:0',
            'joining_date'=> 'required|date',
            'password'    => 'required|string|min:6',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $photoPath = null;
        if ($this->profile_photo) {
            $photoPath = $this->profile_photo->store('employees/photos', 'public');
        }

        User::create([
            'company_id'   => $company->id,
            'name'         => $this->name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'role'         => $this->designation,
            'salary'       => $this->salary,
            'joining_date' => $this->joining_date,
            'password'     => Hash::make($this->password),
            'profile_photo'=> $photoPath,
            'status'       => 'active',
        ]);

        session()->flash('success', "Employee '{$this->name}' onboarded successfully!");

        return redirect()->route('company.owner.employees.index', $this->company_slug);
    }

    public function render()
    {
        return view('livewire.owner.employee-onboard-form');
    }
}