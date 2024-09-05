<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

class OrderExpenseReader extends AbstractOrderExpenseReader
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface
     */
    protected SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig $salesPaymentMerchantConfig
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader
     */
    public function __construct(
        SalesPaymentMerchantConfig $salesPaymentMerchantConfig,
        SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader
    ) {
        parent::__construct($salesPaymentMerchantConfig);
        $this->salesPaymentMerchantPayoutReader = $salesPaymentMerchantPayoutReader;
    }

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
        $expenseTransfers = $this->filterOutNonApplicableMerchantExpenses($orderTransfer, $orderItemPaymentTransmissionItemTransfers);
        $expenseItemReferences = $this->extractItemReferencesFromExpenses($expenseTransfers);

        $salesPaymentMerchantPayoutMapByItemReferencesForPaidOutExpenses = $this->salesPaymentMerchantPayoutReader
            ->getSalesPaymentMerchantPayoutMapByItemReferences(
                $orderTransfer->getOrderReferenceOrFail(),
                $expenseItemReferences,
            );

        foreach ($expenseTransfers as $expenseTransfer) {
            if ($this->isExpenseHasBeenPaidOut($expenseTransfer, $salesPaymentMerchantPayoutMapByItemReferencesForPaidOutExpenses)) {
                continue;
            }

            $orderExpensePaymentTransmissionItemTransfers[] = $this->createOrderExpensePaymentTransmissionItemTransfer(
                $expenseTransfer,
                $orderTransfer,
                $expenseTransfer->getSumPriceToPayAggregationOrFail(),
            );
        }

        return $orderExpensePaymentTransmissionItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ExpenseTransfer $expenseTransfer
     * @param array<string, \Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer> $salesPaymentMerchantPayoutMapByItemReferencesForPaidOutExpenses
     *
     * @return bool
     */
    protected function isExpenseHasBeenPaidOut(
        ExpenseTransfer $expenseTransfer,
        array $salesPaymentMerchantPayoutMapByItemReferencesForPaidOutExpenses
    ): bool {
        return isset($salesPaymentMerchantPayoutMapByItemReferencesForPaidOutExpenses[$expenseTransfer->getUuidOrFail()]);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ExpenseTransfer> $expenseTransfers
     *
     * @return list<string>
     */
    protected function extractItemReferencesFromExpenses(array $expenseTransfers): array
    {
        $itemReferences = [];
        foreach ($expenseTransfers as $expenseTransfer) {
            $itemReferences[] = $expenseTransfer->getUuidOrFail();
        }

        return $itemReferences;
    }
}
