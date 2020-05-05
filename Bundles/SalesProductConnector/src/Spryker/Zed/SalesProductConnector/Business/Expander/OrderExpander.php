<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConnector\Business\Expander;

class OrderExpander implements OrderExpanderInterface
{
    /**
     * @var \Spryker\Zed\SalesProductConnector\Business\Expander\ItemMetadataExpanderInterface
     */
    protected $itemMetadataExpander;

    /**
     * @param \Spryker\Zed\SalesProductConnector\Business\Expander\ItemMetadataExpanderInterface $itemMetadataExpander
     */
    public function __construct(ItemMetadataExpanderInterface $itemMetadataExpander)
    {
        $this->itemMetadataExpander = $itemMetadataExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    public function expandOrdersWithMetadata(array $orderTransfers): array
    {
        $itemTransfers = $this->extractItemTransfersFromOrderTransfers($orderTransfers);

        if (!$itemTransfers) {
            return $orderTransfers;
        }

        $itemTransfers = $this->itemMetadataExpander->expandOrderItemsWithMetadata($itemTransfers);
        $itemTransfers = $this->groupItemTransfersByIdSalesOrder($itemTransfers);

        return $this->updateItemsForOrders($orderTransfers, $itemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function extractItemTransfersFromOrderTransfers(array $orderTransfers): array
    {
        $itemTransfers = [];

        foreach ($orderTransfers as $orderTransfer) {
            $itemTransfers = array_merge(
                $itemTransfers,
                $orderTransfer->getItems()->getArrayCopy()
            );
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[][]
     */
    protected function groupItemTransfersByIdSalesOrder(array $itemTransfers): array
    {
        $groupedItemTransfers = [];

        foreach ($itemTransfers as $itemTransfer) {
            $itemTransfer->requireFkSalesOrder();

            $groupedItemTransfers[$itemTransfer->getFkSalesOrder()][] = $itemTransfer;
        }

        return $groupedItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer[] $orderTransfers
     * @param \Generated\Shared\Transfer\ItemTransfer[][] $groupedItemTransfers
     *
     * @return \Generated\Shared\Transfer\OrderTransfer[]
     */
    protected function updateItemsForOrders(array $orderTransfers, array $groupedItemTransfers): array
    {
        foreach ($orderTransfers as $orderTransfer) {
            $orderTransfer->requireIdSalesOrder();

            $orderTransfer->getItems()->exchangeArray(
                $groupedItemTransfers[$orderTransfer->getIdSalesOrder()]
            );
        }

        return $orderTransfers;
    }
}
