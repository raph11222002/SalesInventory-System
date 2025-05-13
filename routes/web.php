<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/consumable-list/add-consumable', function () {
    return view('add_consumable');
})->middleware(['auth', 'verified'])->name('add.consumable');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('show.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add these routes to your routes/web.php file
    Route::get('/add-product', [App\Http\Controllers\ProductController::class, 'showAddProduct'])->name('add_product');
    Route::get('/product-with-stock', [App\Http\Controllers\ProductController::class, 'showProductWithStock'])->name('product.stock.view');
    Route::post('/product-stock-list/{id}/add-stock', [App\Http\Controllers\ProductController::class, 'productAddStock'])->name('product.addStock');
    Route::get('/product-stock-list/{product_id}/stocks', [App\Http\Controllers\ProductController::class, 'viewProductStocks'])->name('view.product.stocks');

    // Product routes
    Route::post('/consumable-store', [App\Http\Controllers\ConsumableController::class, 'consumableStore'])->name('consumable.store');

    // Product routes
    Route::post('/products/store', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/record-sales', [App\Http\Controllers\ProductController::class, 'showProducts'])->name('record_sales');

    Route::get('/record-sales/product/order/{id}', [App\Http\Controllers\ProductController::class, 'orderForm'])->name('product.order');
    Route::post('/record-sales/product/order/{id}/{product_group}/{product_name}/{product_price}/submit', [App\Http\Controllers\ProductController::class, 'submitOrder'])->name('product.order.submit');


    //Route::get('/inventory-log', [App\Http\Controllers\InventoryController::class, 'showInventoryLog'])->name('inventory_log');
    Route::get('/consumable-list', [App\Http\Controllers\ConsumableController::class, 'showConsumables'])->name('consumable_list');
    Route::post('/consumable-list/{id}/add-stock', [App\Http\Controllers\ConsumableController::class, 'addStock'])->name('inventories.addStock');
    Route::get('/consumable-list/{consumable}/stocks', [App\Http\Controllers\ConsumableController::class, 'viewConsumableStocks'])->name('view.consumable.stocks');

    Route::get('/sales-report', [App\Http\Controllers\OrderController::class, 'showOrders'])->name('sales_report');
    Route::get('/orders/view/{id}', [App\Http\Controllers\OrderController::class, 'viewOrder'])->name('orders.view');
    Route::get('/orders/filter', [App\Http\Controllers\OrderController::class, 'filterOrders'])->name('orders.filter');

    Route::get('/register-staff', [App\Http\Controllers\StaffController::class, 'showStaffRegister'])->name('show.register.staff');
    Route::post('/register-staff/store', [App\Http\Controllers\StaffController::class, 'store'])->name('admin.register.staff');
    Route::post('/login-staff', [App\Http\Controllers\StaffController::class, 'login'])->name('staff.login');
    Route::post('/logout-staff', [App\Http\Controllers\StaffController::class, 'logout'])->name('staff.logout');
});

require __DIR__ . '/auth.php';
