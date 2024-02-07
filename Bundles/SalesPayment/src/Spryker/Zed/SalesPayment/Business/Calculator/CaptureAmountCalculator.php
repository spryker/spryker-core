<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPayment\Business\Calculator;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPayment\SalesPaymentConfig;

class CaptureAmountCalculator implements CaptureAmountCalculatorInterface
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
    public function getCaptureAmount(OrderTransfer $orderTransfer, array $orderItemIds): int
    {
        $itemCost = $this->getItemsCost($orderTransfer, $orderItemIds);
        $expensesCost = $this->getExpensesCost($orderTransfer);

        return $itemCost + $expensesCost;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array<int> $orderItemIds
     *
     * @return int
     */
    protected function getItemsCost(OrderTransfer $orderTransfer, array $orderItemIds): int
    {
        $itemsCost = 0;

        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if (in_array($itemTransfer->getIdSalesOrderItem(), $orderItemIds, true)) {
                $itemsCost += $itemTransfer->getSumPriceToPayAggregation();
            }
        }

        return $itemsCost;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function getExpensesCost(OrderTransfer $orderTransfer): int
    {
        $expensesCost = 0;

        if ($this->hasCaptureForOrderBeenRequestedAtLeastOnce($orderTransfer)) {
            return $expensesCost;
        }

        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            $expensesCost += $expenseTransfer->getSumPriceToPayAggregation();
        }

        return $expensesCost;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    protected function hasCaptureForOrderBeenRequestedAtLeastOnce(OrderTransfer $orderTransfer): bool
    {
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getStateHistory() as $itemStateTransfer) {
                // BC: This is for backward compatibility with older projects.
                $captureStatus = $this->salesPaymentConfig->getPaymentConfirmationRequestedStates();
                if (!$captureStatus) {
                    $captureStatus = $this->salesPaymentConfig->getCapturePaymentStates();
                }
                if (in_array($itemStateTransfer->getName(), $captureStatus, true)) {
                    return true;
                }
            }
        }

        return false;
    }
}
