<?php
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CayCanhController;
Route::get('/', [HomeController::class, 'index']);


Route::get('/dashboard', function () {
    //return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';

Route::get('/gio-hang', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/xoa-gio-hang', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::post('/dat-hang', [CartController::class, 'checkout'])->name('cart.checkout');

Route::match(['get','post'], '/timkiem', [CayCanhController::class, 'timKiem']);