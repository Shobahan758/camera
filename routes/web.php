<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncompleteOrderController;
use App\Http\Controllers\LandingSectionController;
use App\Http\Controllers\OrderController;
use App\Models\LandingSection;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $sections = LandingSection::all()->keyBy('slug');
    } catch (Throwable) {
        $sections = collect();
    }

    return view('landing.home', compact('sections'));
})->name('home');

Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('orders.store');
Route::post('/incomplete-orders', [IncompleteOrderController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('incomplete-orders.store');

Route::redirect('/admin', '/admin/login');

Route::middleware('guest')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('/admin/login', [AdminAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard')->name('dashboard');
    Route::get('/admin/order/create', [AdminOrderController::class, 'create'])->middleware('permission:orders')->name('admin.orders.create');
    Route::post('/admin/order', [AdminOrderController::class, 'store'])->middleware('permission:orders')->name('admin.orders.store');
    Route::get('/admin/orders/{filter?}', [AdminOrderController::class, 'index'])->middleware('permission:orders')->name('admin.orders.index');
    Route::get('/admin/fake-orders/{filter?}', [AdminOrderController::class, 'fakeIndex'])->middleware('permission:fake_orders')->name('admin.fake-orders.index');
    Route::get('/admin/order/{order}/edit', [AdminOrderController::class, 'edit'])->middleware('permission:orders')->name('admin.orders.edit');
    Route::put('/admin/order/{order}', [AdminOrderController::class, 'update'])->middleware('permission:orders')->name('admin.orders.update');
    Route::delete('/admin/order/{order}', [AdminOrderController::class, 'destroy'])->middleware('permission:orders')->name('admin.orders.destroy');
    Route::get('/admin/incomplete-orders/{order}/edit', [AdminOrderController::class, 'editIncomplete'])->middleware('permission:incomplete_orders')->name('admin.incomplete-orders.edit');
    Route::put('/admin/incomplete-orders/{order}', [AdminOrderController::class, 'updateIncomplete'])->middleware('permission:incomplete_orders')->name('admin.incomplete-orders.update');
    Route::delete('/admin/incomplete-orders/{order}', [AdminOrderController::class, 'destroyIncomplete'])->middleware('permission:incomplete_orders')->name('admin.incomplete-orders.destroy');
    Route::get('/admin/incomplete-orders/{filter?}', [AdminOrderController::class, 'incompleteIndex'])->middleware('permission:incomplete_orders')->name('admin.incomplete-orders.index');
    Route::patch('/admin/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->middleware('permission:orders')->name('admin.orders.status');
    Route::middleware('permission:site_settings')->group(function () {
        Route::get('/admin/site-settings', [LandingSectionController::class, 'index'])->name('admin.landing.index');
        Route::put('/admin/site-settings', [LandingSectionController::class, 'updateVisibility'])->name('admin.landing.visibility');
        Route::get('/admin/site-settings/{section}', [LandingSectionController::class, 'edit'])->name('admin.landing.edit');
        Route::put('/admin/site-settings/{section}', [LandingSectionController::class, 'update'])->name('admin.landing.update');
    });
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/settings/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::post('/admin/settings/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::put('/admin/settings/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/settings/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });
    Route::post('/admin/logout', [AdminAuthController::class, 'destroy'])->name('logout');
});
