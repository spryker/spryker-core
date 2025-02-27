<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleNote\Business\Collector;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;

class ConfigurableBundleNoteSalesOrderItemCollector implements ConfigurableBundleNoteSalesOrderItemCollectorInterface
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
            if (!isset($orderItemTransfersIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()])) {
                continue;
            }

            $orderItemTransfer = $orderItemTransfersIndexedByIdSalesOrderItem[$itemTransfer->getIdSalesOrderItemOrFail()];

            $itemConfiguredBundleNote = $itemTransfer->getConfiguredBundle()?->getNote();
            $orderItemConfiguredBundleNote = $orderItemTransfer->getConfiguredBundle()?->getNote();

            if ($itemConfiguredBundleNote !== $orderItemConfiguredBundleNote) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToUpdate($itemTransfer);

                continue;
            }

            $itemTransfersToSkipUpdated[] = $itemTransfer;
        }

        $salesOrderAmendmentItemCollectionTransfer->setItemsToSkip(new ArrayObject($itemTransfersToSkipUpdated));

        return $salesOrderAmendmentItemCollectionTransfer;
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
