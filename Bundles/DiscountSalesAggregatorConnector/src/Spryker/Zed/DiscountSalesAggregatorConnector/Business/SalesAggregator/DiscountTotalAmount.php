<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountSalesAggregatorConnector\Business\SalesAggregator;

use Generated\Shared\Transfer\OrderTransfer;

class DiscountTotalAmount implements OrderAmountAggregatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $this->assertDiscountTotalRequirements($orderTransfer);

        $totalDiscountAmount = $this->getSumTotalGrossDiscountAmount($orderTransfer);
        $orderTransfer->getTotals()->setDiscountTotal($totalDiscountAmount);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getSumTotalGrossDiscountAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = $this->getItemDiscountTotalAmount($orderTransfer);
        $totalSumGrossDiscountAmount += $this->getExpenseDiscountTotalAmount($orderTransfer);

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \ArrayObject $calculatedDiscounts
     *
     * @return int
     */
    protected function getCalculatedDiscountSumGrossAmount(\ArrayObject $calculatedDiscounts)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($calculatedDiscounts as $calculatedDiscountTransfer) {
            $totalSumGrossDiscountAmount += $calculatedDiscountTransfer->getSumGrossAmount();
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getItemDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {

            $itemSumGrossAmount = $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());
            if ($itemSumGrossAmount > $itemTransfer->getSumGrossPrice()) {
                $itemSumGrossAmount = $itemTransfer->getSumGrossPrice();
            }

            $totalSumGrossDiscountAmount += $itemSumGrossAmount;
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getExpenseDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {

            $sumGrossDiscountAmount = $this->getCalculatedDiscountSumGrossAmount($expenseTransfer->getCalculatedDiscounts());
            if ($sumGrossDiscountAmount > $expenseTransfer->getSumGrossPrice()) {
                $sumGrossDiscountAmount = $expenseTransfer->getSumGrossPrice();
            }

            $totalSumGrossDiscountAmount += $sumGrossDiscountAmount;
        }

        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertDiscountTotalRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
    }

}
