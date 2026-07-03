<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;

//main dashboard page
Route::get('/', function () {
    return view('dashboard');
});

//company toyri korar form dakhar route
Route::get('/company/create', [CompanyController::class, 'create']);

//form submit korar por data save korar route (POST Request)
Route::post('/company/store', [CompanyController::class, 'store']);
