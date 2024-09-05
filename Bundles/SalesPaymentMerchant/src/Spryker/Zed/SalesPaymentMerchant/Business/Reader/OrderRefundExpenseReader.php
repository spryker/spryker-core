<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;

class OrderRefundExpenseReader extends AbstractOrderExpenseReader
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    public function getOrderExpensesForTransfer(
        OrderTransfer $orderTransfer,
        array $orderItemPaymentTransmissionItemTransfers
    ): array {
        $orderExpensePaymentTransmissionItemTransfers = [];
        $merchantReferenceMapForUnRefusedItems = $this->getMerchantReferenceMapForUnRefusedItems($orderTransfer, $orderItemPaymentTransmissionItemTransfers);
        $expenseTransfers = $this->filterOutNonApplicableMerchantExpenses($orderTransfer, $orderItemPaymentTransmissionItemTransfers);

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($this->isExpenseForOrderWithUnrefusedItems($expenseTransfer, $merchantReferenceMapForUnRefusedItems)) {
                continue;
            }

            $orderExpensePaymentTransmissionItemTransfers[] = $this->createOrderExpensePaymentTransmissionItemTransfer(
                $expenseTransfer,
                $orderTransfer,
                $this->getReverseAmount($expenseTransfer),
            );
        }

        return $orderExpensePaymentTransmissionItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return array<string, string>
     */
    protected function getMerchantReferenceMapForUnRefusedItems(
        OrderTransfer $orderTransfer,
        array $orderItemPaymentTransmissionItemTransfers
    ): array {
        $merchantReferenceMap = [];
        $itemRefusedStateMap = array_combine(
            $this->salesPaymentMerchantConfig->getItemRefusedStates(),
            $this->salesPaymentMerchantConfig->getItemRefusedStates(),
        );

        $orderItemReferenceMap = $this->extractOrderItemReferenceMap($orderItemPaymentTransmissionItemTransfers);
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            if ($this->isItemInReversePayoutChangeState($orderItemReferenceMap, $itemTransfer)) {
                continue;
            }

            if ($this->isItemInRefusedState($itemTransfer, $itemRefusedStateMap)) {
                continue;
            }

            $merchantReference = $itemTransfer->getMerchantReferenceOrFail();
            $merchantReferenceMap[$merchantReference] = $merchantReference;
        }

        return $merchantReferenceMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, string> $itemRefusedStateMap
     *
     * @return bool
     */
    protected function isItemInRefusedState(ItemTransfer $itemTransfer, array $itemRefusedStateMap): bool
    {
        $itemStateName = $itemTransfer->getStateOrFail()->getNameOrFail();

        return isset($itemRefusedStateMap[$itemStateName]);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param array<string, string> $merchantReferenceMapForUnRefusedItems
     *
     * @return bool
     */
    protected function isExpenseForOrderWithUnrefusedItems(
        ExpenseTransfer $expenseTransfer,
        array $merchantReferenceMapForUnRefusedItems
    ): bool {
        return isset($merchantReferenceMapForUnRefusedItems[$expenseTransfer->getMerchantReferenceOrFail()]);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return array<string, string>
     */
    protected function extractOrderItemReferenceMap(array $orderItemPaymentTransmissionItemTransfers): array
    {
        $orderItemReferenceMap = [];
        foreach ($orderItemPaymentTransmissionItemTransfers as $orderItemPaymentTransmissionItemTransfer) {
            $itemReference = $orderItemPaymentTransmissionItemTransfer->getItemReferenceOrFail();
            $orderItemReferenceMap[$itemReference] = $itemReference;
        }

        return $orderItemReferenceMap;
    }

    /**
     * @param array<string, string> $orderItemReferenceMap
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemInReversePayoutChangeState(array $orderItemReferenceMap, mixed $itemTransfer): bool
    {
        return isset($orderItemReferenceMap[$itemTransfer->getOrderItemReference()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     *
     * @return int
     */
    protected function getReverseAmount(ExpenseTransfer $expenseTransfer): int
    {
        $reverseAmount = $expenseTransfer->getRefundableAmount() !== 0 ? $expenseTransfer->getRefundableAmount() : $expenseTransfer->getCanceledAmountOrFail();

        return $reverseAmount * -1;
    }
}
