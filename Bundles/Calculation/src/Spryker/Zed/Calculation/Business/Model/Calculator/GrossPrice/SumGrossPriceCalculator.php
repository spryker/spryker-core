<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator\GrossPrice;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\Calculation\Business\Model\Calculator\CalculatorInterface;

class SumGrossPriceCalculator implements CalculatorInterface
{
    /**
     * For already ordered entities, sum prices are acting as source of truth.
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->calculateItemGrossAmountForItems($calculableObjectTransfer->getItems());
        $this->calculateSumGrossPriceForExpenses($calculableObjectTransfer->getExpenses());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return void
     */
    protected function calculateSumGrossPriceForExpenses(ArrayObject $expenses)
    {
        foreach ($expenses as $expenseTransfer) {
            if ($expenseTransfer->getIsOrdered() === true) {
                continue;
            }

            $expenseTransfer->setSumGrossPrice($expenseTransfer->getUnitGrossPrice() * $expenseTransfer->getQuantity());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addCalculatedItemGrossAmounts(ItemTransfer $itemTransfer)
    {
        $this->assertItemRequirements($itemTransfer);

        if ($itemTransfer->getIsOrdered() === true) {
            return;
        }

        $itemTransfer->setSumGrossPrice((int)($itemTransfer->getUnitGrossPrice() * $itemTransfer->getQuantity()));
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer)
    {
        $itemTransfer->requireQuantity();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return void
     */
    protected function assertProductOptionPriceCalculationRequirements(ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer->requireQuantity();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return void
     */
    protected function calculateItemGrossAmountForItems(ArrayObject $items)
    {
        foreach ($items as $itemTransfer) {
            $this->addCalculatedItemGrossAmounts($itemTransfer);
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->assertProductOptionPriceCalculationRequirements($productOptionTransfer);

                if ($productOptionTransfer->getIsOrdered() === true) {
                    continue;
                }

                $productOptionTransfer->setSumGrossPrice($productOptionTransfer->getUnitGrossPrice() * $productOptionTransfer->getQuantity());
            }
        }
    }
}
