<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\UserGroupController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\MenuController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::resource('users', UserController::class);
    Route::resource('groups', GroupController::class);
    Route::resource('user-groups', UserGroupController::class);
    Route::resource('reservations', ReservationController::class);
    Route::resource('menus', MenuController::class);
});

// Store Routes (Public E-commerce)
Route::prefix('store')->name('store.')->group(function () {
    Route::get('/products', [App\Http\Controllers\Store\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product}', [App\Http\Controllers\Store\ProductController::class, 'show'])->name('products.show');
    
    Route::get('/cart', [App\Http\Controllers\Store\CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [App\Http\Controllers\Store\CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/items/{cartItem}', [App\Http\Controllers\Store\CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/items/{cartItem}', [App\Http\Controllers\Store\CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [App\Http\Controllers\Store\CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/info', [App\Http\Controllers\Store\CartController::class, 'info'])->name('cart.info');
    
    Route::get('/checkout', [App\Http\Controllers\Store\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [App\Http\Controllers\Store\CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [App\Http\Controllers\Store\CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [App\Http\Controllers\Store\CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/checkout/pending', [App\Http\Controllers\Store\CheckoutController::class, 'pending'])->name('checkout.pending');
    
    // Shipping routes
    Route::post('/shipping/calculate', [App\Http\Controllers\Store\ShippingController::class, 'calcularFrete'])->name('shipping.calculate');
    Route::post('/shipping/validate-cep', [App\Http\Controllers\Store\ShippingController::class, 'validarCep'])->name('shipping.validate-cep');
    
    // Payment routes
    Route::post('/payment/create-preference', [App\Http\Controllers\Store\PaymentController::class, 'criarPreferencia'])->name('payment.create-preference');
    Route::post('/payment/verify-status', [App\Http\Controllers\Store\PaymentController::class, 'verificarStatus'])->name('payment.verify-status');
    Route::get('/payment/methods', [App\Http\Controllers\Store\PaymentController::class, 'getMetodosPagamento'])->name('payment.methods');
    
    // Customer Area (requires authentication)
    Route::middleware('auth')->group(function () {
        Route::get('/customer', [App\Http\Controllers\Store\CustomerController::class, 'dashboard'])->name('customer.dashboard');
        Route::get('/customer/orders', [App\Http\Controllers\Store\CustomerController::class, 'orders'])->name('customer.orders');
        Route::get('/customer/orders/{order}', [App\Http\Controllers\Store\CustomerController::class, 'order'])->name('customer.order');
        Route::get('/customer/profile', [App\Http\Controllers\Store\CustomerController::class, 'profile'])->name('customer.profile');
        Route::put('/customer/profile', [App\Http\Controllers\Store\CustomerController::class, 'updateProfile'])->name('customer.profile.update');
    });
});

// Webhook routes (outside store group)
Route::post('/mercadopago/webhook', [App\Http\Controllers\Store\PaymentController::class, 'webhook'])->name('mercadopago.webhook');

require __DIR__.'/auth.php';
