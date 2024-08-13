<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Merchant\AbstractMerchantTransfer;

class MerchantPayoutReverse extends AbstractMerchantTransfer implements MerchantPayoutReverseInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\ItemTransfer> $salesOrderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return void
     */
    public function reversePayoutMerchants(array $salesOrderItemTransfers, OrderTransfer $orderTransfer): void
    {
        $transferEndpointUrl = $this->transferEndpointReader->getTransferEndpointUrl($orderTransfer);

        if (!$transferEndpointUrl) {
            return;
        }

        $orderItemTransfers = $this->getOrderItemsForTransfer($salesOrderItemTransfers, $orderTransfer);

        if (count($orderItemTransfers) === 0) {
            return;
        }

        $orderItemTransfers = $this->orderItemExpander->expandOrderItemsWithTransferId($orderItemTransfers, $orderTransfer);
        $orderExpenseTransfers = $this->getOrderExpensesForTransfer($orderTransfer);
        $transferRequestData = $this->createTransferRequestData($orderItemTransfers, $orderExpenseTransfers);

        $paymentTransmissionResponseCollectionTransfer = $this->transferRequestSender->requestTransfer(
            $transferRequestData,
            $transferEndpointUrl,
        );

        foreach ($paymentTransmissionResponseCollectionTransfer->getPaymentTransmissions() as $paymentTransmissionResponseTransfer) {
            $paymentTransmissionResponseTransfer->setItemReferences($this->getItemReferences($paymentTransmissionResponseTransfer));

            $this->salesPaymentMerchantEntityManager->saveSalesPaymentMerchantPayoutReversal($paymentTransmissionResponseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return int
     */
    protected function calculatePayoutAmount(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): int
    {
        return $this->merchantPayoutCalculator->calculatePayoutAmount($itemTransfer, $orderTransfer) * -1;
    }
}
