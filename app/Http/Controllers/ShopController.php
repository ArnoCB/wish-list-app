<?php
namespace App\Http\Controllers;

use App\Services\WishlistService;
use App\Services\ApiConsumers\DoingGoodsApi;
use App\Services\ApiConsumers\SneakerApi;

class ShopController extends Controller
{
    /**
     * Display all items for sale (Sneakers and Goods)
     */
    public function index()
    {
        $items = (new SneakerApi())->fetchItems();
        $items = array_merge($items, (new DoingGoodsApi())->fetchItems());
        $items = (new WishlistService())->setWishListStatus($items);

        return view('shop.items', compact('items'));
    }
}
