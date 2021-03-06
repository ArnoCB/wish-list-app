<?php
namespace App\Http\Controllers;

use App\Models\WishlistedItem;
use App\Services\WishlistService;
use App\Services\ApiConsumers\DoingGoodsApi;
use App\Services\ApiConsumers\SneakerApi;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $items = (new SneakerApi())->fetchItems();
        $items = array_merge($items, (new DoingGoodsApi())->fetchItems());
        $items = (new WishlistService())->collectWishedItems($items);

        return view('wishlist.wishlist', compact('items'));
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        WishlistedItem::findOrFail($id)->delete();
        return back();
    }
}
