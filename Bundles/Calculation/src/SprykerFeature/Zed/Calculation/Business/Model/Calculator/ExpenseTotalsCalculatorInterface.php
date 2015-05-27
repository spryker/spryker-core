<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;

interface ExpenseTotalsCalculatorInterface
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
    );

    /**
     * @param OrderInterface $calculableContainer
     * @return int
     */
    public function calculateExpenseTotal(OrderInterface $calculableContainer);
}
