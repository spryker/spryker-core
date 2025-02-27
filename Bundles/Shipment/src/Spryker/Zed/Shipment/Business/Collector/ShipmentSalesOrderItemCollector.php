<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Collector;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;

class ShipmentSalesOrderItemCollector implements ShipmentSalesOrderItemCollectorInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    public function collect(
        OrderTransfer $orderTransfer,
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        $itemTransfersToSkipUpdated = [];
        $orderItemTransfersIndexedByIdSalesOrderItem = $this->getItemTransfersIndexedByIdSalesOrderItem($orderTransfer->getItems());

        foreach ($salesOrderAmendmentItemCollectionTransfer->getItemsToSkip() as $itemTransfer) {
            if ($this->isShipmentChanged($itemTransfer, $orderItemTransfersIndexedByIdSalesOrderItem)) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToUpdate($itemTransfer);

                continue;
            }

            $itemTransfersToSkipUpdated[] = $itemTransfer;
        }

        $salesOrderAmendmentItemCollectionTransfer->setItemsToSkip(new ArrayObject($itemTransfersToSkipUpdated));

        return $salesOrderAmendmentItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<int, \Generated\Shared\Transfer\ItemTransfer> $indexedItems
     *
     * @return bool
     */
    protected function isShipmentChanged(ItemTransfer $itemTransfer, array $indexedItems): bool
    {
        if (!isset($indexedItems[$itemTransfer->getIdSalesOrderItemOrFail()])) {
            return false;
        }

        return $itemTransfer->getShipmentOrFail()->getIdSalesShipment()
            !== $indexedItems[$itemTransfer->getIdSalesOrderItemOrFail()]->getShipmentOrFail()->getIdSalesShipment();
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByIdSalesOrderItem(ArrayObject $itemTransfers): array
    {
        $itemTransfersIndexedByIdSalesOrderItem = [];
        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfersIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer;
        }

        return $itemTransfersIndexedByIdSalesOrderItem;
    }
}
