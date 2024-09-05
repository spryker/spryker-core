<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaymentTransmissionResponseTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\AbstractMerchantTransfer;

class MerchantPayout extends AbstractMerchantTransfer implements MerchantPayoutInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function payoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void
    {
        $transferEndpointUrl = $this->transferEndpointReader->getTransferEndpointUrl($orderTransfer);
        if (!$transferEndpointUrl) {
            return;
        }

        $orderItemPaymentTransmissionItemTransfers = $this->getOrderItemsForTransfer($salesOrderItemTransfers, $orderTransfer);
        if (count($orderItemPaymentTransmissionItemTransfers) === 0) {
            return;
        }

        $this->executePayoutTransmissionTransaction($orderItemPaymentTransmissionItemTransfers, $transferEndpointUrl);

        if (!$this->salesPaymentMerchantConfig->isOrderExpenseIncludedInPaymentProcess()) {
            return;
        }

        $orderExpensePaymentTransmissionItemTransfers = $this->orderExpenseReader->getOrderExpensesForTransfer($orderTransfer, $orderItemPaymentTransmissionItemTransfers);
        if (count($orderExpensePaymentTransmissionItemTransfers) === 0) {
            return;
        }

        $this->executePayoutTransmissionTransaction($orderExpensePaymentTransmissionItemTransfers, $transferEndpointUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
     *
     * @return void
     */
    protected function savePaymentTransmissionResponse(
        PaymentTransmissionResponseTransfer $paymentTransmissionResponseTransfer
    ): void {
        $this->salesPaymentMerchantEntityManager->saveSalesPaymentMerchantPayout($paymentTransmissionResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        return $this->merchantPayoutCalculator->calculatePayoutAmount($itemTransfer, $orderTransfer);
    }
}
