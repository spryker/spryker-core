<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business\Operator;

use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;

class Decrease extends AbstractOperator
{

    const OPERATION_NAME = 'DECREASE';

    /**
     * @param WishlistChangeTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    protected function applyOperation(WishlistChangeTransfer $wishlistItem)
    {
        return $this->storage->decreaseItems($wishlistItem);
    }

    /**
     * @return string
     */
    protected function getOperatorName()
    {
        return self::OPERATION_NAME;
    }

}
