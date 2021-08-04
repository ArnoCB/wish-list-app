<?php

namespace App\Services;

use App\Models\WishlistedItem;
use Illuminate\Http\Request;
use JsonException;

class WishlistService
{
    /**
     * Collect the data on the wished items
     *
     * @param array $items
     * @return array
     */
    public function collectWishedItems(array $items): array
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

    /**
     * @return false|string
     * @throws JsonException
     */
    public function wishlistCount()
    {
        return json_encode(['wishlisted_number' => WishlistedItem::count()], JSON_THROW_ON_ERROR);
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
}
