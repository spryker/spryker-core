<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Expander;

use Exception;
use Generated\Shared\Transfer\OrderTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface;

class OrderItemExpander implements OrderItemExpanderInterface
{
    /**
     * @var string
     */
    protected const ORDER_ITEM_KEY_REFERENCE = 'itemReference';

    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE_TRANSFER_ID_NOT_FOUND = 'Could not find a transfer ID that can be reversed.';

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
     * @param list<\Generated\Shared\Transfer\OrderItemTransfer> $orderItemTransfers
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return list<\Generated\Shared\Transfer\OrderItemTransfer>
     */
    public function expandOrderItemsWithTransferId(
        array $orderItemTransfers,
        OrderTransfer $orderTransfer
    ): array {
        $salesPaymentMerchantPayoutCollectionTransfer = $this->salesPaymentMerchantPayoutReader->getSalesPaymentMerchantPayoutCollectionByOrderReference(
            $orderTransfer->getOrderReferenceOrFail(),
        );

        $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId = $this->salesPaymentMerchantPayoutReader
            ->getSalesPaymentMerchantPayoutTransferItemReferencesMapIndexedByTransferId($salesPaymentMerchantPayoutCollectionTransfer);

        return $this->mapOrderItemsWithTransferId($orderItemTransfers, $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId);
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderItemTransfer> $orderItemTransfers
     * @param array<string, array<string, string>> $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId
     *
     * @throws \Exception
     *
     * @return list<\Generated\Shared\Transfer\OrderItemTransfer>
     */
    protected function mapOrderItemsWithTransferId(
        array $orderItemTransfers,
        array $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId
    ): array {
        foreach ($orderItemTransfers as $orderItemTransfer) {
            $itemReference = $orderItemTransfer->getItemReferenceOrFail();
            $transferId = $this->findTransferIdForItemReference($itemReference, $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId);
            if (!$transferId) {
                throw new Exception(static::EXCEPTION_MESSAGE_TRANSFER_ID_NOT_FOUND);
            }

            $orderItemTransfer->setTransferId($transferId);
        }

        return $orderItemTransfers;
    }

    /**
     * @param string $itemReference
     * @param array<string, array<string, string>> $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId
     *
     * @return string|null
     */
    protected function findTransferIdForItemReference(
        string $itemReference,
        array $salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId
    ): ?string {
        foreach ($salesPaymentMerchantPayoutTransferItemReferencesIndexedByTransferId as $currentTransferId => $itemReferences) {
            if (isset($itemReferences[$itemReference])) {
                return $currentTransferId;
            }
        }

        return null;
    }
}
