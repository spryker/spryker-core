<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemInterfaceTransfer;

/**
 * Class SubtotalWithoutItemExpensesTotalsCalculator
 * @package SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
class SubtotalWithoutItemExpensesTotalsCalculator extends AbstractCalculator implements
    TotalsCalculatorPluginInterface
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
        $expense = $this->calculateSubtotalWithoutItemExpense($calculableItems);
        $totalsTransfer->setSubtotalWithoutItemExpenses($expense);
    }

    /**
     * @param CalculableItemCollectionInterface|CalculableItemInterface[] $calculableItems
     * @return int
     */
    protected function calculateSubtotalWithoutItemExpense(CalculableItemCollectionInterface $calculableItems)
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
