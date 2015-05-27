<?php

namespace SprykerFeature\Zed\Calculation\Dependency\Plugin;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;

interface TotalsCalculatorPluginInterface
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
}
