<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CayCanhController;

Route::get('/gio-hang', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/xoa-gio-hang', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/dat-hang', [CartController::class, 'checkout'])
    ->middleware('auth')
    ->name('cart.checkout');
require __DIR__.'/auth.php';

Route::get('/dashboard', function () {
    //return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/{id_danh_muc?}', [HomeController::class, 'index'])->name('index');
Route::get('/caycanh/theloai/{id}', [HomeController::class, 'theloai'])->name('theloai');
Route::post('/timkiem', [HomeController::class, 'timkiem'])->name('timkiem');
Route::get('/caycanh/{id}', [HomeController::class, 'chitietsanpham'])->name('chitietsanpham');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');
Route::get('/', [HomeController::class, 'index']);

Route::post('/order/create','App\Http\Controllers\CartController@ordercreate')
->middleware('auth')->name('ordercreate');

