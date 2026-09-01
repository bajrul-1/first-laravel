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
Route::get('/login', [OwnerLoginController::class, 'showLoginForm'])->name('login'); // Fallback alias
Route::post('/owner/login', [OwnerLoginController::class, 'login']);
Route::post('/owner/logout', [OwnerLoginController::class, 'logout'])->name('owner.logout');

// 🏢 Multi-Tenant Owner Dashboard Protected Node (🔐 auth:owner গার্ড দিয়ে সুরক্ষিত)
Route::prefix('{company_slug}')->middleware(['web', 'auth:owner'])->group(function () {
    
    // 📊 Dashboard
    Route::get('/dashboard', [OwnerDashboardController::class, 'dashboard'])->name('company.owner.dashboard');
    
    // 👥 Employee Management
    Route::get('/employees', [EmployeeController::class, 'index'])->name('company.owner.employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('company.owner.employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('company.owner.employees.store');

    // 📦 Product Management
    Route::get('/products', [ProductController::class, 'index'])->name('company.owner.products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('company.owner.products.create');
    Route::post('/products/create', [ProductController::class, 'store'])->name('company.owner.products.store');

    Route::get('/products/{id}', [ProductController::class, 'show'])->name('company.owner.products.show');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('company.owner.products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('company.owner.products.update');
    Route::get('/products/{id}/print-barcode', [ProductController::class, 'printBarcode'])->name('company.owner.products.print-barcode');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('company.owner.products.destroy');
    Route::delete('/products/{id}/images/{image_id}', [ProductController::class, 'destroyImage'])->name('company.owner.products.images.destroy');

    // 🛒 POS & Billing Counter Route
    Route::get('/pos/{type?}', function ($company_slug, $type = 'customer') {
        return view('owner.pos.index', compact('company_slug', 'type'));
    })->name('company.owner.pos.index');

    // 🖨️ POS Receipt Print Route
    Route::get('/pos/invoice/{id}/print', function ($company_slug, $id) {
        $company = \App\Models\Company::where('company_slug', $company_slug)->firstOrFail();
        $sale = \App\Models\Sale::with(['items.product', 'payments'])
                                ->where('company_id', $company->id)
                                ->findOrFail($id);
        return view('owner.pos.invoice-print', compact('company', 'sale', 'company_slug'));
    })->name('company.owner.pos.print');

    // 📈 Sales Invoices & Return History
    Route::get('/sales', function ($company_slug) {
        return view('owner.sales.index', compact('company_slug'));
    })->name('company.owner.sales.index');
});