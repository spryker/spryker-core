<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Operator;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;

class Add extends AbstractOperator
{
    const OPERATION_NAME = 'ADD';

    /**
     * @param WishlistChangeInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    protected function applyOperation(WishlistChangeInterface $wishlistItem)
    {
         return $this->storage->addItems($wishlistItem);
    }

    /**
     * @return string
     */
    protected function getOperatorName()
    {
        return self::OPERATION_NAME;
    }
}
