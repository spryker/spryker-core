<?php

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\Calculation\DependencyTotalsInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableContainerInterfaceTransfer;
use Generated\Shared\Transfer\Calculation\DependencyCalculableItemCollectionInterfaceTransfer;

/**
 * Interface ExpenseTotalsCalculatorInterface
 * @package SprykerFeature\Zed\Calculation\Business\Model\Calculator
 */
interface ExpenseTotalsCalculatorInterface
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
    );

    /**
     * @param CalculableContainerInterface $calculableContainer
     * @return int
     */
    public function calculateExpenseTotal(CalculableContainerInterface $calculableContainer);
}
