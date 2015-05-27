<?php

namespace SprykerFeature\Zed\DiscountCalculationConnector\Dependency\Facade;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;

interface DiscountCalculationToCalculationInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param OrderInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateGrandTotalTotals(
        TotalsInterface $totalsTransfer,
        OrderInterface $calculableContainer,
        \ArrayObject $calculableItems
    );
}
