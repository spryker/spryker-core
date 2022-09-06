<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Calculator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPayment\SalesPaymentConfig;

class RefundAmountCalculator implements RefundAmountCalculatorInterface
{
    /**
     * @var \Spryker\Zed\SalesPayment\SalesPaymentConfig
     */
    protected $salesPaymentConfig;

    /**
     * @param \Spryker\Zed\SalesPayment\SalesPaymentConfig $salesPaymentConfig
     */
    public function __construct(SalesPaymentConfig $salesPaymentConfig)
    {
        $this->salesPaymentConfig = $salesPaymentConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return int
     */
    public function getRefundAmount(OrderTransfer $orderTransfer, array $orderItemIds): int
    {
        $refundAmount = $this->getItemRefundAmount($orderTransfer, $orderItemIds);
        $expensesRefundAmount = $this->getExpensesRefundAmount($orderTransfer, $orderItemIds);

        return $refundAmount + $expensesRefundAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return int
     */
    protected function getItemRefundAmount(OrderTransfer $orderTransfer, array $orderItemIds): int
    {
        $refundAmount = 0;

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $orderItemIds, true)) {
                foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                    $refundAmount += $productOptionTransfer->getRefundableAmount();
                }

                $refundAmount += $itemTransfer->getRefundableAmount();
            }
        }

        return $refundAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return int
     */
    protected function getExpensesRefundAmount(OrderTransfer $orderTransfer, array $orderItemIds): int
    {
        $refundAmount = 0;

        if ($this->hasUnreimbursedItemBeenLeft($orderTransfer, $orderItemIds)) {
            return $refundAmount;
        }

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $refundAmount += $expenseTransfer->getRefundableAmount();
        }

        return $refundAmount;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return bool
     */
    protected function hasUnreimbursedItemBeenLeft(OrderTransfer $orderTransfer, array $orderItemIds): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $orderItemIds, true)) {
                continue;
            }

            if (!in_array($itemTransfer->getStateOrFail()->getName(), $this->salesPaymentConfig->getItemRefusedStates(), true)) {
                return true;
            }
        }

        return false;
    }
}
