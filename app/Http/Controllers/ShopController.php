<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\WishlistedItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use JsonException;

class ShopController extends Controller
{

    private $sd_url = 'https://www.sneakerdistrict.com/';

    /**
     * @param array $responseBody
     * @return array
     */
    private function convertSneakerApiResponseToItems(array $responseBody): array
    {
        $items_list = [];

        foreach ($responseBody as $response) {

            $item = new Item;
            $item->id = $response['id'];
            $item->api = 'sneaker';
            $item->image = $this->sd_url . $response['images']['overview'];
            $item->price =  "&euro; " .
                            number_format((float)$response['price']['incl'], 2,
                                ',', '');
            $item->wished = false;
            $item->name = $response['brand']['title'];
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }


    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function changeWishlistStatus(Request $request, $id) {

        $customer_wants_this = $request->input($id) ? true : false;

        if ($customer_wants_this && !WishlistedItem::where('wished_item', $id)->exists()) {

            $wish = new WishlistedItem();
            $wish->wished_item = $id;
            $wish->save();
        }
        else {

            WishlistedItem::where('wished_item', $id)->delete();
        }

        return back();
    }

    /**
     * @see https://laravel.com/docs/8.x/http-client
     *
     * @return array
     * @throws JsonException
     */
    public function sneakerItems(): array
    {
        $url = "https://api.sneakerdistrict.com/products";

        $response = Http::get($url, [
            'type' => 'sneakers',
            'brand' => 'nike',
            'page' => 1,
            'limit' => 100,
            'price' => '0,500'
        ]);

        $responseBody = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        return $this->convertSneakerApiResponseToItems($responseBody['products']);
    }

    /**
     * @param array $responseBody
     * @return array
     */
    private function convertDoingGoodApiResponseToItems(array $responseBody): array
    {
        $items_list = [];

        foreach ($responseBody as $response) {

            $item = new Item;
            $item->id = $response['id'];
            $item->api = 'goods';
            $item->image = $response['images'][0]['src'];
            $item->name = $response['name'];
            $item->price = "&euro; " .
                           number_format((float)$response['price'], 2, ',', '');
            $item->wished = false;
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }

    /**
     * @return array
     * @throws JsonException
     */
    public function doingGoodsItems(): array
    {
        $url = "https://www.doing-goods.com/wp-json/lamapress/v1/products";

        $response = Http::get($url);

        $responseBody = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        return $this->convertDoingGoodApiResponseToItems($responseBody['data']);
    }

    /**
     * Display all items for sale (Sneakers and Goods)
     *
     * @throws JsonException
     */
    public function index()
    {
        $items = $this->sneakerItems();
        $items = array_merge($items, $this->doingGoodsItems());

        $items = $this->setWishListStatus($items);

        return view('shop.items', compact('items'));
    }

    /**
     * Display a listing of the resource.
     *
     * @throws JsonException
     */
    public function wishlist()
    {
        $items = $this->sneakerItems();
        $items = array_merge($items, $this->doingGoodsItems());

        $items = $this->collectWishedItems($items);

        return view('wishlist.wishlist', compact('items'));
    }

    /**
     * @param array $items
     * @return array
     */
    private function setWishListStatus(array $items): array
    {
        $wishlist = WishlistedItem::all();

        foreach ($wishlist as $wish) {

            if (isset($items[$wish->wished_item])) {

                $items[$wish->wished_item]->wished = true;
            }
        }

        return $items;
    }

    /**
     * @param array $items
     * @return array
     */
    private function collectWishedItems(array $items): array
    {
        $wished_items = [];

        $wishlist = WishlistedItem::all();

        foreach ($wishlist as $wish) {

            if (isset($items[$wish->wished_item])) {

                $items[$wish->wished_item]->wished = true;
                $items[$wish->wished_item]->wishlist_id = $wish->id;

                $wished_items[] = $items[$wish->wished_item];
            }
        }

        return $wished_items;
    }
}
