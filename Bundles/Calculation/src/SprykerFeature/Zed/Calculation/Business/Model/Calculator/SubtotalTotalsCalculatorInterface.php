<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;

interface SubtotalTotalsCalculatorInterface
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
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal(\ArrayObject $calculableItems);
}
