<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Wishlist\Business\Operator;

use Generated\Shared\Transfer\WishlistChangeTransfer;

class Add extends AbstractOperator
{

    const OPERATION_NAME = 'ADD';

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistItem
     *
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    protected function applyOperation(WishlistChangeTransfer $wishlistItem)
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
