<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\manager\ReportController;
use App\Http\Controllers\Salesperson\CustomerController;
use App\Http\Controllers\Salesperson\ConditionController;
use App\Http\Controllers\Salesperson\DashboardController as SalespersonDashboardController;
use App\Http\Controllers\Salesperson\OrderController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Manager\AccountManagementController;



// Laluan untuk papar borang minta emel
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

// Laluan untuk hantar pautan reset ke emel
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

// --- Laluan Awam (Boleh diakses tanpa log masuk) ---
Route::get('/', function () { return view('welcome'); });
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'verifyUser'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// TAMBAHKAN LALUAN REGISTER DI SINI:
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'registerCustomer'])->name('register.submit');

// --- Laluan Berasaskan Peranan (Hanya untuk pengguna yang sudah log masuk) ---

// 1. Route untuk Salesperson
Route::middleware(['auth', 'role:salesperson'])->prefix('salesperson')->name('salesperson.')->group(function () {
    Route::get('/dashboard', [SalespersonDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [SalespersonDashboardController::class, 'appointmentsIndex'])->name('appointments.index');
    Route::patch('/appointments/{id}/status', [SalespersonDashboardController::class, 'updateStatus'])->name('appointments.updateStatus');

    // Modul Pendaftaran & Pengurusan Pelanggan
    Route::get('/customers/create', [CustomerController::class, 'displayForm'])->name('customer.create');
    Route::post('/customers', [CustomerController::class, 'saveProfile'])->name('customer.store');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Modul Keadaan Mata / Preskripsi (Optical Conditions)
    Route::get('/conditions/create/{customer_id}', [ConditionController::class, 'create'])->name('conditions.create');
    Route::get('/customers/{customer_id}/conditions', [ConditionController::class, 'index'])->name('conditions.index');
    Route::post('/conditions', [ConditionController::class, 'store'])->name('conditions.store');
    Route::get('/conditions/{id}/edit', [ConditionController::class, 'edit'])->name('conditions.edit');
    Route::put('/conditions/{id}', [ConditionController::class, 'update'])->name('conditions.update');

    // Modul Pengurusan Pesanan & Invois (Orders & Manage Orders)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create/{customer_id?}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::put('/orders/{id}/pay', [OrderController::class, 'updateOrder'])->name('orders.updateOrder');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'generateInvoice'])->name('orders.invoice');
    Route::get('/api/customer/{customer_id}/conditions', [OrderController::class, 'getCustomerConditions'])->name('api.customer.conditions');
});

// 2. Route untuk Customer Portal (Ganti tempat Admin)
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/appointments', [CustomerDashboardController::class, 'createAppointment'])->name('appointments.index');
    Route::get('/appointments/create', [CustomerDashboardController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [CustomerDashboardController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [CustomerDashboardController::class, 'editAppointment'])->name('appointments.edit');
    Route::put('/appointments/{id}', [CustomerDashboardController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [CustomerDashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/warranties', [CustomerDashboardController::class, 'warranties'])->name('warranties');
    Route::get('/api/available-slots', [CustomerDashboardController::class, 'getAvailableSlots'])->name('appointments.slots');
    Route::get('/orders', [CustomerDashboardController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/orders/{id}', [CustomerDashboardController::class, 'showOrder'])->name('orders.show');
    Route::put('/profile/update', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
});

// 3. Route untuk Manager
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [App\Http\Controllers\Manager\AccountManagementController::class, 'index'])->name('index');
        Route::post('/', [App\Http\Controllers\Manager\AccountManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [App\Http\Controllers\Manager\AccountManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\Manager\AccountManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [App\Http\Controllers\Manager\AccountManagementController::class, 'destroy'])->name('destroy');
    });
    Route::get('/reports/create', [ReportController::class, 'create'])->name('GenerateReports.create');
    Route::post('/reports', [ReportController::class, 'generateReport'])->name('reports.generate');
    Route::get('/reports', [ReportController::class, 'viewReports'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{id}/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
    Route::delete('/reports/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');

    // Pengurusan Item & Stok
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ItemController::class, 'destroy'])->name('destroy');
    });
});

// 4. Profil Pengguna Umum (Perlukan auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
