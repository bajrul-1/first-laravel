<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Authenticatable
{
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'mobile',
        'dob',
        'department',
        'designation',
        'role',
        'password',
        'status',
        'father_name',
        'avatar',
        'blood_group',
        'address',
        'pincode',
        'document_type',
        'document_file',
        'emergency_contact_name',
        'emergency_relation',
        'emergency_mobile',
        'joining_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}