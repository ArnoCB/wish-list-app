<?php

namespace App\Services\ApiConsumers;

use App\Interfaces\ShopItemsInterface;
use App\Models\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SneakerApi implements ShopItemsInterface
{
    /**
     * Call the Sneaker District API to get the products on offer.
     *
     * @return array
     */
    public function fetchItems(): array
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
            return $this->convertApiResponseToItems($responseBody['products']);
        });
    }

    /**
     * Convert the API response into standard readable items, so
     * the frontend can know what to expect.
     *
     * @param array $responseBody
     * @return array
     */
    private function convertApiResponseToItems(array $responseBody): array
    {
        $items_list = [];

        // url where the sneaker product images can be found
        $sd_url = env('SNEAKER_IMAGES_LOCATION');

        foreach ($responseBody as $response) {

            $price = number_format((float)$response['price']['incl'], 2,
                ',', '');

            $item = new Item;
            $item->id = $response['id'];
            $item->api = 'sneaker';
            $item->image = $sd_url . $response['images']['overview'];
            $item->price =  "&euro; " . $price;
            $item->wished = false;
            $item->name = $response['brand']['title'];
            $item->description = $response['model'];
            $items_list[$item->api . '_' . $item->id] = $item;
        }

        return $items_list;
    }
}
