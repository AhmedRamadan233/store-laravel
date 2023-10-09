<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Website\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});
// // Route::get('/dashboard/products', [ProductController::class, 'getAllProducts'])->name('getAllProducts');
// Route::prefix('cart')->group(function () {
//     Route::get('/', [CartController::class, 'index'])->name('cart.index');
//     Route::post('/store', [CartController::class, 'store'])->name('cart.store');
//     Route::put('/update', [CartController::class, 'update'])->name('cart.update');
//     Route::delete('/destroy', [CartController::class, 'destroy'])->name('cart.destroy');
// });

Route::get('/admi' , [CartController::class, 'admi']);


require __DIR__.'/auth.php';
