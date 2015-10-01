<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\TotalsInterface;
use Generated\Shared\Cart\ItemInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalWithoutItemExpensesTotalsCalculator implements
    TotalsCalculatorPluginInterface
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
        $expense = $this->calculateSubtotalWithoutItemExpense($calculableItems);
        $totalsTransfer->setSubtotalWithoutItemExpenses($expense);
    }

    /**
     * @param $calculableItems
     *
     * @return int
     */
    protected function calculateSubtotalWithoutItemExpense($calculableItems)
    {
        $subtotal = 0;
        foreach ($calculableItems as $itemTransfer) {
            $subtotal += $itemTransfer->getGrossPrice() * $itemTransfer->getQuantity();
            $subtotal += $this->sumOptions($itemTransfer);
        }

        return $subtotal;
    }

    /**
     * @param ItemInterface $itemTransfer
     *
     * @return int
     */
    protected function sumOptions(ItemInterface $itemTransfer)
    {
        $optionsPrice = 0;
        foreach ($itemTransfer->getProductOptions() as $optionTransfer) {
            $optionsPrice += $optionTransfer->getGrossPrice() * $itemTransfer->getQuantity();
        }

        return $optionsPrice;
    }

}
