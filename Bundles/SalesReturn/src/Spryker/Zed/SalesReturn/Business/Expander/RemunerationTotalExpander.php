<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;

class RemunerationTotalExpander implements RemunerationTotalExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandTotalsWithRemunerationTotal(OrderTransfer $orderTransfer): OrderTransfer
    {
        $orderTransfer->requireTotals();

        $remunerationTotal = $this->calculateItemRemunerationTotal($orderTransfer);

        $orderTransfer->getTotals()
            ->setRemunerationTotal($remunerationTotal);

        return $orderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculateItemRemunerationTotal(OrderTransfer $orderTransfer): int
    {
        $remunerationTotal = 0;

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $remunerationTotal += $itemTransfer->getRemunerationAmount();
        }

        return $remunerationTotal;
    }
}
