<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use Generated\Shared\Calculation\TotalsInterface;

interface SubtotalTotalsCalculatorInterface
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
    );

    /**
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal(\ArrayObject $calculableItems);
}
