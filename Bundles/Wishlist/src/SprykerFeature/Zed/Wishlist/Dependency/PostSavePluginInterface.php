<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Dependency;

use Generated\Shared\Wishlist\WishlistInterface;

interface PostSavePluginInterface
{
    /**
     * @param ItemInterface[] $items
     */
    public function trigger(\ArrayObject $items);
}
