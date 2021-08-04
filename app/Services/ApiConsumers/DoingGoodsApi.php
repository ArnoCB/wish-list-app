<?php
namespace App\Services\ApiConsumers;

use App\Interfaces\ShopItemsInterface;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class DoingGoodsApi implements ShopItemsInterface
{
    /**
     * Call the Doing Goods API to get the products on offer.
     *
     * @return array
     */
    public function fetchItems(): array
    {
        return Cache::remember('goods', env('PRODUCT_DATA_CACHE_TIMEOUT'), function () {

            $url = env('DOING_GOOD_PRODUCTS_ENDPOINT');

            $response = Http::get($url);
            $responseBody = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

            return $this->convertApiResponseToItems($responseBody['data']);
        });
    }

    /**
     * Convert the API response into standard readable items, so
     * the frontend can know what to expect
     *
     * @param array $responseBody
     * @return array
     */
    private function convertApiResponseToItems(array $responseBody): array
    {
        $items_list = [];

        foreach ($responseBody as $response) {

            $price = number_format((float)$response['price'], 2,
                ',', '');

            $item = new Item;
            $item->id = $response['id'];
            $item->api = 'goods';
            $item->image = $response['images'][0]['src'];
            $item->name = $response['name'];
            $item->description = false;
            $item->price = "&euro; " . $price;

            $item->wished = false;
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }
}
