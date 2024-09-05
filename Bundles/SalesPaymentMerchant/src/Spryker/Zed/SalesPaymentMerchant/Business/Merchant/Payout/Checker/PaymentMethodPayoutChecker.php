<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Merchant\Payout\Checker;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

class PaymentMethodPayoutChecker implements PaymentMethodPayoutCheckerInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface
     */
    protected TransferEndpointReaderInterface $transferEndpointReader;

    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface
     */
    protected SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\TransferEndpointReaderInterface $transferEndpointReader
     * @param \Spryker\Zed\SalesPaymentMerchant\Business\Reader\SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader
     */
    public function __construct(
        TransferEndpointReaderInterface $transferEndpointReader,
        SalesPaymentMerchantPayoutReaderInterface $salesPaymentMerchantPayoutReader
    ) {
        $this->transferEndpointReader = $transferEndpointReader;
        $this->salesPaymentMerchantPayoutReader = $salesPaymentMerchantPayoutReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $salesOrderItemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return bool
     */
    public function isPayoutSupportedForPaymentMethodUsedForOrder(
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

        $salesPaymentMerchantPayoutCollectionTransfer = $this->salesPaymentMerchantPayoutReader->getSalesPaymentMerchantPayoutCollectionByMerchantAndOrderReference(
            $orderTransfer->getOrderReferenceOrFail(),
            $salesOrderItemTransfer->getMerchantReferenceOrFail(),
            true,
        );

        $orderItemReferenceMapForMerchant = $this->createOrderItemReferenceMap($salesPaymentMerchantPayoutCollectionTransfer);

        return isset($orderItemReferenceMapForMerchant[$salesOrderItemTransfer->getOrderItemReference()]);
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
     *
     * @return array<string, string>
     */
    protected function createOrderItemReferenceMap(
        SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
    ): array {
        $orderItemReferenceMap = [];

        foreach ($salesPaymentMerchantPayoutCollectionTransfer->getSalesPaymentMerchantPayouts() as $salesPaymentMerchantPayoutTransfer) {
            $orderItemReferenceMap = $this->addOrderItemReferencesToMap($salesPaymentMerchantPayoutTransfer, $orderItemReferenceMap);
        }

        return $orderItemReferenceMap;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer $salesPaymentMerchantPayoutTransfer
     * @param array<string, string> $orderItemReferenceMap
     *
     * @return array<string, string>
     */
    protected function addOrderItemReferencesToMap(
        SalesPaymentMerchantPayoutTransfer $salesPaymentMerchantPayoutTransfer,
        array $orderItemReferenceMap
    ): array {
        $orderItemReferences = explode(
            SalesPaymentMerchantConfig::ITEM_REFERENCE_SEPARATOR,
            $salesPaymentMerchantPayoutTransfer->getItemReferencesOrFail(),
        );
        foreach ($orderItemReferences as $orderItemReference) {
            $orderItemReferenceMap[$orderItemReference] = $orderItemReference;
        }

        return $orderItemReferenceMap;
    }
}
