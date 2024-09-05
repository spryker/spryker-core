<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Expander;

use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface;

class PaymentTransmissionItemExpander implements PaymentTransmissionItemExpanderInterface
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_KEY_REFERENCE = 'itemReference';

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface
     */
    protected SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader
     */
    public function __construct(SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader)
    {
        $this->salesPaymentMerchantPayoutReader = $salesPaymentMerchantPayoutReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    public function expandPaymentTransmissionItemsWithTransferId(
        array $paymentTransmissionItemTransfers,
        OrderTransfer $orderTransfer
    ): array {
        $merchantReferences = $this->extractMerchantReferencesFromPaymentTransmissionItems($paymentTransmissionItemTransfers);

        $salesPaymentMerchantPayoutCollectionTransfer = $this->salesPaymentMerchantPayoutReader->getSalesPaymentMerchantPayoutCollectionByOrderReferenceAndMerchants(
            $orderTransfer->getOrderReferenceOrFail(),
            $merchantReferences,
        );

        $salesPaymentMerchantPayoutTransferIdMapIndexedByItemReference = $this->salesPaymentMerchantPayoutReader
            ->getSalesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference($salesPaymentMerchantPayoutCollectionTransfer);

        return $this->mapPaymentTransmissionItemsWithTransferId($paymentTransmissionItemTransfers, $salesPaymentMerchantPayoutTransferIdMapIndexedByItemReference);
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     * @param array<string, string> $salesPaymentMerchantPayoutTransferIdMapIndexedByItemReference
     *
     * @return list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer>
     */
    protected function mapPaymentTransmissionItemsWithTransferId(
        array $paymentTransmissionItemTransfers,
        array $salesPaymentMerchantPayoutTransferIdMapIndexedByItemReference
    ): array {
        $paymentTransmissionItemTransfersWithTransferId = [];
        foreach ($paymentTransmissionItemTransfers as $paymentTransmissionItemTransfer) {
            $transferId = $salesPaymentMerchantPayoutTransferIdMapIndexedByItemReference[$paymentTransmissionItemTransfer->getItemReferenceOrFail()] ?? null;

            if (!$transferId) {
                // We may have entities in the Database that have been failed and do not have a transfer ID. We filter out payment transmission items that do not have a successful transfer made.
                continue;
            }

            $paymentTransmissionItemTransfer->setTransferId($transferId);
            $paymentTransmissionItemTransfersWithTransferId[] = $paymentTransmissionItemTransfer;
        }

        return $paymentTransmissionItemTransfersWithTransferId;
    }

    /**
     * @param list<\Generated\Shared\Transfer\PaymentTransmissionItemTransfer> $paymentTransmissionItemTransfers
     *
     * @return list<string>
     */
    protected function extractMerchantReferencesFromPaymentTransmissionItems(array $paymentTransmissionItemTransfers): array
    {
        $merchantReferences = [];
        foreach ($paymentTransmissionItemTransfers as $paymentTransmissionItemTransfer) {
            $merchantReferences[] = $paymentTransmissionItemTransfer->getMerchantReferenceOrFail();
        }

        return $merchantReferences;
    }
}
