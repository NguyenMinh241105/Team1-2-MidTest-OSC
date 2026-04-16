<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;  
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CayCanhController;


require __DIR__.'/auth.php';

// Nguyen Thi Nhu
Route::get('/gio-hang', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/xoa-gio-hang', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/dat-hang', [CartController::class, 'checkout'])
    ->middleware('auth')
    ->name('cart.checkout');
Route::post('/order/create','App\Http\Controllers\CartController@ordercreate')
->middleware('auth')->name('ordercreate');
// Nguyen Trong Minh
Route::get('/{id_danh_muc?}', [HomeController::class, 'index'])->name('index');
Route::get('/caycanh/theloai/{id}', [HomeController::class, 'theloai'])->name('theloai');
Route::post('/timkiem', [HomeController::class, 'timkiem'])->name('timkiem');
Route::get('/caycanh/{id}', [HomeController::class, 'chitietsanpham'])->name('chitietsanpham');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
Route::get('/', [HomeController::class, 'index'])->name('index');
// Huynh Thi Thanh Ngoc
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products');
    Route::delete('/admin/product/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
    Route::get('/admin/product/create', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/admin/product/store', [ProductController::class, 'store'])->name('admin.product.store');
});

