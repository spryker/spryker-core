<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableItemInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalWithoutItemExpensesTotalsCalculator implements
    TotalsCalculatorPluginInterface
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
        $expense = $this->calculateSubtotalWithoutItemExpense($calculableItems);
        $totalsTransfer->setSubtotalWithoutItemExpenses($expense);
    }

    /**
     * @param \ArrayObject|CalculableItemInterface[] $calculableItems
     *
     * @return int
     */
    protected function calculateSubtotalWithoutItemExpense(\ArrayObject $calculableItems)
    {
        $subtotal = 0;
        foreach ($calculableItems as $item) {
            $subtotal += $item->getGrossPrice();
            $subtotal += $this->sumOptions($item);
        }

        return $subtotal;
    }

    /**
     * @param CalculableItemInterface $item
     *
     * @return int
     */
    protected function sumOptions(CalculableItemInterface $item)
    {
        $optionsPrice = 0;
        foreach ($item->getOptions() as $option) {
            $optionsPrice += $option->getGrossPrice();
        }

        return $optionsPrice;
    }
}
