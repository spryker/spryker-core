<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\Grouper;

use ArrayObject;
use Generated\Shared\Transfer\PickingListCollectionTransfer;
use Generated\Shared\Transfer\PickingListTransfer;

class PickingListGrouper implements PickingListGrouperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function getPickingListTransferCollectionIndexedByUuid(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array {
        $pickingListTransferCollectionIndexedByUuid = [];

        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListUuid = $pickingListTransfer->getUuid();
            if (!$pickingListUuid) {
                continue;
            }

            $pickingListTransferCollectionIndexedByUuid[$pickingListUuid] = $pickingListTransfer;
        }

        return $pickingListTransferCollectionIndexedByUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListCollectionTransfer $pickingListCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    public function getPickingListItemTransferCollectionIndexedByUuid(
        PickingListCollectionTransfer $pickingListCollectionTransfer
    ): array {
        $pickingListItemTransferCollectionIndexedByUuid = [];
        foreach ($pickingListCollectionTransfer->getPickingLists() as $pickingListTransfer) {
            $pickingListItemTransferCollectionIndexedByUuid[] = $this
                ->getPickingListItemTransferCollectionByPickingListIndexedByUuid($pickingListTransfer);
        }

        return array_merge(...$pickingListItemTransferCollectionIndexedByUuid);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\StockTransfer> $stockTransferCollection
     *
     * @return array<int, \Generated\Shared\Transfer\StockTransfer>
     */
    public function getStockTransferCollectionIndexedByIdWarehouse(ArrayObject $stockTransferCollection): array
    {
        $stockTransferCollectionIndexedByIdWarehouse = [];
        foreach ($stockTransferCollection as $stockTransfer) {
            $idWarehouse = $stockTransfer->getIdStock();
            if (!$idWarehouse) {
                continue;
            }

            $stockTransferCollectionIndexedByIdWarehouse[$idWarehouse] = $stockTransfer;
        }

        return $stockTransferCollectionIndexedByIdWarehouse;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $itemTransferCollection
     *
     * @return array<int, list<\Generated\Shared\Transfer\ItemTransfer>>
     */
    public function getItemTransferCollectionGroupedByIdWarehouse(
        ArrayObject $itemTransferCollection
    ): array {
        $itemTransferCollectionGroupedByIdWarehouse = [];
        foreach ($itemTransferCollection as $itemTransfer) {
            $idWarehouse = $itemTransfer->getWarehouseOrFail()->getIdStockOrFail();
            if (!$idWarehouse) {
                continue;
            }

            $itemTransferCollectionGroupedByIdWarehouse[$idWarehouse][] = $itemTransfer;
        }

        return $itemTransferCollectionGroupedByIdWarehouse;
    }

    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\PickingListItemTransfer>
     */
    protected function getPickingListItemTransferCollectionByPickingListIndexedByUuid(
        PickingListTransfer $pickingListTransfer
    ): array {
        $pickingListItemTransferCollectionIndexedByUuid = [];
        foreach ($pickingListTransfer->getPickingListItems() as $pickingListItemTransfer) {
            $pickingListItemUuid = $pickingListItemTransfer->getUuid();
            if (!$pickingListItemUuid) {
                continue;
            }

            $pickingListItemTransferCollectionIndexedByUuid[$pickingListItemUuid] = $pickingListItemTransfer;
        }

        return $pickingListItemTransferCollectionIndexedByUuid;
    }
}
