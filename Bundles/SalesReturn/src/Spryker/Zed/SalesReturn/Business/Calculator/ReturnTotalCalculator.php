<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Calculator;

use Generated\Shared\Transfer\ReturnTotalsTransfer;
use Generated\Shared\Transfer\ReturnTransfer;

class ReturnTotalCalculator implements ReturnTotalCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTotalsTransfer
     */
    public function calculateReturnTotals(ReturnTransfer $returnTransfer): ReturnTotalsTransfer
    {
        return (new ReturnTotalsTransfer())
            ->setRemunerationTotal($this->calculateItemRemunerationTotal($returnTransfer))
            ->setRefundTotal($this->calculateItemCanceledTotal($returnTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int
     */
    protected function calculateItemRemunerationTotal(ReturnTransfer $returnTransfer): int
    {
        $remunerationTotal = 0;

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $remunerationTotal += $returnItemTransfer->getOrderItemOrFail()->getRemunerationAmount();
        }

        return $remunerationTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return int
     */
    protected function calculateItemCanceledTotal(ReturnTransfer $returnTransfer): int
    {
        $canceledTotal = 0;

        foreach ($returnTransfer->getReturnItems() as $returnItemTransfer) {
            $canceledTotal += $returnItemTransfer->getOrderItemOrFail()->getCanceledAmount();
        }

        return $canceledTotal;
    }
}
