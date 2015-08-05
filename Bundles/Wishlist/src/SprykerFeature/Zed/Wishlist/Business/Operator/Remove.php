<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Operator;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;

class Remove extends AbstractOperator
{
    const OPERATION_NAME = 'REMOVE';

    /**
     * @param WishlistChangeInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    protected function applyOperation(WishlistChangeInterface $wishlistItem)
    {
        return $this->storage->removeItems($wishlistItem);
    }

    /**
     * @return string
     */
    protected function getOperatorName()
    {
        return self::OPERATION_NAME;
    }
}
