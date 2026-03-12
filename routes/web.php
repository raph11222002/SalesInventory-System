<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('show.dashboard');
    }
    return view('welcome');
})->name('welcome');

Route::get('/consumable-list/add-consumable', function () {
    return view('add_consumable');
})->middleware(['auth:web', 'verified'])->name('add.consumable');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

Route::get('/sales-report', [App\Http\Controllers\OrderController::class, 'showOrders'])->name('sales_report');
Route::get('/orders/view/{id}', [App\Http\Controllers\OrderController::class, 'viewOrder'])->name('orders.view');
Route::get('/orders/filter', [App\Http\Controllers\OrderController::class, 'filterOrders'])->name('orders.filter');

// Product routes
Route::post('/products/store', [App\Http\Controllers\ProductController::class, 'store'])->name('products.store');
Route::get('/record-sales', [App\Http\Controllers\ProductController::class, 'showProducts'])->name('record_sales');

Route::post('/record-sales/product/order/{id}/{product_group}/{product_name}/{product_price}/submit', [App\Http\Controllers\ProductController::class, 'submitOrder'])->name('product.order.submit');

Route::get('/record-sales/product/order/{id}', [App\Http\Controllers\OrderController::class, 'orderForm'])->name('product.order');
Route::post('/order/add-to-order/{id}', [App\Http\Controllers\OrderController::class, 'addToOrder'])->name('order.add-to-order');
Route::post('/order/update-quantity', [App\Http\Controllers\OrderController::class, 'updateQuantity'])->name('order.update-quantity');
Route::post('/order/remove-item', [App\Http\Controllers\OrderController::class, 'removeItem'])->name('order.remove-item');
Route::post('/order/confirm', [App\Http\Controllers\OrderController::class, 'confirmOrder'])->name('order.confirm');

Route::get('/orders/{id}/download', [App\Http\Controllers\OrderController::class, 'downloadReceipt'])->name('orders.downloadReceipt');
Route::get('/sales-report/download', [App\Http\Controllers\OrderController::class, 'downloadSalesReport'])->name('sales_report.download');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('show.dashboard');

Route::post('/login-staff', [App\Http\Controllers\StaffController::class, 'login'])
    ->middleware(['guest:staff'])->name('staff.login');

Route::middleware(['auth:web'])->group(function () {

    // Add these routes to your routes/web.php file
    Route::get('/add-product', [App\Http\Controllers\ProductController::class, 'showAddProduct'])->name('add_product');
    Route::get('/product-list', [App\Http\Controllers\ProductController::class, 'showProductWithStock'])->name('product.stock.view');
    Route::post('/product-stock-list/{id}/add-stock', [App\Http\Controllers\ProductController::class, 'productAddStock'])->name('product.addStock');
    Route::get('/product-stock-list/{product_id}/stocks', [App\Http\Controllers\ProductController::class, 'viewProductStocks'])->name('view.product.stocks');
    Route::patch('/products/{product}/toggle-status', [App\Http\Controllers\ProductController::class, 'toggleStatus'])->name('product.toggle.status');
    Route::patch('/product/{stockId}/remove-stocks/{productId}', [App\Http\Controllers\ProductController::class, 'removeStocks'])->name('product.remove.stocks');

    Route::get('/consumable-list', [App\Http\Controllers\ConsumableController::class, 'showConsumables'])->name('consumable_list');
    Route::post('/consumable-store', [App\Http\Controllers\ConsumableController::class, 'consumableStore'])->name('consumable.store');
    Route::post('/consumable-list/{id}/add-stock', [App\Http\Controllers\ConsumableController::class, 'addStock'])->name('inventories.addStock');
    Route::get('/consumable-list/{consumable}/stocks', [App\Http\Controllers\ConsumableController::class, 'viewConsumableStocks'])->name('view.consumable.stocks');
    Route::patch('/consumable/{stockId}/remove-stocks/{consumableId}', [App\Http\Controllers\ConsumableController::class, 'removeStocks'])->name('consumable.remove.stocks');

    Route::get('/register-staff', [App\Http\Controllers\StaffController::class, 'showStaffRegister'])->name('show.register.staff');
    Route::post('/register-staff/store', [App\Http\Controllers\StaffController::class, 'store'])->name('admin.register.staff');
    Route::patch('/user/{id}/deactivate', [App\Http\Controllers\StaffController::class, 'deactivate'])->name('staff.deactivate');
});

Route::middleware(['auth:staff', 'auth'])->group(function () {
    Route::post('/logout-staff', [App\Http\Controllers\StaffController::class, 'logout'])->name('staff.logout');
});

require __DIR__ . '/auth.php';
