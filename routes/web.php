<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/add_product', function () {
    return view('add_product');
})->middleware(['auth', 'verified'])->name('add_product');

Route::get('/sales_report', function () {
    return view('sales_report');
})->middleware(['auth', 'verified'])->name('sales_report');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add these routes to your routes/web.php file

    // Product routes
    Route::post('/products/store', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
    Route::get('/record-sales', [App\Http\Controllers\ProductController::class, 'showProducts'])->name('record_sales');

    //Route::get('/inventory-log', [App\Http\Controllers\InventoryController::class, 'showInventoryLog'])->name('inventory_log');
    Route::get('/inventory', [App\Http\Controllers\InventoryController::class, 'index'])->name('inventory_log');
    Route::post('/inventories/{id}/add-stock', [App\Http\Controllers\InventoryController::class, 'addStock'])->name('inventories.addStock');
});

require __DIR__ . '/auth.php';
