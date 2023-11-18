<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportTenantController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/admin');
});

Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::resource('products', ProductController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    
    
    Route::resource('reports', ReportController::class);
    Route::get('admin/reports/{customerId}/showDetail', [ReportController::class, 'showDetail'])->name('reports.showDetail');
    Route::get('/reports/{customerId}/showDetail', [ReportController::class, 'showDetail'])->name('reports.showDetail');
    Route::get('/admin/reports/{report}/exportPDF', [ReportController::class, 'exportPDF'])->name('reports.exportPDF');

    Route::resource('reportTenants', ReportTenantController::class);
    Route::get('/admin/reportTenants/{report}/exportPDF', [ReportTenantController::class, 'exportPDF'])->name('reportTenants.exportPDF');

    Route::resource('tenants', TenantController::class);

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/change-qty', [CartController::class, 'changeQty']);
    Route::delete('/cart/delete', [CartController::class, 'delete']);
    Route::delete('/cart/empty', [CartController::class, 'empty']);
});
