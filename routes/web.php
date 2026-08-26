<?php

use App\Http\Controllers\Auth\OwnerLoginController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\Owner\EmployeeController;
use App\Http\Controllers\Owner\ProductController;
use Illuminate\Support\Facades\Route;

// 🚀 Home Route Redirect to Owner Login
Route::get('/', function () {
    return redirect()->route('owner.login');
});

// 🔒 Owner Authentication Routes
Route::get('/owner/login', [OwnerLoginController::class, 'showLoginForm'])->name('owner.login');
Route::post('/owner/login', [OwnerLoginController::class, 'login']);
Route::post('/owner/logout', [OwnerLoginController::class, 'logout'])->name('owner.logout');

// 🏢 Multi-Tenant Owner Dashboard Protected Node
Route::prefix('{company_slug}')->group(function () {
    
    // 📊 Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'dashboard'])->name('company.owner.dashboard');
    
    // 👥 Employee Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('company.owner.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('company.owner.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('company.owner.employees.store');

    // 📦 Product Management (Static Routes First)
    Route::get('/products', [ProductController::class, 'index'])->name('company.owner.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('company.owner.products.create');
    Route::post('/products/create', [ProductController::class, 'store'])->name('company.owner.products.store');

    // 🏷️ Product Management (Dynamic ID Routes Afterwards)
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('company.owner.products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('company.owner.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('company.owner.products.update');
    Route::get('/products/{id}/print-barcode', [ProductController::class, 'printBarcode'])->name('company.owner.products.print-barcode');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('company.owner.products.destroy');
    Route::delete('/products/{id}/images/{image_id}', [ProductController::class, 'destroyImage'])->name('company.owner.products.images.destroy');

    //akhana ki commend lekhbo?
    
    Route::get('/pos/{type?}', function ($company_slug, $type = 'customer') {
    return view('owner.pos.index', compact('company_slug', 'type'));
})->name('company.owner.pos.index');
});