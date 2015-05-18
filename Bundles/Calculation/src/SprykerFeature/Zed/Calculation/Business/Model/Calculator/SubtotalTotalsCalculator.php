<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\ExpenseContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\OptionContainerInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalTotalsCalculator implements
    TotalsCalculatorPluginInterface,
    SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        \ArrayObject $calculableItems
    ) {
        $subtotal = $this->calculateSubtotal($calculableItems);
        $totalsTransfer->setSubtotal($subtotal);
    }

    /**
     * @param \ArrayObject|CalculableItemInterface[] $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal(\ArrayObject $calculableItems)
    {
        $subtotal = 0;
        foreach ($calculableItems as $item) {
            $subtotal += $item->getGrossPrice();
            $subtotal += $this->sumOptions($item);
            $subtotal += $this->sumExpenses($item);
        }

        return $subtotal;
    }

    /**
     * @param OptionContainerInterface $item
     *
     * @return int
     */
    protected function sumOptions(OptionContainerInterface $item)
    {
        $optionsPrice = 0;
        foreach ($item->getOptions() as $option) {
            $optionsPrice += $option->getGrossPrice();
        }

        return $optionsPrice;
    }

    /**
     * @param ExpenseContainerInterface $item
     *
     * @return int
     */
    protected function sumExpenses(ExpenseContainerInterface $item)
    {
        $expensesPrice = 0;
        foreach ($item->getExpenses() as $expense) {
            $expensesPrice += $expense->getGrossPrice();
        }

        return $expensesPrice;
    }
}
