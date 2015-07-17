<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Calculation\TotalsInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface ExpenseTotalsCalculatorInterface
{

    /**
     * @param TotalsInterface $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsInterface $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

    /**
     * @param CalculableInterface $calculableContainer
     *
     * @return int
     */
    public function calculateExpenseTotal(CalculableInterface $calculableContainer);

}
