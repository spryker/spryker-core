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

class ItemDiscountAmountFullAggregator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateDiscountAmountFullAggregationForItems($calculableObjectTransfer->getItems());
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateSumProductOptionDiscountAmountAggregation(ItemTransfer $itemTransfer)
    {
        $productOptionSumDiscountAmountAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionSumDiscountAmountAggregation += $productOptionTransfer->getSumDiscountAmountAggregation();
        }

        return $productOptionSumDiscountAmountAggregation;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function calculateUnitProductOptionDiscountAmountAggregation(ItemTransfer $itemTransfer)
    {
        $productOptionUnitDiscountAmountAggregation = 0;
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            $productOptionUnitDiscountAmountAggregation += $productOptionTransfer->getUnitDiscountAmountAggregation();
        }

        return $productOptionUnitDiscountAmountAggregation;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateDiscountAmountFullAggregationForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $unitProductOptionDiscountAmountAggregation = $this->calculateUnitProductOptionDiscountAmountAggregation($itemTransfer);
            $itemTransfer->setUnitDiscountAmountFullAggregation(
                $itemTransfer->getUnitDiscountAmountAggregation() + $unitProductOptionDiscountAmountAggregation
            );

            $sumProductOptionDiscountAmountAggregation = $this->calculateSumProductOptionDiscountAmountAggregation($itemTransfer);
            $itemTransfer->setSumDiscountAmountFullAggregation(
                $itemTransfer->getSumDiscountAmountAggregation() + $sumProductOptionDiscountAmountAggregation
            );
        }
    }

}
