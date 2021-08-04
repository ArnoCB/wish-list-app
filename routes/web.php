<?php

use App\Http\Controllers\QueensProblemController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishListController;
use App\Models\WishlistedItem;
use App\Services\WishlistService;
use Illuminate\Support\Facades\Route;

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

Route::redirect('/', '/shop');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/wishlist', [WishListController::class, 'index'])->name('wishlist');
Route::get('/wishlist_count', [WishlistService::class, 'wishlistCount']);
Route::post('/wishlist/{id}', [WishlistService::class, 'changeWishlistStatus']);
Route::delete('/wishlist/{id}', [WishListController::class, 'destroy']);

Route::get('/queens_problem', [QueensProblemController::class, 'index'])->name('queens');

// Catch all non existing routes
Route::get('{any?}', function ($any) {
    abort(404);
})->where('any', '.*');
