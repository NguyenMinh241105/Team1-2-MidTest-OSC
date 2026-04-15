<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/{id_danh_muc?}', [HomeController::class, 'index'])->name('index');
Route::get('/caycanh/theloai/{id}', [HomeController::class, 'theloai'])->name('theloai');
Route::post('/timkiem', [HomeController::class, 'timkiem'])->name('timkiem');
Route::get('/caycanh/{id}', [HomeController::class, 'chitietsanpham'])->name('chitietsanpham');
Route::post('/cart/add', [HomeController::class, 'addToCart'])->name('cart.add');

Route::get('/dashboard', function () {
    //return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';
