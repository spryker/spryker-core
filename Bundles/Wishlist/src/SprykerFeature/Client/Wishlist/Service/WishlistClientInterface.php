<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Wishlist\Service;

use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Business\WishlistDependencyContainer;

/**
 * @method WishlistDependencyContainer getDependencyContainer()
 */
interface WishlistClientInterface
{

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return mixed
     */
    public function removeItem(WishlistItemInterface $wishlistItemTransfer);

    /**
     * @param WishlistItemInterface $wishlistItemTransfer
     *
     * @return mixed
     */
    public function saveItem(WishlistItemInterface $wishlistItemTransfer);

    /**
     * @return mixed
     */
    public function getWishlist();
}
