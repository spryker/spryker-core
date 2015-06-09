<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface ExpenseTotalsCalculatorInterface
{
    /**
     * @param TotalsInterface $totalsTransfer
     * @ param \ArrayObject $totalsTransfer
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        //\ArrayObject $totalsTransfer,
        //OrderInterface $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems
    );

    /**
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     *
     * @return int
     */
    public function calculateExpenseTotal(CalculableInterface $calculableContainer);
    //public function calculateExpenseTotal(OrderInterface $calculableContainer);
}
