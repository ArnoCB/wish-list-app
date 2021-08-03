<?php
namespace App\Http\Controllers;

use App\Services\ShopService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $shopService = new ShopService();

        $items = $shopService->fetchSneakerItems();
        $items = array_merge($items, $shopService->fetchDoingGoodsItems());
        $items = $shopService->collectWishedItems($items);

        return view('wishlist.wishlist', compact('items'));
    }
}
