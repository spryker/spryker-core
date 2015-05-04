<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyExpenseContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyOptionContainerInterfaceTransfer;

class SubtotalTotalsCalculator extends AbstractCalculator implements
    TotalsCalculatorPluginInterface,
    SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableContainerInterface $calculableContainer
     * @param CalculableItemCollectionInterface $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableContainerInterface $calculableContainer,
        CalculableItemCollectionInterface $calculableItems
    ) {
        $subtotal = $this->calculateSubtotal($calculableItems);
        $totalsTransfer->setSubtotal($subtotal);
    }

    /**
     * @param CalculableItemCollectionInterface|CalculableItemInterface[] $calculableItems
     * @return int
     */
    public function calculateSubtotal(CalculableItemCollectionInterface $calculableItems)
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
