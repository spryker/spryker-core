<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class DiscountTotalAmount implements OrderAmountAggregatorInterface
{

    /**
     * @param OrderTransfer $orderTransfer
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
     * @param OrderTransfer $orderTransfer
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
     * @param \ArrayObject|CalculatedDiscountTransfer[] $calculatedDiscounts
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
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getItemDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $totalSumGrossDiscountAmount += $this->getCalculatedDiscountSumGrossAmount($itemTransfer->getCalculatedDiscounts());
        }
        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getExpenseDiscountTotalAmount(OrderTransfer $orderTransfer)
    {
        $totalSumGrossDiscountAmount = 0 ;
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $totalSumGrossDiscountAmount += $this->getCalculatedDiscountSumGrossAmount($expenseTransfer->getCalculatedDiscounts());
        }
        return $totalSumGrossDiscountAmount;
    }

    /**
     * @param OrderTransfer $orderTransfer
     *
     * @return void
     */
    protected function assertDiscountTotalRequirements(OrderTransfer $orderTransfer)
    {
        $orderTransfer->requireTotals();
    }
}
