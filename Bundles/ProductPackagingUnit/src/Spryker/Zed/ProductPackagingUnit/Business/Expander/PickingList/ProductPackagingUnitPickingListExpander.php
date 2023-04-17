<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnit\Business\Expander\PickingList;

use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListTransfer;
use Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpanderInterface;

class ProductPackagingUnitPickingListExpander implements ProductPackagingUnitPickingListExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpanderInterface
     */
    protected OrderItemExpanderInterface $orderItemExpander;

    /**
     * @param \Spryker\Zed\ProductPackagingUnit\Business\Model\OrderItem\OrderItemExpanderInterface $orderItemExpander
     */
    public function __construct(OrderItemExpanderInterface $orderItemExpander)
    {
        $this->orderItemExpander = $orderItemExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expandPickingListCollection(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        $itemTransfers = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $itemTransfers[] = $this->extractItemTransfersFromPickingListTransfer($pickingListTransfer);
        }

        $this->orderItemExpander->expandOrderItemsWithAmountSalesUnit(array_merge(...$itemTransfers));

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return list<\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function extractItemTransfersFromPickingListTransfer(PickingListTransfer $pickingListTransfer): array
    {
        $itemTransfers = [];
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $itemTransfers[] = $pickingListItemTransfer->getOrderItemOrFail();
        }

        return $itemTransfers;
    }
}
