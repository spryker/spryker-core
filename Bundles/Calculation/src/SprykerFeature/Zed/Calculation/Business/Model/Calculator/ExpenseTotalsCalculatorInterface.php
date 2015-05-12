<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\CalculableContainerInterface;
use SprykerFeature\Shared\Calculation\Dependency\Transfer\TotalsInterface;

interface ExpenseTotalsCalculatorInterface
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
     * @param CalculableContainerInterface $calculableContainer
     * @return int
     */
    public function calculateExpenseTotal(CalculableContainerInterface $calculableContainer);
}
