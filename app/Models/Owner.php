<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Owner extends Authenticatable
{
    protected $fillable = [
        'company_id',
        'name',
        'email',
        'mobile',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    // 🚀 ওনার কোন নির্দিষ্ট কোম্পানির আন্ডারে আছেন তা জানার জন্য
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
