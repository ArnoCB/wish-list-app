<?php

use App\Http\Controllers\QueensProblemController;
use App\Http\Controllers\ShopController;
use App\Models\WishlistedItem;
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

Route::get('/wishlist', [ShopController::class, 'wishlist'])->name('wishlist');
Route::get('/wishlist_count', [ShopController::class, 'wishlistCount']);


Route::get('/queens_problem', [QueensProblemController::class, 'index'])->name('queens');

Route::delete('/wishlist/{id}', function($id) {
    WishlistedItem::findOrFail($id)->delete();
    return back();
});

Route::post('/wishlist/{id}', [ShopController::class, 'changeWishlistStatus']);

