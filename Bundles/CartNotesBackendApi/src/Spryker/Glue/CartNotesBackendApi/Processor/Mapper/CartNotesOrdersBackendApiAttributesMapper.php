<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi\Processor\Mapper;

class CartNotesOrdersBackendApiAttributesMapper implements CartNotesOrdersBackendApiAttributesMapperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer> $ordersBackendApiAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\OrdersBackendApiAttributesTransfer>
     */
    public function mapOrderTransfersToOrdersBackendApiAttributesTransfers(
        array $orderTransfers,
        array $ordersBackendApiAttributesTransfers
    ): array {
        $orderTransfersIndexedByOrderReference = $this->getOrderTransfersIndexedByOrderReference($orderTransfers);
        foreach ($ordersBackendApiAttributesTransfers as $ordersBackendApiAttributesTransfer) {
            if (!$ordersBackendApiAttributesTransfer->getOrderReference()) {
                continue;
            }

            $orderTransfer = $orderTransfersIndexedByOrderReference[$ordersBackendApiAttributesTransfer->getOrderReferenceOrFail()] ?? null;
            if (!$orderTransfer) {
                continue;
            }

            $ordersBackendApiAttributesTransfer->setCartNote($orderTransfer->getCartNote());
        }

        return $ordersBackendApiAttributesTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\OrderTransfer>
     */
    protected function getOrderTransfersIndexedByOrderReference(array $orderTransfers): array
    {
        $orderTransfersIndexedByOrderReference = [];
        foreach ($orderTransfers as $orderTransfer) {
            if (!$orderTransfer->getOrderReference()) {
                continue;
            }

            $orderTransfersIndexedByOrderReference[$orderTransfer->getOrderReferenceOrFail()] = $orderTransfer;
        }

        return $orderTransfersIndexedByOrderReference;
    }
}
