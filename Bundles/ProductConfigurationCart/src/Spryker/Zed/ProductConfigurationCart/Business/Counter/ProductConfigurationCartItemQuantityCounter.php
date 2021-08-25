<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Counter;

use ArrayObject;
use Generated\Shared\Transfer\CartItemQuantityTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface;

class ProductConfigurationCartItemQuantityCounter implements ProductConfigurationCartItemQuantityCounterInterface
{
    protected const DEFAULT_ITEM_QUANTITY = 0;

    /**
     * @var \Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface
     */
    protected $itemComparator;

    /**
     * @param \Spryker\Zed\ProductConfigurationCart\Business\Comparator\ItemComparatorInterface $itemComparator
     */
    public function __construct(ItemComparatorInterface $itemComparator)
    {
        $this->itemComparator = $itemComparator;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemsInCart
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartItemQuantityTransfer
     */
    public function countCartItemQuantity(
        ArrayObject $itemsInCart,
        ItemTransfer $itemTransfer
    ): CartItemQuantityTransfer {
        $currentItemQuantity = static::DEFAULT_ITEM_QUANTITY;

        foreach ($itemsInCart as $itemInCartTransfer) {
            if (!$this->itemComparator->isSameItem($itemInCartTransfer, $itemTransfer)) {
                continue;
            }

            $currentItemQuantity += $itemInCartTransfer->getQuantity() ?? static::DEFAULT_ITEM_QUANTITY;
        }

        return (new CartItemQuantityTransfer())->setQuantity($currentItemQuantity);
    }
}
