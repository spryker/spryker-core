<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\Counter;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class ProductOfferCartItemQuantityCounter implements ProductOfferCartItemQuantityCounterInterface
{
    /**
     * @var int
     */
    protected const DEFAULT_ITEM_QUANTITY = 0;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(ArrayObject $itemsInCart, ItemTransfer $itemTransfer): CartItemQuantityTransfer
    {
        $cartItemQuantityTransfer = (new CartItemQuantityTransfer())
            ->setQuantity(static::DEFAULT_ITEM_QUANTITY);

        foreach ($itemsInCart as $itemInCartTransfer) {
            if (!$this->isSameProductOffer($itemTransfer, $itemInCartTransfer)) {
                continue;
            }

            $itemQuantity = $cartItemQuantityTransfer->getQuantity();
            $itemQuantity += $itemInCartTransfer->getQuantity();

            $cartItemQuantityTransfer->setQuantity($itemQuantity);
        }

        return $cartItemQuantityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemInCartTransfer
     *
     * @return bool
     */
    protected function isSameProductOffer(ItemTransfer $itemTransfer, ItemTransfer $itemInCartTransfer): bool
    {
        if ($itemInCartTransfer->getSkuOrFail() !== $itemTransfer->getSkuOrFail()) {
            return false;
        }

        if ($itemInCartTransfer->getProductOfferReference() !== null || $itemTransfer->getProductOfferReference() !== null) {
            return $itemTransfer->getProductOfferReference() === $itemInCartTransfer->getProductOfferReference();
        }

        return $itemTransfer->getMerchantReference() === $itemInCartTransfer->getMerchantReference();
    }
}
