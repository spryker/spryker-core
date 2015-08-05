<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Operator;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;

class Increase extends AbstractOperator
{
    const OPERATION_NAME = 'INCREASE';

    /**
     * @param WishlistChangeInterface $wishlistItem
     *
     * @return WishlistInterface
     */
    protected function applyOperation(WishlistChangeInterface $wishlistItem)
    {
        return $this->storage->increaseItems($wishlistItem);
    }

    /**
     * @return string
     */
    protected function getOperatorName()
    {
        return self::OPERATION_NAME;
    }
}
