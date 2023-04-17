<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartNotesBackendApi\Processor\Mapper;

class CartNotesApiOrdersAttributesMapper implements CartNotesApiOrdersAttributesMapperInterface
{
    /**
     * @param list<\Generated\Shared\Transfer\OrderTransfer> $orderTransfers
     * @param list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer> $apiOrdersAttributesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ApiOrdersAttributesTransfer>
     */
    public function mapOrderTransfersToApiOrdersAttributesTransfer(
        array $orderTransfers,
        array $apiOrdersAttributesTransfers
    ): array {
        $orderTransfersIndexedByOrderReference = $this->getOrderTransfersIndexedByOrderReference($orderTransfers);
        foreach ($apiOrdersAttributesTransfers as $apiOrdersAttributesTransfer) {
            if (!$apiOrdersAttributesTransfer->getOrderReference()) {
                continue;
            }

            $orderTransfer = $orderTransfersIndexedByOrderReference[$apiOrdersAttributesTransfer->getOrderReferenceOrFail()] ?? null;
            if (!$orderTransfer) {
                continue;
            }

            $apiOrdersAttributesTransfer->setCartNote($orderTransfer->getCartNote());
        }

        return $apiOrdersAttributesTransfers;
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
