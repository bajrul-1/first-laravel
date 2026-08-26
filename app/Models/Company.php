<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'company_name',
        'company_slug',
        'mobile',
        'address',
        'status',
    ];

    // 🚀 একটি কোম্পানির একাধিক ওনার (Partner) থাকতে পারে
    public function owners(): HasMany
    {
        return $this->hasMany(Owner::class);
    }

    // 🚀 একটি কোম্পানির আন্ডারে অনেক কর্মচারী (Staff) থাকতে পারে
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
