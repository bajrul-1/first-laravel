<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdminController;
use Illuminate\Support\Facades\Route;


//Home route no login requird
Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'loginPage'])->name('login');
Route::post('/login/verify', [LoginController::class, 'authenticate'])->name('login.verify');

Route::post('/company/generate-access/{id}', [SuperAdminController::class, 'generateAccess']);
Route::get('/company/edit/{id}', [SuperAdminController::class, 'editCompany']);
Route::post('/company/update/{id}', [SuperAdminController::class, 'updateCompany']);
Route::get('company/suspend/{id}', [SuperAdminController::class, 'suspendCompany']);

Route::get('/companies', [SuperAdminController::class, 'companyIndex']);
Route::get('/company/manage/{id}', [SuperAdminController::class, 'manageCompany']);



//company toyri korar form dakhar route
Route::get('/company/create', [CompanyController::class, 'create']);
//form submit korar por data save korar route (POST Request)
Route::post('/company/store', [CompanyController::class, 'store']);
