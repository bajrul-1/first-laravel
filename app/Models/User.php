<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'company_id',
        'company_name',
        'company_slug',
        'name',
        'email',
        'mobile',
        'dob',
        'department',
        'category',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // 🚀 কর্মচারী কোন বেকারির আন্ডারে কর্মরত আছেন তা জানার জন্য
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
