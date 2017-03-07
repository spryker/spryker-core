<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesAggregator\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;

class OrderTaxAmount implements OrderAmountAggregatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertOrderTaxAmountRequirements($orderTransfer);

        $totalTaxAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalTaxAmount += $itemTransfer->getSumTaxAmount();
        }

        $taxTotalTransfer = new TaxTotalTransfer();
        $taxTotalTransfer->setAmount((int)round($totalTaxAmount));

        $totalsTransfer = $orderTransfer->getTotals();
        $totalsTransfer->setTaxTotal($taxTotalTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertOrderTaxAmountRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
    }

}
