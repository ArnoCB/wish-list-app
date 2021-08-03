<?php

namespace App\Services;

use App\Models\Item;
use App\Models\WishlistedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use JsonException;

class ShopService
{

    /**
     * Convert the API response into standard readable items, so
     * the frontend can know what to expect.
     *
     * @param array $responseBody
     * @return array
     */
    private function convertSneakerApiResponseToItems(array $responseBody): array
    {
        $items_list = [];

        // url where the sneaker product images can be found
        $sd_url = env('SNEAKER_IMAGES_LOCATION');

        foreach ($responseBody as $response) {

            $item = new Item;
            $item->id = $response['id'];
            $item->api = 'sneaker';
            $item->image = $sd_url . $response['images']['overview'];
            $item->price =  "&euro; " .
                            number_format((float)$response['price']['incl'], 2,
                                ',', '');
            $item->wished = false;
            $item->name = $response['brand']['title'];
            $item->description = $response['model'];
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }

    /**
     * If a user changes the 'wished' checkbox in the shop, this
     * method is called to add the object to the wishlist, or remove it.
     *
     * @param Request $request
     * @param $id
     * @return false|string
     * @throws JsonException
     */
    public function changeWishlistStatus(Request $request, $id)
    {
        // This evaluates to true or false
        $customer_wants_this = $request->input($id);

        // Avoid putting it on the wishlist more than once
        if ($customer_wants_this && !WishlistedItem::where('wished_item', $id)->exists()) {

            $wish = new WishlistedItem();
            $wish->wished_item = $id;
            $wish->save();
        }
        else {

            WishlistedItem::where('wished_item', $id)->delete();
        }

        $return_data = [
            'wishlisted_number' => WishlistedItem::count()
        ];

        return json_encode($return_data, JSON_THROW_ON_ERROR);
    }

    /**
     * @return false|string
     * @throws JsonException
     */
    public function wishlistCount()
    {
        return json_encode(['wishlisted_number' => WishlistedItem::count()], JSON_THROW_ON_ERROR);
    }

    /**
     * Call the Sneaker District API to get the products on offer.
     *
     * @return array
     */
    public function fetchSneakerItems(): array
    {
        return Cache::remember('shoes', env('PRODUCT_DATA_CACHE_TIMEOUT'), function () {

            $url = env('SNEAKER_PRODUCTS_ENDPOINT');

            $response = Http::get($url, [
                'type' => 'sneakers',
                'brand' => 'nike',
                'page' => 1,
                'limit' => 100,
                'price' => '0,500'
            ]);

            $responseBody = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            return $this->convertSneakerApiResponseToItems($responseBody['products']);
        });
    }

    /**
     * Convert the API response into standard readable items, so
     * the frontend can know what to expect
     *
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
            $item->description = false;
            $item->price = "&euro; " .
                           number_format((float)$response['price'], 2, ',', '');
            $item->wished = false;
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }

    /**
     * Call the Doing Goods API to get the products on offer.
     *
     * @return array
     */
    public function fetchDoingGoodsItems(): array
    {
        return Cache::remember('goods', env('PRODUCT_DATA_CACHE_TIMEOUT'), function () {

            $url = env('DOING_GOOD_PRODUCTS_ENDPOINT');

            $response = Http::get($url);
            $responseBody = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

            return $this->convertDoingGoodApiResponseToItems($responseBody['data']);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @throws JsonException
     */
    public function wishlist()
    {
        $items = $this->fetchSneakerItems();
        $items = array_merge($items, $this->fetchDoingGoodsItems());

        $items = $this->collectWishedItems($items);

        return view('wishlist.wishlist', compact('items'));
    }

    /**
     * Enrich the item data with wishlisted products. For now there is only
     * one wishlist, but it can be made user specific
     *
     * @param array $items
     * @return array
     */
    public function setWishListStatus(array $items): array
    {
        $wishlist = WishlistedItem::all();

        foreach ($wishlist as $wish) {

            if (isset($items[$wish->wished_item])) {

                $items[$wish->wished_item]->wished = true;
            }
            else {

                // it is on the wishlist, but not available via the api anymore.
                // remove it from the wishlist
                WishlistedItem::where('wished_item', $wish->wished_item)->delete();
            }
        }

        return $items;
    }

    /**
     * Collect the data on the wished items
     *
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
            else {

                // It is on the wishlist, but not available via the api anymore:
                // remove it from the wishlist.
                WishlistedItem::where('wished_item', $wish->wished_item)->delete();
            }
        }

        return $wished_items;
    }
}
