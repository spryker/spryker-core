<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\Collector;

use ArrayObject;
use Generated\Shared\Transfer\DiscountableItemTransfer;
use Generated\Shared\Transfer\ItemTransfer;

class BaseCollector
{
    /**
     * @param int $unitPrice
     * @param float $quantity
     * @param \ArrayObject $originalItemCalculatedDiscounts
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(
        $unitPrice,
        $quantity,
        ArrayObject $originalItemCalculatedDiscounts
    ) {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitPrice($unitPrice);
        $discountableItemTransfer->setQuantity($quantity);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($originalItemCalculatedDiscounts);

        return $discountableItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemForItemTransfer(ItemTransfer $itemTransfer)
    {
        $discountableItemTransfer = $this->createDiscountableItemTransfer(
            $itemTransfer->getUnitPrice(),
            $itemTransfer->getQuantity(),
            $itemTransfer->getCalculatedDiscounts()
        );
        $discountableItemTransfer->setOriginalItem($itemTransfer);

        return $discountableItemTransfer;
    }
}
