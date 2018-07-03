<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Aggregator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class ItemSubtotalAggregator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateSumAggregationForItems($calculableObjectTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateProductOptionTotalSumPrice(ItemTransfer $itemTransfer)
    {
        $productOptionSumPrice = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionSumPrice += $productOptionTransfer->getSumPrice();
        }

        return $productOptionSumPrice;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateProductOptionTotalUnitPrice(ItemTransfer $itemTransfer)
    {
        $productOptionUnitPrice = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionUnitPrice += $productOptionTransfer->getUnitPrice();
        }

        return $productOptionUnitPrice;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateSumAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->sanitizePrices($itemTransfer);
            $productOptionUnitPrice = $this->calculateProductOptionTotalUnitPrice($itemTransfer);
            $itemTransfer->setUnitSubtotalAggregation($itemTransfer->getUnitPrice() + $productOptionUnitPrice);

            $productOptionSumPrice = $this->calculateProductOptionTotalSumPrice($itemTransfer);
            $itemTransfer->setSumSubtotalAggregation($itemTransfer->getSumPrice() + $productOptionSumPrice);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function sanitizePrices($itemTransfer)
    {
        if ($itemTransfer->getSumPrice() === null) {
            $itemTransfer->setSumPrice(0);
        }

        if ($itemTransfer->getUnitPrice() === null) {
            $itemTransfer->setUnitPrice(0);
        }
    }
}
