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

class ItemProductOptionPriceAggregator implements CalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateProductOptionPriceAggregationForItems($calculableObjectTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function aggregateSumProductOptionPrice(ItemTransfer $itemTransfer)
    {
        $productOptionSumPriceAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionSumPriceAggregation += $productOptionTransfer->getSumPrice();
        }

        return $productOptionSumPriceAggregation;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function aggregateUnitProductOptionPrice(ItemTransfer $itemTransfer)
    {
        $productOptionUnitPriceAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionUnitPriceAggregation += $productOptionTransfer->getUnitPrice();
        }

        return $productOptionUnitPriceAggregation;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateProductOptionPriceAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $itemTransfer->setSumProductOptionPriceAggregation(
                $this->aggregateSumProductOptionPrice($itemTransfer)
            );

            $itemTransfer->setUnitProductOptionPriceAggregation(
                $this->aggregateUnitProductOptionPrice($itemTransfer)
            );
        }
    }
}
