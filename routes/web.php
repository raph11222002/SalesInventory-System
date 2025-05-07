<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/consumable-list/add-consumable', function () {
    return view('add_consumable');
})->middleware(['auth', 'verified'])->name('add.consumable');

Route::get('/sales_report', function () {
    return view('sales_report');
})->middleware(['auth', 'verified'])->name('sales_report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add these routes to your routes/web.php file
    Route::get('/add-product', [App\Http\Controllers\ProductController::class, 'showAddProduct'])->name('add_product');

    // Product routes
    Route::post('/consumable-store', [App\Http\Controllers\ConsumableController::class, 'consumableStore'])->name('consumable.store');

    // Product routes
    Route::post('/products/store', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/record-sales', [App\Http\Controllers\ProductController::class, 'showProducts'])->name('record_sales');

    Route::get('/record-sales/product/order/{id}', [App\Http\Controllers\ProductController::class, 'orderForm'])->name('product.order');
    Route::post('/record-sales/product/order/{id}/submit', [App\Http\Controllers\ProductController::class, 'submitOrder'])->name('product.order.submit');


    //Route::get('/inventory-log', [App\Http\Controllers\InventoryController::class, 'showInventoryLog'])->name('inventory_log');
    Route::get('/consumable-list', [App\Http\Controllers\ConsumableController::class, 'showConsumables'])->name('consumable_list');
    Route::post('/consumable-list/{id}/add-stock', [App\Http\Controllers\ConsumableController::class, 'addStock'])->name('inventories.addStock');

    // In web.php (routes file)
    Route::get('/consumable-list/{consumable}/stocks', [App\Http\Controllers\ConsumableController::class, 'viewStocks'])->name('view.stocks');

});

require __DIR__ . '/auth.php';
