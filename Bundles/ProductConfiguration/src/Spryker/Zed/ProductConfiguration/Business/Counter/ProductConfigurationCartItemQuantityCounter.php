<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Counter;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductConfigurationCartItemQuantityCounter implements ProductConfigurationCartItemQuantityCounterInterface
{
    protected const DEFAULT_ITEM_QUANTITY = 0;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer
    {
        $cartItemQuantityTransfer = (new CartItemQuantityTransfer())->setQuantity(static::DEFAULT_ITEM_QUANTITY);

        foreach ($itemsInCart as $itemInCartTransfer) {
            if (!$this->isSameProductConfigurationItem($itemInCartTransfer, $itemTransfer)) {
                continue;
            }

            return $cartItemQuantityTransfer->setQuantity(
                $itemInCartTransfer->getQuantity() ?? static::DEFAULT_ITEM_QUANTITY
            );
        }

        return $cartItemQuantityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isSameProductConfigurationItem(ItemTransfer $itemInCartTransfer, ItemTransfer $itemTransfer): bool
    {
        return $itemInCartTransfer->getSku() === $itemTransfer->getSku() &&
            $itemInCartTransfer->getProductConfigurationInstance() == $itemTransfer->getProductConfigurationInstance();
    }
}
