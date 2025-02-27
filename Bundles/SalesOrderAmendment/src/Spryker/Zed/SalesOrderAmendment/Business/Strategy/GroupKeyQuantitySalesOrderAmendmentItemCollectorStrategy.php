<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Strategy;

use ArrayObject;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer;

class GroupKeyQuantitySalesOrderAmendmentItemCollectorStrategy implements SalesOrderAmendmentItemCollectorStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    public function collect(
        QuoteTransfer $quoteTransfer,
        OrderTransfer $orderTransfer
    ): SalesOrderAmendmentItemCollectionTransfer {
        $salesOrderAmendmentItemCollectionTransfer = new SalesOrderAmendmentItemCollectionTransfer();

        $quoteItemTransfersGroupedByGroupKey = $this->getItemTransfersGroupedByOriginalSalesOrderItemGroupKey(
            $quoteTransfer->getItems(),
        );
        $orderItemTransfersGroupedByGroupKey = $this->getItemTransfersGroupedByGroupKey($orderTransfer->getItems());

        $groupKeys = array_unique(array_merge(
            array_keys($quoteItemTransfersGroupedByGroupKey),
            array_keys($orderItemTransfersGroupedByGroupKey),
        ));

        foreach ($groupKeys as $groupKey) {
            $salesOrderAmendmentItemCollectionTransfer = $this->addItemToSalesOrderAmendmentItemCollection(
                $salesOrderAmendmentItemCollectionTransfer,
                $quoteItemTransfersGroupedByGroupKey,
                $orderItemTransfersGroupedByGroupKey,
                $groupKey,
            );
        }

        return $salesOrderAmendmentItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     * @param array<string, list<\Generated\Shared\Transfer\ItemTransfer>> $quoteItemTransfersGroupedByGroupKey
     * @param array<string, list<\Generated\Shared\Transfer\ItemTransfer>> $orderItemTransfersGroupedByGroupKey
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    protected function addItemToSalesOrderAmendmentItemCollection(
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer,
        array $quoteItemTransfersGroupedByGroupKey,
        array $orderItemTransfersGroupedByGroupKey,
        string $groupKey
    ): SalesOrderAmendmentItemCollectionTransfer {
        if (isset($quoteItemTransfersGroupedByGroupKey[$groupKey]) && !isset($orderItemTransfersGroupedByGroupKey[$groupKey])) {
            foreach ($quoteItemTransfersGroupedByGroupKey[$groupKey] as $quoteItemTransfer) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToCreate($quoteItemTransfer);
            }

            return $salesOrderAmendmentItemCollectionTransfer;
        }

        if (!isset($quoteItemTransfersGroupedByGroupKey[$groupKey]) && isset($orderItemTransfersGroupedByGroupKey[$groupKey])) {
            foreach ($orderItemTransfersGroupedByGroupKey[$groupKey] as $quoteItemTransfer) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToDelete($quoteItemTransfer);
            }

            return $salesOrderAmendmentItemCollectionTransfer;
        }

        if (isset($quoteItemTransfersGroupedByGroupKey[$groupKey]) && isset($orderItemTransfersGroupedByGroupKey[$groupKey])) {
            $salesOrderAmendmentItemCollectionTransfer = $this->addExistingByGroupKeyItems(
                $salesOrderAmendmentItemCollectionTransfer,
                $quoteItemTransfersGroupedByGroupKey,
                $orderItemTransfersGroupedByGroupKey,
                $groupKey,
            );
        }

        return $salesOrderAmendmentItemCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer
     * @param array<string, list<\Generated\Shared\Transfer\ItemTransfer>> $quoteItemTransfersGroupedByGroupKey
     * @param array<string, list<\Generated\Shared\Transfer\ItemTransfer>> $orderItemTransfersGroupedByGroupKey
     * @param string $groupKey
     *
     * @return \Generated\Shared\Transfer\SalesOrderAmendmentItemCollectionTransfer
     */
    protected function addExistingByGroupKeyItems(
        SalesOrderAmendmentItemCollectionTransfer $salesOrderAmendmentItemCollectionTransfer,
        array $quoteItemTransfersGroupedByGroupKey,
        array $orderItemTransfersGroupedByGroupKey,
        string $groupKey
    ): SalesOrderAmendmentItemCollectionTransfer {
        $quoteItemTransfers = $quoteItemTransfersGroupedByGroupKey[$groupKey];
        $orderItemTransfers = $orderItemTransfersGroupedByGroupKey[$groupKey];

        foreach ($orderItemTransfers as $key => $orderItemTransfer) {
            if (!isset($quoteItemTransfers[$key])) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToDelete($orderItemTransfer);

                continue;
            }

            $quoteItemTransfers[$key]->setIdSalesOrderItem($orderItemTransfer->getIdSalesOrderItemOrFail());

            if ($quoteItemTransfers[$key]->getQuantityOrFail() !== $orderItemTransfer->getQuantityOrFail()) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToUpdate($quoteItemTransfers[$key]);

                continue;
            }

            $salesOrderAmendmentItemCollectionTransfer->addItemToSkip($quoteItemTransfers[$key]);
        }

        foreach ($quoteItemTransfers as $key => $quoteItemTransfer) {
            if (!isset($orderItemTransfers[$key])) {
                $salesOrderAmendmentItemCollectionTransfer->addItemToCreate($quoteItemTransfer);
            }
        }

        return $salesOrderAmendmentItemCollectionTransfer;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function getItemTransfersGroupedByOriginalSalesOrderItemGroupKey(ArrayObject $itemTransfers): array
    {
        $itemTransfersGroupedByOriginalSalesOrderItemGroupKey = [];

        foreach ($itemTransfers as $itemTransfer) {
            $groupKey = $itemTransfer->getOriginalSalesOrderItemGroupKey() ?? $itemTransfer->getGroupKeyOrFail();
            $itemTransfersGroupedByOriginalSalesOrderItemGroupKey[$groupKey][] = $itemTransfer;
        }

        return $itemTransfersGroupedByOriginalSalesOrderItemGroupKey;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return array<string, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    protected function getItemTransfersGroupedByGroupKey(ArrayObject $itemTransfers): array
    {
        $itemTransfersGroupedByGroupKey = [];

        foreach ($itemTransfers as $itemTransfer) {
            $groupKey = $itemTransfer->getGroupKeyOrFail();
            $itemTransfersGroupedByGroupKey[$groupKey][] = $itemTransfer;
        }

        return $itemTransfersGroupedByGroupKey;
    }
}
