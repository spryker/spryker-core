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
     * @param int $grossPrice
     * @param int $quantity
     * @param \ArrayObject $originalItemCalculatedDiscounts
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemTransfer(
        $grossPrice,
        $quantity,
        ArrayObject $originalItemCalculatedDiscounts
    ) {
        $discountableItemTransfer = new DiscountableItemTransfer();
        $discountableItemTransfer->setUnitGrossPrice($grossPrice);
        $discountableItemTransfer->setQuantity($quantity);
        $discountableItemTransfer->setOriginalItemCalculatedDiscounts($originalItemCalculatedDiscounts);

        return $discountableItemTransfer;
    }

    /**
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\DiscountableItemTransfer
     */
    protected function createDiscountableItemForItemTransfer($priceMode, ItemTransfer $itemTransfer)
    {
        $discountableItemTransfer = $this->createDiscountableItemTransfer(
            $this->getPrice($itemTransfer, $priceMode),
            $itemTransfer->getQuantity(),
            $itemTransfer->getCalculatedDiscounts()
        );
        $discountableItemTransfer->setOriginalItem($itemTransfer);

        return $discountableItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === 'NET_MODE') {
            return $itemTransfer->getUnitNetPrice() + (int)round($itemTransfer->getUnitNetPrice() * $itemTransfer->getTaxRate() / 100);
        } else {
            return $itemTransfer->getUnitGrossPrice();
        }
    }

}
