<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Storage;

use Generated\Shared\Wishlist\WishlistInterface;

class BaseStorage
{
    /**
     * @var WishlistInterface
     */
    protected $wishlist;

    /**
     * BaseStorage constructor.
     *
     * @param WishlistInterface $wishlist
     */
    public function __construct(WishlistInterface $wishlist)
    {
        $this->wishlist = $wishlist;
    }

    /**
     * @return array
     */
    protected function createIndex()
    {
        $wishlistItem = $this->wishlist->getItems();
        $wishlistIndex = [];
        foreach ($wishlistItem as $key => $cartItem) {
            if (!empty($cartItem->getGroupKey())) {
                $wishlistIndex[$cartItem->getGroupKey()] = $key;
            }
        }

        return $wishlistIndex;
    }
}
