<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\OrderTransfer;

class GrandTotalWithDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertGrandTotalWithDiscountsRequirements($orderTransfer);

        $grandTotal = $orderTransfer->getTotals()->getGrandTotal();
        $totalDiscountAmount = $orderTransfer->getTotals()->getDiscountTotal();

        $orderTransfer->getTotals()->setGrandTotal($grandTotal - $totalDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertGrandTotalWithDiscountsRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
        $orderTransfer->getTotals()->requireGrandTotal();
    }

}
