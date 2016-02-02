<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;
use Spryker\Zed\Calculation\Dependency\Plugin\TotalsCalculatorPluginInterface;

class SubtotalWithoutItemExpensesTotalsCalculator implements
    TotalsCalculatorPluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param $calculableItems
     *
     * @return void
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
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
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function sumOptions(ItemTransfer $itemTransfer)
    {
        $optionsPrice = 0;
        foreach ($itemTransfer->getProductOptions() as $optionTransfer) {
            $optionsPrice += $optionTransfer->getGrossPrice() * $itemTransfer->getQuantity();
        }

        return $optionsPrice;
    }

}
