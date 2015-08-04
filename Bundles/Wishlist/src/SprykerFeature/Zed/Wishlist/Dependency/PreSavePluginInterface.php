<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Dependency;

use Generated\Shared\Wishlist\WishlistChangeInterface;

interface PreSavePluginInterface
{
    /**
     * @param WishlistChangeInterface $wishlist
     */
    public function trigger(WishlistChangeInterface $wishlist);
}
