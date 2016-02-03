<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Business\Model\OrderAmountAggregator;

use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderDiscounts implements OrderAmountAggregatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function aggregate(OrderTransfer $orderTransfer)
    {
        $orderCalculatedDiscounts = $this->getOrderCalculatedDiscounts($orderTransfer);
        $orderTransfer->setCalculatedDiscounts($orderCalculatedDiscounts);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject $orderCalculatedDiscounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getOrderExpenseCalculatedDiscounts(
        OrderTransfer $orderTransfer,
        \ArrayObject $orderCalculatedDiscounts
    ) {
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $orderCalculatedDiscounts = $this->sumCalculatedDiscounts(
                $orderCalculatedDiscounts,
                $expenseTransfer->getCalculatedDiscounts()
            );
        }

        return $orderCalculatedDiscounts;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \ArrayObject $orderCalculatedDiscounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getOrderItemCalculatedDiscounts(
        OrderTransfer $orderTransfer,
        \ArrayObject $orderCalculatedDiscounts
    ) {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderCalculatedDiscounts = $this->sumCalculatedDiscounts(
                $orderCalculatedDiscounts,
                $itemTransfer->getCalculatedDiscounts()
            );
        }

        return $orderCalculatedDiscounts;
    }


    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $orderCalculatedDiscounts
     * @param \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[] $calculatedDiscounts
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    public function sumCalculatedDiscounts(\ArrayObject $orderCalculatedDiscounts, \ArrayObject $calculatedDiscounts)
    {
        foreach ($calculatedDiscounts as $calculatedDiscountDiscountTransfer) {
            $displayName = $calculatedDiscountDiscountTransfer->getDisplayName();
            if ($orderCalculatedDiscounts->offsetExists($displayName) === false) {
                $orderCalculatedDiscounts[$displayName] = clone $calculatedDiscountDiscountTransfer;
                continue;
            }

            $orderCalculatedDiscountTransfer = $orderCalculatedDiscounts[$displayName];
            $orderCalculatedDiscountTransfer->setUnitGrossAmount(
                $orderCalculatedDiscountTransfer->getUnitGrossAmount() + $calculatedDiscountDiscountTransfer->getUnitGrossAmount()
            );
            $orderCalculatedDiscountTransfer->setSumGrossAmount(
                $orderCalculatedDiscountTransfer->getSumGrossAmount() + $calculatedDiscountDiscountTransfer->getSumGrossAmount()
            );

            $orderCalculatedDiscounts[$displayName] = $orderCalculatedDiscountTransfer;
        }

        return $orderCalculatedDiscounts;

    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CalculatedDiscountTransfer[]
     */
    protected function getOrderCalculatedDiscounts(OrderTransfer $orderTransfer)
    {
        $orderCalculatedDiscounts = new \ArrayObject();
        $orderCalculatedDiscounts = $this->getOrderItemCalculatedDiscounts($orderTransfer, $orderCalculatedDiscounts);
        $orderCalculatedDiscounts = $this->getOrderExpenseCalculatedDiscounts(
            $orderTransfer,
            $orderCalculatedDiscounts
        );

        return $orderCalculatedDiscounts;
    }
}
