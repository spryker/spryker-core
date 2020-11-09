<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Counter;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductConfigurationItemQuantityCounter implements ProductConfigurationItemQuantityCounterInterface
{
    protected const DEFAULT_ITEM_QUANTITY = 0;

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     */
    protected const OPERATION_REMOVE = 'remove';

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countItemQuantity(
        CartChangeTransfer $cartChangeTransfer,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer {
        $currentItemQuantity = static::DEFAULT_ITEM_QUANTITY;
        $quoteItems = $cartChangeTransfer->getQuote()->getItems();
        $cartChangeItemsTransfer = $cartChangeTransfer->getItems();

        foreach ($quoteItems as $quoteItemTransfer) {
            if ($this->isSameProductConfigurationItem($quoteItemTransfer, $itemTransfer)) {
                $currentItemQuantity += $quoteItemTransfer->getQuantity();
            }
        }

        foreach ($cartChangeItemsTransfer as $cartChangeItemTransfer) {
            if ($this->isSameProductConfigurationItem($cartChangeItemTransfer, $itemTransfer)) {
                if ($cartChangeTransfer->getOperation() === static::OPERATION_REMOVE) {
                    $currentItemQuantity -= $cartChangeItemTransfer->getQuantity();

                    continue;
                }

                $currentItemQuantity += $cartChangeItemTransfer->getQuantity();
            }
        }

        return (new CartItemQuantityTransfer())->setQuantity($currentItemQuantity);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isSameProductConfigurationItem(
        ItemTransfer $itemInCartTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        return $itemInCartTransfer->getSku() === $itemTransfer->getSku()
            && $itemInCartTransfer->getProductConfigurationInstance() == $itemTransfer->getProductConfigurationInstance();
    }
}
