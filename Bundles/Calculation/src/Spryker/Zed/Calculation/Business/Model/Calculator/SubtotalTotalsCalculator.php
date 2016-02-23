<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\OrderItemsTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalTotalsCalculator implements
    TotalsCalculatorPluginInterface,
    SubtotalTotalsCalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $subtotal = $this->calculateSubtotal($calculableItems);
        $totalsTransfer->setSubtotal($subtotal);
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal($calculableItems)
    {
        $subtotal = 0;

        if ($calculableItems instanceof OrderItemsTransfer) {
            $calculableItems = $calculableItems->getOrderItems();
        }

        foreach ($calculableItems as $item) {
            $quantity = $item->getQuantity();
            for ($i = 0; $i < $quantity; $i++) {
                $subtotal += $item->getGrossPrice();
                $subtotal += $this->sumOptions($item->getProductOptions());
                $subtotal += $this->sumExpenses($item->getExpenses());
            }
        }

        return $subtotal;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[] $options
     *
     * @return int
     */
    protected function sumOptions(\ArrayObject $options)
    {
        $optionsPrice = 0;
        foreach ($options as $option) {
            $optionsPrice += $option->getGrossPrice();
        }

        return $optionsPrice;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ExpenseTransfer[] $expenses
     *
     * @return int
     */
    protected function sumExpenses(\ArrayObject $expenses)
    {
        $expensesPrice = 0;
        foreach ($expenses as $expense) {
            $expensesPrice += $expense->getGrossPrice();
        }

        return $expensesPrice;
    }

}
