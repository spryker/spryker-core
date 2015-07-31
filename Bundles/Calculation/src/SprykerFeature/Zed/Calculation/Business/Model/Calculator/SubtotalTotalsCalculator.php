<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Calculation\ProductOptionInterface;
use Generated\Shared\Sales\OrderItemsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalTotalsCalculator implements
    TotalsCalculatorPluginInterface,
    SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    ) {
        $subtotal = $this->calculateSubtotal($calculableItems);
        $totalsTransfer->setSubtotal($subtotal);
    }

    /**
     * @param $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal($calculableItems)
    {
        $subtotal = 0;

        if ($calculableItems instanceof OrderItemsInterface) {
            $calculableItems = $calculableItems->getOrderItems();
        }

        foreach ($calculableItems as $item) {
            for ($i = 0; $i < $item->getQuantity(); $i++) {
                $subtotal += $item->getGrossPrice();
                $subtotal += $this->sumOptions($item->getProductOptions());
                $subtotal += $this->sumExpenses($item->getExpenses());
            }
        }

        return $subtotal;
    }

    /**
     * @param \ArrayObject|ProductOptionInterface[] $options
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
     * @param \ArrayObject|ExpenseInterface[] $expenses
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
