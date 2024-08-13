<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Refund\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

class PaymentMethodPayoutReverseChecker implements PaymentMethodPayoutReverseCheckerInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface
     */
    protected TransferEndpointReaderInterface $transferEndpointReader;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReaderInterface
     */
    protected SalesPaymentMerchantPayoutReversalReaderInterface $salesPaymentMerchantPayoutReversalReader;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface $transferEndpointReader
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReversalReaderInterface $salesPaymentMerchantPayoutReversalReader
     */
    public function __construct(
        TransferEndpointReaderInterface $transferEndpointReader,
        SalesPaymentMerchantPayoutReversalReaderInterface $salesPaymentMerchantPayoutReversalReader
    ) {
        $this->transferEndpointReader = $transferEndpointReader;
        $this->salesPaymentMerchantPayoutReversalReader = $salesPaymentMerchantPayoutReversalReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutReversalSupportedForPaymentMethodUsedForOrder(
        ItemTransfer $salesOrderItemTransfer,
        OrderTransfer $orderTransfer
    ): bool {
        if (!$salesOrderItemTransfer->getMerchantReference()) {
            return true;
        }

        $transferEndpointUrl = $this->transferEndpointReader->getTransferEndpointUrl($orderTransfer);
        if (!$transferEndpointUrl) {
            return true;
        }

        $salesPaymentMerchantPayoutReversalCollectionTransfer = $this->salesPaymentMerchantPayoutReversalReader->getSalesPaymentMerchantPayoutReversalCollectionByMerchantAndOrderReference(
            $orderTransfer->getOrderReferenceOrFail(),
            $salesOrderItemTransfer->getMerchantReference(),
            true,
        );

        $orderItemReferenceMapForMerchant = $this->createOrderItemReferenceMap($salesPaymentMerchantPayoutReversalCollectionTransfer);

        return isset($orderItemReferenceMapForMerchant[$salesOrderItemTransfer->getOrderItemReference()]);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalCollectionTransfer $salesPaymentMerchantPayoutReversalCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function createOrderItemReferenceMap(
        SalesPaymentMerchantPayoutReversalCollectionTransfer $salesPaymentMerchantPayoutReversalCollectionTransfer
    ): array {
        $orderItemReferenceMap = [];

        foreach ($salesPaymentMerchantPayoutReversalCollectionTransfer->getSalesPaymentMerchantPayoutReversals() as $salesPaymentMerchantPayoutReversalTransfer) {
            $orderItemReferenceMap = $this->addOrderItemReferencesToMap($salesPaymentMerchantPayoutReversalTransfer, $orderItemReferenceMap);
        }

        return $orderItemReferenceMap;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutReversalTransfer $salesPaymentMerchantPayoutReversalTransfer
     * @param array<string, string> $orderItemReferenceMap
     *
     * @return array<string, string>
     */
    protected function addOrderItemReferencesToMap(
        SalesPaymentMerchantPayoutReversalTransfer $salesPaymentMerchantPayoutReversalTransfer,
        array $orderItemReferenceMap
    ): array {
        $orderItemReferences = explode(
            SalesPaymentMerchantConfig::ITEM_REFERENCE_SEPARATOR,
            $salesPaymentMerchantPayoutReversalTransfer->getItemReferencesOrFail(),
        );
        foreach ($orderItemReferences as $orderItemReference) {
            $orderItemReferenceMap[$orderItemReference] = $orderItemReference;
        }

        return $orderItemReferenceMap;
    }
}
