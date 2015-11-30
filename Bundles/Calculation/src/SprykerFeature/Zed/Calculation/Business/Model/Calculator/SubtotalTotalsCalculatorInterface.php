<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;

interface SubtotalTotalsCalculatorInterface
{

    /**
     * @param TotalsTransfer $totalsTransfer
     * @param CalculableInterface $calculableContainer
     * @param $calculableItems
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

    /**
     * @param $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal($calculableItems);

}
