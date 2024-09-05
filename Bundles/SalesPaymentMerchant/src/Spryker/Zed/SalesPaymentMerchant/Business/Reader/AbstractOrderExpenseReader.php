<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransmissionItemTransfer;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

abstract class AbstractOrderExpenseReader implements OrderExpenseReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig $salesPaymentMerchantConfig
     */
    protected SalesPaymentMerchantConfig $salesPaymentMerchantConfig;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig $salesPaymentMerchantConfig
     */
    public function __construct(
        SalesPaymentMerchantConfig $salesPaymentMerchantConfig
    ) {
        $this->salesPaymentMerchantConfig = $salesPaymentMerchantConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param int $amount
     *
     * @return \Generated\Shared\Transfer\PaymentTransmissionItemTransfer
     */
    protected function createOrderExpensePaymentTransmissionItemTransfer(
        ExpenseTransfer $expenseTransfer,
        OrderTransfer $orderTransfer,
        int $amount
    ): PaymentTransmissionItemTransfer {
        return (new PaymentTransmissionItemTransfer())
            ->fromArray($expenseTransfer->toArray(), true)
            ->setType(SalesPaymentMerchantConfig::PAYMENT_TRANSMISSION_ITEM_TYPE_ORDER_EXPENSE)
            ->setMerchantReference($expenseTransfer->getMerchantReferenceOrFail())
            ->setOrderReference($orderTransfer->getOrderReferenceOrFail())
            ->setItemReference($expenseTransfer->getUuidOrFail())
            ->setAmount((string)$amount);
    }

    /**
     * @param string $storeName
     *
     * @return array<string, string>
     */
    protected function getExcludedExpenseTypeMap(string $storeName): array
    {
        $excludedExpenseTypesForStore = $this->salesPaymentMerchantConfig->getExcludedExpenseTypesForStore($storeName);

        return array_combine($excludedExpenseTypesForStore, $excludedExpenseTypesForStore);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return list<\Generated\Shared\Transfer\ExpenseTransfer>
     */
    protected function filterOutNonApplicableMerchantExpenses(
        OrderTransfer $orderTransfer,
        array $orderItemPaymentTransmissionItemTransfers
    ): array {
        $merchantReferenceMap = $this->extractMerchantReferenceMapFromOrderItems($orderItemPaymentTransmissionItemTransfers);
        $excludedExpenseTypeMap = $this->getExcludedExpenseTypeMap($orderTransfer->getStoreOrFail());

        $applicableExpenseTransfers = [];
        foreach ($orderTransfer->getExpenses() as $expenseTransfer) {
            if ($this->isExpenseExcluded($expenseTransfer, $excludedExpenseTypeMap)) {
                continue;
            }

            if (!$this->isExpenseRelatedToMerchantAndOrder($expenseTransfer, $merchantReferenceMap)) {
                continue;
            }

            $applicableExpenseTransfers[] = $expenseTransfer;
        }

        return $applicableExpenseTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $orderItemPaymentTransmissionItemTransfers
     *
     * @return array<string, string>
     */
    protected function extractMerchantReferenceMapFromOrderItems(array $orderItemPaymentTransmissionItemTransfers): array
    {
        $merchantReferences = [];
        foreach ($orderItemPaymentTransmissionItemTransfers as $orderItemPaymentTransmissionItemTransfer) {
            $merchantReference = $orderItemPaymentTransmissionItemTransfer->getMerchantReferenceOrFail();
            $merchantReferences[$merchantReference] = $merchantReference;
        }

        return $merchantReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param array<string, string> $excludedExpenseTypeMap
     *
     * @return bool
     */
    protected function isExpenseExcluded(ExpenseTransfer $expenseTransfer, array $excludedExpenseTypeMap): bool
    {
        return isset($excludedExpenseTypeMap[$expenseTransfer->getTypeOrFail()]);
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param array<string, string> $merchantReferenceMap
     *
     * @return bool
     */
    protected function isExpenseRelatedToMerchantAndOrder(mixed $expenseTransfer, array $merchantReferenceMap): bool
    {
        return $expenseTransfer->getMerchantReference() !== null && isset($merchantReferenceMap[$expenseTransfer->getMerchantReference()]);
    }
}
