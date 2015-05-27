<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\ExpenseInterface;
use Generated\Shared\Calculation\ExpensesInterface;
use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Calculation\OrderItemOptionInterface;
use Generated\Shared\Sales\OrderItemsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalTotalsCalculator implements
    TotalsCalculatorPluginInterface,
    SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $subtotal = $this->calculateSubtotal($calculableItems);
        $totalsTransfer->setSubtotal($subtotal);
    }

    /**
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal(\ArrayObject $calculableItems)
    {
        $subtotal = 0;

        if ($calculableItems instanceof OrderItemsInterface) {
            $calculableItems = $calculableItems->getOrderItems();
        }

        foreach ($calculableItems as $item) {
            $subtotal += $item->getGrossPrice();
            $subtotal += $this->sumOptions($item->getOptions());
            $subtotal += $this->sumExpenses($item->getExpenses());
        }

        return $subtotal;
    }

    /**
     * @param \ArrayObject|OrderItemOptionInterface[] $options
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
