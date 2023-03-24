<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface;

class PickingListExpander implements PickingListExpanderInterface
{
    /**
     * @var \Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface
     */
    protected PickingListToSalesFacadeInterface $salesFacade;

    /**
     * @param \Spryker\Zed\PickingList\Dependency\Facade\PickingListToSalesFacadeInterface $salesFacade
     */
    public function __construct(PickingListToSalesFacadeInterface $salesFacade)
    {
        $this->salesFacade = $salesFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListCollectionTransfer
     */
    public function expandPickingListCollectionWithOrderItems(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): PickingListCollectionTransfer {
        $orderItemFilterTransfer = (new OrderItemFilterTransfer())->setSalesOrderItemUuids(
            $this->extractOrderItemUuidsFromPickingListCollection($pickingListCollectionTransfer),
        );

        $itemCollectionTransfer = $this->salesFacade->getOrderItems($orderItemFilterTransfer);
        $itemTransfersIndexedByUuid = $this->getItemTransfersIndexedByUuid($itemCollectionTransfer);

        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers */
            $pickingListItemTransfers = $pickingListTransfer->getPickingListItems();
            $this->expandPickingListItemTransfersWithOrderItem(
                $pickingListItemTransfers,
                $itemTransfersIndexedByUuid,
            );
        }

        return $pickingListCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractOrderItemUuidsFromPickingListCollection(PickingListCollectionTransfer $pickingListCollectionTransfer): array
    {
        $orderItemUuids = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            /** @var \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers */
            $pickingListItemTransfers = $pickingListTransfer->getPickingListItems();
            $orderItemUuids[] = $this->extractOrderItemUuidsFromPickingListItems($pickingListItemTransfers);
        }

        return array_merge(...$orderItemUuids);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     *
     * @return list<string>
     */
    protected function extractOrderItemUuidsFromPickingListItems(ArrayObject $pickingListItemTransfers): array
    {
        $orderItemUuids = [];
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            if ($pickingListItemTransfer->getOrderItem() && $pickingListItemTransfer->getOrderItemOrFail()->getUuid()) {
                $orderItemUuids[] = $pickingListItemTransfer->getOrderItemOrFail()->getUuidOrFail();
            }
        }

        return $orderItemUuids;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListItemTransfer> $pickingListItemTransfers
     * @param array<string, \Generated\Shared\Transfer\ItemTransfer> $itemTransfersIndexedByUuid
     *
     * @return void
     */
    protected function expandPickingListItemTransfersWithOrderItem(
        ArrayObject $pickingListItemTransfers,
        array $itemTransfersIndexedByUuid
    ): void {
        foreach ($pickingListItemTransfers as $pickingListItemTransfer) {
            if (!$pickingListItemTransfer->getOrderItem() || !$pickingListItemTransfer->getOrderItemOrFail()->getUuid()) {
                continue;
            }

            $itemUuid = $pickingListItemTransfer->getOrderItemOrFail()->getUuidOrFail();
            if (!isset($itemTransfersIndexedByUuid[$itemUuid])) {
                continue;
            }

            $pickingListItemTransfer->getOrderItemOrFail()->fromArray($itemTransfersIndexedByUuid[$itemUuid]->toArray());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getItemTransfersIndexedByUuid(ItemCollectionTransfer $itemCollectionTransfer): array
    {
        $itemTransfersIndexedByUuid = [];
        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            if (!$itemTransfer->getUuid()) {
                continue;
            }

            $itemTransfersIndexedByUuid[$itemTransfer->getUuidOrFail()] = $itemTransfer;
        }

        return $itemTransfersIndexedByUuid;
    }
}
