<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\AccountManagementController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Salesperson\CustomerController;
use App\Http\Controllers\Salesperson\ConditionController;
use App\Http\Controllers\Salesperson\DashboardController as SalespersonDashboardController;
use App\Http\Controllers\Salesperson\OrderController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;

// --- Laluan Awam (Boleh diakses tanpa log masuk) ---
Route::get('/', function () { return view('welcome'); });
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'verifyUser'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// --- Laluan Berasaskan Peranan (Hanya untuk pengguna yang sudah log masuk) ---

// 1. Route untuk Salesperson
Route::middleware(['auth', 'role:salesperson'])->prefix('salesperson')->name('salesperson.')->group(function () {
    Route::get('/dashboard', [SalespersonDashboardController::class, 'dashboard'])->name('dashboard');

    // Modul Pendaftaran Pelanggan
    Route::get('/customers/create', [CustomerController::class, 'displayForm'])->name('customer.create');
    Route::post('/customers', [CustomerController::class, 'saveProfile'])->name('customer.store');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer.index'); // <-- Betulkan di sini (buang perkataan salesperson. yang pertama)

    // Modul Keadaan Mata / Preskripsi (Optical Conditions)
    Route::get('/conditions/create/{customer_id}', [ConditionController::class, 'create'])->name('conditions.create');
    Route::post('/conditions', [ConditionController::class, 'store'])->name('conditions.store');

    // Modul Pengurusan Pesanan & Invois (Orders & Manage Orders)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

    // Pastikan create diletakkan SEBELUM /{id} dan menggunakan Route::get
    Route::get('/orders/create/{customer_id?}', [OrderController::class, 'create'])->name('orders.create');

    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
});

// 2. Route untuk Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Appointments
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', [AppointmentController::class, 'index'])->name('index');
        Route::get('/create', [AppointmentController::class, 'create'])->name('create');
        Route::post('/', [AppointmentController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AppointmentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AppointmentController::class, 'update'])->name('update');
        Route::delete('/{id}', [AppointmentController::class, 'destroy'])->name('destroy');
    });

    // Reports (Admin)
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/create', [ReportController::class, 'create'])->name('create');
        Route::post('/generate', [ReportController::class, 'generateReport'])->name('generate');
        Route::get('/', [ReportController::class, 'viewReports'])->name('index');
        Route::get('/{id}', [ReportController::class, 'show'])->name('show');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->name('destroy'); // <- Tukar jadi begini
    });
    // Pengurusan Akaun Staf
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [AccountManagementController::class, 'index'])->name('index');
        Route::post('/', [AccountManagementController::class, 'store'])->name('store');
        Route::put('/{id}', [AccountManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [AccountManagementController::class, 'destroy'])->name('destroy');
    });
});

// 3. Route untuk Manager
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'viewReports'])->name('reports.index');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{id}/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});

// 4. Profil Pengguna Umum (Perlukan auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.edit'); // Adjusted to standard convention if needed
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
