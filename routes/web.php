<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConsultantController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Route::middleware(['auth'])->group(function () {
// // DASHBOARD

Route::middleware('guest')->prefix('auth')->group(function () {
    Route::get('/login', fn() => Inertia::render('views/LoginEmail'))->name('login');
    Route::get('/otp', fn() => Inertia::render('views/OtpVerify'))->name('otp');
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
});


// // Routes protégées (utilisateur connecté)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::get('/consultants/export/csv', [ConsultantController::class, 'exportCsv'])
        ->name('consultants.export.csv');

    Route::prefix('invoices')->group(function () {
        Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
        Route::post('/create', [InvoiceController::class, 'store'])->name('invoices.store');
        Route::get('/{uuid}', [InvoiceController::class, 'show'])->name('invoices.show');
        Route::post('/{uuid}/validate', [InvoiceController::class, 'validateInvoice'])
            ->name('invoices.validate');
    });


    Route::prefix('consultants')->group(function () {
        Route::get('/', [ConsultantController::class, 'index'])->name('consultants.index');
        Route::get('/create', [ConsultantController::class, 'create'])->name('consultants.create');
        Route::post('/', [ConsultantController::class, 'store'])->name('consultants.store');
        Route::get('/{uuid}', [ConsultantController::class, 'show'])->name('consultants.show');
        Route::get('/{uuid}/edit', [ConsultantController::class, 'edit'])->name('consultants.edit');
        Route::put('/{consultant}', [ConsultantController::class, 'update'])->name('consultants.update');
        Route::delete('/{uuid}', [ConsultantController::class, 'destroy'])->name('consultants.destroy');
    });
    Route::controller(UserController::class)->prefix('users')->group(function () {
        Route::get('/', 'index')->name('admin.users.index');
        Route::get('/{id}', 'show')->name('admin.users.show');
        Route::delete('/{uuid}', 'destroy')->name('admin.users.delete');
    });
});
