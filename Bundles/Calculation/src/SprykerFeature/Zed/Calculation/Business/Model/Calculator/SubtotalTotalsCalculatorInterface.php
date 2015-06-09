<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\OrderInterface;
use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @ param OrderInterface $calculableContainer
     * @param CalculableInterface $calculableContainer
     * @param \ArrayObject $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        //OrderInterface $calculableContainer,
        CalculableInterface $calculableContainer,
        \ArrayObject $calculableItems
    );

    /**
     * @param \ArrayObject $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal(\ArrayObject $calculableItems);
}
