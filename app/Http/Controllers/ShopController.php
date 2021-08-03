<?php
namespace App\Http\Controllers;

use App\Services\ShopService;

class ShopController extends Controller
{
    /**
     * Display all items for sale (Sneakers and Goods)
     */
    public function index()
    {
        $shopService = new ShopService();
        $items = $shopService->fetchSneakerItems();
        $items = array_merge($items, $shopService->fetchDoingGoodsItems());
        $items = $shopService->setWishListStatus($items);

        return view('shop.items', compact('items'));
    }
}
