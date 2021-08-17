<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Counter;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

interface ProductConfigurationCartItemQuantityCounterInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer;
}
