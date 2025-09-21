<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LedgersController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('customers', CustomerController::class);
    Route::post('/customer/{customer}/orders', [CustomerController::class, 'orders'])->name('customer.orders');
    Route::get('/customers/export/{type}', [CustomerController::class, 'export'])->name('customers.export');

    Route::resource('orders', OrderController::class);
    Route::get('/orders/export/{type}', [OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/{order}/bill_pdf', [OrderController::class, 'bill_pdf'])->name('orders.bill_pdf');

    Route::resource('payments', PaymentController::class);
    Route::get('/payments/export/{type}', [PaymentController::class, 'export'])->name('payments.export');
    Route::resource('ledgers', LedgersController::class);
});
