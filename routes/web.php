<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\Manager\ReportController;
use App\Http\Controllers\Manager\AccountManagementController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;

use App\Http\Controllers\Salesperson\CustomerController;
use App\Http\Controllers\Salesperson\ConditionController;
use App\Http\Controllers\Salesperson\OrderController;
use App\Http\Controllers\Salesperson\DashboardController as SalespersonDashboardController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;

/*
|--------------------------------------------------------------------------
| Laluan Awam (boleh diakses tanpa log masuk)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Login & Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'verifyUser'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Register
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'registerCustomer'])->name('register.submit');

// Lupa kata laluan
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

/*
|--------------------------------------------------------------------------
| 1. Salesperson
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:salesperson'])->prefix('salesperson')->name('salesperson.')->group(function () {

    // Dashboard & Temujanji
    Route::get('/dashboard', [SalespersonDashboardController::class, 'index'])->name('dashboard');
    Route::get('/appointments', [SalespersonDashboardController::class, 'appointmentsIndex'])->name('appointments.index');
    Route::patch('/appointments/{id}/status', [SalespersonDashboardController::class, 'updateStatus'])->name('appointments.updateStatus');

    // Pendaftaran & Pengurusan Pelanggan
    Route::get('/customers/create', [CustomerController::class, 'displayForm'])->name('customer.create');
    Route::post('/customers', [CustomerController::class, 'saveProfile'])->name('customer.store');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customers/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customers/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Keadaan Mata / Preskripsi
    Route::get('/conditions/create/{customer_id}', [ConditionController::class, 'create'])->name('conditions.create');
    Route::get('/customers/{customer_id}/conditions', [ConditionController::class, 'index'])->name('conditions.index');
    Route::post('/conditions', [ConditionController::class, 'store'])->name('conditions.store');
    Route::get('/conditions/{id}/edit', [ConditionController::class, 'edit'])->name('conditions.edit');
    Route::put('/conditions/{id}', [ConditionController::class, 'update'])->name('conditions.update');

    // Pesanan & Invois
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

/*
|--------------------------------------------------------------------------
| 2. Customer Portal
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');

    // Profil
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [CustomerDashboardController::class, 'updateProfile'])->name('profile.update');

    // Temujanji
    Route::get('/appointments', [CustomerDashboardController::class, 'createAppointment'])->name('appointments.index');
    Route::get('/appointments/create', [CustomerDashboardController::class, 'createAppointment'])->name('appointments.create');
    Route::post('/appointments', [CustomerDashboardController::class, 'storeAppointment'])->name('appointments.store');
    Route::get('/appointments/{id}/edit', [CustomerDashboardController::class, 'editAppointment'])->name('appointments.edit');
    Route::put('/appointments/{id}', [CustomerDashboardController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [CustomerDashboardController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/api/available-slots', [CustomerDashboardController::class, 'getAvailableSlots'])->name('appointments.slots');

    // Waranti & Pesanan
    Route::get('/warranties', [CustomerDashboardController::class, 'warranties'])->name('warranties');
    Route::get('/orders', [CustomerDashboardController::class, 'ordersIndex'])->name('orders.index');
    Route::get('/orders/{id}', [CustomerDashboardController::class, 'showOrder'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| 3. Manager
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {

    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // Pengurusan Akaun
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountManagementController::class, 'index'])->name('index');
        Route::post('/', [AccountManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AccountManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AccountManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [AccountManagementController::class, 'destroy'])->name('destroy');
    });

    // Laporan
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

/*
|--------------------------------------------------------------------------
| 4. Profil Pengguna Umum (perlukan log masuk)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
