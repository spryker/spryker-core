<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Calculation\Business\Model\Calculator;

use Generated\Shared\Transfer\TotalsTransfer;
use Spryker\Zed\Calculation\Business\Model\CalculableInterface;

interface SubtotalTotalsCalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\TotalsTransfer $totalsTransfer
     * @param \Spryker\Zed\Calculation\Business\Model\CalculableInterface $calculableContainer
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     */
    public function recalculateTotals(
        TotalsTransfer $totalsTransfer,
        CalculableInterface $calculableContainer,
        $calculableItems
    );

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\OrderItemsTransfer|\Generated\Shared\Transfer\ItemTransfer[] $calculableItems
     *
     * @return int
     */
    public function calculateSubtotal($calculableItems);

}
